<?php

namespace App\Filament\Tenant\Resources\StockEntryResource\Widgets;

use App\Constants\StockType;
use App\Models\Tenants\Stock;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StockEntryOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $totalBatch = Stock::whereType(StockType::in)->where("stock", ">", 0)->count();
        $typeByIn = Stock::whereType(StockType::in)->where("stock", ">", 0)->count();
        $typeByManufacture = Stock::whereType(StockType::manufacture)->where("stock", ">", 0)->count();
        $typeByRepack = Stock::whereType(StockType::repack)->where("stock", ">", 0)->count();

        return [
            Stat::make(__('Total Batch / Slot'), $totalBatch),
            Stat::make(__('Type In / Purchase'), $typeByIn),
            Stat::make(__('Type Manufacture'), $typeByManufacture),
            Stat::make(__('Type Repack'), $typeByRepack),
        ];
    }
}
