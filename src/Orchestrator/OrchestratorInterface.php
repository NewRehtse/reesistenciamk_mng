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

interface OrchestratorInterface
{
    /**
     * @return array<string, mixed>
     */
    public function content(Request $request, string $type): array;

    public function canHandleContentOfType(string $type): bool;
}
