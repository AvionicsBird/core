<?php

namespace Longman\TelegramBot\Models;

/**
 * Class BaseTelegramUpdate
 * @property array $fillable
 * @property array $hidden
 * @property array $casts
 * @property array $dates
 * @property array $rules
 *
 * @property integer id
 * @property integer chat_id
 * @property integer message_id
 * @property integer edited_message_id
 * @property integer channel_post_id
 * @property integer edited_channel_post_id
 * @property integer inline_query_id
 * @property integer chosen_inline_result_id
 * @property integer callback_query_id
 * @property integer shipping_query_id
 * @property integer pre_checkout_query_id
 * @property integer poll_id
 */
class Update extends BaseTelegramModel
{
    protected $attributes = [
		'id' => null,
		'chat_id' => null,
		'message_id' => null,
		'edited_message_id' => null,
		'channel_post_id' => null,
		'edited_channel_post_id' => null,
		'inline_query_id' => null,
		'chosen_inline_result_id' => null,
		'callback_query_id' => null,
		'shipping_query_id' => null,
		'pre_checkout_query_id' => null,
		'poll_id' => null
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
			'edited_message_id' => "nullable|numeric|integer",
			'channel_post_id' => "nullable|numeric|integer",
			'edited_channel_post_id' => "nullable|numeric|integer",
			'inline_query_id' => "nullable|numeric|integer",
			'chosen_inline_result_id' => "nullable|numeric|integer",
			'callback_query_id' => "nullable|numeric|integer",
			'shipping_query_id' => "nullable|numeric|integer",
			'pre_checkout_query_id' => "nullable|numeric|integer",
			'poll_id' => "nullable|numeric|integer"
		];
    }

    /**
     * All model fields.
     *
     * @var array
     */
    protected static $fields = ['id', 'chat_id', 'message_id', 'edited_message_id', 'channel_post_id', 'edited_channel_post_id', 'inline_query_id', 'chosen_inline_result_id', 'callback_query_id', 'shipping_query_id', 'pre_checkout_query_id', 'poll_id'];

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['chat_id', 'message_id', 'edited_message_id', 'channel_post_id', 'edited_channel_post_id', 'inline_query_id', 'chosen_inline_result_id', 'callback_query_id', 'shipping_query_id', 'pre_checkout_query_id', 'poll_id'];

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
    protected $casts = ['chat_id' => 'integer', 'message_id' => 'integer', 'edited_message_id' => 'integer', 'channel_post_id' => 'integer', 'edited_channel_post_id' => 'integer', 'inline_query_id' => 'integer', 'chosen_inline_result_id' => 'integer', 'callback_query_id' => 'integer', 'shipping_query_id' => 'integer', 'pre_checkout_query_id' => 'integer', 'poll_id' => 'integer'];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [];

    public $timestamps = false;

    /**
     * @return Update
     */
    public function lastUpdate() {
        return self::all()->last();
    }
}
