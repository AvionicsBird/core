<?php
namespace Longman\TelegramBot\Models;

/**
 * Class BasePreCheckoutQuery
 * @property array $fillable
 * @property array $hidden
 * @property array $casts
 * @property array $dates
 * @property array $rules
 *
 * @property integer id
 * @property integer user_id
 * @property string currency
 * @property integer total_amount
 * @property string invoice_payload
 * @property string shipping_option_id
 * @property string order_info
 * @property datetime created_at
 * @property TelegramUpdate[] telegram_update
 */
class PreCheckoutQuery extends BaseTelegramModel
{
    protected $attributes = [
		'id' => null,
		'user_id' => null,
		'currency' => null,
		'total_amount' => null,
		'invoice_payload' => '',
		'shipping_option_id' => null,
		'order_info' => null,
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
			'currency' => "nullable|string|max:3",
			'total_amount' => "nullable|numeric|integer",
			'invoice_payload' => "required|string|max:255",
			'shipping_option_id' => "nullable|string|max:255",
			'order_info' => "nullable|string",
			'created_at' => "nullable|date"
		];
    }


	public function telegram_update() {
		return $this->hasMany( Update::class, 'pre_checkout_query_id' );
	}



    /**
     * All model fields.
     *
     * @var array
     */
    protected static $fields = ['id', 'user_id', 'currency', 'total_amount', 'invoice_payload', 'shipping_option_id', 'order_info', 'created_at'];

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['user_id', 'currency', 'total_amount', 'invoice_payload', 'shipping_option_id', 'order_info'];

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
    protected $casts = ['user_id' => 'integer', 'total_amount' => 'integer', 'created_at' => 'datetime'];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['created_at', 'created_at'];

}
