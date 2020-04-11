<?php

/*
 * This file is part of the AdminLTE-Bundle demo.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\EventSubscriber;

use App\Security\AdminUsersVoter;
use KevinPapst\AdminLTEBundle\Event\BreadcrumbMenuEvent;
use KevinPapst\AdminLTEBundle\Event\SidebarMenuEvent;
use KevinPapst\AdminLTEBundle\Model\MenuItemModel;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * Class MenuBuilder configures the main navigation.
 */
class MenuBuilderSubscriber implements EventSubscriberInterface
{
    /**
     * @var AuthorizationCheckerInterface
     */
    private $security;

    public function __construct(AuthorizationCheckerInterface $security)
    {
        $this->security = $security;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            SidebarMenuEvent::class => ['onSetupNavbar', 100],
            BreadcrumbMenuEvent::class => ['onSetupNavbar', 100],
        ];
    }

    /**
     * Generate the main menu.
     */
    public function onSetupNavbar(SidebarMenuEvent $event)
    {
        $event->addItem(
            new MenuItemModel('places', 'Demandantes', 'places', [], 'fa fa-map-marker')
        );

        $event->addItem(
            new MenuItemModel('things', 'Imprimibles', 'things', [], 'fa fa-print')
        );

        $event->addItem(
            new MenuItemModel('tasks', 'Lotes', 'tasks', [], 'fa fa-print')
        );
        $event->addItem(
            new MenuItemModel('profile', 'Perfil', 'users.profile', [], 'fa fa-user')
        );

        if ($this->security->isGranted(AdminUsersVoter::MENU)) {
            $event->addItem(new MenuItemModel('users', 'Usuarios', 'users.admin', [], 'fa fa-users'));
        }

        if ($this->security->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            $event->addItem(
                new MenuItemModel('logout', 'Logout', 'logout', [], 'fas fa-sign-in-alt')
            );
        }
    }

    /**
     * @param string          $route
     * @param MenuItemModel[] $items
     */
    protected function activateByRoute($route, $items)
    {
        foreach ($items as $item) {
            if ($item->hasChildren()) {
                $this->activateByRoute($route, $item->getChildren());
            } elseif ($item->getRoute() === $route) {
                $item->setIsActive(true);
            }
        }
    }
}
