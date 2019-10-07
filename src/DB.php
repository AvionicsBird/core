<?php
/**
 * This file is part of the TelegramBot package.
 *
 * (c) Avtandil Kikabidze aka LONGMAN <akalongman@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * Written by Marco Boretto <marco.bore@gmail.com>
 */

namespace Longman\TelegramBot;
use Illuminate\Support\Facades\DB as LaravelDB;

/*
use Longman\TelegramBot\Entities\CallbackQuery;
use Longman\TelegramBot\Entities\Chat;
use Longman\TelegramBot\Entities\ChosenInlineResult;
use Longman\TelegramBot\Entities\InlineQuery;
use Longman\TelegramBot\Entities\Message;
use Longman\TelegramBot\Entities\Payments\PreCheckoutQuery;
use Longman\TelegramBot\Entities\Payments\ShippingQuery;
use Longman\TelegramBot\Entities\Poll;
use Longman\TelegramBot\Entities\ReplyToMessage;

use Longman\TelegramBot\Entities\User;
use Longman\TelegramBot\Exception\TelegramException;
use Longman\TelegramBot\Models\TelegramChat;
*/

use Longman\TelegramBot\Entities\Update;
use Longman\TelegramBot\Models\Update as TelegramUpdate;
use Longman\TelegramBot\Models\ChosenInlineResult;
use Longman\TelegramBot\Models\InlineQuery;
use Longman\TelegramBot\Models\Message;
// use Longman\TelegramBot\Models\Update;
use PDO;
use PDOException;

class DB
{
    /**
     * MySQL credentials
     *
     * @var array
     */
    protected static $mysql_credentials = [];

    /**
     * PDO object
     *
     * @var PDO
     */
    protected static $pdo;

    /**
     * Table prefix
     *
     * @var string
     */
    protected static $table_prefix;

    /**
     * Telegram class object
     *
     * @var Telegram
     */
    protected static $telegram;

    /**
     * Initialize
     *
     * @param array    $credentials  Database connection details
     * @param Telegram $telegram     Telegram object to connect with this object
     * @param string   $table_prefix Table prefix
     * @param string   $encoding     Database character encoding
     *
     * @return PDO PDO database object
     * @throws TelegramException
     */
    public static function initialize(
        array $credentials,
        Telegram $telegram,
        $table_prefix = null,
        $encoding = 'utf8mb4'
    ) {
        // really nothing to initialize anymore

    }


    /**
     * Check if database connection has been created
     *
     * @return bool
     */
    public static function isDbConnected()
    {
        return LaravelDB::connection() != null;
    }


    /**
     * Fetch update(s) from DB
     *
     * @param int    $limit Limit the number of updates to fetch
     * @param string $id    Check for unique update id
     *
     * @return array|bool Fetched data or false if not connected
     * @throws TelegramException
     */
    public static function selectTelegramUpdate($limit = null, $id = null)
    {
        if (!self::isDbConnected()) {
            return false;
        }


        try {
            $sql = '
                SELECT `id`
                FROM `' . TB_TELEGRAM_UPDATE . '`
            ';

            if ($id !== null) {
                $sql .= ' WHERE `id` = :id';
            } else {
                $sql .= ' ORDER BY `id` DESC';
            }

            if ($limit !== null) {
                $sql .= ' LIMIT :limit';
            }

            $sth = self::$pdo->prepare($sql);

            if ($limit !== null) {
                $sth->bindValue(':limit', $limit, PDO::PARAM_INT);
            }
            if ($id !== null) {
                $sth->bindValue(':id', $id);
            }

            $sth->execute();

            return $sth->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new TelegramException($e->getMessage());
        }
    }

    /**
     * Fetch message(s) from DB
     *
     * @param int $limit Limit the number of messages to fetch
     *
     * @return array|bool Fetched data or false if not connected
     * @throws TelegramException
     */
    public static function selectMessages($limit = null)
    {
        if (!self::isDbConnected()) {
            return false;
        }

        try {
            $sql = '
                SELECT *
                FROM `' . TB_MESSAGE . '`
                ORDER BY `id` DESC
            ';

            if ($limit !== null) {
                $sql .= ' LIMIT :limit';
            }

            $sth = self::$pdo->prepare($sql);

            if ($limit !== null) {
                $sth->bindValue(':limit', $limit, PDO::PARAM_INT);
            }

            $sth->execute();

            return $sth->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new TelegramException($e->getMessage());
        }
    }

    /**
     * Convert from unix timestamp to timestamp
     *
     * @param int $time Unix timestamp (if empty, current timestamp is used)
     *
     * @return string
     */
    protected static function getTimestamp($time = null)
    {
        return date('Y-m-d H:i:s', $time ?: time());
    }

    /**
     * Convert array of Entity items to a JSON array
     *
     * @todo Find a better way, as json_* functions are very heavy
     *
     * @param array|null $entities
     * @param mixed      $default
     *
     * @return mixed
     */
    public static function entitiesArrayToJson($entities, $default = null)
    {
        if (!is_array($entities)) {
            return $default;
        }

        // Convert each Entity item into an object based on its JSON reflection
        $json_entities = array_map(function ($entity) {
            return json_decode($entity, true);
        }, $entities);

        return json_encode($json_entities);
    }

    /**
     * Insert users and save their connection to chats
     *
     * @param User   $user
     * @param string $date
     * @param Chat   $chat
     *
     * @return bool If the insert was successful
     * @throws TelegramException
     */
    public static function insertUser(User $user, $date = null, Chat $chat = null)
    {
        if (!self::isDbConnected()) {
            return false;
        }

        try {

            $sth->bindValue(':id', $user->getId());
            $sth->bindValue(':is_bot', $user->getIsBot(), PDO::PARAM_INT);
            $sth->bindValue(':username', $user->getUsername());
            $sth->bindValue(':first_name', $user->getFirstName());
            $sth->bindValue(':last_name', $user->getLastName());
            $sth->bindValue(':language_code', $user->getLanguageCode());


            $sth->bindValue(':created_at', $date);
            $sth->bindValue(':updated_at', $date);

            $status = $sth->execute();
        } catch (PDOException $e) {
            throw new TelegramException($e->getMessage());
        }

        // Also insert the relationship to the chat into the user_chat table
        if ($chat instanceof Chat) {
            try {
                $sth = self::$pdo->prepare('
                    INSERT IGNORE INTO `' . TB_USER_CHAT . '`
                    (`user_id`, `chat_id`)
                    VALUES
                    (:user_id, :chat_id)
                ');

                $sth->bindValue(':user_id', $user->getId());
                $sth->bindValue(':chat_id', $chat->getId());

                $status = $sth->execute();
            } catch (PDOException $e) {
                throw new TelegramException($e->getMessage());
            }
        }

        return $status;
    }

    /**
     * Insert chat
     *
     * @param Chat   $chat
     * @param string $date
     * @param string $migrate_to_chat_id
     *
     * @return bool If the insert was successful
     * @throws TelegramException
     */
    public static function insertChat(Chat $chat, $date = null, $migrate_to_chat_id = null)
    {
        if (!self::isDbConnected()) {
            return false;
        }

        try {

            $chat_entry = new TelegramChat();

            $chat_id   = $chat->getId();
            $chat_type = $chat->getType();

            if ($migrate_to_chat_id !== null) {
                $chat_type = 'supergroup';

                $chat_entry->id = $migrate_to_chat_id;
                $chat_entry->old_id = $chat_id;
            } else {
                $chat_entry->id = $chat_id;
                $chat_entry->old_id = $migrate_to_chat_id;
            }

            $chat_entry->type = $chat_type;
            $chat_entry->title = $chat->getTitle();

            $sth->bindValue(':username', $chat->getUsername());
            $sth->bindValue(':first_name', $chat->getFirstName());
            $sth->bindValue(':last_name', $chat->getLastName());
            $sth->bindValue(':all_members_are_administrators', $chat->getAllMembersAreAdministrators(), PDO::PARAM_INT);
            $date = $date ?: self::getTimestamp();
            $sth->bindValue(':created_at', $date);
            $sth->bindValue(':updated_at', $date);

            return $sth->execute();
        } catch (PDOException $e) {
            throw new TelegramException($e->getMessage());
        }
    }

    /**
     * Insert request into database
     * @param Update $update
     *
     * @return TelegramUpdate
     * @throws TelegramException
     */
    public static function insertRequest(Update $update)
    {
        $telegram_update = new TelegramUpdate();
        $telegram_update->ConsumeUpdate($update);
        return $telegram_update;
    }




    /**
     * Insert Message request in db
     *
     * @param Message $message
     *
     * @return bool If the insert was successful
     * @throws TelegramException
     */
    public static function insertMessageRequest(Message $message)
    {

        $date = self::getTimestamp($message->getDate());

        // Insert chat, update chat id in case it migrated
        $chat = $message->getChat();
        self::insertChat($chat, $date, $message->getMigrateToChatId());

        // Insert user and the relation with the chat
        $user = $message->getFrom();
        if ($user instanceof User) {
            self::insertUser($user, $date, $chat);
        }

        // Insert the forwarded message user in users table
        $forward_date = $message->getForwardDate() ? self::getTimestamp($message->getForwardDate()) : null;

        $forward_from = $message->getForwardFrom();
        if ($forward_from instanceof User) {
            self::insertUser($forward_from);
            $forward_from = $forward_from->getId();
        }
        $forward_from_chat = $message->getForwardFromChat();
        if ($forward_from_chat instanceof Chat) {
            self::insertChat($forward_from_chat);
            $forward_from_chat = $forward_from_chat->getId();
        }

        // New and left chat member
        $new_chat_members_ids = null;
        $left_chat_member_id  = null;

        $new_chat_members = $message->getNewChatMembers();
        $left_chat_member = $message->getLeftChatMember();
        if (!empty($new_chat_members)) {
            foreach ($new_chat_members as $new_chat_member) {
                if ($new_chat_member instanceof User) {
                    // Insert the new chat user
                    self::insertUser($new_chat_member, $date, $chat);
                    $new_chat_members_ids[] = $new_chat_member->getId();
                }
            }
            $new_chat_members_ids = implode(',', $new_chat_members_ids);
        } elseif ($left_chat_member instanceof User) {
            // Insert the left chat user
            self::insertUser($left_chat_member, $date, $chat);
            $left_chat_member_id = $left_chat_member->getId();
        }

        try {

            $user_id = null;
            if ($user instanceof User) {
                $user_id = $user->getId();
            }
            $chat_id = $chat->getId();

            $reply_to_message    = $message->getReplyToMessage();
            $reply_to_message_id = null;
            if ($reply_to_message instanceof ReplyToMessage) {
                $reply_to_message_id = $reply_to_message->getMessageId();
                // please notice that, as explained in the documentation, reply_to_message don't contain other
                // reply_to_message field so recursion deep is 1
                self::insertMessageRequest($reply_to_message);
            }

            $sth->bindValue(':message_id', $message->getMessageId());
            $sth->bindValue(':chat_id', $chat_id);
            $sth->bindValue(':user_id', $user_id);
            $sth->bindValue(':date', $date);
            $sth->bindValue(':forward_from', $forward_from);
            $sth->bindValue(':forward_from_chat', $forward_from_chat);
            $sth->bindValue(':forward_from_message_id', $message->getForwardFromMessageId());
            $sth->bindValue(':forward_signature', $message->getForwardSignature());
            $sth->bindValue(':forward_sender_name', $message->getForwardSenderName());
            $sth->bindValue(':forward_date', $forward_date);

            $reply_to_chat_id = null;
            if ($reply_to_message_id !== null) {
                $reply_to_chat_id = $chat_id;
            }
            $sth->bindValue(':reply_to_chat', $reply_to_chat_id);
            $sth->bindValue(':reply_to_message', $reply_to_message_id);

            $sth->bindValue(':edit_date', $message->getEditDate());
            $sth->bindValue(':media_group_id', $message->getMediaGroupId());
            $sth->bindValue(':author_signature', $message->getAuthorSignature());
            $sth->bindValue(':text', $message->getText());
            $sth->bindValue(':entities', self::entitiesArrayToJson($message->getEntities() ?: null));
            $sth->bindValue(':caption_entities', self::entitiesArrayToJson($message->getCaptionEntities() ?: null));
            $sth->bindValue(':audio', $message->getAudio());
            $sth->bindValue(':document', $message->getDocument());
            $sth->bindValue(':animation', $message->getAnimation());
            $sth->bindValue(':game', $message->getGame());
            $sth->bindValue(':photo', self::entitiesArrayToJson($message->getPhoto() ?: null));
            $sth->bindValue(':sticker', $message->getSticker());
            $sth->bindValue(':video', $message->getVideo());
            $sth->bindValue(':voice', $message->getVoice());
            $sth->bindValue(':video_note', $message->getVideoNote());
            $sth->bindValue(':caption', $message->getCaption());
            $sth->bindValue(':contact', $message->getContact());
            $sth->bindValue(':location', $message->getLocation());
            $sth->bindValue(':venue', $message->getVenue());
            $sth->bindValue(':poll', $message->getPoll());
            $sth->bindValue(':new_chat_members', $new_chat_members_ids);
            $sth->bindValue(':left_chat_member', $left_chat_member_id);
            $sth->bindValue(':new_chat_title', $message->getNewChatTitle());
            $sth->bindValue(':new_chat_photo', self::entitiesArrayToJson($message->getNewChatPhoto() ?: null));
            $sth->bindValue(':delete_chat_photo', $message->getDeleteChatPhoto());
            $sth->bindValue(':group_chat_created', $message->getGroupChatCreated());
            $sth->bindValue(':supergroup_chat_created', $message->getSupergroupChatCreated());
            $sth->bindValue(':channel_chat_created', $message->getChannelChatCreated());
            $sth->bindValue(':migrate_to_chat_id', $message->getMigrateToChatId());
            $sth->bindValue(':migrate_from_chat_id', $message->getMigrateFromChatId());
            $sth->bindValue(':pinned_message', $message->getPinnedMessage());
            $sth->bindValue(':invoice', $message->getInvoice());
            $sth->bindValue(':successful_payment', $message->getSuccessfulPayment());
            $sth->bindValue(':connected_website', $message->getConnectedWebsite());
            $sth->bindValue(':passport_data', $message->getPassportData());
            $sth->bindValue(':reply_markup', $message->getReplyMarkup());

            return $sth->execute();
        } catch (PDOException $e) {
            throw new TelegramException($e->getMessage());
        }
    }

    /**
     * Insert Edited Message request in db
     *
     * @param Message $edited_message
     *
     * @return bool If the insert was successful
     * @throws TelegramException
     */
    public static function insertEditedMessageRequest(Message $edited_message)
    {
        if (!self::isDbConnected()) {
            return false;
        }

        try {
            $edit_date = self::getTimestamp($edited_message->getEditDate());

            // Insert chat
            $chat = $edited_message->getChat();
            self::insertChat($chat, $edit_date);

            // Insert user and the relation with the chat
            $user = $edited_message->getFrom();
            if ($user instanceof User) {
                self::insertUser($user, $edit_date, $chat);
            }

            $sth = self::$pdo->prepare('
                INSERT IGNORE INTO `' . TB_EDITED_MESSAGE . '`
                (`chat_id`, `message_id`, `user_id`, `edit_date`, `text`, `entities`, `caption`)
                VALUES
                (:chat_id, :message_id, :user_id, :edit_date, :text, :entities, :caption)
            ');

            $user_id = null;
            if ($user instanceof User) {
                $user_id = $user->getId();
            }

            $sth->bindValue(':chat_id', $chat->getId());
            $sth->bindValue(':message_id', $edited_message->getMessageId());
            $sth->bindValue(':user_id', $user_id);
            $sth->bindValue(':edit_date', $edit_date);
            $sth->bindValue(':text', $edited_message->getText());
            $sth->bindValue(':entities', self::entitiesArrayToJson($edited_message->getEntities() ?: null));
            $sth->bindValue(':caption', $edited_message->getCaption());

            return $sth->execute();
        } catch (PDOException $e) {
            throw new TelegramException($e->getMessage());
        }
    }

    /**
     * Select Groups, Supergroups, Channels and/or single user Chats (also by ID or text)
     *
     * @param $select_chats_params
     *
     * @return array|bool
     * @throws TelegramException
     */
    public static function selectChats($select_chats_params)
    {
        if (!self::isDbConnected()) {
            return false;
        }

        // Set defaults for omitted values.
        $select = array_merge([
            'groups'      => true,
            'supergroups' => true,
            'channels'    => true,
            'users'       => true,
            'date_from'   => null,
            'date_to'     => null,
            'chat_id'     => null,
            'text'        => null,
        ], $select_chats_params);

        if (!$select['groups'] && !$select['users'] && !$select['supergroups'] && !$select['channels']) {
            return false;
        }

        try {
            $query = '
                SELECT * ,
                ' . TB_CHAT . '.`id` AS `chat_id`,
                ' . TB_CHAT . '.`username` AS `chat_username`,
                ' . TB_CHAT . '.`created_at` AS `chat_created_at`,
                ' . TB_CHAT . '.`updated_at` AS `chat_updated_at`
            ';
            if ($select['users']) {
                $query .= '
                    , ' . TB_USER . '.`id` AS `user_id`
                    FROM `' . TB_CHAT . '`
                    LEFT JOIN `' . TB_USER . '`
                    ON ' . TB_CHAT . '.`id`=' . TB_USER . '.`id`
                ';
            } else {
                $query .= 'FROM `' . TB_CHAT . '`';
            }

            // Building parts of query
            $where  = [];
            $tokens = [];

            if (!$select['groups'] || !$select['users'] || !$select['supergroups'] || !$select['channels']) {
                $chat_or_user = [];

                $select['groups'] && $chat_or_user[] = TB_CHAT . '.`type` = "group"';
                $select['supergroups'] && $chat_or_user[] = TB_CHAT . '.`type` = "supergroup"';
                $select['channels'] && $chat_or_user[] = TB_CHAT . '.`type` = "channel"';
                $select['users'] && $chat_or_user[] = TB_CHAT . '.`type` = "private"';

                $where[] = '(' . implode(' OR ', $chat_or_user) . ')';
            }

            if (null !== $select['date_from']) {
                $where[]              = TB_CHAT . '.`updated_at` >= :date_from';
                $tokens[':date_from'] = $select['date_from'];
            }

            if (null !== $select['date_to']) {
                $where[]            = TB_CHAT . '.`updated_at` <= :date_to';
                $tokens[':date_to'] = $select['date_to'];
            }

            if (null !== $select['chat_id']) {
                $where[]            = TB_CHAT . '.`id` = :chat_id';
                $tokens[':chat_id'] = $select['chat_id'];
            }

            if (null !== $select['text']) {
                $text_like = '%' . strtolower($select['text']) . '%';
                if ($select['users']) {
                    $where[]          = '(
                        LOWER(' . TB_CHAT . '.`title`) LIKE :text1
                        OR LOWER(' . TB_USER . '.`first_name`) LIKE :text2
                        OR LOWER(' . TB_USER . '.`last_name`) LIKE :text3
                        OR LOWER(' . TB_USER . '.`username`) LIKE :text4
                    )';
                    $tokens[':text1'] = $text_like;
                    $tokens[':text2'] = $text_like;
                    $tokens[':text3'] = $text_like;
                    $tokens[':text4'] = $text_like;
                } else {
                    $where[]         = 'LOWER(' . TB_CHAT . '.`title`) LIKE :text';
                    $tokens[':text'] = $text_like;
                }
            }

            if (!empty($where)) {
                $query .= ' WHERE ' . implode(' AND ', $where);
            }

            $query .= ' ORDER BY ' . TB_CHAT . '.`updated_at` ASC';

            $sth = self::$pdo->prepare($query);
            $sth->execute($tokens);

            return $sth->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new TelegramException($e->getMessage());
        }
    }

    /**
     * Get Telegram API request count for current chat / message
     *
     * @param integer $chat_id
     * @param string  $inline_message_id
     *
     * @return array Array containing TOTAL and CURRENT fields or false on invalid arguments
     * @throws TelegramException
     */
    public static function getTelegramRequestCount($chat_id = null, $inline_message_id = null)
    {
        if (!self::isDbConnected()) {
            return [];
        }

        try {
            $sth = self::$pdo->prepare('SELECT
                (SELECT COUNT(DISTINCT `chat_id`) FROM `' . TB_REQUEST_LIMITER . '` WHERE `created_at` >= :created_at_1) AS LIMIT_PER_SEC_ALL,
                (SELECT COUNT(*) FROM `' . TB_REQUEST_LIMITER . '` WHERE `created_at` >= :created_at_2 AND ((`chat_id` = :chat_id_1 AND `inline_message_id` IS NULL) OR (`inline_message_id` = :inline_message_id AND `chat_id` IS NULL))) AS LIMIT_PER_SEC,
                (SELECT COUNT(*) FROM `' . TB_REQUEST_LIMITER . '` WHERE `created_at` >= :created_at_minute AND `chat_id` = :chat_id_2) AS LIMIT_PER_MINUTE
            ');

            $date        = self::getTimestamp();
            $date_minute = self::getTimestamp(strtotime('-1 minute'));

            $sth->bindValue(':chat_id_1', $chat_id);
            $sth->bindValue(':chat_id_2', $chat_id);
            $sth->bindValue(':inline_message_id', $inline_message_id);
            $sth->bindValue(':created_at_1', $date);
            $sth->bindValue(':created_at_2', $date);
            $sth->bindValue(':created_at_minute', $date_minute);

            $sth->execute();

            return $sth->fetch();
        } catch (PDOException $e) {
            throw new TelegramException($e->getMessage());
        }
    }

    /**
     * Insert Telegram API request in db
     *
     * @param string $method
     * @param array  $data
     *
     * @return bool If the insert was successful
     * @throws TelegramException
     */
    public static function insertTelegramRequest($method, $data)
    {
        if (!self::isDbConnected()) {
            return false;
        }

        try {
            $sth = self::$pdo->prepare('INSERT INTO `' . TB_REQUEST_LIMITER . '`
                (`method`, `chat_id`, `inline_message_id`, `created_at`)
                VALUES
                (:method, :chat_id, :inline_message_id, :created_at);
            ');

            $chat_id           = isset($data['chat_id']) ? $data['chat_id'] : null;
            $inline_message_id = isset($data['inline_message_id']) ? $data['inline_message_id'] : null;

            $sth->bindValue(':chat_id', $chat_id);
            $sth->bindValue(':inline_message_id', $inline_message_id);
            $sth->bindValue(':method', $method);
            $sth->bindValue(':created_at', self::getTimestamp());

            return $sth->execute();
        } catch (PDOException $e) {
            throw new TelegramException($e->getMessage());
        }
    }

    /**
     * Bulk update the entries of any table
     *
     * @param string $table
     * @param array  $fields_values
     * @param array  $where_fields_values
     *
     * @return bool
     * @throws TelegramException
     */
    public static function update($table, array $fields_values, array $where_fields_values)
    {
        if (empty($fields_values) || !self::isDbConnected()) {
            return false;
        }

        try {
            // Building parts of query
            $tokens = $fields = $where = [];

            // Fields with values to update
            foreach ($fields_values as $field => $value) {
                $token          = ':' . count($tokens);
                $fields[]       = "`{$field}` = {$token}";
                $tokens[$token] = $value;
            }

            // Where conditions
            foreach ($where_fields_values as $field => $value) {
                $token          = ':' . count($tokens);
                $where[]        = "`{$field}` = {$token}";
                $tokens[$token] = $value;
            }

            $sql = 'UPDATE `' . $table . '` SET ' . implode(', ', $fields);
            $sql .= count($where) > 0 ? ' WHERE ' . implode(' AND ', $where) : '';

            return self::$pdo->prepare($sql)->execute($tokens);
        } catch (PDOException $e) {
            throw new TelegramException($e->getMessage());
        }
    }
}
