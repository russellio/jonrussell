<?php

namespace App\Filament\Resources\TechStackItemResource\Pages;

use App\Filament\Resources\TechStackItemResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewTechStackItem extends ViewRecord
{
    protected static string $resource = TechStackItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
