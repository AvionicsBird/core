<?php
namespace Longman\TelegramBot\Models;

/**
 * Class BasePoll
 * @property array $fillable
 * @property array $hidden
 * @property array $casts
 * @property array $dates
 * @property array $rules
 *
 * @property integer id
 * @property string question
 * @property string options
 * @property boolean is_closed
 * @property datetime created_at
 * @property Update[] telegram_update
 */
class Poll extends BaseTelegramModel
{



    protected $attributes = [
		'id' => null,
		'question' => null,
		'options' => null,
		'is_closed' => 0,
		'created_at' => null
	];

    /**
     * @return array
     */
    public function getRules()
    {
        return [
			'id' => "nullable|numeric|integer",
			'question' => "required|string|max:255",
			'options' => "required|string",
			'is_closed' => "nullable|boolean",
			'created_at' => "nullable|date"
		];
    }


	public function telegram_update() {
		return $this->hasMany( Update::class, 'poll_id' );
	}

    /**
     * All model fields.
     *
     * @var array
     */
    protected static $fields = ['id', 'question', 'options', 'is_closed', 'created_at'];

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['question', 'options', 'is_closed'];

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
    protected $casts = ['is_closed' => 'boolean', 'created_at' => 'datetime'];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['created_at', 'created_at'];


}
