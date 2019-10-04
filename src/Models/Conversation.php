<?php
namespace Longman\TelegramBot\Models;

/**
 * Class BaseConversation
 * @property array $fillable
 * @property array $hidden
 * @property array $casts
 * @property array $dates
 * @property array $rules
 *
 * @property integer id
 * @property integer user_id
 * @property integer chat_id
 * @property string status
 * @property string command
 * @property string notes
 * @property string created_at
 * @property string updated_at
 */
class Conversation extends BaseTelegramModel
{



    protected $attributes = [
		'id' => null,
		'user_id' => null,
		'chat_id' => null,
		'status' => 'active',
		'command' => '',
		'notes' => null,
		'created_at' => null,
		'updated_at' => null
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
			'status' => "required|string",
			'command' => "nullable|string|max:160",
			'notes' => "nullable|string",
			'created_at' => "nullable|date",
			'updated_at' => "nullable|date"
		];
    }

    /**
     * All model fields.
     *
     * @var array
     */
    protected static $fields = ['id', 'user_id', 'chat_id', 'status', 'command', 'notes', 'created_at', 'updated_at'];

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['user_id', 'chat_id', 'status', 'command', 'notes'];

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
    protected $casts = ['user_id' => 'integer', 'chat_id' => 'integer'];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['created_at', 'updated_at', 'created_at', 'updated_at'];


}
