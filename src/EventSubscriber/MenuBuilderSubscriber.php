<?php

/*
 * This file is part of the AdminLTE-Bundle demo.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\EventSubscriber;

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
            new MenuItemModel('places', 'Sitios', 'places', [], 'fa fa-map-marker')
        );

        $event->addItem(
            new MenuItemModel('things', 'Imprimibles', 'things', [], 'fa fa-print')
        );

        $event->addItem(
            new MenuItemModel('tasks', 'Tareas', 'tasks', [], 'fa fa-print')
        );
        $event->addItem(
            new MenuItemModel('profile', 'Perfil', 'users.profile', [], 'fa fa-user')
        );
//
//        $event->addItem(
//            new MenuItemModel('forms', 'menu.form', 'forms', [], 'fab fa-wpforms')
//        );
//
//        $event->addItem(
//            new MenuItemModel('context', 'AdminLTE context', 'context', [], 'fas fa-code')
//        );
//
//        $demo = new MenuItemModel('demo', 'Demo', null, [], 'far fa-arrow-alt-circle-right');
//        $demo->addChild(
//            new MenuItemModel('sub-demo', 'Form - Horizontal', 'forms2', [], 'far fa-arrow-alt-circle-down')
//        )->addChild(
//            new MenuItemModel('sub-demo2', 'Form - Sidebar', 'forms3', [], 'far fa-arrow-alt-circle-up')
//        );
//        $event->addItem($demo);
        if ($this->security->isGranted('ROLE_ADMIN')) {
            $event->addItem(
                new MenuItemModel('users', 'Usuarios', 'users.admin', [], 'fa fa-users')
            );
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
