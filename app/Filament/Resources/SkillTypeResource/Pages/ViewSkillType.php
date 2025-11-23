<?php

namespace App\Filament\Resources\SkillTypeResource\Pages;

use App\Filament\Resources\SkillTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewSkillType extends ViewRecord
{
    protected static string $resource = SkillTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
