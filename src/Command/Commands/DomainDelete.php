<?php

namespace Onetoweb\Drs\Command\Commands;

use Onetoweb\Drs\Command\AbstractCommand;

/**
 * Domain Delete Command.
 */
class DomainDelete extends AbstractCommand
{
    /**
     * @param string $name
     */
    function __construct(string $name) {
        
        parent::__construct();
        
        $deleteElement = $this->createElement('delete');
        
        $domainDeleteElement = $this->createElement('domain:delete');
        $domainDeleteElement->setAttribute('xmlns:domain', 'urn:ietf:params:xml:ns:domain-1.0');
        
        $domainNameElement = $this->createElement('domain:name', $name);
        
        $domainDeleteElement->appendChild($domainNameElement);
        
        $deleteElement->appendChild($domainDeleteElement);
        
        $this->getCommand()->appendChild($deleteElement);
    }
}
