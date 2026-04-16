<?php

namespace Onetoweb\Drs\Command\Commands;

use Onetoweb\Drs\Command\AbstractCommand;

/**
 * Contact Check Command.
 */
class ContactCheck extends AbstractCommand
{
    /**
     * @param array $contactIds
     */
    public function __construct(array $contactIds)
    {
        parent::__construct();
        
        $checkElement = $this->createElement('check');
        
        $contactCheckElement = $this->createElement('contact:check');
        
        $contactCheckElement->setAttribute('xmlns:contact', 'urn:ietf:params:xml:ns:contact-1.0');
        
        foreach ($contactIds as $contactId) {
            
            $contactIdElement = $this->createElement('contact:id', $contactId);
            
            $contactCheckElement->appendChild($contactIdElement);
        }
        
        $checkElement->appendChild($contactCheckElement);
        
        $this->getCommand()->appendChild($checkElement);
    }
}
