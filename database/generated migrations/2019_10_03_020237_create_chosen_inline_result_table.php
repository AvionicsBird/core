<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateChosenInlineResultTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('chosen_inline_result', function(Blueprint $table)
		{
			$table->bigInteger('id', true)->unsigned()->comment('Unique identifier for this entry');
			$table->char('result_id')->default('')->comment('The unique identifier for the result that was chosen');
			$table->bigInteger('user_id')->nullable()->index('user_id')->comment('The user that chose the result');
			$table->char('location')->nullable()->comment('Sender location, only for bots that require user location');
			$table->char('inline_message_id')->nullable()->comment('Identifier of the sent inline message');
			$table->text('query', 65535)->comment('The query that was used to obtain the result');
			$table->dateTime('created_at')->nullable()->comment('Entry date creation');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('chosen_inline_result');
	}

}
