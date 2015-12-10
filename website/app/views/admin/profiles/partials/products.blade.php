
      @if ($profile->user_profile_products()->first() != NULL)

        <table class="js-datas">

          <thead>

            <tr>
              <th>Série</th>
              <th>Produits assignés</th>
              <th>Action</th>
            </tr>

          </thead>

          <tbody>


            @foreach ($profile->orders()->get() as $order)

              <tr>
                <th>{{$order->delivery_serie()->first()->delivery}}</th>
                <th>

                @foreach ($profile->getSeriesProfileProduct($order->delivery_serie_id) as $user_profile_product)

                {{$user_profile_product->partner_product()->first()->name}} ({{$user_profile_product->accuracy}}%), 

                @endforeach

                </th>
    

                <th>

                <a data-toggle="tooltip" title="Editer" class="spyro-btn spyro-btn-warning spyro-btn-sm" href="{{url('/admin/products/edit-profile-products/'.$order->id)}}"><i class="fa fa-pencil"></i></a>

                @if ($profile->seriesProfileProduct($order->delivery_serie_id)->count() > 0)

                <a data-toggle="tooltip" title="Supprimer" class="spyro-btn spyro-btn-danger spyro-btn-sm" href="{{url('/admin/products/delete-profile-products/'.$order->id)}}"><i class="fa fa-trash"></i></a>

                @endif

                </th>

              </tr>

            @endforeach

            </tbody>

          </table>
          
        @endif