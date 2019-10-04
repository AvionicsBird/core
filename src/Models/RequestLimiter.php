<?php

namespace Longman\TelegramBot\Models;

/**
 * Class BaseRequestLimiter
 * @property array $fillable
 * @property array $hidden
 * @property array $casts
 * @property array $dates
 * @property array $rules
 *
 * @property integer id
 * @property string chat_id
 * @property string inline_message_id
 * @property string method
 * @property datetime created_at
 */
class RequestLimiter extends BaseTelegramModel
{



    protected $attributes = [
		'id' => null,
		'chat_id' => null,
		'inline_message_id' => null,
		'method' => null,
		'created_at' => null
	];

    /**
     * @return array
     */
    public function getRules()
    {
        return [
			'id' => "nullable|numeric|integer",
			'chat_id' => "nullable|string|max:255",
			'inline_message_id' => "nullable|string|max:255",
			'method' => "nullable|string|max:255",
			'created_at' => "nullable|date"
		];
    }

    /**
     * All model fields.
     *
     * @var array
     */
    protected static $fields = ['id', 'chat_id', 'inline_message_id', 'method', 'created_at'];

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['chat_id', 'inline_message_id', 'method'];

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
    protected $casts = ['created_at' => 'datetime'];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['created_at', 'created_at'];


}
