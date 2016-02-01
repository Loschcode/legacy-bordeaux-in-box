{!! Html::addButtonNavbar('Résumé', action('MasterBox\Admin\CustomersController@getFocus', ['id' => $customer->id])) !!}
{!! Html::addButtonNavbar('Edition', action('MasterBox\Admin\CustomersController@getEdit', ['id' => $customer->id])) !!}
