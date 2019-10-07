<?php

namespace Longman\TelegramBot\Models;

/**
 * Class BaseEditedMessage
 * @property array $fillable
 * @property array $hidden
 * @property array $casts
 * @property array $dates
 * @property array $rules
 *
 * @property integer id
 * @property integer chat_id
 * @property integer message_id
 * @property integer user_id
 * @property datetime edit_date
 * @property string text
 * @property string entities
 * @property string caption
 * @property Update[] telegram_update
 */
class EditedMessage extends BaseTelegramModel
{



    protected $attributes = [
		'id' => null,
		'chat_id' => null,
		'message_id' => null,
		'user_id' => null,
		'edit_date' => null,
		'text' => null,
		'entities' => null,
		'caption' => null
	];

    /**
     * @return array
     */
    public function getRules()
    {
        return [
			'id' => "nullable|numeric|integer",
			'chat_id' => "nullable|numeric|integer",
			'message_id' => "nullable|numeric|integer",
			'user_id' => "nullable|numeric|integer",
			'edit_date' => "nullable|date",
			'text' => "nullable|string",
			'entities' => "nullable|string",
			'caption' => "nullable|string"
		];
    }

    public function consume_presave()
    {
        $chat = new Chat();
        $chat->consume(array_merge($this->chat, ['edit_date' => $this->edit_date]));
        /** TODO - Associate Chat with usr? */

        /** TODO entitiesarraytoJoin.. reverse engineer and make it work right */
        // $this->entities = self::entitiesArrayToJson($this->entities);
    }

	public function telegram_update() {
		return $this->hasMany( Update::class, 'edited_message_id' );
	}


    /**
     * All model fields.
     *
     * @var array
     */
    protected static $fields = ['id', 'chat_id', 'message_id', 'user_id', 'edit_date', 'text', 'entities', 'caption'];

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['chat_id', 'message_id', 'user_id', 'edit_date', 'text', 'entities', 'caption'];

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
    protected $casts = ['chat_id' => 'integer', 'message_id' => 'integer', 'user_id' => 'integer', 'edit_date' => 'datetime'];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['edit_date', 'edit_date'];


}
