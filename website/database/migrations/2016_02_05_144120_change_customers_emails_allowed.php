<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeCustomersEmailsAllowed extends Migration
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

        $table->boolean('emails_allowed')->default(TRUE);

        $table->index('emails_allowed');

      });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

      Schema::table('customers', function($table) {

        $table->dropColumn('emails_allowed');
    
      });

    }
}
