<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropForeignKeys extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

      // MOVED TO change_user_and_related_to_customer

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // WE CANNOT ROLLBACK FROM HERE
    }
}
