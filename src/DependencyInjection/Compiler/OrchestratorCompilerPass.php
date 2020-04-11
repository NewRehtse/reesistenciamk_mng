<?php

/*
 * This file is part of the Vocento SportsData application.
 *
 * (c) Vocento S.A., <desarrollo.dts@vocento.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */

namespace App\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class OrchestratorCompilerPass implements CompilerPassInterface
{
    private const ORCHESTRATOR_TAG = 'orchestrator';

    public function process(ContainerBuilder $container): void
    {
        $this->processOrchestrators($container);
    }

    /**
     * Registers all the orchestrators tagged as `orchestrator`.
     */
    private function processOrchestrators(ContainerBuilder $container): void
    {
        if (false === $container->hasDefinition('app.orchestrator')) {
            return;
        }

        $definition = $container->getDefinition('app.orchestrator');

        foreach ($container->findTaggedServiceIds(self::ORCHESTRATOR_TAG) as $id => $attributes) {
            $definition->addMethodCall('addOrchestrator', [new Reference($id), $id]);
        }
    }
}
