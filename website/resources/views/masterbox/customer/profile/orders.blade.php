@extends('masterbox.layouts.master')

@section('content')
<div 
  id="gotham"
  data-controller="masterbox.customer.profile.orders"
></div>

<div class="container">
  <div class="row">
    <div class="grid-2">
      @include('masterbox.partials.sidebar_profile')
    </div>
    <div class="grid-9">
      <div class="profile profile__wrapper">
        <div class="profile__section">
          <h3 class="profile__title">Abonnements</h3>
          <p>Ci-dessous les abonnements auxquelles tu as souscris.<br/></p>
          <table class="table table__wrapper">
            <thead class="table__head">
              <tr class="table__head-items">
                <th></th>
                <th>Abonnement</th>
                <th>Durée</th>
                <th>Livraisons restantes</th>
                <th></th>
              </tr>
            </thead>

            <tbody class="table__body">

              @foreach ($profiles as $profile)

                @if ($profile->orders()->first() != NULL)

                <tr data-href="{{ action('MasterBox\Customer\ProfileController@getOrder', ['id' => $profile->id])}}" class="table__body-items --orders" title="En savoir plus">
                  @if ($profile->status == 'subscribed')
                    <td class="table__item --status-active --space-left"><i class="fa fa-circle"></i> {{ Html::getReadableProfileStatus($profile->status) }}</td>
                  @else
                    <td class="table__item --status-unactive --space-left"><i class="fa fa-circle"></i> {{ Html::getReadableProfileStatus($profile->status) }}</td>
                  @endif
                  <td class="table__item --orders">N°{{$profile->id}}</td>
                  <td>
                  @if ($profile->order_preference()->first()->frequency == 0)
                    Non précisée
                  @else
                    {{$profile->orders()->notCanceledOrders()->count()}} mois
                  @endif
                  </td>
                  <td>
                  @if ($profile->order_preference()->first()->frequency == 0)
                    Non indiqué
                  @else
                    {{$profile->orders()->whereNull('date_sent')->count()}}
                  @endif
                  </td>
           
                  <td>
                    <!-- <a class="button button__table" href="{{action('MasterBox\Customer\ProfileController@getOrder', ['id' => $profile->id])}}"><i class="fa fa-search"></i></a> -->
                  </td>
                </tr>

                @endif

              @endforeach

            </tbody>

          </table>
        </div>
      </div>
    </div>
  </div>
</div>

@stop