<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Models\Order;
use Filament\Forms\Form;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';
    protected static ?string $navigationLabel = 'Pedidos';
    protected static ?string $modelLabel = 'Pedido';
    protected static ?string $pluralModelLabel = 'Pedidos';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Pedido')
                    ->schema([
                        TextInput::make('id')
                            ->label('ID')
                            ->disabled()
                            ->dehydrated(false),

                        TextInput::make('restaurant.name')
                            ->label('Restaurante')
                            ->disabled()
                            ->dehydrated(false),

                        TextInput::make('order_type')
                            ->label('Tipo')
                            ->disabled()
                            ->dehydrated(false),

                        Select::make('status')
                            ->label('Status')
                            ->options([
                                'new' => 'Novo',
                                'preparing' => 'Preparando',
                                'ready' => 'Pronto',
                                'delivering' => 'Saiu para entrega',
                                'done' => 'Finalizado',
                                'canceled' => 'Cancelado',
                            ])
                            ->required(),

                        TextInput::make('total_cents')
                            ->label('Total (centavos)')
                            ->disabled()
                            ->dehydrated(false),
                    ])
                    ->columns(2),

                Section::make('Cliente / Entrega')
                    ->schema([
                        TextInput::make('customer_name')->label('Nome')->disabled()->dehydrated(false),
                        TextInput::make('customer_phone')->label('Telefone')->disabled()->dehydrated(false),
                        TextInput::make('delivery_address')->label('Endereço')->disabled()->dehydrated(false),
                        TextInput::make('public_token')->label('Token público')->disabled()->dehydrated(false),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('id')
                    ->label('#')
                    ->sortable(),

                TextColumn::make('restaurant.name')
                    ->label('Restaurante')
                    ->searchable()
                    ->sortable(),

                BadgeColumn::make('order_type')
                    ->label('Tipo')
                    ->colors([
                        'primary' => 'local',
                        'warning' => 'delivery',
                        'success' => 'pickup',
                    ])
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'local' => 'Local',
                        'delivery' => 'Delivery',
                        'pickup' => 'Retirada',
                        default => $state,
                    }),

                BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'warning' => 'new',
                        'info' => 'preparing',
                        'primary' => 'ready',
                        'success' => 'done',
                        'danger' => 'canceled',
                        'gray' => 'delivering',
                    ])
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'new' => 'Novo',
                        'preparing' => 'Preparando',
                        'ready' => 'Pronto',
                        'delivering' => 'Entregando',
                        'done' => 'Finalizado',
                        'canceled' => 'Cancelado',
                        default => $state,
                    }),

                TextColumn::make('total_cents')
                    ->label('Total')
                    ->alignRight()
                    ->formatStateUsing(fn ($state) => 'R$ ' . number_format(($state ?? 0) / 100, 2, ',', '.'))
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('Criado em')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'new' => 'Novo',
                        'preparing' => 'Preparando',
                        'ready' => 'Pronto',
                        'delivering' => 'Entregando',
                        'done' => 'Finalizado',
                        'canceled' => 'Cancelado',
                    ]),
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
            // Vamos adicionar itens do pedido aqui no próximo passo (RelationManager)
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
}
