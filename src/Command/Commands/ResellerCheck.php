<?php

namespace Onetoweb\Drs\Command\Commands;

use Onetoweb\Drs\Command\AbstractCommand;

/**
 * Reseller Check Command.
 */
class ResellerCheck extends AbstractCommand
{
    /**
     * @param array $resellerIds
     */
    public function __construct(array $resellerIds)
    {
        parent::__construct();
        
        $checkElement = $this->createElement('check');
        
        $resellerCheckElement = $this->createElement('reseller:check');
        
        $resellerCheckElement->setAttribute('xmlns:reseller', 'http://rxsd.domain-registry.nl/sidn-reseller-1.0');
        
        foreach ($resellerIds as $resellerId) {
            
            $resellerIdElement = $this->createElement('reseller:id', $resellerId);
            
            $resellerCheckElement->appendChild($resellerIdElement);
        }
        
        $checkElement->appendChild($resellerCheckElement);
        
        $this->getCommand()->appendChild($checkElement);
    }
}
