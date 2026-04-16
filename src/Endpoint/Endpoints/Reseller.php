<?php

namespace Onetoweb\Drs\Endpoint\Endpoints;

use Onetoweb\Drs\Endpoint\AbstractEndpoint;
use Onetoweb\Drs\Command\Commands;

/**
 * Reseller Endpoint.
 */
class Reseller extends AbstractEndpoint
{
    /**
     * @param array $resellerIds
     * 
     * @return array
     */
    public function check(array $resellerIds): array
    {
        $resellerCheckCommand = new Commands\ResellerCheck($resellerIds);
        
        $document = $this->client->request($resellerCheckCommand);
        
        $result = [];
        
        $idElements = $document->getElementsByTagName('id');
        foreach ($idElements as $idElement) {
            $result[$idElement->nodeValue] = (bool) $idElement->getAttribute('avail');
        }
        
        return $result;
    }
    
    /**
     * @param string $resellerId
     * 
     * @return array
     */
    public function info(string $resellerId): array
    {
        $resellerInfoCommand = new Commands\ResellerInfo($resellerId);
        
        $document = $this->client->request($resellerInfoCommand);
        
        $results = [];
        $infData = $document->getElementsByTagNameNS('http://rxsd.domain-registry.nl/sidn-reseller-1.0', 'infData');
        
        if ($infData->count() > 0) {
            
            $resellerElement = $infData->item(0);
            
            $addressElement = $document->getElementsByTagName('address')->item(0);
            
            $results = [
                'id' => $client->getXmlValue($resellerElement, 'id'),
                'roid' => $client->getXmlValue($resellerElement, 'roid'),
                'status_linked' => $client->getXmlValueByAttributeValue($resellerElement, 'status', 's', 'linked'),
                'status_ok' => $client->getXmlValueByAttributeValue($resellerElement, 'status', 's', 'ok'),
                'trading_name' => $client->getXmlValue($resellerElement, 'tradingName'),
                'url' => $client->getXmlValue($resellerElement, 'url'),
                'email' => $client->getXmlValue($resellerElement, 'email'),
                'voice' => $client->getXmlValue($resellerElement, 'voice'),
                'street' => $client->getXmlValue($addressElement, 'street'),
                'city' => $client->getXmlValue($addressElement, 'city'),
                'pc' => $client->getXmlValue($addressElement, 'pc'),
                'cc' => $client->getXmlValue($addressElement, 'cc'),
                'cl_id' => $client->getXmlValue($resellerElement, 'clID'),
                'cr_id' => $client->getXmlValue($resellerElement, 'crID'),
                'cr_date' => $client->getXmlValue($resellerElement, 'crDate'),
                'up_id' => $client->getXmlValue($resellerElement, 'upID'),
                'up_date' => $client->getXmlValue($resellerElement, 'upDate'),
            ];
        }
        
        return $results;
    }
}
