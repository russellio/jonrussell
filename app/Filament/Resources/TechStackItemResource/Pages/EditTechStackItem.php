<?php

namespace App\Filament\Resources\TechStackItemResource\Pages;

use App\Filament\Resources\TechStackItemResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTechStackItem extends EditRecord
{
    protected static string $resource = TechStackItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
