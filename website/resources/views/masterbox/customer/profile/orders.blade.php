@extends('masterbox.layouts.master')

@section('content')

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

                <tr class="table__body-items">
                  @if ($profile->status == 'subscribed')
                    <th class="table__item --status-active"><i class="fa fa-circle"></i> {{ Html::getReadableProfileStatus($profile->status) }}</th>
                  @else
                    <th class="table__item --status-unactive"><i class="fa fa-circle"></i> {{ Html::getReadableProfileStatus($profile->status) }}</th>
                  @endif
                  <th>N°{{$profile->id}}</th>
                  <th>
                  @if ($profile->order_preference()->first()->frequency == 0)
                    Non précisée
                  @else
                    {{$profile->orders()->notCanceledOrders()->count()}} mois
                  @endif
                  </th>
                  <th>
                  @if ($profile->order_preference()->first()->frequency == 0)
                    Non indiqué
                  @else
                    {{$profile->orders()->whereNull('date_sent')->count()}}
                  @endif
                  </th>
           
                  <th>
                    <a class="button button__table" href="{{action('MasterBox\Customer\ProfileController@getOrder', ['id' => $profile->id])}}"><i class="fa fa-search"></i></a>
                  </th>
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