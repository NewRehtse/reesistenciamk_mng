<?php

namespace App\Security;

use App\Persistence\Doctrine\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Security;

class AdminUsersVoter extends Voter
{
    public const VIEW = 'user_view';
    public const EDIT = 'user_edit';
    public const CREATE = 'user_create';
    public const LIST = 'user_list';
    public const MENU = 'user_menu';

    private const VALID_ATTRIBUTES = [
            self::EDIT,
            self::CREATE,
            self::VIEW,
            self::LIST,
            self::MENU,
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
        return $this->isAttributeValid($attribute);
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

        //Sólo los admins pueden acceder aquí
        return $this->security->isGranted('ROLE_ADMIN');
    }

    private function isAttributeValid(string $attribute): bool
    {
        return \in_array($attribute, static::VALID_ATTRIBUTES, true);
    }
}
