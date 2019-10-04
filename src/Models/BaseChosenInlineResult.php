<?php

namespace App\Models\Base;

/**
 * Class BaseChosenInlineResult
 * @property array $fillable
 * @property array $hidden
 * @property array $casts
 * @property array $dates
 * @property array $rules
 *
 * @property integer id
 * @property string result_id
 * @property integer user_id
 * @property string location
 * @property string inline_message_id
 * @property string query
 * @property datetime created_at
 * @property \App\Models\TelegramUpdate[] telegram_update
 */
class BaseChosenInlineResult extends \Colorgreen\Generator\Models\BaseModel
{



    protected $attributes = [
		'id' => null,
		'result_id' => '',
		'user_id' => null,
		'location' => null,
		'inline_message_id' => null,
		'query' => null,
		'created_at' => null
	];

    /**
     * @return array
     */
    public function getRules()
    {
        return [
			'id' => "nullable|numeric|integer",
			'result_id' => "required|string|max:255",
			'user_id' => "nullable|numeric|integer",
			'location' => "nullable|string|max:255",
			'inline_message_id' => "nullable|string|max:255",
			'query' => "required|string",
			'created_at' => "nullable|date"
		];
    }


	public function telegram_update() {
		return $this->hasMany( \App\Models\TelegramUpdate::class, 'chosen_inline_result_id' );
	}


    /**
     * All model fields.
     *
     * @var array
     */
    protected static $fields = ['id', 'result_id', 'user_id', 'location', 'inline_message_id', 'query', 'created_at'];

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['result_id', 'user_id', 'location', 'inline_message_id', 'query'];

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
    protected $casts = ['user_id' => 'integer', 'created_at' => 'datetime'];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['created_at', 'created_at'];


}
