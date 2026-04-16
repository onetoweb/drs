<?php

namespace Onetoweb\Drs\Command\Commands;

use Onetoweb\Drs\Command\AbstractCommand;

/**
 * Domain Renew Command.
 */
class DomainRenew extends AbstractCommand
{
    /**
     * @param string $name
     * @param string $expirationDate
     * @param int $period = 1
     * @param string $periodUnit = 'y'
     */
    public function __construct(
        string $name,
        string $expirationDate,
        int $period = 1,
        string $periodUnit = 'y'
    ) {
        
        parent::__construct();
        
        $renewElement = $this->createElement('renew');
        
        $domainRenewElement = $this->createElement('domain:renew');
        $domainRenewElement->setAttribute('xmlns:domain', 'urn:ietf:params:xml:ns:domain-1.0');
        
        $domainNameElement = $this->createElement('domain:name', $name);
        
        $domainRenewElement->appendChild($domainNameElement);
        
        // add expiration date
        $domainCurExpDateElement = $this->createElement('domain:curExpDate', $expirationDate);
        
        $domainRenewElement->appendChild($domainCurExpDateElement);
        
        // add domain:period
        $domainPeriodElement = $this->createElement('domain:period', $period);
        
        $domainPeriodElement->setAttribute('unit', $periodUnit);
        
        $domainRenewElement->appendChild($domainPeriodElement);
        
        $renewElement->appendChild($domainRenewElement);
        
        $this->getCommand()->appendChild($renewElement);
    }
}
