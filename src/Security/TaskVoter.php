<?php

namespace App\Security;

use App\Persistence\Doctrine\Entity\Task;
use App\Persistence\Doctrine\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Security;

class TaskVoter extends Voter
{
    public const LIST = 'task_list';
    public const VIEW = 'task_view';
    public const EDIT = 'task_edit';
    public const CREATE = 'task_create';

    private const VALID_ATTRIBUTES = [
            self::EDIT,
            self::LIST,
            self::VIEW,
            self::CREATE,
    ];

    /** @var Security */
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

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

        return $subject instanceof Task;
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
            case self::EDIT:
                return $this->canEdit($user, $subject);
            case self::LIST:
                return $this->canList($user, $subject);
            case self::CREATE:
                return $this->canCreate($user, $subject);
            case self::VIEW:
                return $this->canView($user, $subject);
            default:
                throw new \LogicException('This code should not be reached!');
        }
    }

    private function isAttributeValid(string $attribute): bool
    {
        return \in_array($attribute, static::VALID_ATTRIBUTES, true);
    }

    private function canView(User $user, ?Task $subject): bool
    {
        return true;
    }

    private function canEdit(User $user, ?Task $subject): bool
    {
        // only admins and owners can edit a place
        $validRoles = ['ROLE_ADMIN'];

        foreach ($validRoles as $validRole) {
            if ($this->security->isGranted($validRole)) {
                return true;
            }
        }

        return null !== $subject && $user === $subject->maker()->user();
    }

    private function canList(User $user, ?Task $subject): bool
    {
        return true;
    }

    private function canCreate(User $user, ?Task $subject): bool
    {
        return true;
    }
}
