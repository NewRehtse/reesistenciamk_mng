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
        $deliveryTypeMap = [
            Task::DELIVER_TYPE_UNDEFINED => 'Por definir',
            Task::DELIVER_TYPE_COLLECT => 'Recogida',
            Task::DELIVER_TYPE_DELIVER => 'Entrega',
        ];

        return $deliveryTypeMap[$deliveryType] ?? Task::DELIVER_TYPE_UNDEFINED;
    }

    public function getStatusTypeText(int $status): string
    {
        $statusMap = [
            Task::STATUS_PENDING => 'Pendiente',
            Task::STATUS_DELIVERING => 'Entregandose',
            Task::STATUS_PROCESSING => 'Procesandose',
            Task::STATUS_DONE => 'Hecho',
        ];

        return $statusMap[$status] ?? Task::STATUS_PENDING;
    }
}
