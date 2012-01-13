<?php
namespace Venocl\GroupACLBundle\Tests\UnitTests;

use Venocl\GroupACLBundle\Tests\TestUser;
use Venocl\GroupACLBundle\Lib\VenoclGroupACLBundleSecurityIdentityRetrievalStrategy;


class VenoclGroupACLBundleSecurityIdentityRetrievalStrategyTest extends \PHPUnit_Framework_TestCase
{
    public function testGetSecurityIdentities()
    {
        $rh = $this->getMock('Symfony\Component\Security\Core\Role\RoleHierarchyInterface');
        $atr = $this->getMock('Symfony\Component\Security\Core\Authentication\AuthenticationTrustResolver', array(), array('', ''));
        /*$rh->expects($this->any())
                ->method('getReachableRoles')
                ->with(array())
                ->will($this->returnValue(array()));*/
        
        $token = $this->getMock('Symfony\Component\Security\Core\Authentication\Token\TokenInterface');
        $rh->expects($this->any())
                ->method('getRoles')
                ->will($this->returnValue(array()));

        
        $token->setUser(new TestUser());
        
        $strategy = new VenoclGroupACLBundleSecurityIdentityRetrievalStrategy($rh, $atr);
        
        $ids = $strategy->getSecurityIdentities($token);
        
        $this->assertCount(3, $ids, "Incorrect number of Ids");
        print_r($ids);
        //$this->assertContains("", $ids, "Ids should contain");
        
    }

}


