<?php

namespace Onetoweb\Drs\Endpoint\Endpoints;

use Onetoweb\Drs\Endpoint\AbstractEndpoint;
use Onetoweb\Drs\Command\Commands;

/**
 * Domain Endpoint.
 */
class Domain extends AbstractEndpoint
{
    /**
     * @param array $domains
     * 
     * @return array
     */
    public function check(array $domains): array
    {
        $domainCheckCommand = new Commands\DomainCheck($domains);
        
        $document = $this->client->request($domainCheckCommand);
        
        $result = [];
        
        $nameElements = $document->getElementsByTagName('name');
        foreach ($nameElements as $nameElement) {
            $result[$nameElement->nodeValue] = (bool) $nameElement->getAttribute('avail');
        }
        
        return $result;
    }
    
    /**
     * @param string $domain
     * 
     * @return array
     */
    public function info(string $domain): array
    {
        $domainInfoCommand = new Commands\DomainInfo($domain);
        
        $document = $this->client->request($domainInfoCommand);
        
        $results = [];
        $infData = $document->getElementsByTagNameNS('urn:ietf:params:xml:ns:domain-1.0', 'infData');
        
        if ($infData->count() > 0) {
            
            $domainElement = $infData->item(0);
            
            $results = [
                'name' => $this->client->getXmlValue($domainElement, 'name'),
                'roid' => $this->client->getXmlValue($domainElement, 'roid'),
                'status' => $this->client->getXmlAttribute($domainElement, 'status', 's'),
                'registrant' => $this->client->getXmlValue($domainElement, 'registrant'),
                'contactTech' => $this->client->getXmlValueByAttributeValue($domainElement, 'contact', 'type', 'tech'),
                'contactAdmin' => $this->client->getXmlValueByAttributeValue($domainElement, 'contact', 'type', 'admin'),
                'host' => $this->client->getXmlValue($domainElement, 'host'),
                'clID' => $this->client->getXmlValue($domainElement, 'clID'),
                'crID' => $this->client->getXmlValue($domainElement, 'crID'),
                'crDate' => $this->client->getXmlValue($domainElement, 'crDate'),
                'upID' => $this->client->getXmlValue($domainElement, 'upID'),
                'upDate' => $this->client->getXmlValue($domainElement, 'upDate'),
                'exDate' => $this->client->getXmlValue($domainElement, 'exDate'),
                'trDate' => $this->client->getXmlValue($domainElement, 'trDate'),
                'pw' => $this->client->getXmlValue($domainElement, 'pw'),
            ];
        }
        
        return $results;
    }
    
    /**
     * @param string $name
     * @param string $hostObj
     * @param string $registrant
     * @param string $adminHandle
     * @param string $techHandle
     * @param string $domainPw
     * @param int $period = 1
     * @param string $periodUnit = 'y'
     * 
     * @return bool
     */
    public function create(
        string $name,
        string $hostObj,
        string $registrant,
        string $adminHandle,
        string $techHandle,
        string $domainPw,
        int $period = 1,
        string $periodUnit = 'y'
    ): bool {
        
        $domainCreateCommand = new Commands\DomainCreate(
            $name,
            $hostObj,
            $registrant,
            $adminHandle,
            $techHandle,
            $domainPw,
            $period,
            $periodUnit
        );
        
        $document = $this->client->request($domainCreateCommand);
        
        return true;
    }
    
    /**
     * @param string $name
     * 
     * @return bool
     */
    public function delete(string $name): bool
    {
        $domainDeleteCommand = new Commands\DomainDelete($name);
        
        $document = $this->client->request($domainDeleteCommand);
        
        return true;
    }
    
    /**
     * @param string $name
     * @param string $expirationDate
     * @param int $period = 1
     * @param string $periodUnit = 'y'
     * 
     * @return bool
     */
    public function renew(
        string $name,
        string $expirationDate,
        int $period = 1,
        string $periodUnit = 'y'
    ): bool {
        
        $domainRenewCommand = new Commands\DomainRenew($name, $expirationDate, $period, $periodUnit);
        
        $document = $this->client->request($domainRenewCommand);
        
        return true;
    }
}
