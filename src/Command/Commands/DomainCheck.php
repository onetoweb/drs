<?php

namespace Onetoweb\Drs\Command\Commands;

use Onetoweb\Drs\Command\AbstractCommand;

/**
 * Domain Check Command.
 */
class DomainCheck extends AbstractCommand
{
    /**
     * @param array $domains = []
     */
    public function __construct(array $domains = [])
    {
        parent::__construct();
        
        $check = $this->createElement('check');
        
        $domainCheck = $this->createElement('domain:check');
        
        foreach ($domains as $domain) {
            
            $domainCheck->setAttribute('xmlns:domain', 'urn:ietf:params:xml:ns:domain-1.0');
            
            $domainCheck->appendChild($this->createElement('domain:name', $domain));
        }
        
        $check->appendChild($domainCheck);
        
        $this->getCommand()->appendChild($check);
    }
}
