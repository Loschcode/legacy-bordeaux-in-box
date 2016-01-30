
          <div class="panel">
            <div class="panel__heading">
              <h2 class="panel__title">Informations de livraison</h2>
            </div>
            <div class="panel__content --white">


              <div class="row billing__container">
                <div class="grid-6">
                  
                  <div class="billing__label">
                    {!! Form::label('destination_first_name', 'Prénom') !!}
                  </div>
                  
                  {!! Form::text("destination_first_name", ($order_building->destination_first_name) ? $order_building->destination_first_name : Request::old("destination_first_name"), ['class' => 'billing__input']) !!}
                  {!! Html::checkError('destination_first_name', $errors) !!}

                </div>
                <div class="grid-6">
                  <div class="billing__label">
                    {!! Form::label('destination_last_name', 'Nom de famille') !!}
                  </div>

                  {!! Form::text("destination_last_name", ($order_building->destination_last_name) ? $order_building->destination_last_name : Request::old("destination_last_name"), ['class' => 'billing__input']) !!}

                  {!! Html::checkError('destination_last_name', $errors) !!}

                </div>
              </div>

              <div class="row billing__container">
                <div class="grid-6">
                  
                  <div class="billing__label">
                    {!! Form::label('destination_city', 'Ville') !!}
                  </div>
                  
                  {!! Form::text("destination_city", ($order_building->destination_city) ? $order_building->destination_city : Request::old("destination_city"), ['class' => 'billing__input']) !!}
                  {!! Html::checkError('destination_city', $errors) !!}


                </div>
                <div class="grid-6">
                  <div class="billing__label">
                    {!! Form::label('destination_zip', 'Code postal') !!}
                  </div>
                  {!! Form::text("destination_zip", ($order_building->destination_zip) ? $order_building->destination_zip : Request::old("destination_zip"), ['class' => 'billing__input']) !!}
                  {!! Html::checkError('destination_zip', $errors) !!}
                </div>
              </div>

              <div class="billing__label">
                {!! Form::label('destination_address', 'Adresse') !!}
              </div>
              {!! Form::textarea("destination_address", ($order_building->destination_address) ? $order_building->destination_address : Request::old("destination_address"), ['class' => 'billing__input --textarea']) !!}
              {!! Html::checkError('destination_address', $errors) !!}
            </div>
          </div>