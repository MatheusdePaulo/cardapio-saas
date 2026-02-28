<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OptionResource\Pages;
use App\Models\Option;
use App\Models\OptionGroup;
use Filament\Forms\Form;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;

class OptionResource extends Resource
{
    protected static ?string $model = Option::class;

    protected static ?string $navigationIcon = 'heroicon-o-plus-circle';
    protected static ?string $navigationLabel = 'Adicionais';
    protected static ?string $modelLabel = 'Adicional';
    protected static ?string $pluralModelLabel = 'Adicionais';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Section::make('Adicional')
                ->schema([
                    Select::make('option_group_id')
                        ->label('Grupo')
                        ->options(OptionGroup::query()->orderBy('name')->pluck('name', 'id'))
                        ->searchable()
                        ->required(),

                    TextInput::make('name')
                        ->label('Nome')
                        ->required()
                        ->maxLength(255),

                    TextInput::make('price_cents')
                        ->label('Preço (centavos)')
                        ->numeric()
                        ->default(0)
                        ->minValue(0)
                        ->helperText('Ex: R$ 5,00 = 500'),

                    TextInput::make('sort_order')
                        ->label('Ordem')
                        ->numeric()
                        ->default(0)
                        ->minValue(0),

                    Toggle::make('is_active')
                        ->label('Ativo')
                        ->default(true),
                ])
                ->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('sort_order', 'asc')
            ->columns([
                TextColumn::make('optionGroup.restaurant.name')
                    ->label('Restaurante')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('optionGroup.name')
                    ->label('Grupo')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('name')
                    ->label('Adicional')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('price_cents')
                    ->label('Preço')
                    ->alignRight()
                    ->formatStateUsing(fn ($state) => 'R$ ' . number_format(($state ?? 0) / 100, 2, ',', '.'))
                    ->sortable(),

                TextColumn::make('sort_order')
                    ->label('Ordem')
                    ->sortable(),

                IconColumn::make('is_active')
                    ->label('Ativo')
                    ->boolean(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('option_group_id')
                    ->label('Grupo')
                    ->options(OptionGroup::query()->orderBy('name')->pluck('name', 'id')),

                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Ativo'),
            ])
            ->actions([
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
            'index' => Pages\ListOptions::route('/'),
            'create' => Pages\CreateOption::route('/create'),
            'edit' => Pages\EditOption::route('/{record}/edit'),
        ];
    }
}
