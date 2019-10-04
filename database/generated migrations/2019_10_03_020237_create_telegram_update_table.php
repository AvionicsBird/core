<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTelegramUpdateTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('telegram_update', function(Blueprint $table)
		{
			$table->bigInteger('id')->unsigned()->primary()->comment('Update\'s unique identifier');
			$table->bigInteger('chat_id')->nullable()->comment('Unique chat identifier');
			$table->bigInteger('message_id')->unsigned()->nullable()->comment('New incoming message of any kind - text, photo, sticker, etc.');
			$table->bigInteger('edited_message_id')->unsigned()->nullable()->index('edited_message_id')->comment('New version of a message that is known to the bot and was edited');
			$table->bigInteger('channel_post_id')->unsigned()->nullable()->index('channel_post_id')->comment('New incoming channel post of any kind - text, photo, sticker, etc.');
			$table->bigInteger('edited_channel_post_id')->unsigned()->nullable()->index('edited_channel_post_id')->comment('New version of a channel post that is known to the bot and was edited');
			$table->bigInteger('inline_query_id')->unsigned()->nullable()->index('inline_query_id')->comment('New incoming inline query');
			$table->bigInteger('chosen_inline_result_id')->unsigned()->nullable()->index('chosen_inline_result_id')->comment('The result of an inline query that was chosen by a user and sent to their chat partner');
			$table->bigInteger('callback_query_id')->unsigned()->nullable()->index('callback_query_id')->comment('New incoming callback query');
			$table->bigInteger('shipping_query_id')->unsigned()->nullable()->index('shipping_query_id')->comment('New incoming shipping query. Only for invoices with flexible price');
			$table->bigInteger('pre_checkout_query_id')->unsigned()->nullable()->index('pre_checkout_query_id')->comment('New incoming pre-checkout query. Contains full information about checkout');
			$table->bigInteger('poll_id')->unsigned()->nullable()->index('poll_id')->comment('New poll state. Bots receive only updates about polls, which are sent or stopped by the bot');
			$table->index(['chat_id','channel_post_id'], 'chat_id');
			$table->index(['chat_id','message_id'], 'message_id');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('telegram_update');
	}

}
