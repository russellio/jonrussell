<?php

namespace App\Filament\Resources;

use App\Filament\Resources\IconResource\Pages;
use App\Models\Icon;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class IconResource extends Resource
{
    protected static ?string $model = Icon::class;

    protected static ?string $navigationIcon = 'heroicon-o-photo';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('icon_type')
                    ->options([
                        'fa' => 'FontAwesome',
                        'si' => 'Simple Icons',
                    ])
                    ->required()
                    ->default('fa')
                    ->rules(['in:'.implode(',', \App\Models\Icon::VALID_ICON_TYPES)]),
                Forms\Components\TextInput::make('icon_name')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('icon_type')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'fa' => 'success',
                        'si' => 'info',
                        default => 'gray',
                    })
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('icon_name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('techStackItems_count')
                    ->counts('techStackItems')
                    ->label('Tech Stack Items')
                    ->sortable(),
                Tables\Columns\TextColumn::make('skills_count')
                    ->counts('skills')
                    ->label('Skills')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('icon_type')
                    ->options([
                        'fa' => 'FontAwesome',
                        'si' => 'Simple Icons',
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListIcons::route('/'),
            'create' => Pages\CreateIcon::route('/create'),
            'view' => Pages\ViewIcon::route('/{record}'),
            'edit' => Pages\EditIcon::route('/{record}/edit'),
        ];
    }
}
