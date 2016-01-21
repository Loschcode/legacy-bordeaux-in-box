<div class="+spacer-small"></div>

<div class="container">
	<div class="pipeline">
		<div class="row">
			<div class="grid-3 no-gutter">
				<div class="pipeline__step">
					<h2 class="pipeline__title">Etape 1</h2>
					<div class="pipeline__progress --first {{ Html::pipelineComplete(1, $step) }}"></div>
					<a class="pipeline__dot {{ Html::pipelineComplete(1, $step) }}" href="#"></a>
					<p class="pipeline__description"><i class="fa fa-clock-o"></i> Fréquence de livraison</p>
				</div>
			</div>
			<div class="grid-3 no-gutter">
				<div class="pipeline__step">
					<h2 class="pipeline__title">Etape 2</h2>
					<div class="pipeline__progress {{ Html::pipelineComplete(2, $step) }}"></div>
					<a class="pipeline__dot {{ Html::pipelineComplete(2, $step) }}" href="#"></a>
					<p class="pipeline__description"><i class="fa fa-truck"></i> Livraison / Facturation</p>
				</div>
			</div>
			<div class="grid-3 no-gutter">
				<div class="pipeline__step">
					<h2 class="pipeline__title">Etape 3</h2>
					<div class="pipeline__progress {{ Html::pipelineComplete(3, $step) }}"></div>
					<a class="pipeline__dot {{ Html::pipelineComplete(3, $step) }}" href="#"></a>
					<p class="pipeline__description"><i class="fa fa-credit-card"></i> Paiement</p>
				</div>
			</div>
			<div class="grid-3 no-gutter">
				<div class="pipeline__step">
					<h2 class="pipeline__title">Etape 4</h2>
					<div class="pipeline__progress --last {{ Html::pipelineComplete(4, $step) }}"></div>
					<a class="pipeline__dot {{ Html::pipelineComplete(4, $step) }}" href="#"></a>
					<p class="pipeline__description"><i class="fa fa-magic"></i> Personnalisation</p>
				</div>
			</div>
		</div>
	</div>
</div>
<?php 
/*
<div class="container">

	<div class="spacer20"></div>
	
	<div class="row bs-wizard" style="border-bottom:0;">

		<div class="col-xs-2 col-md-offset-2 bs-wizard-step {{ $states['step1'] }}">
			<div class="text-center bs-wizard-stepnum">Etape 1</div>
			<div class="progress"><div class="progress-bar"></div></div>
			<a {{ $tooltips['step2'] }} href="{{ action('MasterBox\Customer\PurchaseController@getBoxForm') }}" class="bs-wizard-dot"></a>
			<div class="bs-wizard-info text-center">Personnalise ta box</div>
		</div>

		<div class="col-xs-2 bs-wizard-step {{ $states['step2'] }}">
			<div class="text-center bs-wizard-stepnum">Etape 2</div>
			<div class="progress"><div class="progress-bar"></div></div>
			<a {{ $tooltips['step3'] }} href="{{ action('MasterBox\Customer\PurchaseController@getChooseFrequency') }}" class="bs-wizard-dot"></a>
			<div class="bs-wizard-info text-center">Fréquence de livraison</div>
		</div>

		<div class="col-xs-2 bs-wizard-step {{ $states['step3'] }}">
			<div class="text-center bs-wizard-stepnum">Etape 3</div>
			<div class="progress"><div class="progress-bar"></div></div>
			<a {{ $tooltips['step4'] }} href="{{ action('MasterBox\Customer\PurchaseController@getBillingAddress') }}" class="bs-wizard-dot"></a>
			<div class="bs-wizard-info text-center">Facturation / Livraison</div>
		</div>

		<div class="col-xs-2 bs-wizard-step {{ $states['step4'] }}">
			<div class="text-center bs-wizard-stepnum">Etape 4</div>
			<div class="progress"><div class="progress-bar"></div></div>
			<a {{ $tooltips['step5'] }} href="{{ action('MasterBox\Customer\PurchaseController@getPayment') }}" class="bs-wizard-dot"></a>
			<div class="bs-wizard-info text-center">Paiement</div>
		</div>
	</div>


</div>
*/ ?>