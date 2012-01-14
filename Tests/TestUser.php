<?php

namespace Venocl\GroupACLBundle\Tests;

use Symfony\Component\Security\Core\User\User;
use Venocl\GroupACLBundle\Tests\TestGroup;

class TestUser
{
    public function __toString() {
        return 'my_username';
    }
    public function getGroups()
    {
        $g1 = new TestGroup(1);
        $g2 = new TestGroup(2);
        
        return array($g1,$g2);
    }

    public function getId()
    {
        return 13;
    }
    public function getRoles()
    {
        return array();
    }
}
