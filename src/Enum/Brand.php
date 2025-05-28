<?php
namespace App\Enum;

enum Brand: string
{
    case FUNKOPOP = 'funko-pop';
    case BANDAI = 'bandai';
    case BANPRESTO = 'banpresto';
    case KOTOBUKIYA = 'kotobukiya';
    case SEGA = 'sega';
    case OTHER = 'other';
}
?>