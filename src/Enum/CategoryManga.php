<?php
namespace App\Enum;

enum CategoryManga: string
{
    case SHONEN = 'shonen';
    case SEINEN = 'seinen';
    case SHOJO = 'shojo';
    case JOSEI = 'josei';
    case LIGTHNOVEL = 'ligthnovel';

    public function label(): string
    {
        return match ($this) {
            self::SHONEN => 'Shōnen',
            self::SEINEN => 'Seinen',
            self::SHOJO => 'Shōjo',
            self::JOSEI => 'Josei',
            self::LIGTHNOVEL => 'Light Novel',
        };
    }
}
?>