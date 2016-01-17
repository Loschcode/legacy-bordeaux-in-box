<div class="container">

	<div class="spacer20"></div>
	
	<div class="row bs-wizard" style="border-bottom:0;">

		<div class="col-xs-2 bs-wizard-step {{ $states['step1'] }} col-md-offset-1">
			<div class="text-center bs-wizard-stepnum">Etape 1</div>
			<div class="progress"><div class="progress-bar"></div></div>
			<a {{ $tooltips['step1'] }} href="" class="bs-wizard-dot"></a>
			<div class="bs-wizard-info text-center">Choix de la box</div>
		</div>

		<div class="col-xs-2 bs-wizard-step {{ $states['step2'] }}">
			<div class="text-center bs-wizard-stepnum">Etape 2</div>
			<div class="progress"><div class="progress-bar"></div></div>
			<a {{ $tooltips['step2'] }} href="{{ action('MasterBox\Customer\PurchaseController@getBoxForm') }}" class="bs-wizard-dot"></a>
			<div class="bs-wizard-info text-center">Personnalise ta box</div>
		</div>

		<div class="col-xs-2 bs-wizard-step {{ $states['step3'] }}">
			<div class="text-center bs-wizard-stepnum">Etape 3</div>
			<div class="progress"><div class="progress-bar"></div></div>
			<a {{ $tooltips['step3'] }} href="{{ action('MasterBox\Customer\PurchaseController@getChooseFrequency') }}" class="bs-wizard-dot"></a>
			<div class="bs-wizard-info text-center">Fréquence de livraison</div>
		</div>

		<div class="col-xs-2 bs-wizard-step {{ $states['step4'] }}">
			<div class="text-center bs-wizard-stepnum">Etape 4</div>
			<div class="progress"><div class="progress-bar"></div></div>
			<a {{ $tooltips['step4'] }} href="{{ action('MasterBox\Customer\PurchaseController@getBillingAddress') }}" class="bs-wizard-dot"></a>
			<div class="bs-wizard-info text-center">Facturation / Livraison</div>
		</div>

		<div class="col-xs-2 bs-wizard-step {{ $states['step5'] }}">
			<div class="text-center bs-wizard-stepnum">Etape 5</div>
			<div class="progress"><div class="progress-bar"></div></div>
			<a {{ $tooltips['step5'] }} href="{{ action('MasterBox\Customer\PurchaseController@getPayment') }}" class="bs-wizard-dot"></a>
			<div class="bs-wizard-info text-center">Paiement</div>
		</div>
	</div>


</div>