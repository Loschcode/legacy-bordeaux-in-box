
      <table class="js-datas" width="100%">

        <thead>

          <tr>

            <th>ID</th>
            <th>Utilisateur</th>
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
            <th>Facture</th>
            <th>Action</th>

          </tr>

        </thead>

        <tbody>

          @foreach ($payments as $payment)

            <tr>


              <th><a class="simple" href="{{url('/admin/payments/focus/'.$payment->id)}}">{{$payment->id}}</a></th>

          <th><a class="spyro-btn spyro-btn-primary spyro-btn-sm" href="{{ url('/admin/users/focus/'.$payment->profile()->first()->customer()->first()->id)}}">{{$payment->customer()->first()->getFullName()}}</a></th>

              <th>{{$payment->stripe_customer}}</th>
              <th>{{$payment->stripe_event}}</th>
              <th>{{$payment->stripe_charge}}</th>
              <th>{{$payment->stripe_card}}</th>
              <th>
              @if ($payment->orders()->count() > 0)

                @foreach ($payment->orders()->get() as $order)
                  
                  @if ($order->delivery_serie()->first() !== NULL)
                    {{$order->delivery_serie()->first()->delivery}}
                  @else
                    N/A
                  @endif

                @endforeach

              @else

                Non disponible

              @endif
                </th>
              <th>{!! Html::getReadablePaymentType($payment->type) !!}</th>
              <th>{{$payment->amount}}€</th>
              <th>{!! Html::getReadablePaymentStatus($payment->paid) !!}</th>
              <th>{{$payment->last4}}</th>
              <th>{{$payment->created_at}}</th>
              <th><a href="{{url('/admin/payments/download-bill/'.$payment->bill_id)}}">{{$payment->bill_id}}</a></th>
             
              <th>
                @if ($payment->paid)
                  <a data-toggle="tooltip" data-position="left" title="Forcer l'échec" class="spyro-btn spyro-btn-sm spyro-btn-warning" href="{{url('/admin/payments/make-fail/'.$payment->id)}}"><i class="fa fa-times"></i></a>
                @else
                  <a data-toggle="tooltip" title="Considérer comme payé" class="spyro-btn spyro-btn-sm spyro-btn-success" href="{{url('/admin/payments/make-success/'.$payment->id)}}"><i class="fa fa-check"></i></a>
                @endif

                <a data-toggle="tooltip" title="Archiver" class="spyro-btn spyro-btn-danger" href="{{url('/admin/payments/delete/'.$payment->id)}}"><i class="fa fa-archive"></i></a>


              </th>
              
            </tr>

          @endforeach

          </tbody>

        </table>