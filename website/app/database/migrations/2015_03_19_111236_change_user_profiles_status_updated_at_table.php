	<?php

	use Illuminate\Database\Schema\Blueprint;
	use Illuminate\Database\Migrations\Migration;

	class ChangeUserProfilesStatusUpdatedAtTable extends Migration {

		/**
		 * Run the migrations.
		 *
		 * @return void
		 */
		public function up()
		{

			Schema::table('user_profiles', function($table)
			{

				// Keys
				$table->datetime('status_updated_at')->nullable();

			});

		}

		/**
		 * Reverse the migrations.
		 *
		 * @return void
		 */
		public function down()
		{

			Schema::table('user_profiles', function($table)
			{

				// Columns to remove
				$table->dropColumn('status_updated_at');

			});

		}

	}
