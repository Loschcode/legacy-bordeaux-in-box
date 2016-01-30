<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAdministratorsAndRemoveRoles extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

      Schema::create('administrators', function($table)
      {

        // Keys
        $table->increments('id');

        // Fields
        $table->string('email');
        $table->string('password');

        $table->string('first_name');
        $table->string('last_name');
        $table->string('remember_token');

        $table->string('phone');

        // Indexes
        $table->unique('email');

        // Timestamps
        $table->timestamps();

      });

      /**
       * We do a loop to convert all the customers into true admins from the new table
       */
      $administrators = App\Models\Customer::where('role', '=', 'admin')->get();

      foreach ($administrators as $admin) {

        $new_admin = new App\Models\Administrator;
        $new_admin->email = $admin->email;
        $new_admin->password = $admin->password;
        $new_admin->first_name = $admin->first_name;
        $new_admin->last_name = $admin->last_name;
        $new_admin->phone = $admin->phone;
        $new_admin->save();

      }

      Schema::table('customers', function(Blueprint $table)
      {

       $table->dropColumn('role');

      });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

      Schema::dropIfExists('administrators');

      Schema::table('customers', function(Blueprint $table)
      {

       $table->addColumn('role');

      });

    }

}
