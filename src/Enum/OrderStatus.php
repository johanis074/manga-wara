<?php
// src/Enum/OrderStatus.php
namespace App\Enum;

enum OrderStatus: string
{
    case Recu = 'Reçu';
    case Preparation = 'Préparation';
    case Prete = 'Prête';
    case Expedie = 'Expédié';
    case Termine = 'Terminé';

    public static function next(self $current): ?self
    {
        $order = [
            self::Recu,
            self::Preparation,
            self::Prete,
            self::Expedie,
            self::Termine
        ];
        $index = array_search($current, $order);
        return $order[$index + 1] ?? null;
    }
}
