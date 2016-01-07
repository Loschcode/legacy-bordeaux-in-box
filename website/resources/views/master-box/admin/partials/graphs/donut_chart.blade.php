<?php
  $datas = $config;
  $rules = [
    'id' => 'required',
    'data' => 'required',
  ];

  $validation = Validator::make($datas, $rules);

  if ($validation->passes())
  {
    $success = true;
  }
  else
  {
    $success = false;
  }
?>

@if ($success)
  <div data-real-width class="col-md-12">
    <div id="{{ $config['id'] }}" data-graph="donut-chart" data-config="{{ htmlspecialchars(json_encode($config)) }}" style="height: 250px"></div>
  </div>
@else
  <div class="spyro-alert spyro-alert-danger">
    <p><strong>Error generate donut chart</strong></p>
    @foreach ($validation->messages()->all() as $message)
      {{ $message }}<br/>
    @endforeach
  </div>  
@endif
