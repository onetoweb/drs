<?php

namespace Onetoweb\Drs\Command\Commands;

use Onetoweb\Drs\Command\AbstractCommand;

/**
 * Login Command.
 */
class Login extends AbstractCommand
{
    /**
     * @var string
     */
    private $version = '1.0';
    
    /**
     * @var string
     */
    private $language = 'en';
    
    /**
     * @param string $username
     * @param string $password
     * 
     * @return void
     */
    public function __construct(string $username, string $password)
    {
        parent::__construct();
        
        $login = $this->createElement('login');
        
        $login->appendChild($this->createElement('clID', $username));
        $login->appendChild($this->createElement('pw', $password));
        
        $options = $this->createElement('options');
        
        $options->appendChild($this->createElement('version', $this->version));
        $options->appendChild($this->createElement('lang', $this->language));
        
        $login->appendChild($options);
        
        $svcs = $this->createElement('svcs');
        
        $svcs->appendChild($this->createElement('objURI', 'urn:ietf:params:xml:ns:contact-1.0'));
        $svcs->appendChild($this->createElement('objURI', 'urn:ietf:params:xml:ns:host-1.0'));
        $svcs->appendChild($this->createElement('objURI', 'urn:ietf:params:xml:ns:domain-1.0'));
        
        $svcExtension = $this->createElement('svcExtension');
        
        $svcExtension->appendChild($this->createElement('extURI', 'http://rxsd.domain-registry.nl/sidn-ext-epp-1.0'));
        
        $svcs->appendChild($svcExtension);
        
        $login->appendChild($svcs);
        
        $this->getCommand()->appendChild($login);
    }
    
    /**
     * @param string $language
     * 
     * @return self
     */
    public function setLanguage(string $language): self
    {
        $this->language = $language;
        
        return $this;
    }
    
    /**
     * @param string $version
     * 
     * @return self
     */
    public function setVersion(string $version): self
    {
        $this->version = $version;
        
        return $this;
    }
}
