<?php

namespace Longman\TelegramBot\Models;

use Longman\TelegramBot\Entities\Entity;

/**
 * Original version of this sucks... there is definetely much better ways to do this..
 *
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
    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['update_type', 'update_id'];
    protected $attributes = ['id' => 0, 'update_type' => null, 'update_id' => null];


    /**
     * @return Update
     */
    public function lastUpdate() {
        return self::all()->last();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function updatetype()
    {
        return $this->morphTo('update');
    }


    /**
     *
     * Consume Update Entity and make a database entry out of it
     *
     * @param \Longman\TelegramBot\Entities\Update $update
     * @return $this
     */
    public function ConsumeUpdate(\Longman\TelegramBot\Entities\Update $update)
    {

        $update_type = $update->getUpdateType();
        $update_class = $this->humanize_type($update_type);
        $entity_namespace = '\\Longman\\TelegramBot\\Entities\\';
        $update_getter = $update_type . $update_type;
        $entity_class = $entity_namespace . $update_class;
        /** @var Entity $entity_obj */
        $entity_obj = new $entity_class(call_user_func([$update, $update_getter]));
        $update_obj = new $update_class();

        /** @var BaseTelegramModel $db_entry */
        $db_entry = new $update_obj();
        $db_entry->consume($entity_obj);

        $this->updatetype()->save($db_entry);
        $this->save();
        return $this;
    }

}
