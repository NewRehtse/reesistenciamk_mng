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

namespace App\Twig;

use App\Entity\Task;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 * @author Esther Ibáñez González <eibanez@ces.vocento.com>
 */
class TextExtension extends AbstractExtension
{
    public function getFunctions(): array
    {
        return [
            new TwigFunction('getDeliveryTypeText', [$this, 'getDeliveryTypeText']),
            new TwigFunction('getStatusTypeText', [$this, 'getStatusTypeText']),
        ];
    }

    public function getDeliveryTypeText(int $deliveryType): string
    {
        return Task::GetDeliveryTypeText($deliveryType);
    }

    public function getStatusTypeText(int $status): string
    {
        return Task::GetStatusText($status);
    }
}
