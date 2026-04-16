<?php

namespace Onetoweb\Drs\Command\Commands;

use Onetoweb\Drs\Command\AbstractCommand;

/**
 * Logout Command.
 */
class Logout extends AbstractCommand
{
    /**
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        
        $this->getEpp()->setAttribute('xmlns:xsi', 'http://www.w3.org/2001/XMLSchema-instance');
        $this->getEpp()->setAttribute('xsi:schemaLocation', 'urn:ietf:params:xml:ns:epp-1.0 epp-1.0.xsd');
        
        $logout = $this->createElement('logout');
        
        $this->getCommand()->appendChild($logout);
    }
}
