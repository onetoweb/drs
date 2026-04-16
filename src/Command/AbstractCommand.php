<?php

namespace Onetoweb\Drs\Command;

use Onetoweb\Drs\Command\CommandInterface;
use DOMDocument;
use DomElement;

/**
 * Abstract Command.
 */
abstract class AbstractCommand extends DOMDocument implements CommandInterface
{
    /**
     * @var DomElement
     */
    private $epp;
    
    /**
     * @var DomElement
     */
    private $command;
    
    /**
     * @var DomElement
     */
    private $sessionid;
    
    /**
     * Constructor.
     */
    public function __construct()
    {
        parent::__construct('1.0', 'UTF-8');
        
        $this->formatOutput = true;
        
        $this->epp = $this->createElement('epp');
        
        $this->epp->setAttribute('xmlns', 'urn:ietf:params:xml:ns:epp-1.0');
        
        $this->command = $this->createElement('command');
        
        $this->epp->appendChild($this->command);
        
        $this->appendChild($this->epp);
    }
    
    /**
     * @return DOMDocument
     */
    public function getEpp(): DomElement
    {
        return $this->epp;
    }
    
    /**
     * @return DOMDocument
     */
    public function getCommand(): DomElement
    {
        return $this->command;
    }
    
    /**
     * @return void
     */
    public function addSessionId(string $sessionid): void
    {
        $this->command->appendChild($this->createElement('clTRID', $sessionid));
    }
    
    /**
     * @return string
     */
    public function __toString(): string
    {
        $this->formatOutput = false;
        
        return $this->saveXml();
    }
}

