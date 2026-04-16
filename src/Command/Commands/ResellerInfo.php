<?php

namespace Onetoweb\Drs\Command\Commands;

use Onetoweb\Drs\Command\AbstractCommand;

/**
 * Reseller Info Command.
 */
class ResellerInfo extends AbstractCommand
{
    /**
     * @param string $resellerId
     */
    function __construct(string $resellerId)
    {
        parent::__construct();
        
        $infoElement = $this->createElement('info');
        
        $resellerInfoElement = $this->createElement('reseller:info');
        
        $resellerInfoElement->setAttribute('xmlns:reseller', 'http://rxsd.domain-registry.nl/sidn-reseller-1.0');
        
        $resellerIdElement = $this->createElement('reseller:id', $resellerId);
        
        $resellerInfoElement->appendChild($resellerIdElement);
        
        $infoElement->appendChild($resellerInfoElement);
        
        $this->getCommand()->appendChild($infoElement);
    }
}
