<?php $i = 0 ?>

<div class="spacer"></div>
@foreach ($orders_filtered as $order)

  <?php
    $i++;
  ?>
  
  <div class="panel panel__wrapper">
    <div class="panel__header">
      <h1 class="panel__title">
      @if ($order->already_paid == 0)
        <i class="fa fa-exclamation-triangle" style="color: red"></i>
      @endif
      N° {{ $i }} - {{ $order->customer_profile()->first()->customer()->first()->getFullName() }}</h1>
    </div>
    <div class="panel__content">

      <div class="row">
        <div class="grid-10">
          @if ($order->customer_profile()->first()->isBirthday())
            <span class="easygo__label --green">Anniversaire: Oui</span>
          @else
            <span class="easygo__label --red">Anniversaire: Non</span>
          @endif

          @if ($order->gift == true)
            <span class="easygo__label --green">Cadeau: Oui</span>
          @else
            <span class="easygo__label --red">Cadeau: Non</span>
          @endif

          @if ($order->customer()->first()->orders()->notCanceledOrders()->where('status', 'delivered')->count() == 0)
            <span class="easygo__label --green">Nouvelle cliente: Oui</span>
          @else
            <span class="easygo__label --red">Nouvelle cliente: Non ({{ $order->customer()->first()->orders()->notCanceledOrders()->where('status', 'delivered')->count() }} livrées)</span>
          @endif


        </div>
        <div class="grid-2 +text-right">
            <a target="_blank" class="button__default --green" href="{{ action('MasterBox\Admin\ProfilesController@getFocus', ['id' => $order->customer_profile()->first()->id]) }}"><i class="fa fa-external-link"></i> En savoir plus</a>
        </div>
      </div>
      
      <div class="+spacer-extra-small"></div>
      <div class="divider divider__section"></div>
      
      <div class="typography">

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
  
  <div class="+spacer-small"></div>

@endforeach
