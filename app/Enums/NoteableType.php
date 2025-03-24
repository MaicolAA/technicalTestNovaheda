<?php

namespace App\Enums;

enum NoteableType: string
{
    case COMPANY = 'company';
    case CONTACT = 'contact';

    // Añadir para cuantos modelos sea necesaria la relación

    /**
     * Obtiene los valores válidos del enum.
     *
     * @return array
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}