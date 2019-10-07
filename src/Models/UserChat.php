<?php

namespace Longman\TelegramBot\Models;

/**
 * Class BaseUserChat
 * @property array $fillable
 * @property array $hidden
 * @property array $casts
 * @property array $dates
 * @property array $rules
 *
 * @property integer user_id
 * @property integer chat_id
 */
class UserChat extends BaseTelegramModel
{


	protected $primaryKey = 'chat_id';


    protected $attributes = [
		'user_id' => null,
		'chat_id' => null
	];

    /**
     * @return array
     */
    public function getRules()
    {
        return [
			'user_id' => "required|numeric|integer",
			'chat_id' => "required|numeric|integer"
		];
    }

    public function User()
    {
        return $this->belongsTo(User::class);
    }

    public function Chat()
    {
        return $this->belongsTo(Chat::class);
    }

    /**
     * All model fields.
     *
     * @var array
     */
    protected static $fields = ['user_id', 'chat_id'];

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['user_id', 'chat_id'];

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
    protected $dates = [];

    public $timestamps = false;
}
