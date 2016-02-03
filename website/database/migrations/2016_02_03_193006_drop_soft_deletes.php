<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropSoftDeletes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

      $to_drop = App\Models\BoxQuestion::whereNotNull('deleted_at')->get();
      foreach ($to_drop as $drop) { $drop->delete(); }

      Schema::table('box_questions', function ($table) {
        $table->dropColumn('deleted_at');
      });

      $to_drop = App\Models\Contact::whereNotNull('deleted_at')->get();
      foreach ($to_drop as $drop) { $drop->delete(); }
      
      Schema::table('contacts', function ($table) {
        $table->dropColumn('deleted_at');
      });

      $to_drop = App\Models\CustomerPaymentProfile::whereNotNull('deleted_at')->get();
      foreach ($to_drop as $drop) { $drop->delete(); }
      
      Schema::table('customer_payment_profiles', function ($table) {
        $table->dropColumn('deleted_at');
      });

      $to_drop = App\Models\CustomerProfile::whereNotNull('deleted_at')->get();
      foreach ($to_drop as $drop) { $drop->delete(); }
      
      Schema::table('customer_profiles', function ($table) {
        $table->dropColumn('deleted_at');
      });

      $to_drop = App\Models\CustomerProfileNote::whereNotNull('deleted_at')->get();
      foreach ($to_drop as $drop) { $drop->delete(); }
      
      Schema::table('customer_profile_notes', function ($table) {
        $table->dropColumn('deleted_at');
      });

      $to_drop = App\Models\Order::whereNotNull('deleted_at')->get();
      foreach ($to_drop as $drop) { $drop->delete(); }
      
      Schema::table('orders', function ($table) {
        $table->dropColumn('deleted_at');
      });

      $to_drop = App\Models\Payment::whereNotNull('deleted_at')->get();
      foreach ($to_drop as $drop) { $drop->delete(); }
      
      Schema::table('payments', function ($table) {
        $table->dropColumn('deleted_at');
      });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
