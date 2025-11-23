<?php

namespace App\Filament\Resources\SkillTypeResource\Pages;

use App\Filament\Resources\SkillTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSkillType extends EditRecord
{
    protected static string $resource = SkillTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
