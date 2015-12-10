@section('page')
  <i class="fa fa-folder"></i> Produits &amp; Partenaires
@stop

@section('content')
  @if (Session::has('message'))
    <div class="js-alert-remove spyro-alert spyro-alert-success">{{ Session::get('message') }}</div>
  @endif

  @include('_includes.errors', ['errors' => $errors])

  {{ HTML::info('Gestion des produits et partenaires du site.') }}


  <ul class="nav nav-tabs" role="tablist">

    <li class="active"><a href="#products" role="tab" data-toggle="tab"><i class="fa fa-folder-open"></i>Produits</a></li>
    <li><a href="#partners" role="tab" data-toggle="tab"><i class="fa fa-users"></i> Partenaires</a></li>
    <li><a href="#filters" role="tab" data-toggle="tab"><i class="fa fa-filter"></i> Filtres &amp; Séries</a></li>

  </ul>

  <div class="tab-content">

    <!-- Tab List -->
    <div class="tab-pane" id="filters">

      <table class="js-datas">

        <thead>

          <tr>
    
            <th>ID</th>
            <th>Série</th>
            <th>Produits sélectionnés</th>
            <th>Coût moyen par box</th>
            <th>Valeur moyenne par box</th>
            <th>Poids moyen par box</th>
            <th>Action</th>
          </tr>

        </thead>

        <tbody>

          @foreach ($series as $serie)

            @if (!$serie->wasDelivered())

              <tr>

              <th>{{$serie->id}}</th>
              <th>{{$serie->delivery}}</th>
              <th>{{$serie->serie_products()->count()}}</th>
              <th>{{UserProfileProduct::getAverageCost($serie->id)}} €</th>
              <th>{{UserProfileProduct::getAverageValue($serie->id)}} €</th>
              <th>{{UserProfileProduct::getAverageWeight($serie->id)}}</th>
              <th>
                
                @if ($serie->product_filter_setting()->first() !== NULL)

                @if ($serie->serieProductsAreReady())

                  <a data-toggle="tooltip" title="Générer la sélection de produits pour cette série" class="spyro-btn spyro-btn-primary spyro-btn-sm" href="{{ url('/admin/products/generate-products-selection/'.$serie->id) }}"><i class="fa fa-magic"></i></a>

                  <a data-toggle="tooltip" title="Editer les produits de la série" class="spyro-btn spyro-btn-success spyro-btn-sm" href="{{ url('/admin/products/customize-products-selection/'.$serie->product_filter_setting()->first()->id) }}"><i class="fa fa-folder-open"></i></a>
                  
                @else

                  <a data-toggle="tooltip" title="Personnaliser les produits de la série" class="spyro-btn spyro-btn-inverse spyro-btn-sm" href="{{ url('/admin/products/customize-products-selection/'.$serie->product_filter_setting()->first()->id) }}"><i class="fa fa-folder-open"></i></a>

                @endif


                <a data-toggle="tooltip" title="Editer les paramètres pour les produits de cette série" class="spyro-btn spyro-btn-success spyro-btn-sm" href="{{ url('/admin/products/update-products-selection/'.$serie->product_filter_setting()->first()->id) }}"><i class="fa fa-gear"></i></a>

                @else

                <a data-toggle="tooltip" title="Paramètrer les produits pour cette série" class="spyro-btn spyro-btn-inverse spyro-btn-sm" href="{{ url('/admin/products/setup-products-selection/'.$serie->id) }}"><i class="fa fa-gear"></i></a>

                @endif

              </th>

              </tr>

            @endif

          @endforeach

          </tbody>

        </table>


    </div>

    <!-- Tab List -->
    <div class="tab-pane active" id="products">

      <table class="js-datas">

        <thead>

          <tr>
            <th>ID</th>
            <th>Nom</th>
            <th>Partenaire</th>
            <th>Catégorie</th>
            <th>Description</th>
            <th>Taille</th>
            <th>Poids</th>
            <th>Images</th>
            <th>Boxes</th>
            <th>Options</th>
            <th>Restrictions</th>
            <th>Action</th>

          </tr>

        </thead>

        <tbody>

          @foreach ($products as $product)

            <tr>
              <th>{{$product->id}}</th>
              <th>{{$product->name}}</th>
              <th>{{$product->partner()->first()->name}}</th>
              <th>{{Config::get('bdxnbx.product_categories.'.$product->category)}}</th>
              <th>{{$product->description}}</th>
              <th>{{HTML::getReadableProductSize($product->size)}}</th>
              <th>{{$product->weight}}</th>
              <th>
              @if ($product->images()->count() > 0)

                @foreach ($product->images()->get() as $image)
                  <img width="50" src="{{ $image->getImageUrl() }}">
                @endforeach

              @else
                Aucun
              @endif

              </th>
              <th>
                @foreach ($product->boxes()->get() as $box)
                {{$box->title}}, 
                @endforeach
              </th>
              <th>
                @if ($product->birthday_ready == TRUE)
                  Anniversaire, 
                @endif

                @if ($product->sponsor_ready == TRUE)
                  Marraine, 
                @endif
              </th>
              <th>
                @if ($product->regional_only == TRUE)
                  Régional, 
                @endif
              </th>
              <th>

              @if ($product->filter_box_answers()->first() === NULL)

                <a data-toggle="tooltip" title="Ajouter des filtres avancés" class="spyro-btn spyro-btn-primary spyro-btn-sm" href="{{ url('/admin/products/advanced-product-filters/'.$product->id) }}"><i class="fa fa-filter"></i></a>

              @else

                <a data-toggle="tooltip" title="Editer des filtres avancés" class="spyro-btn spyro-btn-success spyro-btn-sm" href="{{ url('/admin/products/advanced-product-filters/'.$product->id) }}"><i class="fa fa-filter"></i></a>

              @endif

              <a data-toggle="tooltip" title="Editer" class="spyro-btn spyro-btn-warning spyro-btn-sm" href="{{ url('/admin/products/edit-product/'.$product->id) }}"><i class="fa fa-pencil"></i></a>
              
              <a data-toggle="tooltip" title="Archiver" class="spyro-btn spyro-btn-danger spyro-btn-sm" href="{{ url('/admin/products/delete-product/'.$product->id) }}"><i class="fa fa-trash-o"></i></a>

              </th>

            </tr>

          @endforeach

        </tbody>

      </table>

      <br /><br />

    <div class="panel panel-default">

        <!-- Flag -->
        <div id="add-product"></div>

        <div class="panel-heading">Ajouter un produit</div>
        <div class="panel-body">

          {{ Form::open(['action' => 'AdminProductsController@postAddProduct', 'files' => true]) }}

          <h4>Clonage</h4>

          <div class="form-group @if ($errors->first('master_partner_product_id')) has-error has-feedback @endif">
            {{ Form::label("master_partner_product_id", "Produit similaire") }} 
            {{ Form::select('master_partner_product_id', $products_list, null, ['data-toggle' => 'chosen']) }} <em>(Optionnel)</em>
            </div>

          <div id="checkbox-similar" class="form-group">
            <label for="past_advanced_filters">Copier les filtres avancés de l'article similaire</label>
            {{ Form::checkbox('past_advanced_filters', null, Input::old('past_advanced_filters')) }}

          </div>

          <h4>Description &amp; Images</h4>
          
          <div class="form-group @if ($errors->first('partner_id')) has-error has-feedback @endif">
            {{ Form::label("partner_id", "Partenaire") }}
            {{ Form::select('partner_id', $partners_list) }}
            </div>

          <div class="form-group @if ($errors->first('category')) has-error has-feedback @endif">
            {{ Form::label("category", "Catégorie") }}
            {{ Form::select('category', $categories_list) }}
            </div>

          <div class="form-group @if ($errors->first('name')) has-error has-feedback @endif">
            {{ Form::label("name", "e.g. Tanga noir taille XS)", ['class' => 'sr-only']) }}
            {{ Form::text("name", Input::old("name"), ['class' => 'form-control', 'placeholder' => 'Nom (e.g. Tanga noir taille XS)']) }}
          </div>

          <div class="form-group @if ($errors->first('description')) has-error has-feedback @endif">
            {{ Form::label("description", "Description", ['class' => 'sr-only']) }}
            {{ Form::textarea("description", Input::old("description"), ['class' => 'form-control', 'placeholder' => 'Description']) }}
          </div>


          <!-- Image -->
          <div class="form-group @if ($errors->first('image')) has-error has-feedback @endif">
            
            {{ Form::file('images[0]') }} <em>(Optionnel)</em>
            {{ Form::file('images[1]') }}
            {{ Form::file('images[2]') }}

          </div>

          <h4>Détails technique</h4>

          <div class="form-group @if ($errors->first('size')) has-error has-feedback @endif">
            {{ Form::label("size", "Taille", ['class' => 'sr-only']) }}
            {{ Form::select('size', $product_sizes_list) }}
            </div>

          <div class="form-group @if ($errors->first('weight')) has-error has-feedback @endif">
            {{ Form::label("weight", "Poids", ['class' => 'sr-only']) }}
            {{ Form::text("weight", Input::old("weight"), ['class' => 'form-control', 'placeholder' => 'Poids en grammes (e.g. 75.80)']) }}
          </div>

          <div class="form-group @if ($errors->first('birthday_ready')) has-error has-feedback @endif">

          <h4>Filtre : boxes</h4>

            @foreach (Box::get() as $box)
              
              {{ Form::label("boxes[".$box->id."]", $box->title) }}
              {{ Form::checkbox("boxes[".$box->id."]", $box->id, true) }}

            @endforeach

            <br />

            <h4>Filtre : options</h4>

            + {{ Form::label("birthday_ready", "Anniversaire") }}
            {{ Form::checkbox("birthday_ready", true) }}

            <br />

            + {{ Form::label("sponsor_ready", "Marraine") }}
            {{ Form::checkbox("sponsor_ready", true) }}

            <br />

            <h4>Filtre : restrictions</h4>

            - {{ Form::label("regional_only", "Régional (uniquement)") }}
            {{ Form::checkbox("regional_only", true) }}

            <br />

          </div>

          {{ Form::submit("Ajouter le produit", ['class' => 'spyro-btn spyro-btn-success']) }}

          {{ Form::close() }}

          </div>

      </div>

    </div>

    <!-- Tab List -->
    <div class="tab-pane" id="partners">

      <table class="js-datas">

        <thead>

          <tr>
            <th>ID</th>
            <th>Nom</th>
            <th>Description</th>
            <th>Site</th>
            <th>Facebook</th>
            <th>Article relié</th>
            <th>Images</th>
            <th>Action</th>

          </tr>

        </thead>

        <tbody>

          @foreach ($partners as $partner)

            <tr>
              <th>{{$partner->id}}</th>
              <th>{{$partner->name}}</th>
              <th>{{$partner->description}}</th>
              <th>{{$partner->website}}</th>
              <th>{{$partner->facebook}}</th>
              <th>
              @if ($partner->blog_article()->first() != NULL)
                <a href="{{ url('blog/article/' . $partner->blog_article()->first()->id) }}">{{$partner->blog_article()->first()->title}}</a>
              @else
                Aucun
              @endif
              </th>
              <th>
              @if ($partner->images()->count() > 0)

                @foreach ($partner->images()->get() as $image)
                  <img width="50" src="{{ $image->getImageUrl() }}">
                @endforeach

              @else
                Aucun
              @endif

              </th>
              <th>

              <a data-toggle="tooltip" title="Editer" class="spyro-btn spyro-btn-warning spyro-btn-sm" href="{{ url('/admin/products/edit-partner/'.$partner->id) }}"><i class="fa fa-pencil"></i></a>
              
              <a data-toggle="tooltip" title="Archiver" class="spyro-btn spyro-btn-danger spyro-btn-sm" href="{{ url('/admin/products/delete-partner/'.$partner->id) }}"><i class="fa fa-trash-o"></i></a>

              </th>

            </tr>

          @endforeach

        </tbody>

      </table>

      <br /><br />

    <div class="panel panel-default">
      
        <div class="panel-heading">Ajouter un partenaire</div>
        <div class="panel-body">
          
          {{ Form::open(['action' => 'AdminProductsController@postAddPartner', 'files' => true]) }}

          <div class="form-group @if ($errors->first('name')) has-error has-feedback @endif">
            {{ Form::label("name", "Nom", ['class' => 'sr-only']) }}
            {{ Form::text("name", Input::old("name"), ['class' => 'form-control', 'placeholder' => 'Nom']) }}
          </div>


          <div class="form-group @if ($errors->first('description')) has-error has-feedback @endif">
            {{ Form::label("description", "Description", ['class' => 'sr-only']) }}
            {{ Form::textarea("description", Input::old("description"), ['class' => 'form-control', 'placeholder' => 'Description']) }}
          </div>

          <div class="form-group @if ($errors->first('website')) has-error has-feedback @endif">
            {{ Form::label("website", "Site web", ['class' => 'sr-only']) }}
            {{ Form::text("website", Input::old("website"), ['class' => 'form-control', 'placeholder' => 'Site web (http://www.monsite.fr)']) }}
          </div>

          <div class="form-group @if ($errors->first('facebook')) has-error has-feedback @endif">
            {{ Form::label("facebook", "Facebook", ['class' => 'sr-only']) }}
            {{ Form::text("facebook", Input::old("facebook"), ['class' => 'form-control', 'placeholder' => 'Facebook (http://www.facebook.com)']) }}
          </div>

          <div class="form-group @if ($errors->first('blog_article_id')) has-error has-feedback @endif">
            {{ Form::label("blog_article_id", "Article du blog", ['class' => 'sr-only']) }}
            {{ Form::select('blog_article_id', $blog_articles_list) }}

          </div>

          <!-- Image -->
          <div class="form-group @if ($errors->first('image')) has-error has-feedback @endif">
            
            {{ Form::file('images[0]') }}
            {{ Form::file('images[1]') }}
            {{ Form::file('images[2]') }}

          </div>

          {{ Form::submit("Ajouter le partenaire", ['class' => 'spyro-btn spyro-btn-success']) }}

          {{ Form::close() }}

          </div>

      </div>

    </div>

@stop