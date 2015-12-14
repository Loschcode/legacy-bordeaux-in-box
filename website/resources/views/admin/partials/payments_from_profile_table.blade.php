
      <table class="js-datas">

        <thead>

          <tr>
            <th>ID</th>
            <th>Stripe Customer</th>
            <th>Stripe Event</th>
            <th>Stripe Charge</th>
            <th>Stripe Card</th>
            <th>Série</th>
            <th>Type</th>
            <th>Prix</th>
            <th>Statut</th>
            <th>Derniers chiffres de carte</th>
            <th>Date</th>
            <th>Action</th>
          </tr>

        </thead>

        <tbody>

          @foreach ($payments as $payment)

            <tr>

              <th>{{$payment->id}}</th>
              <th>{{$payment->stripe_customer}}</th>
              <th>{{$payment->stripe_event}}</th>
              <th>{{$payment->stripe_charge}}</th>
              <th>{{$payment->stripe_card}}</th>
              <th>
              @if ($payment->order()->first() != NULL)
              
              <a href="{{ url('/admin/deliveries/focus/' . $payment->order()->first()->delivery_serie()->first()->id) }}">

              {{$payment->order()->first()->delivery_serie()->first()->delivery}}

              </a>

              @else

                N/A
                
              @endif
              </th>
              <th>{!! Form::getReadablePaymentType($payment->type) !!}</th>
              <th>{{$payment->amount}}€</th>
              <th>{!! Form::getReadablePaymentStatus($payment->paid) !!}</th>
              <th>{{$payment->last4}}</th>
              <th>{{$payment->created_at}}</th>
              <th>

                <button data-lightbox data-lightbox-id="lightbox-payments-from-profile-table" data-lightbox-url="{{ url('/admin/payments/focus/' . $payment->id) }}" class="spyro-btn spyro-btn-primary spyro-btn-sm"><i class="fa fa-search"></i></button>

              </th>

            </tr>

          @endforeach

          </tbody>

        </table>

        <div class="spacer10"></div>

        <a class="spyro-btn spyro-btn-primary spyro-btn-lg" href="{{url('/admin/profiles/reset-subscription-and-pay/'.$profile->id)}}">Réinitialiser l'abonnement (et forcer un paiement)</a>

        <a class="spyro-btn spyro-btn-primary spyro-btn-lg" href="{{url('/admin/profiles/force-pay/'.$profile->id)}}">Forcer un paiement simple (type "Transfert unique")</a>