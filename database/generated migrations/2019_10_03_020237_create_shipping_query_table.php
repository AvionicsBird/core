<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateShippingQueryTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('shipping_query', function(Blueprint $table)
		{
			$table->bigInteger('id')->unsigned()->primary()->comment('Unique query identifier');
			$table->bigInteger('user_id')->nullable()->index('user_id')->comment('User who sent the query');
			$table->char('invoice_payload')->default('')->comment('Bot specified invoice payload');
			$table->char('shipping_address')->default('')->comment('User specified shipping address');
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
		Schema::drop('shipping_query');
	}

}
