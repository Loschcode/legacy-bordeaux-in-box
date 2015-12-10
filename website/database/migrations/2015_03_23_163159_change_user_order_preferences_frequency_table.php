<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeUserOrderPreferencesFrequencyTable extends Migration {

	function up()
	{
	    DB::statement('ALTER TABLE `user_order_preferences` MODIFY `frequency` INTEGER UNSIGNED NULL;');
	    DB::statement('UPDATE `user_order_preferences` SET `frequency` = NULL WHERE `frequency` = 0;');
	}

	function down()
	{
	    DB::statement('UPDATE `user_order_preferences` SET `frequency` = 0 WHERE `frequency` IS NULL;');
	    DB::statement('ALTER TABLE `user_order_preferences` MODIFY `frequency` INTEGER UNSIGNED NOT NULL;');
	}

}
