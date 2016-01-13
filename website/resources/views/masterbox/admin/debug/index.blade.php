@extends('masterbox.layouts.admin')

@section('page')
  <i class="fa fa-bug"></i> Debug
@stop

@section('buttons')

@stop

@section('content')

  @if (session()->has('message'))
    <div class="js-alert-remove spyro-alert spyro-alert-success">{{ session()->get('message') }}</div>
  @endif

  @if (session()->has('error'))
    <div class="js-alert-remove spyro-alert spyro-alert-error">{{ session()->get('error') }}</div>
  @endif

  {!! Html::info("Section permettant de repèrer les bugs liés au site (paiement n'étant pas relié à une commande par exemple") !!}


  @if ($errors->has())
    <div class="js-alert-remove spyro-alert spyro-alert-danger">Erreurs dans le formulaire</div>
  @endif

  <ul class="nav nav-tabs" role="tablist">

    <li class="active"><a href="#payments" role="tab" data-toggle="tab"><i class="fa fa-thumbs-up"></i> Paiements orphelins ({{$payments->count()}})</a></li>

    <li><a href="#refund" role="tab" data-toggle="tab"><i class="fa fa-thumbs-down"></i> Remboursements orphelins ({{$refunded_payments->count()}})</a></li>

    <li><a href="#series_refund" role="tab" data-toggle="tab"><i class="fa fa-thumbs-down"></i> Remboursements non orphelins ({{$series_refunded_payments->count()}})</a></li>

    <li><a href="#all" role="tab" data-toggle="tab"><i class="fa fa-question-circle"></i> Toutes les transactions orphelines ({{$all_transactions->count()}})</a></li>

    <li><a href="#graphs" role="tab" data-toggle="tab"><i class="fa fa-circle-o-notch"></i> Tests &amp; Styles</a>
    </li>

  </ul>

    <!-- Tab List -->
    <div class="tab-content">


    <div class="tab-pane active" id="payments">

      @include('masterbox.admin.partials.payments_table', array('payments' => $payments))

    </div>

    <!-- Tab List -->
    <div class="tab-pane" id="refund">

      @include('masterbox.admin.partials.payments_table', array('payments' => $refunded_payments))

    </div>

    <!-- Tab List -->
    <div class="tab-pane" id="series_refund">

      @include('masterbox.admin.partials.payments_table', array('payments' => $series_refunded_payments))

    </div>

    <div class="tab-pane" id="all">

      @include('masterbox.admin.partials.payments_table', array('payments' => $all_transactions))

    </div>

    <div class="tab-pane" id="graphs">

        <a class="spyro-btn spyro-btn-lg spyro-btn-bichette" href="#">.spyro-btn-bichette</a>
        <a class="spyro-btn spyro-btn-lg spyro-btn-ghetto" href="#">.spyro-btn-ghetto</a>
        <a class="spyro-btn spyro-btn-lg spyro-btn-mamoune" href="#">.spyro-btn-mamoune</a>

        <!-- Example Area Chart -->

        <!--
          You can use options from http://morrisjs.github.io/morris.js/lines.html (Area section, but not the options which ask for a js function ... obviously.)
        -->
        @include('masterbox.admin.partials.graphs.area_chart', ['config' => [

          // Id (required)
          // Description : Set an id for the graph, need to be uniqueness
          'id' => 'graph-area-supertest',

          // Data (required)
          // Description : Chart data records, each entry in this array corresponds to a point on the chart.
          'data' => [

            ['y' => '2006', 'a' => '100', 'b' => '90'],
            ['y' => '2007', 'a' => '75', 'b' => '65' ],
            ['y' => '2008', 'a' => '50', 'b' => '40' ],
            ['y' => '2009', 'a' => '75', 'b' => '65' ],
            ['y' => '2010', 'a' => '50', 'b' => '40' ],
            ['y' => '2011', 'a' => '75', 'b' => '65' ],
            ['y' => '2012', 'a' => '100', 'b' => '90' ]

          ],

          // Xkey (required)
          // Description: The name of the data record attribute that contains x-values.
          'xkey' => 'y',

          // Ykeys (required)
          // Description: A list of names of data record attributes that contain y-values.
          'ykeys' => ['a', 'b'],

          // Labels (required)
          // Description: Labels for the ykeys, will be displayed when you hover over the chart.
          'labels' => ['Series A', 'Series B'],

          // Height (optional)
          // Description : Set the height of the graph
          // Default: 250px
          'height' => '200px',

        ]])


        <!-- Example 1 Line Chart -->

        <!--
          You can use options from http://morrisjs.github.io/morris.js/lines.html (But not the options which ask for a js function ... obviously.)
        -->
        @include('masterbox.admin.partials.graphs.line_chart', ['config' => [

          // Id (required)
          // Description : Set an id for the graph, need to be uniqueness
          'id' => 'graph-superman',

          // Data (required)
          // Description : Chart data records, each entry in this array corresponds to a point on the chart.
          'data' => [

            ['year' => '2008', 'value' => '20'],
            ['year' => '2009', 'value' => '23'],
            ['year' => '2012', 'value' => '40'],

          ],

          // Xkey (required)
          // Description: The name of the data record attribute that contains x-values.
          'xkey' => 'year',

          // Ykeys (required)
          // Description: A list of names of data record attributes that contain y-values.
          'ykeys' => ['value'],

          // Labels (required)
          // Description: Labels for the ykeys, will be displayed when you hover over the chart.
          'labels' => ['Values'],

          // Height (optional)
          // Description : Set the height of the graph
          // Default: 250px
          'height' => '200px',

        ]])

        <!-- Example 2 Line Chart -->
        @include('masterbox.admin.partials.graphs.line_chart', ['config' => [

          // Id (required)
          // Description : Set an id for the graph, need to be uniqueness
          'id' => 'graph-laurent',

          // Data (required)
          // Description : Chart data records, each entry in this array corresponds to a point on the chart.
          'data' => [

            ['y' => '2006', 'a' => '100', 'b' => '90'],
            ['y' => '2007', 'a' => '75', 'b' => '105'],

          ],

          // Xkey (required)
          // Description: The name of the data record attribute that contains x-values.
          'xkey' => 'y',

          // Ykeys (required)
          // Description: A list of names of data record attributes that contain y-values.
          'ykeys' => ['a', 'b'],

          // Labels (required)
          // Description: Labels for the ykeys, will be displayed when you hover over the chart.
          'labels' => ['Series A', 'Series B'],

          // Height (optional)
          // Description : Set the height of the graph
          // Default: 250px
          'height' => '200px',

          // Line colors (optional)
          // Description : You want to be a designer ? You can set an array containing colors for the series lines/points.
          // Tip : You can use flatuicolors.com for great colors !
          'lineColors' => ['#9b59b6'],

          // Line width (optional)
          // Description : Width of the series lines, in pixels.
          'lineWidth' => ['6px', '2px']
    
        ]])

        <!-- Example Donut Chart -->
        <!--
          All options of that chart are used, so it's useless to give you the link to the documentation for that
        -->
        @include('masterbox.admin.partials.graphs.donut_chart', ['config' => [

          'id' => 'graph-donut-heroes',

          'data' => [

            ['label' => 'Superman', 'value' => '60'],
            ['label' => 'Batman', 'value' => '20'],
            ['label' => 'Ironman', 'value' => '20']

          ],

          'colors' => ['#9b59b6', '#8e44ad', '#34495e']

        ]])
        
        <!-- Example Bar Chart -->

        <!--
          You can use options from http://morrisjs.github.io/morris.js/bars.htmll (But not the options which ask for a js function ... obviously.)
        -->
        @include('masterbox.admin.partials.graphs.bar_chart', ['config' => [

          'id' => 'graph-bar-example',

          'data' => [

            ['y' => '2006', 'a' =>  '10', 'b' => '90' ],
            ['y' => '2007', 'a' =>  '75', 'b' => '65' ],
            ['y' => '2008', 'a' =>  '50', 'b' => '40' ],
            ['y' => '2009', 'a' =>  '75', 'b' => '65' ],
            ['y' => '2010', 'a' =>  '50', 'b' => '40' ],
            ['y' => '2011', 'a' =>  '75', 'b' => '65' ],
            ['y' => '2012', 'a' =>  '10', 'b' => '90' ]

          ],

          'xkey' => 'y',
          'ykeys' =>  ['a', 'b'],
          'labels' => ['Series A', 'Series B']

        ]])

      </div>

    </div>

    <h2>DEV TOOLS</h2>

    {!! Html::info("Ne touchez à rien si vous n'êtes pas un développeur et ne connaissez pas les fonctionnalités ci-dessous, la plupart sont des moulinettes permettant de mettre à jour la base de données et sont très sensibles") !!}

    <h3>Correction base de données</h3>

    <a class="spyro-btn spyro-btn-danger" href="{{ url('admin/debug/database-correction-completed-series') }}">Correction de la série 2015-05-18 commandes complétées</a>

    <a class="spyro-btn spyro-btn-danger" href="{{ url('admin/debug/database-correction-series-infinite-sequel') }}">Correction de la série 2015-07-10 doublée pour les plans infinis</a>

    <a class="spyro-btn spyro-btn-danger" href="{{ url('admin/debug/database-correction-series-infinite') }}">Correction de la série 2015-06-10 doublée pour les plans infinis</a>

    <h3>Abonnements incomplets / complets</h3>

    <a class="spyro-btn spyro-btn-danger" href="{{ url('admin/debug/generate-user-answers-slugs') }}">Générer le `slug` des `customer_answers` si vide</a>

    <a class="spyro-btn spyro-btn-danger" href="{{ url('admin/debug/convert-sponsor-answer-into-email') }}">Convertir les réponses liés aux parrains en email</a>

    <h3>Abonnements en constructions / incomplets</h3>

    <a class="spyro-btn spyro-btn-danger" href="{{ url('admin/debug/update-link-series-and-user-order-building') }}">Mettre à jour les liens entre séries et profils en construction</a>

    <br /><br />

    <a class="spyro-btn spyro-btn-danger" href="{{ url('admin/debug/update-user-profile-status') }}">Mettre à jour les status des abonnement (abonnés, expirés, en création, non-abonnés)</a>

    <br /><br />

    <a class="spyro-btn spyro-btn-danger" href="{{ url('admin/debug/update-user-profile-status-updated-at') }}">Mettre à jour la date du changement de status des abonnement (selon qu'ils soient abonnés, expirés, en création, non-abonnés)</a>

    <br /><br />

    <a class="spyro-btn spyro-btn-danger" href="{{ url('admin/debug/update-link-user-profiles-and-user-order-building') }}">Mettre à jour les liens entre `user_profile` et profils en construction si orphelins</a>

    <h3>Système de paiement</h3>

    <a class="spyro-btn spyro-btn-danger" href="{{ url('admin/debug/inherit-payments-order-id-via-orders') }}">Faire hériter les ids `order_id` dans la table `payments` via l'ancien système de la table `orders` (`payment_id`)</a>

    <br /><br />

    <a class="spyro-btn spyro-btn-danger" href="{{ url('admin/debug/retrieve-plan-id-in-user-payment-profiles-with-stripe') }}">Trouver tous les `plan_id` via stripe dans la table `user_payment_profiles` si vide</a>

    <a class="spyro-btn spyro-btn-danger" href="{{ url('admin/debug/retrieve-last4-in-user-payment-profiles-with-stripe') }}">Trouver tous les `last4` via stripe dans la table `user_payment_profiles` si vide</a>

    <h3>Préférences de livraison</h3>

    <a class="spyro-btn spyro-btn-danger" href="{{ url('admin/debug/convert-user-order-preferences-zero-to-null') }}">Convertir la `frequency` dans `user_order_preferences` en `NULL` si `0`</a>

    <br /><br />

    <h3>Livraisons avec anomalies</h3>

    <a class="spyro-btn spyro-btn-danger" href="{{ url('admin/debug/update-date-completed-to-sent-orders') }}">Changer la date complétée des livraisons effectivement envoyées (possédant une date d'envoi) comme même date que celle d'envoi (aucune solution alternative possible)</a>

    <br /><br />

    <a class="spyro-btn spyro-btn-danger" href="{{ url('admin/debug/update-sent-orders-as-delivered') }}">Changer le `status` des livraisons effectivement envoyées (possédant une date d'envoi) en `delivered`</a>

<!--
<a class="spyro-btn spyro-btn-success" href="{{ url('admin/orders/download-csv-orders') }}">Télécharger le fichier CSV</a>-->


@stop