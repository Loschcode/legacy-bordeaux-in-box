<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCustomersProviderAndProviderId extends Migration
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

      $table->string('provider');
      $table->string('provider_id');
      
      // Indexes
      $table->index('provider');
      $table->index('provider_id');

    });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

      Schema::table('customers', function(Blueprint $table)
      {

       $table->removeColumn('provider');
       $table->removeColumn('provider_id');

      });

    }
}
