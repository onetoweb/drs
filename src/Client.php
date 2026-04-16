<?php

namespace Onetoweb\Drs;

use Onetoweb\Drs\Endpoint\Endpoints;
use GuzzleHttp\RequestOptions;
use GuzzleHttp\Client as GuzzleCLient;
use Onetoweb\Drs\Exception\{PrivateKeyFileException, ResponseException};
use Onetoweb\Drs\Command\CommandInterface;
use Onetoweb\Drs\Command\Commands\{Login, Logout};
use DomDocument;
use DOMElement;

/**
 * Drs Api Client.
 */
#[\AllowDynamicProperties]
class Client
{
    /**
     * Base href
     */
    public const BASE_HREF_TEST = 'ssl://testdrs.domain-registry.nl:700';
    public const BASE_HREF_LIVE = 'ssl://drs.domain-registry.nl:700';
    
    /**
     * @var string
     */
    private $username;
    
    /**
     * @var string
     */
    private $password;
    
    /**
     * @var string
     */
    private $privateKey;
    
    /**
     * @var string
     */
    private $passphrase;
    
    /**
     * @var bool
     */
    private $testModus;
    
    /**
     * @var resource
     */
    private $connection;
    
    /**
     * @var int
     */
    private $errorCode;
    
    /**
     * @var string
     */
    private $errorMessage;
    
    /**
     * @var int
     */
    private $timeout = 5;
    
    /**
     * @var string
     */
    private $sessionId;
    
    /**
     * @var bool
     */
    private $loggedIn = false;
    
    /**
     * @param string $username
     * @param string $password
     * @param string $privateKey
     * @param string $passphrase = null
     * @param bool $testModus = true
     * 
     * @throws PrivateKeyFileException if the private key is not readable
     */
    public function __construct(string $username, string $password, string $privateKey, string $passphrase = null, bool $testModus = false)
    {
        $this->username = $username;
        $this->password = $password;
        $this->privateKey = $privateKey;
        $this->passphrase = $passphrase;
        $this->testModus = $testModus;
        
        if (!is_readable($this->privateKey)) {
            throw new PrivateKeyFileException("private key file: {$this->privateKey} is not readable");
        }
        
        // load endpoints
        $this->loadEndpoints();
    }
    
    /**
     * Destructor.
     */
    public function __destruct()
    {
        if (is_resource($this->connection)) {
            
            if ($this->loggedIn) {
                $this->logout();
            }
            
            fclose($this->connection);
        }
    }
    
    /**
     * @return void
     */
    private function loadEndpoints(): void
    {
        foreach (Endpoints::list() as $name => $class) {
            $this->{$name} = new $class($this);
        }
    }
    
    /**
     * @return string
     */
    public function getBaseHref(): string
    {
        return $this->testModus ? self::BASE_HREF_TEST : self::BASE_HREF_LIVE;
    }
    
    /**
     * @param string $endpoint
     * 
     * @return string
     */
    public function getUrl(string $endpoint): string
    {
        return $this->getBaseHref() . '/' . ltrim($endpoint, '/');
    }
    
    /**
     * @return bool
     */
    public function isLoggedIn(): bool
    {
        return $this->loggedIn;
    }
    
    /**
     * @throws ResponseException
     * 
     * @return bool
     */
    public function login(): void
    {
        if (!$this->loggedIn) {
            
            // generate session id
            $sessionId = uniqid();
            
            // build login command
            $loginCommand = new Login($this->username, $this->password);
            
            // add session id to login command
            $loginCommand->addSessionId($sessionId);
            
            // write login
            $this->write($loginCommand);
            
            $xmlElement = $this->read();
            
            $this->loggedIn = true;
            $this->sessionId = $sessionId;
        }
    }
    
    /**
     * @return void
     */
    public function logout(): void
    {
        if ($this->loggedIn) {
            
            // build login command
            $logoutCommand = new Logout();
            
            // add session id to login command
            $logoutCommand->addSessionId($this->sessionId);
            
            // write logout
            $this->write($logoutCommand);
            
            // read logout response
            $this->read();
        }
    }
    
    /**
     * @return int
     */
    public function getErrorCode(): int
    {
        return $this->errorCode;
    }
    
    /**
     * @return string
     */
    public function getErrorMessage(): string
    {
        return $this->errorMessage;
    }
    
    /**
     * @param int $timeout
     * 
     * @return self
     */
    public function setTimeout(int $timeout): self
    {
        $this->timeout = $timeout;
        
        return $this;
    }
    
    /**
     * @param DOMElement $element
     * @param string $tagName
     * 
     * @return string|NULL
     */
    public function getXmlValue(DOMElement $element, string $tagName): ?string
    {
        $tag = $element->getElementsByTagName($tagName);
        
        if ($tag->length > 0) {
            
            return $tag->item(0)->nodeValue;
        }
        
        return null;
    }
    
    /**
     * @param DOMElement $element
     * @param string $tagName
     * @param string $attributeName
     * 
     * @return string|NULL
     */
    public function getXmlAttribute(DOMElement $element, string $tagName, string $attributeName): ?string
    {
        $tag = $element->getElementsByTagName($tagName);
        
        if ($tag->length > 0) {
            
            if ($tag->item(0)->hasAttribute($attributeName)) {
                
                return $tag->item(0)->getAttribute($attributeName);
            }
        }
        
        return null;
    }
    
    /**
     * @param DOMElement $element
     * @param string $tagName
     * @param string $attributeName
     * @param string $attributeValue
     * 
     * @return string|NULL
     */
    public function getXmlValueByAttributeValue(DOMElement $element, string $tagName, string $attributeName, string $attributeValue): ?string
    {
        $tags = $element->getElementsByTagName($tagName);
        
        if ($tags->length > 0) {
            
            foreach ($tags as $tag) {
                
                if ($tag->hasAttribute($attributeName)) {
                    
                    if ($tag->getAttribute($attributeName) === $attributeValue) {
                        
                        return $tag->nodeValue;
                    }
                }
            }
        }
        
        return null;
    }
    
    /**
     * @param DomDocument $document
     * 
     * @return array
     */
    public function getResponseResult(DomDocument $document)
    {
        $code = null;
        $message = null;
        
        // get response code
        $result = $document->getElementsByTagName('result');
        
        if ($result->count() > 0) {
            
            if ($result->item(0)->hasAttribute('code')) {
                
                $code = (int) $result->item(0)->getAttribute('code');
            }
            
            $msg = $document->getElementsByTagName('msg');
            
            if ($result->count() > 0) {
                
                $message = $msg->item(0)->nodeValue;
            }
        }
        
        return [
            $code,
            $message
        ];
    }
    
    /**
     * @return DomDocument
     */
    public function read(): DomDocument
    {
        // get size header
        $sizeHeader = fread($this->connection, 4);
        
        $size = current(unpack('N', $sizeHeader)) - 4;
        
        $message = fread($this->connection, $size);
        
        // load dom document
        $document = new DOMDocument('1.0', 'utf-8');
        $document->loadXML($message);
        
        return $document;
    }
    
    /**
     * @param CommandInterface $command
     */
    public function write(CommandInterface $command)
    {
        $xml = (string) $command;
        
        $sizeHeader = pack('N', intval(strlen($xml) + 4));
        
        $content = $sizeHeader.$xml;
        
        fwrite($this->connection, $content);
    }
    
    /**
     * @return resource
     */
    public function connect()
    {
        if ($this->connection === null) {
            
            $context = stream_context_create();
            
            stream_context_set_option($context, 'ssl', 'local_pk', $this->privateKey);
            stream_context_set_option($context, 'ssl', 'passphrase', $this->passphrase);
            
            $this->connection = stream_socket_client($this->getBaseHref(), $this->errorCode, $this->errorMessage, $this->timeout, STREAM_CLIENT_CONNECT, $context);
            
            stream_set_blocking($this->connection, true);
            
            if (!$this->loggedIn) {
                
                // read welcome message
                $welcomeMessage = $this->read();
            }
        }
        
        return $this->connection;
    }
    
    /**
     * @param CommandInterface $command
     * 
     * @return DomDocument
     */
    public function request(CommandInterface $command): DomDocument
    {
        $this->connect();
        
        if (!$this->loggedIn) {
            $this->login();
        }
        
        $command->addSessionId($this->sessionId);
        
        $this->write($command);
        
        $document = $this->read();
        
        list (
            $code,
            $message
        ) = $this->getResponseResult($document);
        
        if (!in_array($code, [1000, 1500])) {
            throw new ResponseException($message, $code);
        }
        
        return $document;
    }
}
