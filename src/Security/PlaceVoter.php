<?php

namespace App\Security;

use App\Persistence\Doctrine\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Security;

/**
 * @author Esther Ibáñez González <eibanez@ces.vocento.com>
 */
class PlaceVoter extends Voter
{
    // these strings are just invented: you can use anything
    public const VIEW = 'place_view';
    public const EDIT = 'place_edit';
    public const LIST = 'place_list';
    public const CREATE = 'place_create';
    public const DELETE = 'place_delete';
    public const COVER_NEED = 'place_cover_need';

    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    private function isAttributeValid($attribute): bool
    {
        $validAttributes = [
            static::EDIT,
            static::CREATE,
            static::LIST,
            static::VIEW,
            static::DELETE,
            static::COVER_NEED,
        ];

        return \in_array($attribute, $validAttributes, true);
    }

    protected function supports($attribute, $subject)
    {
        // if the attribute isn't one we support, return false
        if (!$this->isAttributeValid($attribute)) {
            return false;
        }

        // only vote on `Post` objects
//        if (!$subject instanceof Post) {
//            return false;
//        }

        return true;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();

        if (!$user instanceof User) {
            // the user must be logged in; if not, deny access
            return false;
        }

        switch ($attribute) {
            case self::VIEW:
                return $this->canView($user);
            case self::EDIT:
                return $this->canEdit($user);
            case self::LIST:
                return $this->canList($user);
            case self::CREATE:
                return $this->canCreate($user);
            case self::DELETE:
                return $this->canDelete($user);
            case self::COVER_NEED:
                return $this->canCoverNeed($user);
            default:
                \var_dump($attribute); die();
                throw new \LogicException('This code should not be reached!');
        }
    }

    private function canView(User $user)
    {
        // if they can edit, they can view
        if ($this->canEdit($user)) {
            return true;
        }

        // everybody can view detail of place
        return true;
    }

    private function canEdit(User $user)
    {
        // only admins and healty can edit a place
        $validRoles = ['ROLE_ADMIN', 'ROLE_HEALTH'];

        foreach ($validRoles as $validRole) {
            if ($this->security->isGranted($validRole)) {
                return true;
            }
        }

        return false;
    }

    private function canCreate(User $user)
    {
        // Everybody can create places (but if not admin is not valid)
        return true;
    }

    private function canList(User $user)
    {
        // Everybody can list places
        return true;
    }

    private function canDelete(User $user)
    {
        // only admins and healty can edit a place
        $validRoles = ['ROLE_ADMIN', 'ROLE_HEALTH'];

        foreach ($validRoles as $validRole) {
            if ($this->security->isGranted($validRole)) {
                return true;
            }
        }

        return false;
    }

    private function canCoverNeed(User $user)
    {
        // only admins and healty can edit a place
        $validRoles = ['ROLE_ADMIN', 'ROLE_HEALTH'];

        foreach ($validRoles as $validRole) {
            if ($this->security->isGranted($validRole)) {
                return true;
            }
        }

        return false;
    }
}
