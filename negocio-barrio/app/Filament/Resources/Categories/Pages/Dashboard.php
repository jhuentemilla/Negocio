<?php

namespace App\Filament\Resources\Categories\Pages;

use App\Filament\Resources\Categories\CategoryResource;
use Filament\Resources\Pages\Page;

class Dashboard extends Page
{
    protected static string $resource = CategoryResource::class;

    protected string $view = 'filament.resources.categories.pages.dashboard';
}
