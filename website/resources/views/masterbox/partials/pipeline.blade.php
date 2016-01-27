<div class="+spacer-small"></div>

<div class="container">
	<div class="pipeline">
		<div class="row">
			<div class="grid-3 no-gutter">
				<div class="pipeline__step">
					<h2 class="pipeline__title">Etape 1</h2>
					<div class="pipeline__progress --first {{ Html::pipelineComplete(1, $step) }}"></div>

					@if (Html::pipelineStepCompleted(1, $step) && Html::pipelinePaymentStepDone($step))
						<span class="pipeline__dot --complete"></span>
					@elseif (Html::pipelineStepCompleted(1, $step))
						<a id="test-step-choose-frequency" href="{{ action('MasterBox\Customer\PurchaseController@getChooseFrequency') }}" class="pipeline__dot --complete"></a>
					@else
						<span class="pipeline__dot"></span>
					@endif

					<p class="pipeline__description"><i class="fa fa-clock-o"></i> Fréquence</p>
				</div>
			</div>
			<div class="grid-3 no-gutter">
				<div class="pipeline__step">
					<h2 class="pipeline__title">Etape 2</h2>
					<div class="pipeline__progress {{ Html::pipelineComplete(2, $step) }}"></div>

					@if (Html::pipelineStepCompleted(2, $step) && Html::pipelinePaymentStepDone($step))
						<span class="pipeline__dot --complete"></span>
					@elseif (Html::pipelineStepCompleted(2, $step))
						<a id="test-step-billing-address" href="{{ action('MasterBox\Customer\PurchaseController@getBillingAddress') }}" class="pipeline__dot --complete"></a>
					@else
						<span class="pipeline__dot"></span>
					@endif

					<p class="pipeline__description"><i class="fa fa-truck"></i> Livraison</p>
				</div>
			</div>
			<div class="grid-3 no-gutter">
				<div class="pipeline__step">
					<h2 class="pipeline__title">Etape 3</h2>
					<div class="pipeline__progress {{ Html::pipelineComplete(3, $step) }}"></div>

					@if (Html::pipelineStepCompleted(3, $step) && Html::pipelinePaymentStepDone($step))
						<span class="pipeline__dot --complete"></span>
					@elseif (Html::pipelineStepCompleted(3, $step))
						<a id="test-step-destination" href="{{ action('MasterBox\Customer\PurchaseController@getPayment') }}" class="pipeline__dot --complete"></a>
					@else
						<span class="pipeline__dot"></span>
					@endif

					<p class="pipeline__description"><i class="fa fa-credit-card"></i> Paiement</p>
				</div>
			</div>
			<div class="grid-3 no-gutter">
				<div class="pipeline__step">
					<h2 class="pipeline__title">Etape 4</h2>
					<div class="pipeline__progress --last {{ Html::pipelineComplete(4, $step) }}"></div>
					
					@if (Html::pipelineStepCompleted(4, $step) && Html::pipelinePaymentStepDone($step))
						<span class="pipeline__dot --complete"></span>
					@elseif (Html::pipelineStepCompleted(4, $step))
						<a id="test-step-payment" href="{{ action('MasterBox\Customer\PurchaseController@getBoxForm') }}" class="pipeline__dot --complete"></a>
					@else
						<span class="pipeline__dot"></span>
					@endif
					
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