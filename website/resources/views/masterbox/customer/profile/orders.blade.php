@extends('masterbox.layouts.master')

@section('gotham')
  {!! Html::gotham([
    'controller' => 'masterbox.customer.profile.orders',
  ]) !!}
@stop

@section('content')

<div class="container">
  <div class="row row-align-center@xs">
    <div class="grid-2">
      @include('masterbox.partials.sidebar_profile')
    </div>
    <div class="grid-9 grid-11@xs">
      <div class="profile profile__wrapper">
        <div class="profile__section">
          <h3 class="profile__title">Abonnements</h3>
          <p>Ci-dessous les abonnements auxquelles tu as souscrit.<br/></p>
          
          {{-- Default  --}}
          <div class="hide@xs">
            <table class="table table__wrapper">
              <thead class="table__head">
                <tr class="table__head-items">
                  <th></th>
                  <th>Abonnement</th>
                  <th>Durée</th>
                  <th>Livraisons restantes</th>
                  <th>Prochaine Livraison</th>
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

                      @if ($profile->status != 'subscribed')
                        0
                      @else
                        {{$profile->orders()->whereNull('date_sent')->count()}}
                      @endif
                    @endif
                    </td>
             
                    <td>
                      @if ($profile->orders()->whereNull('date_sent')->count() > 0 && $profile->status == 'subscribed')
                        {{ Html::dateFrench($profile->orders()->whereNull('date_sent')->first()->delivery_serie()->first()->delivery, true) }}
                      @endif
                    </td>
                  </tr>

                  @endif

                @endforeach

              </tbody>

            </table>
          </div>

          {{-- When XS (responsive) --}}
          <div class="show@xs hide">
            <div class="+spacer-extra-small"></div>
              @foreach ($profiles as $profile)
                <div class="typography">
                  <strong>Numéro:</strong> {{ $profile->id }}<br/>
                  <strong>Status:</strong>
                  @if ($profile->status == 'subscribed')
                    {{ Html::getReadableProfileStatus($profile->status) }}
                  @else
                    {{ Html::getReadableProfileStatus($profile->status) }}
                  @endif
                  <br/>
                  <strong>Durée:</strong>
                  @if ($profile->order_preference()->first()->frequency == 0)
                    Non précisée
                  @else
                    {{$profile->orders()->notCanceledOrders()->count()}} mois
                  @endif
                  <br/>
                  <strong>Livraisons restantes:</strong>
                  @if ($profile->order_preference()->first()->frequency == 0)
                    Non indiqué
                  @else

                    @if ($profile->status != 'subscribed')
                      0
                    @else
                      {{$profile->orders()->whereNull('date_sent')->count()}}
                    @endif
                  @endif
                  <br/>
                  <strong>Prochaine livraison:</strong>
                  @if ($profile->orders()->whereNull('date_sent')->count() > 0 && $profile->status == 'subscribed')
                    {{ Html::dateFrench($profile->orders()->whereNull('date_sent')->first()->delivery_serie()->first()->delivery, true) }}
                  @endif
                </div>
                <a class="button button__submit" href="{{ action('MasterBox\Customer\ProfileController@getOrder', ['id' => $profile->id])}}">Voir l'abonnement</a>

                <div class="+spacer-small"></div>
              @endforeach
          </div>

        </div>
      </div>
    </div>
  </div>
</div>

@stop