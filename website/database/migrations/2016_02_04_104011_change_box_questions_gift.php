<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeBoxQuestionsGift extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

    Schema::table('box_questions', function($table)
    {

      $table->boolean('only_gift')->index()->default(FALSE);
      $table->string('question_gift');

    });

    $box_questions = App\Models\BoxQuestion::get();
    foreach ($box_questions as $box_question) {
      $box_question->question_gift = $box_question->question;
      $box_question->save();
    }

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

    Schema::table('box_questions', function($table) {

      $table->dropColumn('only_gift');
      $table->dropColumn('question_gift');
  
    });

    }
}
