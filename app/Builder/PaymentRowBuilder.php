<?php

namespace App\Builder;

use App\DTO\PaymentDTO;

class PaymentRowBuilder
{
    /**
     * Передаем динамические поля для динамического импорта
     * id проекта
     * и сформированные заголовки
     *
     * Разбиваем динамические поля в цикле на key - value
     * Проверяем есть ли у нас данный заголовок в списке разрешенных, если нет то пропускаем.
     * Иначе
     * Создаем payment DTO и помещаем его в массив payments
     * Возвращаем платежи
     */
    public function build(
        array $dynamicRow,
        int $projectId,
        array $headers
    ): array
    {
        $payments = [];

        foreach ($dynamicRow as $key => $value) {
            if (!isset($headers[$key])) {
                continue;
            }

            $payments[] = new PaymentDTO(
                projectId: $projectId,
                title: $headers[$key],
                value: (float) $value,
            );
        }

        return $payments;
    }
}
