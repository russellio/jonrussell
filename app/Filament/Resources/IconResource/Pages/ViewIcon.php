<?php

namespace App\Filament\Resources\IconResource\Pages;

use App\Filament\Resources\IconResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewIcon extends ViewRecord
{
    protected static string $resource = IconResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
