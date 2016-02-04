<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangePaymentsStripeSubscription extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

      Schema::table('payments', function($table)
      {

        $table->string('stripe_subscription');

      });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

      Schema::table('payments', function($table) {

        $table->dropColumn('stripe_subscription');
    
      });

    }
}
