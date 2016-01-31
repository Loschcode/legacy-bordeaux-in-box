{!! Html::addButtonNavbar('Résumé', action('MasterBox\Admin\ProfilesController@getFocus', ['id' => $profile->id])) !!}
{!! Html::addButtonNavbar('Livraisons', action('MasterBox\Admin\ProfilesController@getDeliveries', ['id' => $profile->id])) !!}
{!! Html::addButtonNavbar('Historique de paiements', action('MasterBox\Admin\ProfilesController@getPayments', ['id' => $profile->id])) !!}
{!! Html::addButtonNavbar('Questionnaire', action('MasterBox\Admin\ProfilesController@getQuestions', ['id' => $profile->id])) !!}
