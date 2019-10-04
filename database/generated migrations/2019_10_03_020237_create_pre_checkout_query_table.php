<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePreCheckoutQueryTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('pre_checkout_query', function(Blueprint $table)
		{
			$table->bigInteger('id')->unsigned()->primary()->comment('Unique query identifier');
			$table->bigInteger('user_id')->nullable()->index('user_id')->comment('User who sent the query');
			$table->char('currency', 3)->nullable()->comment('Three-letter ISO 4217 currency code');
			$table->bigInteger('total_amount')->nullable()->comment('Total price in the smallest units of the currency');
			$table->char('invoice_payload')->default('')->comment('Bot specified invoice payload');
			$table->char('shipping_option_id')->nullable()->comment('Identifier of the shipping option chosen by the user');
			$table->text('order_info', 65535)->nullable()->comment('Order info provided by the user');
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
		Schema::drop('pre_checkout_query');
	}

}
