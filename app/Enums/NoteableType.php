<?php

// namespace App\Enums;

// enum NoteableType: string
// {
//     case COMPANY = 'company';
//     case CONTACT = 'contact';

//     // Añadir para cuantos modelos sea necesaria la relación

//     /**
//      * Obtiene los valores válidos del enum.
//      *
//      * @return array
//      */
//     public static function values(): array
//     {
//         return array_column(self::cases(), 'value');
//     }

//     /**
//      * Obtiene el modelo de base de datos asociado a cada caso del enum.
//      */
//     public static function getNoteable(string $noteableEntity): ?string
//     {
//         return match ($noteableEntity) {
//             self::COMPANY->value => "App\Models\Company",
//             self::CONTACT->value => "App\Models\Contact",
//             default => null,
//         };
//     }

//     /**
//      * Obtiene el enum a partir del nombre del modelo.
//      * @param string $modelClass
//      */
//     public static function getNoteableByModel(string $modelClass): ?self
//     {
//         return match ($modelClass) {
//             "App\Models\Company" => self::COMPANY,
//             "App\Models\Contact" => self::CONTACT,
//             default => null,
//         };
//     }
// }


namespace App\Enums;

use App\Models\Company;
use App\Models\Contact;

enum NoteableType: string
{
    case COMPANY = 'company';
    case CONTACT = 'contact';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public static function getModelClass(string $noteableType): string
    {
        return match ($noteableType) {
            self::COMPANY->value => Company::class,
            self::CONTACT->value => Contact::class,
            default => throw new \InvalidArgumentException("Invalid noteable type: $noteableType")
        };
    }

    public static function getSimpleType(string $modelClass): string
    {
        return match ($modelClass) {
            Company::class => self::COMPANY->value,
            Contact::class => self::CONTACT->value,
            default => throw new \InvalidArgumentException("Invalid model class: $modelClass")
        };
    }
}