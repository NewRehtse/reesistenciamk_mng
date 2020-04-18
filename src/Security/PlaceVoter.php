<?php

namespace App\Security;

use App\Persistence\Doctrine\Entity\Place;
use App\Persistence\Doctrine\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Security;

class PlaceVoter extends Voter
{
    public const EDIT = 'place_edit';
    public const LIST = 'place_list';
    public const CREATE = 'place_create';
    public const CREATE_VALID_PLACES = 'place_create_valid';
    public const DELETE = 'place_delete';
    public const COVER_NEED = 'place_cover_need';
    public const ADD_NEED = 'place_add_need';
    public const ADD_VALID_NEED = 'place_add_valid_need';
    public const LIST_NEEDS = 'place_list_needs';

    private const VALID_ATTRIBUTES = [
        self::EDIT,
        self::CREATE,
        self::LIST,
        self::LIST_NEEDS,
        self::DELETE,
        self::COVER_NEED,
        self::ADD_NEED,
        self::ADD_VALID_NEED,
        self::CREATE_VALID_PLACES,
    ];

    /** @var Security */
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    private function isAttributeValid(string $attribute): bool
    {
        return \in_array($attribute, static::VALID_ATTRIBUTES, true);
    }

    protected function supports($attribute, $subject)
    {
        if (!$this->isAttributeValid($attribute)) {
            return false;
        }

        if (null === $subject) {
            return true;
        }

        return $subject instanceof Place;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();

        if (!$user instanceof User) {
            // the user must be logged in; if not, deny access
            return false;
        }

        switch ($attribute) {
            case self::EDIT:
                return $this->canEdit($user, $subject);
            case self::LIST:
                return $this->canList($user, $subject);
            case self::CREATE:
                return $this->canCreate($user, $subject);
            case self::CREATE_VALID_PLACES:
                return $this->canCreateValidPlaces($user, $subject);
            case self::DELETE:
                return $this->canDelete($user, $subject);
            case self::COVER_NEED:
                return $this->canCoverNeed($user, $subject);
            case self::ADD_NEED:
                return $this->canAddNeed($user, $subject);
            case self::LIST_NEEDS:
                return $this->canListNeeds($user, $subject);
            case self::ADD_VALID_NEED:
                return $this->canAddValidNeed($user, $subject);
            default:
                throw new \LogicException('This code should not be reached!');
        }
    }

    private function canEdit(User $user, ?Place $place): bool
    {
        // only admins, healthy and owners can edit a place
        $validRoles = ['ROLE_ADMIN', 'ROLE_HEALTH'];

        foreach ($validRoles as $validRole) {
            if ($this->security->isGranted($validRole)) {
                return true;
            }
        }

        return null !== $place && $user === $place->owner();
    }

    private function canCreate(User $user, ?Place $place): bool
    {
        // Everybody can create places (but if not admin is not valid)
        return true;
    }

    private function canCreateValidPlaces(User $user, ?Place $place): bool
    {
        // Everybody can create places but only admins and health systems can create valid places
        $validRoles = ['ROLE_ADMIN', 'ROLE_HEALTH'];

        foreach ($validRoles as $validRole) {
            if ($this->security->isGranted($validRole)) {
                return true;
            }
        }

        return false;
    }

    private function canList(User $user, ?Place $place): bool
    {
        // Everybody can list places
        return true;
    }

    private function canDelete(User $user, ?Place $place): bool
    {
        //At the moment is the same than edit
        return $this->canEdit($user, $place);
    }

    private function canAddNeed(User $user, ?Place $place): bool
    {
        //Everybody can add need but is not valid
        return true;
    }

    private function canAddValidNeed(User $user, ?Place $place): bool
    {
        //Only admin and healty systems can do it. same as create places
        return $this->canCreateValidPlaces($user, $place);
    }

    private function canCoverNeed(User $user, ?Place $place): bool
    {
        //At the moment is the same than edit
        return $this->canEdit($user, $place);
    }

    private function canListNeeds(User $user, ?Place $place): bool
    {
        return true;
    }
}
