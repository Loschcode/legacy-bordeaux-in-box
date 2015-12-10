@section('page')
	<i class="fa fa-gift"></i> Boxes
@stop

@section('buttons')
	<a class="spyro-btn spyro-btn-success" href="{{ url('admin/boxes/new') }}"><i class="fa fa-plus"></i> Ajouter une box</a>
@stop

@section('content')
	

	@if (Session::has('message'))
	  <div class="js-alert-remove spyro-alert spyro-alert-success">{{ Session::get('message') }}</div>
	@endif

	@if ($errors->has())
	  <div class="js-alert-remove spyro-alert spyro-alert-danger">Erreurs dans le formulaire</div>
	@endif

	<ul class="nav nav-tabs" role="tablist">
	  <li class="active"><a href="#online" role="tab" data-toggle="tab"><i class="fa fa-check"></i> En ligne ({{ $active_boxes->count() }})</a></li>
	  <li><a href="#offline" role="tab" data-toggle="tab"><i class="fa fa-close"></i> En préparation / Hors ligne ({{$unactive_boxes->count()}})</a></li>
	</ul>

	<div class="tab-content">
		<div class="tab-pane active" id="online">
			
			<table id="table-boxes">

				<thead>

					<tr>
						<th>Titre</th>
						<th>Description</th>
						<th>Image</th>
						<th>Questions</th>
						<th>Action</th>

					</tr>

				</thead>

				<tbody>

					@foreach ($active_boxes as $box)

						<tr>
							<th>{{$box->title}}</th>
							<th>{{$box->description}}</th>
							<th><img width="150" src="{{ url($box->image->full)}}"></th>
							<th>
							<a class="spyro-btn spyro-btn-primary spyro-btn-sm" href="{{ url('/admin/boxes/questions/focus/' . $box->id) }}">
								@if ($box->questions()->first() !== NULL)
								Cette box possède {{$box->questions()->count()}} questions
								@else
								Cette box ne possède aucune question pour le moment
								@endif
							</a>
							</th>
							<th>

								<a data-toggle="tooltip" title="" class="spyro-btn spyro-btn-primary spyro-btn" href="{{ url('/admin/deliveries/focus-box/'.$box->id) }}"><i class="fa fa-search"></i></a>

								<a data-toggle="tooltip" title="Désactiver"class="spyro-btn spyro-btn-inverse" href="{{ url('/admin/boxes/desactivate/'.$box->id) }}"><i class="fa fa-close"></i></a>
								<a data-toggle="tooltip" title="Editer" class="spyro-btn spyro-btn-warning" href="{{ url('/admin/boxes/edit/'.$box->id) }}"><i class="fa fa-pencil"></i></a>
								<a data-toggle="tooltip" title="Archiver" class="spyro-btn spyro-btn-danger" href="{{ url('/admin/boxes/delete/'.$box->id) }}"><i class="fa fa-archive"></i></a>
							</th>
						</tr>

					@endforeach

				</tbody>

			</table>
		</div>
		<div class="tab-pane" id="offline">

			<table id="boxes" class="js-datas">

				<thead>

					<tr>
						<th>Titre</th>
						<th>Description</th>
						<th>Image</th>
						<th>Questions</th>
						<th class="w50">Action</th>

					</tr>

				</thead>

				<tbody>

					@foreach ($unactive_boxes as $box)

						<tr>
							<th>{{$box->title}}</th>
							<th>{{$box->description}}</th>
							<th><img width="150" src="{{ url($box->image->full)}}"></th>
							<th>
							<a class="simple" href="{{ url('admin/boxes/questions/focus/' . $box->id) }}">
								@if ($box->questions()->first() !== NULL)
								Cette box possède {{$box->questions()->count()}} questions
								@else
								Cette box ne possède aucune question pour le moment
								@endif
							</a>
							</th>
							<th class="w50">

								<a data-toggle="tooltip" title="" class="spyro-btn spyro-btn-primary spyro-btn" href="{{ url('/admin/deliveries/focus-box/'.$box->id) }}"><i class="fa fa-search"></i></a>

								<a data-toggle="tooltip" title="Activer" class="spyro-btn spyro-btn-success spyro-btn-sm" href="{{ url('/admin/boxes/activate/'.$box->id) }}"><i class="fa fa-check"></i></a>
								<a data-toggle="tooltip" title="Editer" class="spyro-btn spyro-btn-warning spyro-btn-sm" href="{{ url('/admin/boxes/edit/'.$box->id) }}"><i class="fa fa-pencil"></i> </a>
								<a data-toggle="tooltip" title="Archiver" class="spyro-btn spyro-btn-danger spyro-btn-sm" href="{{ url('/admin/boxes/delete/'.$box->id) }}"><i class="fa fa-archive"></i></a></th>
						</tr>

					@endforeach

				</tbody>

			</table>
		</div>

	</div>
@stop