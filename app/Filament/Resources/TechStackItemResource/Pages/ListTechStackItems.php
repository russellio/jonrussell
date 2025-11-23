<?php

namespace App\Filament\Resources\TechStackItemResource\Pages;

use App\Filament\Resources\TechStackItemResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTechStackItems extends ListRecords
{
    protected static string $resource = TechStackItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
