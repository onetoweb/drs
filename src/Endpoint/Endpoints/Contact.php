<?php

namespace Onetoweb\Drs\Endpoint\Endpoints;

use Onetoweb\Drs\Endpoint\AbstractEndpoint;
use Onetoweb\Drs\Command\Commands;

/**
 * Contact Endpoint.
 */
class Contact extends AbstractEndpoint
{
    /**
     * @param array $contactIds
     * 
     * @return array
     */
    public function check(array $contactIds): array
    {
        $contactCheckCommand = new Commands\ContactCheck($contactIds);
        
        $document = $this->client->request($contactCheckCommand);
        
        $result = [];
        
        $idElements = $document->getElementsByTagName('id');
        foreach ($idElements as $idElement) {
            $result[$idElement->nodeValue] = (bool) $idElement->getAttribute('avail');
        }
        
        return $result;
    }
    
    /**
     * @param string $contactId
     * 
     * @return array
     */
    public function info(string $contactId): array
    {
        $contactInfoCommand = new Commands\ContactInfo($contactId);
        
        $document = $this->client->request($contactInfoCommand);
        
        $infData = $document->getElementsByTagNameNS('urn:ietf:params:xml:ns:contact-1.0', 'infData');
        
        if ($infData->count() > 0) {
            
            $contactElement = $infData->item(0);
            
            $postalInfoElement = $document->getElementsByTagName('postalInfo')->item(0);
            
            $results = [
                'id' => $this->client->getXmlValue($contactElement, 'id'),
                'roid' => $this->client->getXmlValue($contactElement, 'roid'),
                'status_linked' => $this->client->getXmlValueByAttributeValue($contactElement, 'status', 's', 'linked'),
                'status_ok' => $this->client->getXmlValueByAttributeValue($contactElement, 'status', 's', 'ok'),
                'name' => $this->client->getXmlValue($postalInfoElement, 'name'),
                'street' => $this->client->getXmlValue($postalInfoElement, 'street'),
                'city' => $this->client->getXmlValue($postalInfoElement, 'city'),
                'pc' => $this->client->getXmlValue($postalInfoElement, 'pc'),
                'cc' => $this->client->getXmlValue($postalInfoElement, 'cc'),
                'voice' => $this->client->getXmlValue($contactElement, 'voice'),
                'email' => $this->client->getXmlValue($contactElement, 'email'),
                'cl_id' => $this->client->getXmlValue($contactElement, 'clID'),
                'cr_id' => $this->client->getXmlValue($contactElement, 'crID'),
                'cr_date' => $this->client->getXmlValue($contactElement, 'crDate'),
                'up_id' => $this->client->getXmlValue($contactElement, 'upID'),
                'up_date' => $this->client->getXmlValue($contactElement, 'upDate'),
            ];
        }
        
        return $results;
    }
}
