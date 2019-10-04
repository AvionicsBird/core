<?php

namespace Longman\TelegramBot\Models;

use Longman\TelegramBot\Models\BaseTelegramModel;

/**
 * Class BaseUser
 * @property array $fillable
 * @property array $hidden
 * @property array $casts
 * @property array $dates
 * @property array $rules
 *
 * @property integer id
 * @property string name
 * @property string email
 * @property string email_verified_at
 * @property string password
 * @property string remember_token
 * @property string created_at
 * @property string updated_at
 * @property CallbackQuery[] callback_query
 * @property ChosenInlineResult[] chosen_inline_result
 * @property Conversation[] conversation
 * @property EditedMessage[] edited_message
 * @property InlineQuery[] inline_query
 * @property Message[] message
 * @property PreCheckoutQuery[] pre_checkout_query
 * @property ShippingQuery[] shipping_query
 * @property UserChat[] user_chat
 */
class User extends BaseTelegramModel
{

	public function callback_query() {
		return $this->hasMany( CallbackQuery::class, 'user_id' );
	}

	public function chosen_inline_result() {
		return $this->hasMany( ChosenInlineResult::class, 'user_id' );
	}

	public function conversation() {
		return $this->hasMany( Conversation::class, 'user_id' );
	}

	public function edited_message() {
		return $this->hasMany( EditedMessage::class, 'user_id' );
	}

	public function inline_query() {
		return $this->hasMany( InlineQuery::class, 'user_id' );
	}

	public function message() {
		return $this->hasMany( Message::class, 'user_id' );
	}

	public function pre_checkout_query() {
		return $this->hasMany( PreCheckoutQuery::class, 'user_id' );
	}

	public function shipping_query() {
		return $this->hasMany( ShippingQuery::class, 'user_id' );
	}

	public function user_chat() {
		return $this->hasMany( UserChat::class, 'user_id' );
	}

   /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['id', 'first_name', 'last_name', 'username', 'is_bot', 'language_code', 'app_user_id'];
}
