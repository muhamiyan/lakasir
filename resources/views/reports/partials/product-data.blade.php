<div class="max-w-full">
  <div class="text-center space-y-2">
    <h1 class="text-3xl font-semibold">{{ __('Product Report') }}</h1>
    <h3 class="text-xl">{{ $header['shop_name'] }}</h3>
  </div>
  <p class="mb-4">{{ __('Period') }}: <b>{{ $header['start_date'] }} - {{ $header['end_date'] }}</b></p>
  <x-table class="w-full table-fixed">
    <x-table-header>
      <x-table-header-cell>SKU</x-table-header-cell>
      <x-table-header-cell>{{ __('Product Name') }}</x-table-header-cell>
      <x-table-header-cell style="width: 100px;" class="number">{{ __('Price') }}</x-table-header-cell>
      <x-table-header-cell style="width: 100px;" class="number">{{ __('Qty') }}</x-table-header-cell>
      <x-table-header-cell style="width: 100px;" class="number">{{ __('Total Price') }}</x-table-header-cell>
      <x-table-header-cell style="width: 100px;" class="number">{{ __('Total Discount') }}</x-table-header-cell>
      <x-table-header-cell style="width: 100px;" class="number">{{ __('Net Selling') }}</x-table-header-cell>
    </x-table-header>
    <tbody>
      @foreach($reports as $key => $report)
        <x-table-row>
          <!-- <td>{{ $report['code'] }}</td> -->
          <x-table-cell>{{ $report['sku'] }}</x-table-cell>
          <x-table-cell>{{ $report['name'] }}</x-table-cell>
          <x-table-cell class="number">{{ $report['selling_price'] }}</x-table-cell>
          <x-table-cell style="width: 100px;" class="number">{{ $report['qty'] }}</x-table-cell>
          <x-table-cell class="number">{{ $report['selling'] }}</x-table-cell>
          <x-table-cell class="number">{{ $report['discount_price'] }}</x-table-cell>
          <x-table-cell class="number">{{ $report['net_selling'] }}</x-table-cell>
        </x-table-row>
      @endforeach
      <x-table-row>
        <x-table-cell colspan="3"><b>{{ __('Sub Total') }}</b></x-table-cell>
        <x-table-cell class="number"><b>{{ $footer['total_qty'] }}</b></x-table-cell>
        <x-table-cell class="number"><b>{{ $footer['total_gross'] }}</b></x-table-cell>
        <x-table-cell class="number"><b>{{ $footer['total_all_discount_per_item'] }}</b></x-table-cell>
        <x-table-cell class="number"><b>{{ $footer['total_net_price'] }}</b></x-table-cell>
      </x-table-row>
      <x-table-row>
        <x-table-cell colspan="6"><b>{{ __('Discount Voucher') }}</b></x-table-cell>
        <x-table-cell class="number"><b>({{ $footer['total_discount'] }})</b></x-table-cell>
      </x-table-row>
      <x-table-row>
        <x-table-cell colspan="6"><b>Total</b></x-table-cell>
        <x-table-cell class="number"><b>{{ $footer['total_net'] }}</b></x-table-cell>
      </x-table-row>
    </tbody>
  </x-table>

  <x-table class="w-full table-fixed mt-4">
    <x-table-header>
      <x-table-row>
        <x-table-header-cell colspan="5" style="text-align: center;">{{ __('Grand Total') }}</x-table-header-cell>
      </x-table-row>
      <x-table-row>
        <x-table-header-cell style="width: 100px;" class="number">{{ __('Discount Voucher') }}</x-table-header-cell>
        <x-table-header-cell style="width: 100px;" class="number">{{ __('Total Discount Per Item') }}</x-table-header-cell>
        <x-table-header-cell style="width: 100px;" class="number">{{ __('Net Selling') }}</x-table-header-cell>
        <x-table-header-cell style="width: 100px;" class="number">{{ __('Total Cost') }}</x-table-header-cell>
        <x-table-header-cell style="width: 100px;" class="number">{{ __('Gross Profit') }}</x-table-header-cell>
      </x-table-row>
    </x-table-header>
    <tbody>
      <x-table-row>
        <x-table-cell class="number"><b>{{ $footer['total_discount'] }}</b></x-table-cell>
        <x-table-cell class="number"><b>{{ $footer['total_all_discount_per_item'] }}</b></x-table-cell>
        <x-table-cell class="number"><b>{{ $footer['total_net'] }}</b></x-table-cell>
        <x-table-cell class="number"><b>{{ $footer['total_cost'] }}</b></x-table-cell>
        <x-table-cell class="number"><b>{{ $footer['total_net_profit_after_discount_selling'] }}</b></x-table-cell>
      </x-table-row>
    </tbody>
  </x-table>

</div>
