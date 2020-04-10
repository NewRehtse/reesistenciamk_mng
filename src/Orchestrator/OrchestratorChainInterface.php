<?php
/*
 * This file is part of the Vocento Software.
 *
 * (c) Vocento S.A., <desarrollo.dts@vocento.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */

namespace App\Orchestrator;

interface OrchestratorChainInterface extends OrchestratorInterface
{
    /**
     * @param int $priority
     */
    public function addOrchestrator(OrchestratorInterface $orchestrator, $priority = 0): void;
}
