<?php $i = 0 ?>

<div class="spacer"></div>
@foreach ($orders_filtered as $order)

  <?php
    $i++;
  ?>

  <div class="order">
    <div class="order__header">
      <img title="{{$order->user_profile()->first()->box()->first()->title}}" class="order__image" src="{{ url($order->box()->first()->image->full) }}">
      <p class="order__title">
        @if ($order->already_paid == 0)
          <i class="fa fa-exclamation-triangle" style="color: red"></i>
        @endif
        N°{{ $i }} - {{ $order->user_profile()->first()->customer()->first()->getFullName() }}
      </p>
    </div>

    <div class="spacer"></div>

    <div class="order__content">
      <div class="center">
        <span class="button --lg --disabled --inverse">
          Anniversaire:
          @if (Html::getAge($order->user_profile()->first()->getAnswer('birthday')) != 0)
            @if (Html::isBirthday($order->user_profile()->first()->getAnswer('birthday')))
              <i class="fa fa-check" style="color: green"></i>
            @else
              <i class="fa fa-times"></i>
            @endif
          @else
            <i class="fa fa-times"></i>
          @endif
        </span>

        <span class="button --lg --disabled --default">
          Marraine:
          @if ($order->user_profile()->first()->isSponsor())
            <i class="fa fa-check" style="color: green"></i>
          @else
            <i class="fa fa-times"></i>
          @endif
        </span>

        <span class="button --lg --disabled --default">
          Cadeau:
          @if ($order->gift == true)
            <i class="fa fa-check" style="color: green"></i>
          @else
            <i class="fa fa-times"></i>
          @endif
        </span>

        @if (Html::getAge($order->user_profile()->first()->getAnswer('birthday')) != 0)
          <span class="button --lg --disabled --default">
            {{ Html::getAge($order->user_profile()->first()->getAnswer('birthday')) }} ans
          </span>
        @else
          <span class="button --lg --disabled --default">
            N/A
          </span>
        @endif

        <a target="_blank" class="button --lg --primary" href="{{ url('/admin/profiles/edit/' . $order->user_profile()->first()->id) }}">En savoir plus</a>

      </div>

      <div class="spacer"></div>

      {!! Html::displayQuizz($order->box()->first(), $order->user_profile()->first()) !!}

      <div class="spacer"></div>

      <div class="center">
        <a href="{{ url('admin/orders/confirm-ready/' . $order->id) }}" class="button --success --xl">La box est prête</a>

      </div>

    </div>

    <div class="spacer"></div>


  </div>

  <div class="spacer --lg"></div>
@endforeach
