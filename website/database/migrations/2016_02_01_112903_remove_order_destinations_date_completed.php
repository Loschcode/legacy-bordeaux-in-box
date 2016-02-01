<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveOrderDestinationsDateCompleted extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

      Schema::table('order_destinations', function ($table) {
        $table->dropColumn('date_completed');
      });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

      Schema::table('order_destinations', function ($table) {
        $table->datetime('date_completed');
      });

    }
}
