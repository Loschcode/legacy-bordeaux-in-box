<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use App\Models\Coordinate;
use App\Models\Customer;

class ChangeCustomersCoordinatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

      Schema::table('customers', function($table)
      {
        $table->integer('coordinate_id');
        $table->index('coordinate_id');
      });

      /**
       * We also convert it
       */
      
      /**
       * WARNING :
       * If this blow up, comment the getAddressAttributr accessors and equivalents
       */
      $customers = Customer::get();
      foreach ($customers as $customer) {
        $customer->coordinate_id = Coordinate::getMatchingOrGenerate($customer->address, $customer->zip, $customer->city)->id;
        $customer->save();
      }

      /**
       * Then we remove the column
       */
      Schema::table('customers', function($table)
      {
        $table->dropColumn('address');
        $table->dropColumn('zip');
        $table->dropColumn('city');
      });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

      Schema::table('customers', function($table)
      {
        // WE CANNOT REALLY ROLLBACK BUT I PUT IT HERE ANYWAY BY PRINCIPLE
        $table->dropColumn('coordinate_id');

        $table->string('address');
        $table->string('zip');
        $table->string('city');
        
      });

    }
}
