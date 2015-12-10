<?php

View::composer('_includes.pipeline', function($view)
{
	// Total steps of the pipeline	
	$totalSteps = 5;

	// Fetch datas
	$datas = $view->getData();	

	$currentStep = $datas['step'];

	$states = [];

	// Loop to create an array with css class for steps
	for ($i = 1; $i != $totalSteps+1; $i++)
	{
		if ($i <= $currentStep)
		{
			$states['step' . $i] = 'complete';
		}
		else
		{
			$states['step' . $i] = 'disabled';
		}
	}

	$tooltips = [];

	// Loop to create html tooltips
	foreach ($states as $key => $state)
	{

		if ($state == 'complete')
		{
			$tooltips[$key] = 'data-toggle="tooltip" data-title="Revenir à cette étape" data-placement="top"';
		}
		else
		{
			$tooltips[$key] = '';
		}
	}

	foreach ($tooltips as $key => $tooltip)
	{	
		// Empty tooltip found
		if (empty($tooltip))
		{

			// Check if previous occurence exists
			$previousKey = str_replace('step', '', $key);
			$previousKey = $previousKey - 1;
			$previousKey = 'step' . $previousKey;


			if (isset($previousKey) && ! empty($previousKey))
			{
				$tooltips[$previousKey] = '';
			}

			break;

		}
	}

    $view->with(compact('states', 'tooltips'));
});