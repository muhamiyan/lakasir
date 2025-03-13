<?php

namespace App\Filament\Tenant\Resources\StocksResource\Pages;

use App\Filament\Tenant\Resources\StockEntryResource;
use App\Filament\Tenant\Resources\StockEntryResource\Widgets\StockEntryOverview;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListStockEntry extends ListRecords
{
    protected static string $resource = StockEntryResource::class;

    protected function getHeaderWidgets(): array
    {
        return [
            StockEntryOverview::class
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
            ->visible(can('create stock entry')),
        ];
    }
}
