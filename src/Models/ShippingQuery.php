<?php

namespace Longman\TelegramBot\Models;

use DateTime;

/**
 * Class BaseShippingQuery
 * @property array $fillable
 * @property array $hidden
 * @property array $casts
 * @property array $dates
 * @property array $rules
 *
 * @property integer id
 * @property integer user_id
 * @property string invoice_payload
 * @property string shipping_address
 * @property datetime created_at
 * @property Update[] telegram_update
 */
class ShippingQuery extends BaseTelegramModel
{



    protected $attributes = [
		'id' => null,
		'user_id' => null,
		'invoice_payload' => '',
		'shipping_address' => '',
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
			'invoice_payload' => "required|string|max:255",
			'shipping_address' => "required|string|max:255",
			'created_at' => "nullable|date"
		];
    }


	public function telegram_update() {
		return $this->hasMany( Update::class, 'shipping_query_id' );
	}



    /**
     * All model fields.
     *
     * @var array
     */
    protected static $fields = ['id', 'user_id', 'invoice_payload', 'shipping_address', 'created_at'];

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['user_id', 'invoice_payload', 'shipping_address'];

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
