<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use App\Models\CustomerProfileNote;
use App\Models\Customer;
use App\Models\CustomerProfile;
use App\Models\Administrator;

class ChangeCustomerProfileNotesAdministratorId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

      $notes = CustomerProfileNote::get();

      Schema::table('customer_profile_notes', function ($table) {
        $table->dropForeign('user_profile_notes_user_id_foreign');
        $table->dropForeign('user_profile_notes_user_profile_id_foreign');
      });

      foreach ($notes as $note) {

        if ($note->customer_id !== NULL) {
          $author = Customer::find($note->customer_id);
          $administrator_equivalent = Administrator::where('email', '=', $author->email)->first();
        } else {
          $administrator_equivalent = NULL;
        }

        if ($administrator_equivalent === NULL)
          $note->customer_id = NULL;
        else
          $note->customer_id = $administrator_equivalent->id;

        $note->save();

      }

      Schema::table('customer_profile_notes', function ($table) {

        $table->renameColumn('customer_id', 'administrator_id');

      });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      Schema::table('customer_profile_notes', function ($table) {
        $table->renameColumn('administrator_id', 'customer_id');
      });
    }
}
