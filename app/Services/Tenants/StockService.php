<?php

namespace App\Services\Tenants;

use App\Models\Tenants\Product;
use App\Models\Tenants\Purchasing;
use App\Models\Tenants\Setting;
use App\Models\Tenants\Stock;

class StockService
{
    private function adjustStockPrepare(Product $product): Stock
    {
        if (Setting::get('selling_method', env('SELLING_METHOD', 'fifo')) == 'normal') {
            /** @var Stock $lastStock */
            $lastStock = $product
                ->stocks()
                ->where('stock', '>', 0)
                ->orderBy('date', 'asc')
                ->latest()
                ->first();
        } else {
            /** @var Stock $lastStock */
            $lastStock = $product->stockLatestCalculateIn()->first();
        }

        return $lastStock;
    }

    public function addStock(Product $product, $qty): void
    {
        $lastStock = $this->adjustStockPrepare($product);

        if ($lastStock) {
            if ($lastStock->stock < $qty) {
                $qty = $qty + $lastStock->stock;
                $lastStock->stock = 0;
                $lastStock->save();
                $this->reduceStock($product, $qty);
            } else {
                $lastStock->stock = $lastStock->stock + $qty;
                $lastStock->save();
            }
        } else {
            $product->stock = $product->stock + $qty;
            $product->save();
        }
    }

    public function reduceStock(Product $product, $qty): void
    {
        $lastStock = $this->adjustStockPrepare($product);

        if ($lastStock) {
            if ($lastStock->stock < $qty) {
                $qty = $qty - $lastStock->stock;
                $lastStock->stock = 0;
                $lastStock->save();
                $this->reduceStock($product, $qty);
            } else {
                $lastStock->stock = $lastStock->stock - $qty;
                $lastStock->save();
            }
        } else {
            $product->stock = $product->stock - $qty;
            $product->save();
        }
    }

    public function entryStock($data): Stock
    {
        $product = Product::find($data['product_id']);

        $data['stock'] = $data['init_stock'];
        $data['date'] = $data['date'] ?? now();

        if ($data['is_ready']) {
            $product->stock += $data['stock'];
            $product->save();
        }

        $stock = new Stock();
        $stock->fill($data);
        $stock->save();

        return $stock;
    }

    public function updateReadyStock(Stock $stock): void
    {
        $product = Product::find($stock->product_id);
        if ($stock->is_ready) {
            $stock->is_ready = false;
            $product->stock -= $stock->stock;
        }else {
            $stock->is_ready = true;
            $product->stock += $stock->stock;
        }
        $stock->save();
        $product->save();
    }

    public function create($data, ?Purchasing $purchasing = null): Stock
    {
        $data['stock'] = $data['stock'] ?? 0;
        $data['date'] = $data['date'] ?? now();
        $stock = new Stock();
        $data['init_stock'] = $data['stock'];
        $stock->fill($data);
        $stock->product()->associate(Product::find($data['product_id']));
        if ($purchasing) {
            $stock->purchasing()->associate($purchasing);
        }
        $stock->save();

        return $stock;
    }

    public function update(Stock $stock, array $data, ?Purchasing $purchasing = null)
    {
        $data['init_stock'] = $data['stock'];
        $stock->fill($data);
        $stock->product()->associate(Product::find($data['product_id']));
        if ($purchasing) {
            $stock->purchasing()->associate($purchasing);
        }
        $stock->save();
    }
}
