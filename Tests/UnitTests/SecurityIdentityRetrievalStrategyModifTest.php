<?php
namespace Venocl\GroupACLBundle\Tests\UnitTests;

use Venocl\GroupACLBundle\Tests\TestUser;
use Venocl\GroupACLBundle\Lib\SecurityIdentityRetrievalStrategy;


class SecurityIdentityRetrievalStrategyModifTest extends \PHPUnit_Framework_TestCase
{
    public function testGetSecurityIdentities()
    {
        $rh = $this->getMock('Symfony\Component\Security\Core\Role\RoleHierarchyInterface');
        $atr = $this->getMock('Symfony\Component\Security\Core\Authentication\AuthenticationTrustResolver', array(), array('', ''));
        $rh->expects($this->any())
                ->method('getReachableRoles')
                ->with(array())
                ->will($this->returnValue(array()));
        
        $token = $this->getMock('Symfony\Component\Security\Core\Authentication\Token\TokenInterface');
        $token->expects($this->any())
                ->method('getRoles')
                ->will($this->returnValue(array()));
        $token->expects($this->any())
                ->method('getUser')
                ->will($this->returnValue(new TestUser()));
        
        $strategy = new SecurityIdentityRetrievalStrategy($rh, $atr);
        
        $ids = $strategy->getSecurityIdentities($token);

        $this->assertEquals(3, count($ids), "Incorrect number of Ids");
    }

}


