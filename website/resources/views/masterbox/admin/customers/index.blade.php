@extends('masterbox.layouts.admin')

@section('gotham')
  {!! Html::gotham([
    'controller' => 'masterbox.admin.customers.index'
  ]) !!}
@stop

@section('navbar')
  @include('masterbox.admin.partials.navbar_customers')
@stop

@section('content')

<div class="row">
	<div class="grid-8">
		<h1 class="title title__section">Clients</h1>
	</div>
</div>

<div class="divider divider__section"></div>

{{-- Table rendered by ajax --}}
<table 
	data-request="{{ action('MasterBox\Service\ApiController@getCustomers') }}"
	data-edit-customer="{{ action('MasterBox\Admin\CustomersController@getEdit') }}"
  data-focus-customer="{{ action('MasterBox\Admin\CustomersController@getFocus') }}"
	data-focus-profile="{{ action('MasterBox\Admin\ProfilesController@getFocus') }}"
>
  <thead>
    <tr>
    	<th></th>
      <th>Id</th>
      <th>Nom</th>
      <th>Email</th>
      <th>Téléphone</th>
      <th>Action</th>
    </tr>
  </thead>
</table>


@stop