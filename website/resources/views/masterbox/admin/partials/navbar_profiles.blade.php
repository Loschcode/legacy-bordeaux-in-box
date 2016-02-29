{!! Html::addButtonNavbar('<i class="fa fa-file-text-o"></i> Résumé', action('MasterBox\Admin\ProfilesController@getFocus', ['id' => $profile->id])) !!}
{!! Html::addButtonNavbar('<i class="fa fa-truck"></i> Livraisons', action('MasterBox\Admin\ProfilesController@getDeliveries', ['id' => $profile->id])) !!}
{!! Html::addButtonNavbar('<i class="fa fa-credit-card"></i> Historique de paiements', action('MasterBox\Admin\ProfilesController@getPayments', ['id' => $profile->id])) !!}
{!! Html::addButtonNavbar('<i class="fa fa-question-circle"></i> Questionnaire', action('MasterBox\Admin\ProfilesController@getQuestions', ['id' => $profile->id])) !!}
{!! Html::addButtonNavbar('<i class="fa fa-tasks"></i> Logs', action('MasterBox\Admin\ProfilesController@getLogs', ['id' => $profile->id])) !!}
