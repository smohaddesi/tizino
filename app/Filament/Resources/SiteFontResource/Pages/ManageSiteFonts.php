<?php

namespace App\Filament\Resources\SiteFontResource\Pages;

use App\Filament\Resources\SiteFontResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageSiteFonts extends ManageRecords
{
    protected static string $resource = SiteFontResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()->label('افزودن فونت'),
        ];
    }
}
