<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeCustomerProfileNotesTypeDeliverySerieId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

      Schema::table('customer_profile_notes', function($table)
      {

        $table->string('type');
        $table->integer('delivery_serie_id')->unsigned()->nullable();

        $table->index('delivery_serie_id');

      });

      foreach(\App\Models\CustomerProfileNote::get() as $note) {
        $note->type = 'general';
        $note->save();
      }

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

      Schema::table('customer_profile_notes', function($table) {

        $table->dropColumn('type');
        $table->dropColumn('delivery_serie_id');
    
      });

    }
}
