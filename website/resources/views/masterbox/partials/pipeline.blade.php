<div class="grid-10 float-right grid-12@xs">
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