<?php

namespace AppBundle\Security;

use AppBundle\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class UserVoter extends Voter {

    const SHOW = 'show';
    const EDIT = 'edit';

    /**
     * @var AccessDecisionManager
     */
    private $accessManager;

    public function __construct(AccessDecisionManagerInterface $accessManager) {

        $this->accessManager = $accessManager;
    }

    protected function supports($attribute, $subject): bool {

        // Comprobar que el parametro attribute contiene el metodo "show"
        if (!in_array($attribute, [self::SHOW, self::EDIT])) {

            return false;
        }

        // Comprobar que el parametro subject sea instancia de la entidad User
        if (!$subject instanceof User) {

            return false;
        }

        return true;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token): bool {

        // Comprobar si el usuario que llega por token tiene el ROLE ADMIN
        if ($this->accessManager->decide($token, [User::ROLE_ADMIN])) {

            return true;
        }

        switch ($attribute) {

            case self::SHOW:
            case self::EDIT:
                return $this->isUserHimself($subject, $token);
        }

        return false;
    }

    protected function isUserHimself($subject, TokenInterface $token) {

        $authenticatedUser = $token->getUser();

        if (!$authenticatedUser instanceof User) {

            return false;
        }

        return $authenticatedUser->getId() === $subject->getId();
    }

}
