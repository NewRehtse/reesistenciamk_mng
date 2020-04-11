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

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class OrchestratorChain implements OrchestratorChainInterface
{
    /** @var \SplPriorityQueue */
    private $orchestrators;

    /**
     * Orchestrator constructor.
     */
    public function __construct()
    {
        $this->orchestrators = new \SplPriorityQueue();
    }

    /**
     * @param int $priority
     */
    public function addOrchestrator(OrchestratorInterface $orchestrator, $priority = 0): void
    {
        $this->orchestrators->insert($orchestrator, $priority);
    }

    /**
     * @throws NotFoundHttpException
     *
     * @return array<mixed>
     */
    public function content(Request $request, string $type): array
    {
        foreach ($this->orchestrators as $orchestrator) {
            if ($orchestrator->canHandleContentOfType($type)) {
                return $orchestrator->content($request, $type);
            }
        }

        throw new NotFoundHttpException(\sprintf('No orchestrator available for type "%s"', $type));
    }

    public function canHandleContentOfType(string $type): bool
    {
        return true;
    }
}
