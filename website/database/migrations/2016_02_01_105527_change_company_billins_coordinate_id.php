<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use App\Models\Coordinate;
use App\Models\CompanyBilling;

class ChangeCompanyBillinsCoordinateId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

      Schema::table('company_billings', function($table)
      {
        $table->integer('coordinate_id');
        $table->index('coordinate_id');
      });

      /**
       * We also convert it
       */
      
      /**
       * WARNING :
       * If this blow up, comment the getAddressAttribute accessors and equivalents
       */
      $company_billings = CompanyBilling::get();
      foreach ($company_billings as $company_billing) {
        $company_billing->coordinate_id = Coordinate::getMatchingOrGenerate($company_billing->address, $company_billing->zip, $company_billing->city)->id;
        $company_billing->save();
      }

      /**
       * Then we remove the column
       */
      Schema::table('company_billings', function($table)
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

      Schema::table('company_billings', function($table)
      {
        // WE CANNOT REALLY ROLLBACK BUT I PUT IT HERE ANYWAY BY PRINCIPLE
        $table->dropColumn('coordinate_id');

        $table->string('address');
        $table->string('zip');
        $table->string('city');
        
      });

    }
}
