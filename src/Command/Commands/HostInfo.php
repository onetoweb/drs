<?php

namespace Onetoweb\Drs\Command\Commands;

use Onetoweb\Drs\Command\AbstractCommand;

/**
 * Host Info Command.
 */
class HostInfo extends AbstractCommand
{
    /**
     * @param string $hostName
     */
    function __construct(string $hostName)
    {
        parent::__construct();
        
        $infoElement = $this->createElement('info');
        
        $hostInfoElement = $this->createElement('host:info');
        
        $hostInfoElement->setAttribute('xmlns:host', 'urn:ietf:params:xml:ns:host-1.0');
        
        $hostNameElement = $this->createElement('host:name', $hostName);
        
        $hostInfoElement->appendChild($hostNameElement);
        
        $infoElement->appendChild($hostInfoElement);
        
        $this->getCommand()->appendChild($infoElement);
    }
}
