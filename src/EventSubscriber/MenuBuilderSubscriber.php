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
                new MenuItemModel('imprimibles', 'Imprimibles', 'things', [], 'fas fa-sign-in-alt')
        );

        if ($this->security->isGranted(AdminUsersVoter::MENU_ADMIN)) {
            $administacion = new MenuItemModel('administacion', 'Administración', '', [], 'fa fa-cogs');
            $administacion->setIsActive(true);
            $administacion->addChild(
                    new MenuItemModel('users', 'Usuarios', 'users.admin', [], 'fa fa-users')
            );
            $administacion->addChild(
                    new MenuItemModel('configuration', 'Configuración', 'admin.configuration', [], 'fa fa-cog')
            );
            $administacion->addChild(
                    new MenuItemModel('places', 'Demandantes', 'admin.places.list', [], 'fa fa-map-marker')
            );

            $administacion->addChild(
                    new MenuItemModel('things', 'Imprimibles', 'admin.things.list', [], 'fa fa-print')
            );

            $administacion->addChild(
                    new MenuItemModel('tasks', 'Lotes', 'admin.tasks.list', [], 'fa fa-print')
            );
            $administacion->addChild(
                    new MenuItemModel('profile', 'Perfil', 'users.profile', [], 'fa fa-user')
            );
            $event->addItem($administacion);
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
