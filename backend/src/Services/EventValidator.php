<?php

namespace App\Services;

use App\Models\Event;

class EventValidator
{
    private static Event $eventModel;

    public static function validate(array $data, ?int $eventId = null): array
    {
        if (!isset(self::$eventModel)) {
            self::$eventModel = new Event();
        }

        $errors = [];

        if (empty($data['title'])) {
            $errors[] = "O título é obrigatório";
        }

        if (empty($data['start'])) {
            $errors[] = "A data de início é obrigatória";
        } elseif (!self::isValidDateTime($data['start'])) {
            $errors[] = "Data de início inválida";
        }

        if (empty($data['end'])) {
            $errors[] = "A data de término é obrigatória";
        } elseif (!self::isValidDateTime($data['end'])) {
            $errors[] = "Data de término inválida";
        }

        if (!empty($data['start']) && !empty($data['end'])) {
            if (strtotime($data['end']) < strtotime($data['start'])) {
                $errors[] = "A data de término deve ser posterior à data de início";
            }

            if (empty($errors) && self::$eventModel->hasTimeConflict($data['start'], $data['end'], $eventId)) {
                $errors[] = "Já existe um evento programado para este período. Por favor, escolha outro horário";
            }
        }

        return $errors;
    }

    private static function isValidDateTime(string $dateTime): bool
    {
        $format = 'Y-m-d H:i:s';
        $d = \DateTime::createFromFormat($format, $dateTime);
        return $d && $d->format($format) === $dateTime;
    }
}
