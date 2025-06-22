<?php

namespace App\Twig;


use App\Enum\CategoryManga;
use twig\Extension\GlobalsInterface;
use twig\Extension\AbstractExtension;

class AppExtension extends AbstractExtension implements GlobalsInterface
{
    public function getGlobals(): array
    {
        return [
            'categories' => CategoryManga::cases(), // Partage global des catégories
        ];
    }
}
