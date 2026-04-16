<?php

namespace Onetoweb\Drs\Command\Commands;

use Onetoweb\Drs\Command\AbstractCommand;

/**
 * Host Check Command.
 */
class HostCheck extends AbstractCommand
{
    /**
     * @param array $hostNames
     */
    public function __construct(array $hostNames)
    {
        parent::__construct();
        
        $checkElement = $this->createElement('check');
        
        $hostCheckElement = $this->createElement('host:check');
        
        $hostCheckElement->setAttribute('xmlns:host', 'urn:ietf:params:xml:ns:host-1.0');
        
        foreach ($hostNames as $hostName) {
            
            $hostNameElement = $this->createElement('host:name', $hostName);
            
            $hostCheckElement->appendChild($hostNameElement);
        }
        
        $checkElement->appendChild($hostCheckElement);
        
        $this->getCommand()->appendChild($checkElement);
    }
}
