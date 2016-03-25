<div class="spacer"></div>
@foreach ($orders_filtered->chunk(2) as $orders)
  
  <div class="row">
  @foreach ($orders as $index => $order)
    <div class="grid-6">
      <div class="panel panel__wrapper">
        <div class="panel__header">
          <h1 class="panel__title">
          @if ($order->already_paid == 0)
            <i class="fa fa-exclamation-triangle" style="color: red"></i>
          @endif
          N°{{ $index + 1 }}- {{ $order->customer_profile()->first()->customer()->first()->getFullName() }}</h1>
        </div>
        <div class="panel__content">

              @if ($order->customer_profile()->first()->isBirthday())
                <span class="label__default --green">Anniversaire: Oui</span>
              @else
                <span class="label__default --red">Anniversaire: Non</span>
              @endif

              @if ($order->gift == true)
                <span class="label__default --green">Cadeau: Oui</span>
              @else
                <span class="label__default --red">Cadeau: Non</span>
              @endif

              @if ($order->customer()->first()->orders()->notCanceledOrders()->where('status', 'delivered')->count() == 0)
                <span class="label__default --green">Nouvelle cliente: Oui</span>
              @else
                <span class="label__default --red">Nouvelle cliente: Non ({{ $order->customer()->first()->orders()->notCanceledOrders()->where('status', 'delivered')->count() }} livrées)</span>
              @endif
              
                <a target="_blank" class="button__default --green" href="{{ action('MasterBox\Admin\ProfilesController@getFocus', ['id' => $order->customer_profile()->first()->id]) }}"><i class="fa fa-external-link"></i> En savoir plus</a>
          
          <div class="+spacer-extra-small"></div>
          <div class="divider divider__section"></div>
          
          <div class="typography">
            
            @if ($order->take_away === false)
                
              <strong>Téléphone</strong><br/>

              {{ $order->customer()->first()->phone_format }}<br/>

              <strong>Adresse</strong><br/>
              {{ $order->destination()->first()->first_name }}
              {{ $order->destination()->first()->last_name }}<br/>

              {{ $order->destination()->first()->address }},
              {{ $order->destination()->first()->address_detail }}
              {{ $order->destination()->first()->city }}
              ({{ $order->destination()->first()->zip }})
              <br/>

            @endif





            <strong>Age</strong><br/>

            @if (Html::getAge($order->customer_profile()->first()->getAnswer('birthday')) != 0)
              {{ Html::getAge($order->customer_profile()->first()->getAnswer('birthday')) }} ans
            @else
              N/A
            @endif

            {!! Html::displayQuizz($order->customer_profile()->first(), ' ', true) !!}
          </div>

            <div class="center">
              <a data-confirm-text="La box est-elle vraiment prête ?" href="{{ action('MasterBox\Admin\OrdersController@getConfirmReady', ['id' => $order->id]) }}" class="button__default --blue js-confirm">La box est prête</a>
            </div>

        </div>
      </div>
    </div>
    
  @endforeach
  </div>
  
  <div class="+spacer-small"></div>

@endforeach
