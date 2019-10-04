<?php
namespace Longman\TelegramBot\Models;

/**
 * Class BaseMessage
 * @property array $fillable
 * @property array $hidden
 * @property array $casts
 * @property array $dates
 * @property array $rules
 *
 * @property integer chat_id
 * @property integer id
 * @property integer user_id
 * @property datetime date
 * @property integer forward_from
 * @property integer forward_from_chat
 * @property integer forward_from_message_id
 * @property string forward_signature
 * @property string forward_sender_name
 * @property datetime forward_date
 * @property integer reply_to_chat
 * @property integer reply_to_message
 * @property integer edit_date
 * @property string media_group_id
 * @property string author_signature
 * @property string text
 * @property string entities
 * @property string caption_entities
 * @property string audio
 * @property string document
 * @property string animation
 * @property string game
 * @property string photo
 * @property string sticker
 * @property string video
 * @property string voice
 * @property string video_note
 * @property string caption
 * @property string contact
 * @property string location
 * @property string venue
 * @property string poll
 * @property string new_chat_members
 * @property integer left_chat_member
 * @property string new_chat_title
 * @property string new_chat_photo
 * @property boolean delete_chat_photo
 * @property boolean group_chat_created
 * @property boolean supergroup_chat_created
 * @property boolean channel_chat_created
 * @property integer migrate_to_chat_id
 * @property integer migrate_from_chat_id
 * @property string pinned_message
 * @property string invoice
 * @property string successful_payment
 * @property string connected_website
 * @property string passport_data
 * @property string reply_markup
 * @property CallbackQuery[] callback_query
 * @property EditedMessage[] edited_message
 * @property Update[] telegram_update
 */
class Message extends BaseTelegramModel
{



    protected $attributes = [
		'chat_id' => null,
		'id' => null,
		'user_id' => null,
		'date' => null,
		'forward_from' => null,
		'forward_from_chat' => null,
		'forward_from_message_id' => null,
		'forward_signature' => null,
		'forward_sender_name' => null,
		'forward_date' => null,
		'reply_to_chat' => null,
		'reply_to_message' => null,
		'edit_date' => null,
		'media_group_id' => null,
		'author_signature' => null,
		'text' => null,
		'entities' => null,
		'caption_entities' => null,
		'audio' => null,
		'document' => null,
		'animation' => null,
		'game' => null,
		'photo' => null,
		'sticker' => null,
		'video' => null,
		'voice' => null,
		'video_note' => null,
		'caption' => null,
		'contact' => null,
		'location' => null,
		'venue' => null,
		'poll' => null,
		'new_chat_members' => null,
		'left_chat_member' => null,
		'new_chat_title' => null,
		'new_chat_photo' => null,
		'delete_chat_photo' => 0,
		'group_chat_created' => 0,
		'supergroup_chat_created' => 0,
		'channel_chat_created' => 0,
		'migrate_to_chat_id' => null,
		'migrate_from_chat_id' => null,
		'pinned_message' => null,
		'invoice' => null,
		'successful_payment' => null,
		'connected_website' => null,
		'passport_data' => null,
		'reply_markup' => null
	];

    /**
     * @return array
     */
    public function getRules()
    {
        return [
			'chat_id' => "required|numeric|integer",
			'id' => "nullable|numeric|integer",
			'user_id' => "nullable|numeric|integer",
			'date' => "nullable|date",
			'forward_from' => "nullable|numeric|integer",
			'forward_from_chat' => "nullable|numeric|integer",
			'forward_from_message_id' => "nullable|numeric|integer",
			'forward_signature' => "nullable|string",
			'forward_sender_name' => "nullable|string",
			'forward_date' => "nullable|date",
			'reply_to_chat' => "nullable|numeric|integer",
			'reply_to_message' => "nullable|numeric|integer",
			'edit_date' => "nullable|numeric|integer",
			'media_group_id' => "nullable|string",
			'author_signature' => "nullable|string",
			'text' => "nullable|string",
			'entities' => "nullable|string",
			'caption_entities' => "nullable|string",
			'audio' => "nullable|string",
			'document' => "nullable|string",
			'animation' => "nullable|string",
			'game' => "nullable|string",
			'photo' => "nullable|string",
			'sticker' => "nullable|string",
			'video' => "nullable|string",
			'voice' => "nullable|string",
			'video_note' => "nullable|string",
			'caption' => "nullable|string",
			'contact' => "nullable|string",
			'location' => "nullable|string",
			'venue' => "nullable|string",
			'poll' => "nullable|string",
			'new_chat_members' => "nullable|string",
			'left_chat_member' => "nullable|numeric|integer",
			'new_chat_title' => "nullable|string|max:255",
			'new_chat_photo' => "nullable|string",
			'delete_chat_photo' => "nullable|boolean",
			'group_chat_created' => "nullable|boolean",
			'supergroup_chat_created' => "nullable|boolean",
			'channel_chat_created' => "nullable|boolean",
			'migrate_to_chat_id' => "nullable|numeric|integer",
			'migrate_from_chat_id' => "nullable|numeric|integer",
			'pinned_message' => "nullable|string",
			'invoice' => "nullable|string",
			'successful_payment' => "nullable|string",
			'connected_website' => "nullable|string",
			'passport_data' => "nullable|string",
			'reply_markup' => "nullable|string"
		];
    }


	public function callback_query() {
		return $this->hasMany( CallbackQuery::class, 'message_id' );
	}

	public function edited_message() {
		return $this->hasMany( EditedMessage::class, 'message_id' );
	}

	public function telegram_update() {
		return $this->hasMany( Update::class, 'message_id' );
	}

    /**
     * All model fields.
     *
     * @var array
     */
    protected static $fields = ['chat_id', 'id', 'user_id', 'date', 'forward_from', 'forward_from_chat', 'forward_from_message_id', 'forward_signature', 'forward_sender_name', 'forward_date', 'reply_to_chat', 'reply_to_message', 'edit_date', 'media_group_id', 'author_signature', 'text', 'entities', 'caption_entities', 'audio', 'document', 'animation', 'game', 'photo', 'sticker', 'video', 'voice', 'video_note', 'caption', 'contact', 'location', 'venue', 'poll', 'new_chat_members', 'left_chat_member', 'new_chat_title', 'new_chat_photo', 'delete_chat_photo', 'group_chat_created', 'supergroup_chat_created', 'channel_chat_created', 'migrate_to_chat_id', 'migrate_from_chat_id', 'pinned_message', 'invoice', 'successful_payment', 'connected_website', 'passport_data', 'reply_markup'];

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['chat_id', 'user_id', 'date', 'forward_from', 'forward_from_chat', 'forward_from_message_id', 'forward_signature', 'forward_sender_name', 'forward_date', 'reply_to_chat', 'reply_to_message', 'edit_date', 'media_group_id', 'author_signature', 'text', 'entities', 'caption_entities', 'audio', 'document', 'animation', 'game', 'photo', 'sticker', 'video', 'voice', 'video_note', 'caption', 'contact', 'location', 'venue', 'poll', 'new_chat_members', 'left_chat_member', 'new_chat_title', 'new_chat_photo', 'delete_chat_photo', 'group_chat_created', 'supergroup_chat_created', 'channel_chat_created', 'migrate_to_chat_id', 'migrate_from_chat_id', 'pinned_message', 'invoice', 'successful_payment', 'connected_website', 'passport_data', 'reply_markup'];

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
    protected $casts = ['chat_id' => 'integer', 'user_id' => 'integer', 'date' => 'datetime', 'forward_from' => 'integer', 'forward_from_chat' => 'integer', 'forward_from_message_id' => 'integer', 'forward_date' => 'datetime', 'reply_to_chat' => 'integer', 'reply_to_message' => 'integer', 'edit_date' => 'integer', 'left_chat_member' => 'integer', 'delete_chat_photo' => 'boolean', 'group_chat_created' => 'boolean', 'supergroup_chat_created' => 'boolean', 'channel_chat_created' => 'boolean', 'migrate_to_chat_id' => 'integer', 'migrate_from_chat_id' => 'integer'];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['date', 'forward_date', 'date', 'forward_date'];


}
