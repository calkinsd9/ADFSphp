<?php

namespace LightSaml\Tests\Model\Protocol;

use LightSaml\Model\Protocol\Status;
use LightSaml\Model\Protocol\StatusCode;
use LightSaml\SamlConstants;

class StatusTest extends \PHPUnit_Framework_TestCase
{
    public function test_status_set_message_constructor()
    {
        $message = "Test message";
        $status = new Status(new StatusCode(SamlConstants::STATUS_SUCCESS), $message);
        $this->assertEquals($status->getStatusMessage(), $message);
    }
    
    public function test_status_set_message_setter()
    {
        $message = "Test message";
        $status = new Status(new StatusCode(SamlConstants::STATUS_SUCCESS));
        $status->setStatusMessage($message);
        $this->assertEquals($status->getStatusMessage(), $message);
    }
}
