<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OptionGroupResource\Pages;
use App\Models\OptionGroup;
use App\Models\Restaurant;
use Filament\Forms\Form;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Grid;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\BadgeColumn;
use Illuminate\Support\Str;

class OptionGroupResource extends Resource
{
    protected static ?string $model = OptionGroup::class;

    protected static ?string $navigationIcon = 'heroicon-o-adjustments-horizontal';
    protected static ?string $navigationLabel = 'Grupos de adicionais';
    protected static ?string $modelLabel = 'Grupo de adicional';
    protected static ?string $pluralModelLabel = 'Grupos de adicionais';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Section::make('Grupo')
                ->schema([
                    Select::make('restaurant_id')
                        ->label('Restaurante')
                        ->options(Restaurant::query()->orderBy('name')->pluck('name', 'id'))
                        ->searchable()
                        ->required(),

                    TextInput::make('name')
                        ->label('Nome')
                        ->required()
                        ->maxLength(255)
                        ->live(onBlur: true)
                        ->afterStateUpdated(function ($state, callable $set) {
                            $set('slug', Str::slug((string) $state));
                        }),

                    TextInput::make('slug')
                        ->label('Slug')
                        ->required()
                        ->maxLength(255)
                        ->helperText('Usado internamente. Ex: adicionais, bebida, batata'),

                    Grid::make(3)->schema([
                        TextInput::make('min_select')
                            ->label('Mínimo')
                            ->numeric()
                            ->default(0)
                            ->minValue(0)
                            ->helperText('0 = opcional'),

                        TextInput::make('max_select')
                            ->label('Máximo')
                            ->numeric()
                            ->default(0)
                            ->minValue(0)
                            ->helperText('0 = sem limite'),

                        TextInput::make('sort_order')
                            ->label('Ordem')
                            ->numeric()
                            ->default(0)
                            ->minValue(0),
                    ]),

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
                TextColumn::make('restaurant.name')
                    ->label('Restaurante')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('name')
                    ->label('Grupo')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('slug')
                    ->label('Slug')
                    ->toggleable(isToggledHiddenByDefault: true),

                BadgeColumn::make('min_select')
                    ->label('Min')
                    ->sortable(),

                BadgeColumn::make('max_select')
                    ->label('Max')
                    ->sortable(),

                TextColumn::make('sort_order')
                    ->label('Ordem')
                    ->sortable(),

                IconColumn::make('is_active')
                    ->label('Ativo')
                    ->boolean(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('restaurant_id')
                    ->label('Restaurante')
                    ->options(Restaurant::query()->orderBy('name')->pluck('name', 'id')),

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
            // Depois a gente pode colocar RelationManager pra cadastrar Options dentro do Grupo
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOptionGroups::route('/'),
            'create' => Pages\CreateOptionGroup::route('/create'),
            'edit' => Pages\EditOptionGroup::route('/{record}/edit'),
        ];
    }
}
