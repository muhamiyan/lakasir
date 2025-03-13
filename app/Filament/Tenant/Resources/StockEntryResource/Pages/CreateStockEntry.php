<?php

namespace App\Filament\Tenant\Resources\StocksResource\Pages;

use App\Filament\Tenant\Resources\StockEntryResource;
use App\Filament\Tenant\Resources\Traits\RedirectToIndex;
use App\Services\Tenants\StockService;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateStockEntry extends CreateRecord
{
    use RedirectToIndex;

    protected static string $resource = StockEntryResource::class;

    private StockService $stockService;

    public function __construct()
    {
        $this->stockService = new StockService();
    }

    protected function handleRecordCreation(array $data): Model
    {
        return $this->stockService->entryStock($data);
    }
}
