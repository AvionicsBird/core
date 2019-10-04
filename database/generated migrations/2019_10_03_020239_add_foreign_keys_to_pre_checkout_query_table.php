<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToPreCheckoutQueryTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('pre_checkout_query', function(Blueprint $table)
		{
			$table->foreign('user_id', 'pre_checkout_query_ibfk_1')->references('id')->on('user')->onUpdate('RESTRICT')->onDelete('RESTRICT');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('pre_checkout_query', function(Blueprint $table)
		{
			$table->dropForeign('pre_checkout_query_ibfk_1');
		});
	}

}
