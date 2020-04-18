<?php

namespace App\Security;

use App\Persistence\Doctrine\Entity\Thing;
use App\Persistence\Doctrine\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Security;

/**
 * @author Esther Ibáñez González <eibanez@ces.vocento.com>
 */
class ThingVoter extends Voter
{
    public const CREATE = 'thing_create';
    public const CREATE_VALID = 'thing_create_valid';
    public const EDIT = 'thing_edit';
    public const LIST = 'thing_list';
    public const DELETE = 'thing_delete';
    public const VIEW = 'thing_view';

    private const VALID_ATTRIBUTES = [
            self::LIST,
            self::EDIT,
            self::DELETE,
            self::VIEW,
            self::CREATE,
            self::CREATE_VALID,
    ];

    /**
     * @inheritDoc
     */
    protected function supports($attribute, $subject)
    {
        if (!$this->isAttributeValid($attribute)) {
            return false;
        }

        if (null === $subject) {
            return true;
        }

        return $subject instanceof Thing;
    }

    /**
     * @inheritDoc
     */
    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();

        if (!$user instanceof User) {
            // the user must be logged in; if not, deny access
            return false;
        }

        switch ($attribute) {
            case self::VIEW:
                return $this->canView($user, $subject);
            case self::EDIT:
                return $this->canEdit($user, $subject);
            case self::LIST:
                return $this->canList($user, $subject);
            case self::CREATE:
                return $this->canCreate($user, $subject);
            case self::CREATE_VALID:
                return $this->canCreateValid($user, $subject);
            case self::DELETE:
                return $this->canDelete($user, $subject);
            default:
                throw new \LogicException('This code should not be reached!');
        }
    }

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

    private function canView(User $user, ?Thing $subject): bool
    {
        return true;
    }

    private function canEdit(User $user, ?Thing $subject): bool
    {
        // only admins, healthy and owners can edit
        $validRoles = ['ROLE_ADMIN', 'ROLE_HEALTH'];

        foreach ($validRoles as $validRole) {
            if ($this->security->isGranted($validRole)) {
                return true;
            }
        }

        return null !== $subject && $user === $subject->owner();
    }

    private function canList(User $user, ?Thing $subject): bool
    {
        return true;
    }

    private function canCreate(User $user, ?Thing $subject): bool
    {
        return true;
    }

    private function canCreateValid(User $user, ?Thing $subject): bool
    {
        // only admins, healthy and owners can create valid objects
        $validRoles = ['ROLE_ADMIN', 'ROLE_HEALTH'];

        foreach ($validRoles as $validRole) {
            if ($this->security->isGranted($validRole)) {
                return true;
            }
        }

        return false;
    }

    private function canDelete(User $user, ?Thing $subject): bool
    {
        return $this->canEdit($user, $subject);
    }
}
