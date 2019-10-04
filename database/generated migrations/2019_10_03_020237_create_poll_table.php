<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePollTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('poll', function(Blueprint $table)
		{
			$table->bigInteger('id')->unsigned()->primary()->comment('Unique poll identifier');
			$table->char('question')->comment('Poll question');
			$table->text('options', 65535)->comment('List of poll options');
			$table->boolean('is_closed')->nullable()->default(0)->comment('True, if the poll is closed');
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
		Schema::drop('poll');
	}

}
