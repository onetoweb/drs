<?php

namespace Onetoweb\Drs\Command\Commands;

use Onetoweb\Drs\Command\AbstractCommand;

/**
 * Contact Info Command.
 */
class ContactInfo extends AbstractCommand
{
    /**
     * @param string $contactId
     */
    function __construct(string $contactId)
    {
        parent::__construct();
        
        $infoElement = $this->createElement('info');
        
        $contactInfoElement = $this->createElement('contact:info');
        
        $contactInfoElement->setAttribute('xmlns:contact', 'urn:ietf:params:xml:ns:contact-1.0');
        
        $contactIdElement = $this->createElement('contact:id', $contactId);
        
        $contactInfoElement->appendChild($contactIdElement);
        
        $infoElement->appendChild($contactInfoElement);
        
        $this->getCommand()->appendChild($infoElement);
        
    }
}
