<?php

namespace Onetoweb\Drs\Command\Commands;

use Onetoweb\Drs\Command\AbstractCommand;

/**
 * Domain Create Command.
 */
class DomainCreate extends AbstractCommand
{
    /**
     * @param string $name
     * @param string $hostObj
     * @param string $registrant
     * @param string $adminHandle
     * @param string $techHandle
     * @param string $domainPw
     * @param int $period = 1
     * @param string $periodUnit = 'y'
     */
    public function __construct(
        string $name,
        string $hostObj,
        string $registrant,
        string $adminHandle,
        string $techHandle,
        string $domainPw,
        int $period = 1,
        string $periodUnit = 'y'
    ) {
        
        parent::__construct();
        
        $create = $this->createElement('create');
        
        // add domain
        $domainCreate = $this->createElement('domain:create');
        
        $domainCreate->setAttribute('xmlns:domain', 'urn:ietf:params:xml:ns:domain-1.0');
        
        $domainCreate->appendChild($this->createElement('domain:name', $name));
        
        // add host object
        $periodElm = $this->createElement('domain:period', $period);
        
        $periodElm->setAttribute('unit', $periodUnit);
        
        $domainCreate->appendChild($periodElm);
        
        // add host object
        $domainNsElm = $this->createElement('domain:ns');
        
        $hostObjElm = $this->createElement('domain:hostObj', $hostObj);
        
        $domainNsElm->appendChild($hostObjElm);
        
        $domainCreate->appendChild($domainNsElm);
        
        // add registrant
        $domainContactAdminElm = $this->createElement('domain:registrant', $registrant);
        
        $domainCreate->appendChild($domainContactAdminElm);
        
        // add admin handle
        $domainContactAdminElm = $this->createElement('domain:contact', $adminHandle);
        
        $domainContactAdminElm->setAttribute('type', 'admin');
        
        $domainCreate->appendChild($domainContactAdminElm);
        
        // add tech handle
        $domainContactTechElm = $this->createElement('domain:contact', $techHandle);
        
        $domainContactTechElm->setAttribute('type', 'tech');
        
        $domainCreate->appendChild($domainContactTechElm);
        
        // add auth info
        $authInfoElm = $this->createElement('domain:authInfo');
        
        $domainPwElm = $this->createElement('domain:pw', $domainPw);
        
        $authInfoElm->appendChild($domainPwElm);
        
        $domainCreate->appendChild($authInfoElm);
        
        // add domain create to create
        $create->appendChild($domainCreate);
        
        $this->getCommand()->appendChild($create);
    }
}
