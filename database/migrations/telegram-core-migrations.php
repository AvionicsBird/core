<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTelegramTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(config('telegram.database.users'), function (Blueprint $table) {
            $table->bigInteger('id')->primary()->comment('Unique identifier for this user or bot');
            $table->bigIncrements('app_user_id')->comment('Application User_id');
            $table->boolean('is_bot')->nullable()->default(0)->comment('True, if this user is a bot');
            $table->char('first_name')->default('')->comment('User\'s or bot\'s first name');
            $table->char('last_name')->nullable()->comment('User\'s or bot\'s last name');
            $table->char('username', 191)->nullable()->index('username')->comment('User\'s or bot\'s username');
            $table->char('language_code', 10)->nullable()->comment('IETF language tag of the user\'s language');
            $table->timestamps();
        });
        Schema::create(config('telegram.database.chats'), function(Blueprint $table) {
            $table->bigInteger('id')->primary()->comment('Unique identifier for this chat');
            $table->enum('type', array('private','group','supergroup','channel'))->comment('Type of chat, can be either private, group, supergroup or channel');
            $table->char('title')->nullable()->default('')->comment('Title, for supergroups, channels and group chats');
            $table->char('username')->nullable()->comment('Username, for private chats, supergroups and channels if available');
            $table->char('first_name')->nullable()->comment('First name of the other party in a private chat');
            $table->char('last_name')->nullable()->comment('Last name of the other party in a private chat');
            $table->boolean('all_members_are_administrators')->nullable()->default(0)->comment('True if a all members of this group are admins');
            $table->bigInteger('old_id')->nullable()->index('old_id')->comment('Unique chat identifier, this is filled when a group is converted to a supergroup');
            $table->timestamps();
        });
        Schema::create(config('telegram.database.conversation'), function(Blueprint $table)
        {
            $table->bigInteger('id', true)->unsigned()->comment('Unique identifier for this entry');
            $table->bigInteger('user_id')->nullable()->index('user_id')->comment('Unique user identifier');
            $table->bigInteger('chat_id')->nullable()->index('chat_id')->comment('Unique user or chat identifier');
            $table->enum('status', array('active','cancelled','stopped'))->default('active')->index('status')->comment('Conversation state');
            $table->string('command', 160)->nullable()->default('')->comment('Default command to execute');
            $table->text('notes')->nullable()->comment('Data stored from command');
            $table->timestamps();
        });
        Schema::create(config('telegram.database.user_chat'), function(Blueprint $table) {
            $table->bigInteger('user_id')->comment('Unique user identifier');
            $table->bigInteger('chat_id')->index('chat_id')->comment('Unique user or chat identifier');
            $table->primary(['user_id','chat_id']);
        });
        Schema::create(config('telegram.database.inline_query'), function(Blueprint $table) {
            $table->bigInteger('id')->unsigned()->primary()->comment('Unique identifier for this query');
            $table->bigInteger('user_id')->nullable()->index('user_id')->comment('Unique user identifier');
            $table->char('location')->nullable()->comment('Location of the user');
            $table->text('query')->comment('Text of the query');
            $table->char('offset')->nullable()->comment('Offset of the result');
            $table->timestamps();
        });
        Schema::create(config('telegram.database.chosen_inline_result'), function(Blueprint $table) {
            $table->bigInteger('id', true)->unsigned()->comment('Unique identifier for this entry');
            $table->char('result_id')->default('')->comment('The unique identifier for the result that was chosen');
            $table->bigInteger('user_id')->nullable()->index('user_id')->comment('The user that chose the result');
            $table->char('location')->nullable()->comment('Sender location, only for bots that require user location');
            $table->char('inline_message_id')->nullable()->comment('Identifier of the sent inline message');
            $table->text('query')->comment('The query that was used to obtain the result');
            $table->timestamps();
        });
        Schema::create(config('telegram.database.message'), function(Blueprint $table) {
            $table->bigInteger('chat_id')->comment('Unique chat identifier');
            $table->bigInteger('id')->unsigned()->comment('Unique message identifier');
            $table->bigInteger('user_id')->nullable()->index('user_id')->comment('Unique user identifier');
            $table->dateTime('date')->nullable()->comment('Date the message was sent in timestamp format');
            $table->bigInteger('forward_from')->nullable()->index('forward_from')->comment('Unique user identifier, sender of the original message');
            $table->bigInteger('forward_from_chat')->nullable()->index('forward_from_chat')->comment('Unique chat identifier, chat the original message belongs to');
            $table->bigInteger('forward_from_message_id')->nullable()->comment('Unique chat identifier of the original message in the channel');
            $table->text('forward_signature')->nullable()->comment('For messages forwarded from channels, signature of the post author if present');
            $table->text('forward_sender_name')->nullable()->comment('Sender\'s name for messages forwarded from users who disallow adding a link to their account in forwarded messages');
            $table->dateTime('forward_date')->nullable()->comment('date the original message was sent in timestamp format');
            $table->bigInteger('reply_to_chat')->nullable()->index('reply_to_chat')->comment('Unique chat identifier');
            $table->bigInteger('reply_to_message')->unsigned()->nullable()->index('reply_to_message')->comment('Message that this message is reply to');
            $table->bigInteger('edit_date')->unsigned()->nullable()->comment('Date the message was last edited in Unix time');
            $table->text('media_group_id')->nullable()->comment('The unique identifier of a media message group this message belongs to');
            $table->text('author_signature')->nullable()->comment('Signature of the post author for messages in channels');
            $table->text('text')->nullable()->comment('For text messages, the actual UTF-8 text of the message max message length 4096 char utf8mb4');
            $table->text('entities')->nullable()->comment('For text messages, special entities like usernames, URLs, bot commands, etc. that appear in the text');
            $table->text('caption_entities')->nullable()->comment('For messages with a caption, special entities like usernames, URLs, bot commands, etc. that appear in the caption');
            $table->text('audio')->nullable()->comment('Audio object. Message is an audio file, information about the file');
            $table->text('document')->nullable()->comment('Document object. Message is a general file, information about the file');
            $table->text('animation')->nullable()->comment('Message is an animation, information about the animation');
            $table->text('game')->nullable()->comment('Game object. Message is a game, information about the game');
            $table->text('photo')->nullable()->comment('Array of PhotoSize objects. Message is a photo, available sizes of the photo');
            $table->text('sticker')->nullable()->comment('Sticker object. Message is a sticker, information about the sticker');
            $table->text('video')->nullable()->comment('Video object. Message is a video, information about the video');
            $table->text('voice')->nullable()->comment('Voice Object. Message is a Voice, information about the Voice');
            $table->text('video_note')->nullable()->comment('VoiceNote Object. Message is a Video Note, information about the Video Note');
            $table->text('caption')->nullable()->comment('For message with caption, the actual UTF-8 text of the caption');
            $table->text('contact')->nullable()->comment('Contact object. Message is a shared contact, information about the contact');
            $table->text('location')->nullable()->comment('Location object. Message is a shared location, information about the location');
            $table->text('venue')->nullable()->comment('Venue object. Message is a Venue, information about the Venue');
            $table->text('poll')->nullable()->comment('Poll object. Message is a native poll, information about the poll');
            $table->text('new_chat_members')->nullable()->comment('List of unique user identifiers, new member(s) were added to the group, information about them (one of these members may be the bot itself)');
            $table->bigInteger('left_chat_member')->nullable()
                ->index('left_chat_member')->comment('Unique user identifier, a member was removed from the group, information about them (this member may be the bot itself)');
            $table->char('new_chat_title')->nullable()->comment('A chat title was changed to this value');
            $table->text('new_chat_photo')->nullable()->comment('Array of PhotoSize objects. A chat photo was change to this value');
            $table->boolean('delete_chat_photo')->nullable()->default(0)->comment('Informs that the chat photo was deleted');
            $table->boolean('group_chat_created')->nullable()->default(0)->comment('Informs that the group has been created');
            $table->boolean('supergroup_chat_created')->nullable()->default(0)->comment('Informs that the supergroup has been created');
            $table->boolean('channel_chat_created')->nullable()->default(0)->comment('Informs that the channel chat has been created');
            $table->bigInteger('migrate_to_chat_id')->nullable()->index('migrate_to_chat_id')->comment('Migrate to chat identifier. The group has been migrated to a supergroup with the specified identifier');
            $table->bigInteger('migrate_from_chat_id')->nullable()->index('migrate_from_chat_id')->comment('Migrate from chat identifier. The supergroup has been migrated from a group with the specified identifier');
            $table->text('pinned_message')->nullable()->comment('Message object. Specified message was pinned');
            $table->text('invoice')->nullable()->comment('Message is an invoice for a payment, information about the invoice');
            $table->text('successful_payment')->nullable()->comment('Message is a service message about a successful payment, information about the payment');
            $table->text('connected_website')->nullable()->comment('The domain name of the website on which the user has logged in.');
            $table->text('passport_data')->nullable()->comment('Telegram Passport data');
            $table->text('reply_markup')->nullable()->comment('Inline keyboard attached to the message');
            $table->primary(['chat_id','id']);
            $table->index(['reply_to_chat','reply_to_message'], 'reply_to_chat_2');
            $table->timestamps();
        });
        Schema::create(config('telegram.database.edited_message'), function(Blueprint $table) {
            $table->bigInteger('id', true)->unsigned()->comment('Unique identifier for this entry');
            $table->bigInteger('chat_id')->nullable()->index('chat_id')->comment('Unique chat identifier');
            $table->bigInteger('message_id')->unsigned()->nullable()->index('message_id')->comment('Unique message identifier');
            $table->bigInteger('user_id')->nullable()->index('user_id')->comment('Unique user identifier');
            $table->dateTime('edit_date')->nullable()->comment('Date the message was edited in timestamp format');
            $table->text('text')->nullable()->comment('For text messages, the actual UTF-8 text of the message max message length 4096 char utf8');
            $table->text('entities')->nullable()->comment('For text messages, special entities like usernames, URLs, bot commands, etc. that appear in the text');
            $table->text('caption')->nullable()->comment('For message with caption, the actual UTF-8 text of the caption');
            $table->index(['chat_id','message_id'], 'chat_id_2');
            $table->timestamps();
        });
        Schema::create(config('telegram.database.callback_query'), function(Blueprint $table) {
            $table->bigInteger('id')->unsigned()->primary()->comment('Unique identifier for this query');
            $table->bigInteger('user_id')->nullable()->index('user_id')->comment('Unique user identifier');
            $table->bigInteger('chat_id')->nullable()->index('chat_id')->comment('Unique chat identifier');
            $table->bigInteger('message_id')->unsigned()->nullable()->index('message_id')->comment('Unique message identifier');
            $table->char('inline_message_id')->nullable()->comment('Identifier of the message sent via the bot in inline mode, that originated the query');
            $table->char('chat_instance')->default('')->comment('Global identifier, uniquely corresponding to the chat to which the message with the callback button was sent');
            $table->char('data')->default('')->comment('Data associated with the callback button');
            $table->char('game_short_name')->default('')->comment('Short name of a Game to be returned, serves as the unique identifier for the game');
            $table->timestamps();
            $table->index(['chat_id','message_id'], 'chat_id_2');
        });
        Schema::create(config('telegram.database.shipping_query'), function(Blueprint $table) {
            $table->bigInteger('id')->unsigned()->primary()->comment('Unique query identifier');
            $table->bigInteger('user_id')->nullable()->index('user_id')->comment('User who sent the query');
            $table->char('invoice_payload')->default('')->comment('Bot specified invoice payload');
            $table->char('shipping_address')->default('')->comment('User specified shipping address');
            $table->timestamps();
        });
        Schema::create(config('telegram.database.pre_checkout_query'), function(Blueprint $table) {
            $table->bigInteger('id')->unsigned()->primary()->comment('Unique query identifier');
            $table->bigInteger('user_id')->nullable()->index('user_id')->comment('User who sent the query');
            $table->char('currency', 3)->nullable()->comment('Three-letter ISO 4217 currency code');
            $table->bigInteger('total_amount')->nullable()->comment('Total price in the smallest units of the currency');
            $table->char('invoice_payload')->default('')->comment('Bot specified invoice payload');
            $table->char('shipping_option_id')->nullable()->comment('Identifier of the shipping option chosen by the user');
            $table->text('order_info')->nullable()->comment('Order info provided by the user');
            $table->timestamps();
        });
        Schema::create(config('telegram.database.poll'), function(Blueprint $table) {
            $table->bigInteger('id')->unsigned()->primary()->comment('Unique poll identifier');
            $table->char('question')->comment('Poll question');
            $table->text('options')->comment('List of poll options');
            $table->boolean('is_closed')->nullable()->default(0)->comment('True, if the poll is closed');
            $table->timestamps();
        });
        Schema::create(config('telegram.database.update'), function(Blueprint $table) {
            $table->bigInteger('id')->unsigned()->primary()->comment('Update\'s unique identifier');
            /*
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
            */
            $table->bigInteger('update_id')->comment('ID on Associated Update Type');
            $table->string('update_type')->comment('Update Table Type');
            $table->timestamps();
        });

        // Now set foreign keys
        Schema::table(config('telegram.database.callback_query'), function(Blueprint $table)
        {
            $table->foreign('user_id', 'callback_query_ibfk_1')
                ->references('id')
                ->on(config('telegram.database.users'))
                ->onUpdate('RESTRICT')->onDelete('RESTRICT');
            $table->foreign('chat_id', 'callback_query_ibfk_2')
                ->references('chat_id')
                ->on(config('telegram.message'))->onUpdate('RESTRICT')->onDelete('RESTRICT');
        });
        Schema::table(config('telegram.database.chosen_inline_result'), function(Blueprint $table)
        {
            $table->foreign('user_id', 'chosen_inline_result_ibfk_1')
                ->references('id')->on(config('telegram.database.users'))->onUpdate('RESTRICT')->onDelete('RESTRICT');
        });
        Schema::table(config('telegram.database.conversation'), function(Blueprint $table)
        {
            $table->foreign('user_id', 'conversation_ibfk_1')
                ->references('id')->on(config('telegram.database.users'))->onUpdate('RESTRICT')
                ->onDelete('RESTRICT');
            $table->foreign('chat_id', 'conversation_ibfk_2')->references('id')->on(config('telegram.database.chats'))
                ->onUpdate('RESTRICT')->onDelete('RESTRICT');
        });
        Schema::table(config('telegram.database.edited_message'), function(Blueprint $table)
        {
            $table->foreign('chat_id', 'edited_message_ibfk_1')->references('id')->on(config('telegram.database.chats'))
                ->onUpdate('RESTRICT')->onDelete('RESTRICT');
            $table->foreign('chat_id', 'edited_message_ibfk_2')->references('chat_id')->on(config('telegram.database.message'))
            ->onUpdate('RESTRICT')->onDelete('RESTRICT');
            $table->foreign('user_id', 'edited_message_ibfk_3')->references('id')->on(config('telegram.database.users'))
                ->onUpdate('RESTRICT')->onDelete('RESTRICT');
        });
        Schema::table(config('telegram.database.inline_query'), function(Blueprint $table)
        {
            $table->foreign('user_id', 'inline_query_ibfk_1')->references('id')
                ->on(config('telegram.database.usersuser'))->onUpdate('RESTRICT')->onDelete('RESTRICT');
        });
        Schema::table(config('telegram.database.message'), function(Blueprint $table)
        {
            $table->foreign('user_id', 'message_ibfk_1')->references('id')->on(config('telegram.database.users'))
                ->onUpdate('RESTRICT')->onDelete('RESTRICT');
            $table->foreign('chat_id', 'message_ibfk_2')->references('id')->on(config('telegram.database.chats'))
                ->onUpdate('RESTRICT')->onDelete('RESTRICT');
            $table->foreign('forward_from', 'message_ibfk_3')->references('id')->on(config('telegram.database.users'))
                ->onUpdate('RESTRICT')->onDelete('RESTRICT');
            $table->foreign('forward_from_chat', 'message_ibfk_4')->references('id')->on(config('telegram.database.chats'))
                ->onUpdate('RESTRICT')->onDelete('RESTRICT');
            $table->foreign('reply_to_chat', 'message_ibfk_5')->references('chat_id')->on(config('telegram.database.message'))
                ->onUpdate('RESTRICT')->onDelete('RESTRICT');
            $table->foreign('forward_from', 'message_ibfk_6')->references('id')->on(config('telegram.database.users'))->onUpdate('RESTRICT')
                ->onDelete('RESTRICT');
            $table->foreign('left_chat_member', 'message_ibfk_7')->references('id')->on(config('telegram.database.users'))->onUpdate('RESTRICT')
                ->onDelete('RESTRICT');
        });
        Schema::table(config('telegram.database.pre_checkout_query'), function(Blueprint $table)
        {
            $table->foreign('user_id', 'pre_checkout_query_ibfk_1')->references('id')
                ->on(config('telegram.database.users'))->onUpdate('RESTRICT')->onDelete('RESTRICT');
        });
        Schema::table(config('telegram.database.shipping_query'), function(Blueprint $table)
        {
            $table->foreign('user_id', 'shipping_query_ibfk_1')->references('id')
            ->on(config('telegram.database.users'))->onUpdate('RESTRICT')->onDelete('RESTRICT');
        });
        Schema::table(config('telegram.database.update'), function(Blueprint $table)
        {
            $table->foreign('chat_id', 'telegram_update_ibfk_1')->references('chat_id')->on(config('telegram.database.message'))
                ->onUpdate('RESTRICT')->onDelete('RESTRICT');
            $table->foreign('poll_id', 'telegram_update_ibfk_10')->references('id')->on(config('telegram.database.poll'))
                ->onUpdate('RESTRICT')->onDelete('RESTRICT');
            $table->foreign('edited_message_id', 'telegram_update_ibfk_2')->references('id')->on(config('telegram.database.edited_message'))
                ->onUpdate('RESTRICT')->onDelete('RESTRICT');
            $table->foreign('chat_id', 'telegram_update_ibfk_3')->references('chat_id')->on(config('telegram.database.message'))
                ->onUpdate('RESTRICT')->onDelete('RESTRICT');
            $table->foreign('edited_channel_post_id', 'telegram_update_ibfk_4')->references('id')->on(config('telegram.database.edited_message'))
                ->onUpdate('RESTRICT')->onDelete('RESTRICT');
            $table->foreign('inline_query_id', 'telegram_update_ibfk_5')->references('id')->on(config('telegram.database.inline_query'))
                ->onUpdate('RESTRICT')->onDelete('RESTRICT');
            $table->foreign('chosen_inline_result_id', 'telegram_update_ibfk_6')->references('id')->on(config('telegram.database.chosen_inline_result'))
                ->onUpdate('RESTRICT')->onDelete('RESTRICT');
            $table->foreign('callback_query_id', 'telegram_update_ibfk_7')->references('id')->on(config('telegram.database.callback_query'))
                ->onUpdate('RESTRICT')->onDelete('RESTRICT');
            $table->foreign('shipping_query_id', 'telegram_update_ibfk_8')->references('id')->on(config('telegram.database.shipping_query'))
                ->onUpdate('RESTRICT')->onDelete('RESTRICT');
            $table->foreign('pre_checkout_query_id', 'telegram_update_ibfk_9')->references('id')->on(config('telegram.database.pre_checkout_query'))
                ->onUpdate('RESTRICT')->onDelete('RESTRICT');
        });
        Schema::table(config('telegram.database.user_chat'), function(Blueprint $table)
        {
            $table->foreign('user_id', 'user_chat_ibfk_1')->references('id')->on(config('telegram.database.users'))
                ->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign('chat_id', 'user_chat_ibfk_2')->references('id')->on(config('telegram.database.chats'))
                ->onUpdate('CASCADE')->onDelete('CASCADE');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $tables = config('telegram.database');

        foreach($tables as $key => $table) {
            Schema::drop($table);
        }
    }
}
