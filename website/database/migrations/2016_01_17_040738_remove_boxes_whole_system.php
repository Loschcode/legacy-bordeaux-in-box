<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use App\Models\BoxQuestion;
use App\Models\BoxAnswer;

class RemoveBoxesWholeSystem extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {


      Schema::table('box_questions', function(Blueprint $table)
      {
        $table->dropForeign('box_questions_box_id_foreign');
      });
      Schema::table('customer_profiles', function(Blueprint $table)
      {
        $table->dropForeign('user_profiles_box_id_foreign');
      });
      Schema::table('orders', function(Blueprint $table)
      {
        $table->dropForeign('orders_box_id_foreign');
      });

      Schema::dropIfExists('boxes');

      Schema::table('box_questions', function ($table) {
        $table->dropColumn('box_id');
        $table->dropColumn('filter_must_match');
      });

      Schema::table('box_answers', function ($table) {

        //$table->integer('customer_profile_id')->unsigned()->nullable();
        //$table->index('customer_profile_id');

      });

      /**
       * We drop the other Box links
       */
      Schema::table('customer_profiles', function ($table) {
        $table->dropColumn('box_id');
      });
      Schema::table('product_filter_boxes', function ($table) {
        $table->dropColumn('box_id');
      });
      Schema::table('orders', function ($table) {
        $table->dropColumn('box_id');
      });

      Schema::rename('customer_answers', 'box_question_customer_answers');

      /**
       * New system datas conversion
       */
      $box_questions = BoxQuestion::get();
      foreach ($box_questions as $box_question) {

        $all_same_questions = BoxQuestion::where('question', '=', $box_question->question)->get();
        $new_box_question = $box_question;

        foreach ($all_same_questions as $all_same_question) {
          $customer_answers = $all_same_question->customer_answers()->get();
          foreach ($customer_answers as $customer_answer) {
            $customer_answer->box_question_id = $new_box_question->id;
            $customer_answer->save();
          }
        }

        $box_answers = BoxAnswer::get();
        foreach ($box_answers as $box_answer) {

          $question = $box_answer->question()->first();
          if ($question === NULL) {
            $box_answer->delete();
            continue;
          }

        }
        
      }

      $box_questions = BoxQuestion::get();
      foreach ($box_questions as $box_question) {
        if ($box_question->customer_answers()->count() <= 0) {
          $box_question->forceDelete();
        }
      }

        /**
         * End of data conversion
         */
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

      Schema::table('box_questions', function ($table) {
        $table->addColumn('box_id');
        $table->addColumn('filter_must_match');
      });

      Schema::table('box_answers', function ($table) {
        $table->addColumn('box_id');
        $table->addColumn('filter_must_match');

        //$table->dropColumn('customer_profile_id');

      });

      /**
       * We add the other Box links
       */
      Schema::table('customer_profiles', function ($table) {
        $table->addColumn('box_id');
      });
      Schema::table('product_filter_boxes', function ($table) {
        $table->addColumn('box_id');
      });
      Schema::table('orders', function ($table) {
        $table->addColumn('box_id');
      });

    }
}
