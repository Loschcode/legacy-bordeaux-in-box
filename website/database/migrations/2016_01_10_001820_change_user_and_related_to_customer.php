<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeUserAndRelatedToCustomer extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

      // MOVED
      Schema::table('box_answers', function ($table) {
        $table->dropForeign('box_answers_box_question_id_foreign');
      });

      Schema::table('box_question_user_answers', function ($table) {
        $table->dropForeign('user_answers_box_question_id_foreign');
        $table->dropForeign('user_answers_user_profile_id_foreign');
      });

      Schema::table('user_payment_profiles', function ($table) {
        $table->dropForeign('user_payment_profiles_user_profile_id_foreign');
      });

      Schema::table('user_profiles', function ($table) {
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
        $table->dropForeign('payments_user_profile_id_foreign');
      });
      // END OF MOVED

      /**
       * Workaround for the ENUM problem (Thank you Laravel 5.2, you suck.)
       */
      DB::getDoctrineSchemaManager()->getDatabasePlatform()->registerDoctrineTypeMapping('enum', 'string');

      /**
       * 
       * Big migration doing the changes
       * On the User and related to Customer
       * 
       */

      // Table : users
      Schema::rename('users', 'customers');

      // Linked to the table
      Schema::table('orders', function ($table) {
        $table->renameColumn('user_profile_id', 'customer_profile_id');
        $table->renameColumn('user_id', 'customer_id');
      });
      Schema::table('payments', function ($table) {
        $table->renameColumn('user_profile_id', 'customer_profile_id');
        $table->renameColumn('user_id', 'customer_id');
      });
      Schema::table('email_traces', function ($table) {
        $table->renameColumn('user_profile_id', 'customer_profile_id');
        $table->renameColumn('user_id', 'customer_id');
      });
      Schema::table('image_articles', function ($table) {
        $table->renameColumn('user_id', 'customer_id');
      });
      Schema::table('blog_articles', function ($table) {
        $table->renameColumn('user_id', 'customer_id');
      });
      // End linked to the table
  
      // Table : user_profiles
      Schema::rename('user_profiles', 'customer_profiles');
      Schema::table('customer_profiles', function ($table) {
        $table->renameColumn('user_id', 'customer_id');
      });

      Schema::rename('user_profile_products', 'customer_profile_products');
      Schema::table('customer_profile_products', function ($table) {
        $table->renameColumn('user_profile_id', 'customer_profile_id');
      });

      Schema::rename('user_profile_notes', 'customer_profile_notes');
      Schema::table('customer_profile_notes', function ($table) {
        $table->renameColumn('user_profile_id', 'customer_profile_id');
        $table->renameColumn('user_id', 'customer_id');
      });

      Schema::rename('user_answers', 'customer_answers');
      Schema::table('customer_answers', function ($table) {
        $table->renameColumn('user_profile_id', 'customer_profile_id');
      });

      Schema::rename('user_order_preferences', 'customer_order_preferences');
      Schema::table('customer_order_preferences', function ($table) {
        $table->renameColumn('user_profile_id', 'customer_profile_id');
      });

      Schema::rename('user_payment_profiles', 'customer_payment_profiles');
      Schema::table('customer_payment_profiles', function ($table) {
        $table->renameColumn('user_profile_id', 'customer_profile_id');
      });

      Schema::rename('user_order_buildings', 'customer_order_buildings');
      Schema::table('customer_order_buildings', function ($table) {
        $table->renameColumn('user_profile_id', 'customer_profile_id');
        $table->renameColumn('user_id', 'customer_id');
        $table->renameColumn('user_order_preference_id', 'customer_order_preference_id');
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

      /**
       * Workaround for the ENUM problem (Thank you Laravel 5.2, you suck.)
       */
      DB::getDoctrineSchemaManager()->getDatabasePlatform()->registerDoctrineTypeMapping('enum', 'string');

      // Table : users
      Schema::rename('customers', 'users');

      // Linked to the table
      Schema::table('orders', function ($table) {
        $table->renameColumn('customer_profile_id', 'user_profile_id');
        $table->renameColumn('customer_id', 'user_id');
      });
      Schema::table('payments', function ($table) {
        $table->renameColumn('customer_profile_id', 'user_profile_id');
        $table->renameColumn('customer_id', 'user_id');
      });
      Schema::table('email_traces', function ($table) {
        $table->renameColumn('customer_profile_id', 'user_profile_id');
        $table->renameColumn('customer_id', 'user_id');
      });
      Schema::table('image_articles', function ($table) {
        $table->renameColumn('customer_id', 'user_id');
      });
      Schema::table('blog_articles', function ($table) {
        $table->renameColumn('customer_id', 'user_id');
      });
      // End linked to the table
  
      // Table : customer_profiles
      Schema::table('customer_profiles', function ($table) {
        $table->renameColumn('customer_id', 'user_id');
      });
      Schema::rename('customer_profiles', 'user_profiles');

      Schema::table('customer_profile_products', function ($table) {
        $table->renameColumn('customer_profile_id', 'user_profile_id');
      });
      Schema::rename('customer_profile_products', 'user_profile_products');

      Schema::table('customer_profile_notes', function ($table) {
        $table->renameColumn('customer_profile_id', 'user_profile_id');
        $table->renameColumn('customer_id', 'user_id');
      });
      Schema::rename('customer_profile_notes', 'user_profile_notes');

      Schema::table('customer_answers', function ($table) {
        $table->renameColumn('customer_profile_id', 'user_profile_id');
      });
      Schema::rename('customer_answers', 'user_answers');

      Schema::table('customer_order_preferences', function ($table) {
        $table->renameColumn('customer_profile_id', 'user_profile_id');
      });
      Schema::rename('customer_order_preferences', 'user_order_preferences');

      Schema::table('customer_payment_profiles', function ($table) {
        $table->renameColumn('customer_profile_id', 'user_profile_id');
      });
      Schema::rename('customer_payment_profiles', 'user_payment_profiles');

      Schema::table('customer_order_buildings', function ($table) {
        $table->renameColumn('customer_profile_id', 'user_profile_id');
        $table->renameColumn('customer_id', 'user_id');
        $table->renameColumn('customer_order_preference_id', 'user_order_preference_id');
      });
      Schema::rename('customer_order_buildings', 'user_order_buildings');

    }
}
