<div class="dialog">
  <h4 class="dialog__title">Liaisons coordonnée {{ $coordinate->id }}</h4>
  <div class="dialog__divider"></div>
</div>

@foreach ($coordinate->company_billings()->get() as $company_billing)
  <a class="button button__default --green" target="_blank" href="{{ action('Company\Guest\BillingController@getWatch', ['encrypted_access' => $company_billing->encrypted_access]) }}">Facture #{{$company_billing->id}}</a>
@endforeach

@foreach ($coordinate->customers()->get() as $customer)
<a class="button button__default --green" target="_blank" href="{{ action('MasterBox\Admin\CustomersController@getFocus', ['id' => $customer->id])}}">Client #{{$customer->id}}</a>
@endforeach

@foreach ($coordinate->customer_order_buildings()->get() as $customer_order_building)
<a class="button button__default --green" href="#">Building #{{$customer_order_building->id}}</a>
@endforeach

@foreach ($coordinate->delivery_spots()->get() as $delivery_spot)
<a class="button button__default --green"  target="_blank" href="{{ action('MasterBox\Admin\SpotsController@getEdit', ['id' => $delivery_spot->id])}}">Point relais #{{$delivery_spot->id}}</a>

@endforeach

@foreach ($coordinate->order_billings()->get() as $order_billing)
<a class="button button__default --green"  target="_blank" href="#">Facturation #{{$order_billing->id}}</a>
@endforeach

@foreach ($coordinate->order_destinations()->get() as $order_destination)
<a class="button button__default --green"  target="_blank" href="#">Destination #{{$order_destination->id}}</a>
@endforeach