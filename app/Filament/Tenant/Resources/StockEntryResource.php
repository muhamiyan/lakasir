<?php

namespace App\Filament\Tenant\Resources;

use App\Features\ProductInitialPrice;
use App\Filament\Tenant\Resources\StocksResource\Pages;
use App\Filament\Tenant\Resources\StocksResource\RelationManagers;
use App\Models\Tenants\Product;
use App\Models\Tenants\Setting;
use App\Models\Tenants\Stock;
use App\Services\Tenants\StockService;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Support\Enums\ActionSize;
use Filament\Support\RawJs;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Laravel\Pennant\Feature;

class StockEntryResource extends Resource
{
    protected static ?string $model = Stock::class;

    protected static ?string $navigationIcon = 'heroicon-o-circle-stack';

    public static ?string $label = 'Stock Entry';

    public static function form(Form $form): Form
    {
        return $form
            ->schema(
                [
                    Select::make('product_id')
                        ->translateLabel()
                        ->live()
                        ->required()
                        ->native(false)
                        ->placeholder(__('Search...'))
                        ->relationship(name: 'product', titleAttribute: 'name')
                        ->searchable(['sku', 'name', 'barcode'])
                        ->afterStateUpdated(static function (Set $set, ?string $state) {
                            $product = Product::find($state);
                            if ($product) {
                                if ($product->is_non_stock) {
                                    $set('product_id', "");
                                    $set('initial_price', 0);
                                    $set('selling_price', 0);

                                    Notification::make()
                                        ->title(__('Non-stock product cannot be added'))
                                        ->warning()
                                        ->send();
                                } else {
                                    $set('initial_price', $product->stocks()->orderByDesc('date')->first()->initial_price);
                                    $set('selling_price', $product->stocks()->orderByDesc('date')->first()->selling_price);
                                }
                            }

                            $fifoMethod = Setting::get('selling_method', env('SELLING_METHOD', 'fifo')) == 'fifo';
                            $lifoMethod = Setting::get('selling_method', env('SELLING_METHOD', 'fifo')) == 'lifo';
                            if ($fifoMethod) {
                                $set('is_ready', 0);
                            } else if ($lifoMethod) {
                                $set('is_ready', true);
                            }
                        }),
                    Select::make('type')
                        ->translateLabel()
                        ->options([
                            'in' => 'In',
                            'manufacture' => 'Manufacture',
                            'repack' => 'Repack',
                        ])
                        ->live()
                        ->default('in')
                        ->required(),
                    TextInput::make('initial_price')
                        ->visible(Feature::active(ProductInitialPrice::class))
                        ->translateLabel()
                        ->live()
                        ->mask(RawJs::make('$money($input)'))
                        ->lte('selling_price')
                        ->default(0)
                        ->stripCharacters(',')
                        ->numeric()
                        ->prefix(Setting::get('currency', 'IDR'))
                        ->required(),
                    TextInput::make('selling_price')
                        ->translateLabel()
                        ->live()
                        ->mask(RawJs::make('$money($input)'))
                        ->gte('initial_price')
                        ->default(0)
                        ->stripCharacters(',')
                        ->numeric()
                        ->prefix(Setting::get('currency', 'IDR'))
                        ->required(),
                    TextInput::make('init_stock')
                        ->translateLabel()
                        ->live()
                        ->numeric()
                        ->required(),
                    Toggle::make('is_ready')
                        ->translateLabel()
                        ->live()
                        ->default(0)
                        ->required(),
                ]
            );
    }

    public static function table(Table $table): Table
    {
        return $table
            ->query(fn (): Builder => Stock::query()
                ->where("stock", '>', 0)
                ->latest())
            ->columns([
                TextColumn::make('product.name')
                    ->translateLabel()
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('stock')
                    ->translateLabel()
                    ->toggleable(),
                TextColumn::make('init_stock')
                    ->translateLabel()
                    ->toggleable(),
                TextColumn::make('type')
                    ->translateLabel()
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('is_ready')
                    ->badge()
                    ->color(static fn (bool $state): string => match ($state) {
                        false => 'gray',
                        true => 'success',
                    })
                    ->formatStateUsing(static fn ($state) => $state ? __('Ready') : __('Not Ready'))
                    ->toggleable()
                    ->translateLabel(),
                TextColumn::make('date')
                    ->translateLabel()
                    ->date()
                    ->searchable()
                    ->toggleable(),
            ])
            ->searchPlaceholder(__('Search (Name, Type)'))
            ->actions([
                ActionGroup::make([
                    Action::make('set_status_ready')
                        ->translateLabel()
                        ->label(static function (Stock $stock) {
                            if ($stock->is_ready) {
                                return 'Set Not Ready';
                            }
                            return 'Set Ready';
                        })
                        ->action(static function (Stock $stock, StockService $stockService) {
                            if (can('update stock entry')) {
                                $stockService->updateReadyStock($stock);
                            }
                        })
                        ->icon('heroicon-s-pencil-square'),
                ])
                    ->visible(can('update stock entry'))
                    ->icon('heroicon-m-ellipsis-vertical')
                    ->size(ActionSize::Small)
                    ->button()
                    ->translateLabel(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListStockEntry::route('/'),
            'create' => Pages\CreateStockEntry::route('/create'),
        ];
    }
}
