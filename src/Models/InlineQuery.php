<?php
namespace Longman\TelegramBot\Models;

/**
 * Class BaseInlineQuery
 * @property array $fillable
 * @property array $hidden
 * @property array $casts
 * @property array $dates
 * @property array $rules
 *
 * @property integer id
 * @property integer user_id
 * @property string location
 * @property string query
 * @property string offset
 * @property datetime created_at
 * @property TelegramUpdate[] telegram_update
 */
class InlineQuery extends BaseTelegramModel
{



    protected $attributes = [
		'id' => null,
		'user_id' => null,
		'location' => null,
		'query' => null,
		'offset' => null,
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
			'location' => "nullable|string|max:255",
			'query' => "required|string",
			'offset' => "nullable|string|max:255",
			'created_at' => "nullable|date"
		];
    }


	public function telegram_update() {
		return $this->hasMany( Update::class, 'inline_query_id' );
	}

    /**
     * All model fields.
     *
     * @var array
     */
    protected static $fields = ['id', 'user_id', 'location', 'query', 'offset', 'created_at'];

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['user_id', 'location', 'query', 'offset'];

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
