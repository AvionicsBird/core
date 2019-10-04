<?php

namespace Longman\TelegramBot\Models;

/**
 * Class BaseChat
 * @property array $fillable
 * @property array $hidden
 * @property array $casts
 * @property array $dates
 * @property array $rules
 *
 * @property integer id
 * @property string type
 * @property string title
 * @property string username
 * @property string first_name
 * @property string last_name
 * @property boolean all_members_are_administrators
 * @property string created_at
 * @property string updated_at
 * @property integer old_id
 * @property CallbackQuery[] callback_query
 * @property Conversation[] conversation
 * @property EditedMessage[] edited_message
 * @property Message[] message
 * @property RequestLimiter[] request_limiter
 * @property Update[] telegram_update
 * @property UserChat[] user_chat
 */
class Chat extends BaseTelegramModel
{

    protected $attributes = [
		'id' => null,
		'type' => null,
		'title' => '',
		'username' => null,
		'first_name' => null,
		'last_name' => null,
		'all_members_are_administrators' => 0,
		'created_at' => null,
		'updated_at' => null,
		'old_id' => null
	];

    /**
     * @return array
     */
    public function getRules()
    {
        return [
			'id' => "nullable|numeric|integer",
			'type' => "required|string",
			'title' => "nullable|string|max:255",
			'username' => "nullable|string|max:255",
			'first_name' => "nullable|string|max:255",
			'last_name' => "nullable|string|max:255",
			'all_members_are_administrators' => "nullable|boolean",
			'created_at' => "nullable|date",
			'updated_at' => "nullable|date",
			'old_id' => "nullable|numeric|integer"
		];
    }


	public function callback_query() {
		return $this->hasMany( CallbackQuery::class, 'chat_id' );
	}

	public function conversation() {
		return $this->hasMany( Conversation::class, 'chat_id' );
	}

	public function edited_message() {
		return $this->hasMany( EditedMessage::class, 'chat_id' );
	}

	public function message() {
		return $this->hasMany( Message::class, 'chat_id' );
	}

	public function request_limiter() {
		return $this->hasMany( RequestLimiter::class, 'chat_id' );
	}

	public function telegram_update() {
		return $this->hasMany( Update::class, 'chat_id' );
	}

	public function user_chat() {
		return $this->hasMany( UserChat::class, 'chat_id' );
	}


    /**
     * All model fields.
     *
     * @var array
     */
    protected static $fields = ['id', 'type', 'title', 'username', 'first_name', 'last_name', 'all_members_are_administrators', 'created_at', 'updated_at', 'old_id'];

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['type', 'title', 'username', 'first_name', 'last_name', 'all_members_are_administrators', 'old_id'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = ['all_members_are_administrators' => 'boolean', 'old_id' => 'integer'];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['created_at', 'updated_at', 'created_at', 'updated_at'];


}
