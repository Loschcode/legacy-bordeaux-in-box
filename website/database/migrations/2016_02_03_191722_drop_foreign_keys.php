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

      Schema::table('box_answers', function ($table) {
        $table->dropForeign('box_answers_box_question_id_foreign');
      });

      Schema::table('box_question_customer_answers', function ($table) {
        $table->dropForeign('user_answers_box_question_id_foreign');
        $table->dropForeign('user_answers_user_profile_id_foreign');
      });

      Schema::table('customer_payment_profiles', function ($table) {
        $table->dropForeign('user_payment_profiles_user_profile_id_foreign');
      });

      Schema::table('customer_profiles', function ($table) {
        $table->dropForeign('user_profiles_user_id_foreign');
      });

      Schema::table('order_billings', function ($table) {
        $table->dropForeign('order_billings_order_id_foreign');
      });

      Schema::table('order_destinations', function ($table) {
        $table->dropForeign('order_destinations_order_id_foreign');
      });

      Schema::table('orders', function ($table) {
        $table->dropForeign('orders_delivery_serie_id_foreign');
        $table->dropForeign('orders_user_id_foreign');
        $table->dropForeign('orders_user_profile_id_foreign');
      });

      Schema::table('payments', function ($table) {
        $table->dropForeign('payments_user_id_foreign');
        $table->dropForeign('payments_user_profile_id_foreign')
;      });


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
