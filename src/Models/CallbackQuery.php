<?php

namespace Longman\TelegramBot\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class BaseCallbackQuery
 * @property array $fillable
 * @property array $hidden
 * @property array $casts
 * @property array $dates
 * @property array $rules
 *
 * @property integer id
 * @property integer user_id
 * @property integer chat_id
 * @property integer message_id
 * @property string inline_message_id
 * @property string chat_instance
 * @property string data
 * @property string game_short_name
 * @property datetime created_at
 * @property Update[] telegram_update
 */
class CallbackQuery extends BaseTelegramModel
{



    protected $attributes = [
		'id' => null,
		'user_id' => null,
		'chat_id' => null,
		'message_id' => null,
		'inline_message_id' => null,
		'chat_instance' => '',
		'data' => '',
		'game_short_name' => '',
		'created_at' => null
	];

    /**
     * @return array
     */
    public function getRules()
    {
        return [
			'id' => "nullable|numeric|integer",
			'user_id' => "nullable|numeric|integer",
			'chat_id' => "nullable|numeric|integer",
			'message_id' => "nullable|numeric|integer",
			'inline_message_id' => "nullable|string|max:255",
			'chat_instance' => "required|string|max:255",
			'data' => "required|string|max:255",
			'game_short_name' => "required|string|max:255",
			'created_at' => "nullable|date"
		];
    }

    protected function consume_presave()
    {
        if ($this->message instanceof \Longman\TelegramBot\Entities\Message) {
            $chat_id = $this->message->getChat()->getId();
            $message_id = $this->message->getMessageId();

            $existing_message = Message::where('id', $message_id)->where('chat_id', $chat_id)->count();

            $message = $edited_message ? new EditedMessage() : new Message();
            $message->consume();
        }
        return true;
    }


	public function TelegramUpdate() {
		return $this->hasMany( Update::class, 'callback_query_id' );
	}


    /**
     * All model fields.
     *
     * @var array
     */
    protected static $fields = ['id', 'user_id', 'chat_id', 'message_id', 'inline_message_id', 'chat_instance', 'data', 'game_short_name', 'created_at'];

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['user_id', 'chat_id', 'message_id', 'inline_message_id', 'chat_instance', 'data', 'game_short_name'];

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
    protected $casts = ['user_id' => 'integer', 'chat_id' => 'integer', 'message_id' => 'integer', 'created_at' => 'datetime'];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['created_at', 'created_at'];
}
