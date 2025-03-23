<div class="max-w-full">
  <div class="text-center space-y-2">
    <h1 class="text-3xl font-semibold">Laporan kasir</h1>
    <h3 class="text-xl">{{ $header['shop_name'] }}</h3>
  </div>
  <p class="mb-4">{{ __('Period') }}: <b>{{ $header['start_date'] }} - {{ $header['end_date'] }}</b></p>
  <div class="space-y-4">
    @foreach($reports as $key => $report)
      <x-table class="w-full table-fixed">
        <x-table-header>
          <x-table-row>
            <x-table-header-cell colspan="3">{{ __('Cashier') }} : {{ $report['user'] }}</x-table-header-cell>
            <x-table-header-cell colspan="2"># {{ $report['number'] }}</x-table-header-cell>
            <x-table-header-cell colspan="2">{{ __('Date') }} : {{ $report['created_at'] }}</x-table-header-cell>
          </x-table-row>
          <x-table-row>
            <x-table-header-cell>{{ __('Items') }}</x-table-header-cell>
            <x-table-header-cell style="width: 100px;" class="number">{{ __('Price') }} </x-table-header-cell>
            <x-table-header-cell style="width: 100px;" class="number">{{ __('Cost') }}</x-table-header-cell>
            <x-table-header-cell style="width: 100px;" class="number">{{ __('Discount') }}</x-table-header-cell>
            <x-table-header-cell style="width: 100px;" class="number">{{ __('Total Price') }}</x-table-header-cell>
            <x-table-header-cell style="width: 100px;" class="number">{{ __('Total Cost') }}</x-table-header-cell>
            <x-table-header-cell style="width: 100px;" class="number">{{ __('Net Selling') }}</x-table-header-cell>
          </x-table-row>
        </x-table-header>
        <tbody>
          @foreach($report['transaction']['items'] as $item)
            <x-table-row>
              <x-table-cell>{{ $item['product'] }}</x-table-cell>
              <x-table-cell class="number">{{ $item['product_price'] }} x {{ $item['quantity'] }}</x-table-cell>
              <x-table-cell class="number">{{ $item['product_cost'] }} x {{ $item['quantity'] }}</x-table-cell>
              <x-table-cell class="number">({{ $item['discount_price'] }})</x-table-cell>
              <x-table-cell class="number">{{ $item['price'] }}</x-table-cell>
              <x-table-cell class="number">{{ $item['cost'] }}</x-table-cell>
              <x-table-cell class="number">{{ $item['total_after_discount'] }}</x-table-cell>
            </x-table-row>
          @endforeach
          <x-table-row>
            <x-table-cell colspan="3"><b>{{ __('Sub Total') }}</b></x-table-cell>
            <x-table-cell class="number"><b>({{ $report['total']['discount'] }})</b></x-table-cell>
            <x-table-cell class="number"><b>{{ $report['total']['gross_selling'] }}</b></x-table-cell>
            <x-table-cell class="number"><b>{{ $report['total']['cost'] }}</b></x-table-cell>
            <x-table-cell class="number"><b>{{ $report['total']['net_selling'] }}</b></x-table-cell>
          </x-table-row>
          <x-table-row>
            <x-table-cell colspan="6"><b>{{ __('Discount Voucher') }}</b></x-table-cell>
            <x-table-cell class="number"><b>({{ $report['total']['discount_selling'] }})</b></x-table-cell>
          </x-table-row>
          <x-table-row>
            <x-table-cell colspan="6"><b>Total</b></x-table-cell>
            <x-table-cell class="number"><b>{{ $report['total']['grand_total'] }}</b></x-table-cell>
          </x-table-row>
        </tbody>
      </x-table>
    @endforeach
  </div>
  <x-table class="mt-4">
    <x-table-header>
      <x-table-row>
        <x-table-header-cell colspan="6" style="text-align: center;">{{ __('Grand Total') }}</x-table-header-cell>
      </x-table-row>
      <x-table-row>
        <x-table-header-cell style="width: 100px;" class="number">{{ __('Total Price') }}</x-table-header-cell>
        <x-table-header-cell style="width: 100px;" class="number">{{ __('Discount Voucher') }}</x-table-header-cell>
        <x-table-header-cell style="width: 100px;" class="number">{{ __('Total Discount Per Item') }}</x-table-header-cell>
        <x-table-header-cell style="width: 100px;" class="number">{{ __('Net Selling') }}</x-table-header-cell>
        <x-table-header-cell style="width: 100px;" class="number">{{ __('Total Cost') }}</x-table-header-cell>
        <x-table-header-cell style="width: 100px;" class="number">{{ __('Gross Profit') }}</x-table-header-cell>
      </x-table-row>
    </x-table-header>
    <tbody>
      <x-table-cell class="number"><b>{{ $footer['total_price'] }}</b></x-table-cell>
      <x-table-cell class="number"><b>{{ $footer['total_discount'] }}</b></x-table-cell>
      <x-table-cell class="number"><b>{{ $footer['total_discount_per_item'] }}</b></x-table-cell>
      <x-table-cell class="number"><b>{{ $footer['total_net'] }}</b></x-table-cell>
      <x-table-cell class="number"><b>{{ $footer['total_cost'] }}</b></x-table-cell>
      <x-table-cell class="number"><b>{{ $footer['total_net_profit_after_discount_selling'] }}</b></x-table-cell>
    </tbody>
  </x-table>

</div>
