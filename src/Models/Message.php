<?php
namespace Longman\TelegramBot\Models;

use DateTime;
use Longman\TelegramBot\Entities\Animation as EntityAnimation;
use Longman\TelegramBot\Entities\Audio as EntityAudio;
use Longman\TelegramBot\Entities\Contact as EntityContact;
use Longman\TelegramBot\Entities\Document as EntityDocument;
use Longman\TelegramBot\Entities\Games\Game as EntityGame;
use Longman\TelegramBot\Entities\InlineKeyboard as EntityInlineKeyboard;
use Longman\TelegramBot\Entities\Location as EntityLocation;
use Longman\TelegramBot\Entities\MessageEntity;
use Longman\TelegramBot\Entities\Payments\Invoice as EntityInvoice;
use Longman\TelegramBot\Entities\Payments\SuccessfulPayment as EntitySuccessfulPayment;
use Longman\TelegramBot\Entities\PhotoSize as EntityPhotosize;
use Longman\TelegramBot\Entities\Poll as EntityPoll;
use Longman\TelegramBot\Entities\ReplyToMessage as EntityReplyToMessage;
use Longman\TelegramBot\Entities\Sticker as EntitySticker;
use Longman\TelegramBot\Entities\TelegramPassport\PassportData as EntityPassportData;
use Longman\TelegramBot\Entities\Venue as EntityVenue;
use Longman\TelegramBot\Entities\Video as EntityVideo;
use Longman\TelegramBot\Entities\VideoNote as EntityVideoNote;
use Longman\TelegramBot\Entities\Voice as EntityVoice;

/**
 * Class Message
 * This is probably most complex porting from the existin sdk to the laravel implementation..
 * Hopefully we get this right.
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
 * @method int               getMessageId()             Unique message identifier
 * @method User              getFrom()                  Optional. Sender, can be empty for messages sent to channels
 * @method int               getDate()                  Date the message was sent in Unix time
 * @method Chat              getChat()                  Conversation the message belongs to
 * @method User              getForwardFrom()           Optional. For forwarded messages, sender of the original message
 * @method Chat              getForwardFromChat()       Optional. For messages forwarded from a channel, information about the original channel
 * @method int               getForwardFromMessageId()  Optional. For forwarded channel posts, identifier of the original message in the channel
 * @method string            getForwardSignature()      Optional. For messages forwarded from channels, signature of the post author if present
 * @method string            getForwardSenderName()     Optional. Sender's name for messages forwarded from users who disallow adding a link to their account in forwarded messages
 * @method int               getForwardDate()           Optional. For forwarded messages, date the original message was sent in Unix time
 * @method Message           getReplyToMessage()        Optional. For replies, the original message. Note that the Message object in this field will not contain further reply_to_message fields even if it itself is a reply.
 * @method int               getEditDate()              Optional. Date the message was last edited in Unix time
 * @method string            getMediaGroupId()          Optional. The unique identifier of a media message group this message belongs to
 * @method string            getAuthorSignature()       Optional. Signature of the post author for messages in channels
 * @method MessageEntity[]   getEntities()              Optional. For text messages, special entities like usernames, URLs, bot commands, etc. that appear in the text
 * @method MessageEntity[]   getCaptionEntities()       Optional. For messages with a caption, special entities like usernames, URLs, bot commands, etc. that appear in the caption
 * @method EntityAudio             getAudio()                 Optional. Message is an audio file, information about the file
 * @method EntityDocument          getDocument()              Optional. Message is a general file, information about the file
 * @method EntityAnimation         getAnimation()             Optional. Message is an animation, information about the animation. For backward compatibility, when this field is set, the document field will also be set
 * @method EntityGame              getGame()                  Optional. Message is a game, information about the game.
 * @method EntityPhotoSize[]       getPhoto()                 Optional. Message is a photo, available sizes of the photo
 * @method EntitySticker           getSticker()               Optional. Message is a sticker, information about the sticker
 * @method EntityVideo             getVideo()                 Optional. Message is a video, information about the video
 * @method EntityVoice             getVoice()                 Optional. Message is a voice message, information about the file
 * @method EntityVideoNote         getVideoNote()             Optional. Message is a video note message, information about the video
 * @method string            getCaption()               Optional. Caption for the document, photo or video, 0-200 characters
 * @method EntityContact           getContact()               Optional. Message is a shared contact, information about the contact
 * @method EntityLocation          getLocation()              Optional. Message is a shared location, information about the location
 * @method EntityVenue             getVenue()                 Optional. Message is a venue, information about the venue
 * @method Poll              getPoll()                  Optional. Message is a native poll, information about the poll
 * @method User[]            getNewChatMembers()        Optional. A new member(s) was added to the group, information about them (one of this members may be the bot itself)
 * @method User              getLeftChatMember()        Optional. A member was removed from the group, information about them (this member may be the bot itself)
 * @method string            getNewChatTitle()          Optional. A chat title was changed to this value
 * @method EntityPhotoSize[]       getNewChatPhoto()          Optional. A chat photo was changed to this value
 * @method bool              getDeleteChatPhoto()       Optional. Service message: the chat photo was deleted
 * @method bool              getGroupChatCreated()      Optional. Service message: the group has been created
 * @method bool              getSupergroupChatCreated() Optional. Service message: the supergroup has been created. This field can't be received in a message coming through updates, because bot can’t be a member of a supergroup when it is created. It can only be found in reply_to_message if someone replies to a very first message in a directly created supergroup.
 * @method bool              getChannelChatCreated()    Optional. Service message: the channel has been created. This field can't be received in a message coming through updates, because bot can’t be a member of a channel when it is created. It can only be found in reply_to_message if someone replies to a very first message in a channel.
 * @method int               getMigrateToChatId()       Optional. The group has been migrated to a supergroup with the specified identifier. This number may be greater than 32 bits and some programming languages may have difficulty/silent defects in interpreting it. But it smaller than 52 bits, so a signed 64 bit integer or double-precision float type are safe for storing this identifier.
 * @method int               getMigrateFromChatId()     Optional. The supergroup has been migrated from a group with the specified identifier. This number may be greater than 32 bits and some programming languages may have difficulty/silent defects in interpreting it. But it smaller than 52 bits, so a signed 64 bit integer or double-precision float type are safe for storing this identifier.
 * @method Message           getPinnedMessage()         Optional. Specified message was pinned. Note that the Message object in this field will not contain further reply_to_message fields even if it is itself a reply.
 * @method EntityInvoice           getInvoice()               Optional. Message is an invoice for a payment, information about the invoice.
 * @method EntitySuccessfulPayment getSuccessfulPayment()     Optional. Message is a service message about a successful payment, information about the payment.
 * @method string            getConnectedWebsite()      Optional. The domain name of the website on which the user has logged in.
 * @method EntityPassportData      getPassportData()          Optional. Telegram Passport data
 * @method EntityInlineKeyboard    getReplyMarkup()           Optional. Inline keyboard attached to the message. login_url buttons are represented as ordinary url buttons.
 */
class Message extends BaseTelegramModel
{


    /**
     * {@inheritdoc}
     */
    protected function subEntities()
    {
        return [
            'from' => array(User::class, 'find'),
            'chat' => array(Chat::class, 'find'),
            'forward_from' => array(User::class, 'find'),
            'forward_from_chat' => array(Chat::class, 'find'),
            'reply_to_message' => EntityReplyToMessage::class,
            'entities' => [MessageEntity::class],
            'caption_entities' => [MessageEntity::class],
            'audio' => EntityAudio::class,
            'document' => EntityDocument::class,
            'animation' => EntityAnimation::class,
            'game' => EntityGame::class,
            'photo' => [EntityPhotoSize::class],
            'sticker' => EntitySticker::class,
            'video' => EntityVideo::class,
            'voice' => EntityVoice::class,
            'video_note' => EntityVideoNote::class,
            'contact' => EntityContact::class,
            'location' => EntityLocation::class,
            'venue' => EntityVenue::class,
            'poll' => Poll::class,
            'new_chat_members' => [User::class],
            'left_chat_member' => User::class,
            'new_chat_photo' => [EntityPhotoSize::class],
            'pinned_message' => Message::class,
            'invoice' => EntityInvoice::class,
            'successful_payment' => EntitySuccessfulPayment::class,
            'passport_data' => EntityPassportData::class,
            'reply_markup' => EntityInlineKeyboard::class,
        ];
    }

    protected $attributes = [
        'id' => null,
        'chat_id' => null,
        'user_id' => null,
        'date' => null,
        'forward_from_id' => null,
        'forward_from_chat_id' => null,
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
            'forward_from_id' => "nullable|numeric|integer",
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


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function ForwardFrom()
    {
        return $this->belongsTo('User', 'forward_from_id');
    }


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function ForwardFromChat()
    {
        return $this->belongsTo('Chat', 'forward_from_chat_id');
    }

    public function postinit_Chat()
    {
        // Associate Chat with From Object
        $userchat = new UserChat();
        $userchat->User()->save($this->from);
        $userchat->Chat()->save($this->chat);
        $userchat->save();
    }


    /**
     * @return bool
     */
    public function consume_presave()
    {
        /** @var Chat $chat */
        $chat = new Chat();
        $chat->consume($this->getChat());
        $this->Chat()->associate($chat);
        return true;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function Chat()
    {
        return $this->belongsTo(Chat::class);
    }


    public function callback_query()
    {
        return $this->hasMany( CallbackQuery::class, 'message_id' );
    }

    public function edited_message() {
        return $this->hasMany( EditedMessage::class, 'message_id' );
    }

    public function telegram_update() {
        return $this->morphOne(Update::class, 'update');
        //	return $this->hasMany( Update::class, 'message_id' );
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
