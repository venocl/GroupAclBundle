<?php
namespace Venocl\GroupACLBundle\Tests\UnitTests;

use Symfony\Component\Security\Acl\Domain\RoleSecurityIdentity;
use Venocl\GroupACLBundle\Lib\GroupSecurityIdentity;

class GroupSecurityIdentityTest extends \PHPUnit_Framework_TestCase
{
    public function testConstructor()
    {
        $id = new GroupSecurityIdentity('foo', 'Foo');

        $this->assertEquals('foo', $id->getGroupname());
        $this->assertEquals('Foo', $id->getClass());
    }

    /**
     * @dataProvider getCompareData
     */
    public function testEquals($id1, $id2, $equal)
    {
        $this->assertSame($equal, $id1->equals($id2));
    }

    public function getCompareData()
    {
        $account = $this->getMockBuilder('Venocl\GroupACLBundle\Tests\TestGroup')
                            ->setMockClassName('USI_AccountImpl')
                            ->getMock();
        $account
            ->expects($this->any())
            ->method('getName')
            ->will($this->returnValue('foo'))
        ;

        $token = $this->getMock('Symfony\Component\Security\Core\Authentication\Token\TokenInterface');
        $token
            ->expects($this->any())
            ->method('getGroup')
            ->will($this->returnValue($account))
        ;

        return array(
            array(new GroupSecurityIdentity('foo', 'Foo'), new GroupSecurityIdentity('foo', 'Foo'), true),
            array(new GroupSecurityIdentity('foo', 'Bar'), new GroupSecurityIdentity('foo', 'Foo'), false),
            array(new GroupSecurityIdentity('foo', 'Foo'), new GroupSecurityIdentity('bar', 'Foo'), false),
            array(new GroupSecurityIdentity('foo', 'Foo'), GroupSecurityIdentity::fromAccount($account), false),
            array(new GroupSecurityIdentity('bla', 'Foo'), new GroupSecurityIdentity('blub', 'Foo'), false),
            array(new GroupSecurityIdentity('foo', 'Foo'), new RoleSecurityIdentity('foo'), false),
        );
    }
}
