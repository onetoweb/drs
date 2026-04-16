<?php

namespace Onetoweb\Drs\Endpoint\Endpoints;

use Onetoweb\Drs\Endpoint\AbstractEndpoint;
use Onetoweb\Drs\Command\Commands;

/**
 * Host Endpoint.
 */
class Host extends AbstractEndpoint
{
    /**
     * @param array $hostNames
     */
    public function check(array $hostNames)
    {
        $hostCheckCommand = new Commands\HostCheck($hostNames);
        
        $document = $this->client->request($hostCheckCommand);
        
        $result = [];
        
        $idElements = $document->getElementsByTagName('name');
        foreach ($idElements as $idElement) {
            $result[$idElement->nodeValue] = (bool) $idElement->getAttribute('avail');
        }
        
        return $result;
    }
    
    /**
     * @param array $hostName
     */
    public function info(string $hostName)
    {
        $hostInfoCommand = new Commands\HostInfo($hostName);
        
        $document = $this->client->request($hostInfoCommand);
        
        $infData = $document->getElementsByTagNameNS('urn:ietf:params:xml:ns:host-1.0', 'infData');
        
        $result = [];
        if ($infData->count() > 0) {
            
            $hostElement = $infData->item(0);
            
            $result = [
                'name' => $this->client->getXmlValue($hostElement, 'name'),
                'roid' => $this->client->getXmlValue($hostElement, 'roid'),
                'status_linked' => $this->client->getXmlValueByAttributeValue($hostElement, 'status', 's', 'linked'),
                'status_ok' => $this->client->getXmlValueByAttributeValue($hostElement, 'status', 's', 'ok'),
                'addr' => $this->client->getXmlValue($hostElement, 'addr'),
                'cl_id' => $this->client->getXmlValue($hostElement, 'clID'),
                'cr_id' => $this->client->getXmlValue($hostElement, 'crID'),
                'cr_date' => $this->client->getXmlValue($hostElement, 'crDate'),
                'up_date' => $this->client->getXmlValue($hostElement, 'upDate'),
            ];
        }
        
        return $result;
    }
}
