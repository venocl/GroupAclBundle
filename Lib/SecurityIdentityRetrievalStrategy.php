<?php

namespace Venocl\GroupACLBundle\Lib;

use Symfony\Component\Security\Core\Authentication\Token\AnonymousToken;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Acl\Model\SecurityIdentityRetrievalStrategyInterface;
use Symfony\Component\Security\Core\Authentication\AuthenticationTrustResolver;
use Symfony\Component\Security\Core\Role\RoleHierarchyInterface;
use Symfony\Component\Security\Core\Authorization\Voter\AuthenticatedVoter;
use Symfony\Component\Security\Acl\Domain\UserSecurityIdentity;

/**
 * Strategy for retrieving security identities
 *
 * @author Johannes M. Schmitt <schmittjoh@gmail.com>
 */
class SecurityIdentityRetrievalStrategy implements SecurityIdentityRetrievalStrategyInterface
{
    private $roleHierarchy;
    private $authenticationTrustResolver;

    /**
     * Constructor
     *
     * @param RoleHierarchyInterface      $roleHierarchy
     * @param AuthenticationTrustResolver $authenticationTrustResolver
     */
    public function __construct(RoleHierarchyInterface $roleHierarchy, AuthenticationTrustResolver $authenticationTrustResolver)
    {
        $this->roleHierarchy = $roleHierarchy;
        $this->authenticationTrustResolver = $authenticationTrustResolver;
    }

    /**
     * {@inheritDoc}
     */
    public function getSecurityIdentities(TokenInterface $token)
    {
        $sids = array();

        // add user security identity
        if (!$token instanceof AnonymousToken) {
            try {
                $sids[] = UserSecurityIdentity::fromToken($token);
                //Get the groups sids
                $user = $token->getUser();
                if(method_exists($user, "getGroups")) {
                    foreach ($user->getGroups() as $group) {
                        $sids[] = GroupSecurityIdentity::fromAccount($group);
                    }
                }
            } catch (\InvalidArgumentException $invalid) {
                // ignore, user has no user security identity
            }
        }

        // add all reachable roles
        foreach ($this->roleHierarchy->getReachableRoles($token->getRoles()) as $role) {
            $sids[] = new RoleSecurityIdentity($role);
        }

        // add built-in special roles
        if ($this->authenticationTrustResolver->isFullFledged($token)) {
            $sids[] = new RoleSecurityIdentity(AuthenticatedVoter::IS_AUTHENTICATED_FULLY);
            $sids[] = new RoleSecurityIdentity(AuthenticatedVoter::IS_AUTHENTICATED_REMEMBERED);
            $sids[] = new RoleSecurityIdentity(AuthenticatedVoter::IS_AUTHENTICATED_ANONYMOUSLY);
        } elseif ($this->authenticationTrustResolver->isRememberMe($token)) {
            $sids[] = new RoleSecurityIdentity(AuthenticatedVoter::IS_AUTHENTICATED_REMEMBERED);
            $sids[] = new RoleSecurityIdentity(AuthenticatedVoter::IS_AUTHENTICATED_ANONYMOUSLY);
        } elseif ($this->authenticationTrustResolver->isAnonymous($token)) {
            $sids[] = new RoleSecurityIdentity(AuthenticatedVoter::IS_AUTHENTICATED_ANONYMOUSLY);
        }

        return $sids;
    }
}
