<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TechStackItemResource\Pages;
use App\Models\TechStackItem;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class TechStackItemResource extends Resource
{
    protected static ?string $model = TechStackItem::class;

    protected static ?string $navigationIcon = 'heroicon-o-cpu-chip';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('skill_id')
                    ->relationship('skill', 'name')
                    ->searchable()
                    ->preload(),
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('percent')
                    ->numeric()
                    ->minValue(0)
                    ->maxValue(100)
                    ->suffix('%'),
                Forms\Components\Select::make('icon_id')
                    ->relationship('icon', 'icon_name')
                    ->searchable()
                    ->preload()
                    ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->icon_type} - {$record->icon_name}"),
                Forms\Components\Toggle::make('active')
                    ->required()
                    ->default(true),
                Forms\Components\TextInput::make('order')
                    ->required()
                    ->numeric()
                    ->default(0),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('skill.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('percent')
                    ->numeric()
                    ->sortable()
                    ->suffix('%'),
                Tables\Columns\TextColumn::make('icon.icon_type')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'fa' => 'success',
                        'si' => 'info',
                        default => 'gray',
                    })
                    ->searchable(),
                Tables\Columns\TextColumn::make('icon.icon_name')
                    ->searchable(),
                Tables\Columns\IconColumn::make('active')
                    ->boolean(),
                Tables\Columns\TextColumn::make('order')
                    ->numeric()
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
                //
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
            'index' => Pages\ListTechStackItems::route('/'),
            'create' => Pages\CreateTechStackItem::route('/create'),
            'view' => Pages\ViewTechStackItem::route('/{record}'),
            'edit' => Pages\EditTechStackItem::route('/{record}/edit'),
        ];
    }
}
