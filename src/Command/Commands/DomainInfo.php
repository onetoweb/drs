<?php

namespace Onetoweb\Drs\Command\Commands;

use Onetoweb\Drs\Command\AbstractCommand;

/**
 * Domain Info Command.
 */
class DomainInfo extends AbstractCommand
{
    /**
     * @param string $domain
     */
    function __construct(string $domain)
    {
        parent::__construct();
        
        $info = $this->createElement('info');
        
        $domainInfo = $this->createElement('domain:info');
        
        $domainInfo->setAttribute('xmlns:domain', 'urn:ietf:params:xml:ns:domain-1.0');
        
        $domainName = $this->createElement('domain:name', $domain);
        
        $domainName->setAttribute('hosts', 'all');
        
        $domainInfo->appendChild($domainName);
        
        $info->appendChild($domainInfo);
        
        $this->getCommand()->appendChild($info);
    }
}
