<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Venocl\GroupACLBundle\Lib;

use Symfony\Component\Security\Acl\Model\SecurityIdentityInterface;

final class GroupSecurityIdentity implements SecurityIdentityInterface
{
    private $groupname;
    private $class;

    /**
     * Constructor
     *
     * @param string $groupname the groupname representation
     * @param string $class the group's fully qualified class name
     */
    public function __construct($groupname, $class)
    {
        if (empty($groupname)) {
            throw new \InvalidArgumentException('$groupname must not be empty.');
        }
        if (empty($class)) {
            throw new \InvalidArgumentException('$class must not be empty.');
        }

        $this->groupname = (string) $groupname;
        $this->class = $class;
    }

    /**
     * Creates a group security identity from a Group
     *
     * @param Group $group
     * @return GroupSecurityIdentity
     */
    static public function fromAccount($group)
    {
        return new self($group->getName(), get_class($group));
    }

    /**
     * Returns the groupname
     *
     * @return string
     */
    public function getGroupname()
    {
        return $this->groupname;
    }

    /**
     * Returns the group's class name
     *
     * @return string
     */
    public function getClass()
    {
        return $this->class;
    }

    /**
     * {@inheritDoc}
     */
    public function equals(SecurityIdentityInterface $sid)
    {
        if (!$sid instanceof GroupSecurityIdentity) {
            return false;
        }

        return $this->groupname === $sid->getGroupname()
               && $this->class === $sid->getClass();
    }

    /**
     * A textual representation of this security identity.
     *
     * This is not used for equality comparison, but only for debugging.
     *
     * @return string
     */
    public function __toString()
    {
        return sprintf('GroupSecurityIdentity(%s, %s)', $this->groupname, $this->class);
    }
}
