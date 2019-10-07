<?php
namespace Longman\TelegramBot\Models;

use Illuminate\Database\Eloquent\Model;
use Longman\TelegramBot\Entities\Chat as ChatEntity;
use Longman\TelegramBot\Entities\User as UserEntity;
use Longman\TelegramBot\Entities\Entity;
use Longman\TelegramBot\Exception\TelegramException;

class BaseTelegramModel extends Model
{
    /* Table Name is stored in Configuration config.data.<filename> */
    private $raw_data;
    /** @var BaseTelegramModel $origin */
    protected $origin;
    protected $entity;
    protected $subEntities;
    protected $current_key;

    /**
     * Create a new Eloquent model instance
     * @param BaseTelegramModel $origin
     * @param array $attributes
     * @return void
     */
    public function __construct(array $attributes = [], BaseTelegramModel &$origin = null, $save = false)
    {
        $this->origin = $origin;
        $this->current_key = null;
        $this->bootIfNotBooted();
        $this->initializeTraits();
        $this->syncOriginal();
        // Filter Out Items that shouldn't be set in the db
        $just_attributes = array_diff_key($just_attributes, $attributes);
        $just_attributes = array_merge($just_attributes, array_intersect_key($attributes, $just_attributes));
        $this->fill($just_attributes);
        $this->consume($attributes, $save);
        $this->InitializeOrigin();
    }

    /**
     * Reach Back around the origin object if necesarry.
     */
    public function InitializeOrigin()
    {
    }

    public function Origin()
    {
        return $this->origin;
    }

    public function getTable()
    {
        $class_name = strtolower(class_basename($this));
        $table_name = config('telegram.database.'.$class_name);
        return $table_name;
    }

    public function Entity()
    {
        return $this->entity;
    }

    public function __call($method, $paramaters)
    {
        // Check for Special Entity getter methods..
        $entity_name = strtolower(ltrim(preg_replace('/[A-Z]/', '_$0', substr($method, 3)), '_'));
        $property_name = 'data_' . $entity_name;
        $action = substr($method, 0, 3);

        if ($action == "get") {
            if (isset($this->subEntities[$entity_name])) {
                $class = $this->subEntities[$entity_name];
                /** @var BaseTelegramModel $class_obj */
                $class_obj = new $class($this->$property_name, $this, true);
                return $class_obj;
            }
        }
        return parent::__call($method, $paramaters);
    }

    /**
     * Types that have special consumption cases may override this
     * @param Entity|array $data
     */
    public function consume($data, $save = true)
    {
        if (is_object($data)) {
            $this->entity = $data;
        }

        // If its an object we are consuming an entity, otherwise we
        // are consuming raw data
        $content = is_object($data) ? $data->getRawData() : $data;
        $this->raw_data = $content;
        >
        $this->assignMemberVariables($content);

        // Loop through entities and do subentity setups..
        foreach ($this->subEntities() as $sbntnm => $se_intlzr) {
            $this->setup_sub_entity($sbntnm, $se_intlzr);
        }

        if ($this->consume_presave() and $save) {
            $this->save();
        }
    }

    public function SetOrigin(&$origin)
    {
        $this->origin = $origin;
    }

    public function GetOrigin()
    {
        return $this->origin;
    }

    protected function setup_sub_entity($entity_key, $class)
    {
        try {
            $key_id = $entity_key . '_id';
            $pre_init_call = 'preinit_' . $entity_key;
            if (method_exists($this, $pre_init_call)) {
                $this->$pre_init_call($entity_key);
            }
            if ((is_array($class) and $class[0] instanceof BaseTelegramModel and $class[1] == "find")) {
                // Retrieve the Raw Property Value
                $data = $this->getProperty($entity_key);
                /** @var BaseTelegramModel $this ->$key */
                $this->$entity_key = $class[0]::where([$class[0]::getKeyName(), $data[$class[0]::getKeyName()]]);
                if ($this->$entity_key != null) {
                    if ($this->$entity_key instanceof BaseTelegramModel) {
                        $this->$entity_key->consume($this->getProperty($entity_key));
                    }
                } else {  // Can't find it continue with creating a new guy by resetting the arrayishness
                    $class = $class[0];
                }
            }

            if ($this->$entity_key === null) {
                if ($class instanceof BaseTelegramModel) {
                    $this->$entity_key = new $class($this->getProperty($entity_key), $this, true);
                } else {
                    $this->$entity_key = new $class($this->getProperty($entity_key));
                }
            }
            if (in_array($key_id, $this->attributes)) {
                $this->$key_id = $this->$entity_key->getId();
            }
            $post_init_call = 'postinit_' . $entity_key;
            if (method_exists($this, $post_init_call)) {
                $this->$post_init_call($entity_key, $this->$entity_key);
            }
        } catch (TelegramException $e) {
            return;
        }
    }

    public function getRawData()
    {
        return isset($this->raw_data) ? $this->raw_data : array();
    }

    /**
     * Great place to put any special logic but not needing to completely overwrite the
     * primary consume function
     * @return bool
     */
    protected function consume_presave()
    {
        return true;
    }

    /**
     * @param $user
     * @return mixed|void
     */
    protected function consume_user($user)
    {
        if (($user instanceof UserEntity)) {
            $found_user = User::where(['id', $user->getId()])->get()->first();
            if (!$found_user) {
                $found_user = new User();
            }
            $this->from = $found_user->consume($user);
            if (in_array('user_id', $this->fillable)) {
                $this->User = $this->from;
            }
        }
        return $this->User;
    }

    protected function consume_chat($chat)
    {

    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo|null
     */
    public function User()
    {
        if ($this->user_id) {
            return $this->belongsTo('Longman\TelegramBot\Models\User');
        }
        return null;
    }

    /**
     * Perform to json
     *
     * @return string
     */
    public function RawToJson()
    {
        return json_encode($this->getRawData());
    }


    /**
     * Helper to set member variables
     *
     * @param array $data
     */
    protected function assignMemberVariables(array $data)
    {

        foreach ($data as $key => $value) {
            $data_key = 'data_' . $key;
            $this->$data_key = $value;
        }
    }

    /**
     * Return an array of nice objects from an array of object arrays
     *
     * This method is used to generate pretty object arrays
     * mainly for PhotoSize and Entities object arrays.
     *
     * @param string $class
     * @param string $property
     *
     * @return array
     */
    protected function makePrettyObjectArray($class, $property)
    {
        $new_objects = [];

        try {
            if ($objects = $this->getProperty($property)) {
                foreach ($objects as $object) {
                    if (!empty($object)) {
                        $new_objects[] = new $class($object);
                    }
                }
            }
        } catch (Exception $e) {
            $new_objects = [];
        }

        return $new_objects;
    }

    /**
     * Escape markdown special characters
     *
     * @param string $string
     *
     * @return string
     */
    public function escapeMarkdown($string)
    {
        return str_replace(
            ['[', '`', '*', '_',],
            ['\[', '\`', '\*', '\_',],
            $string
        );
    }

    /**
     * Try to mention the user
     *
     * Mention the user with the username otherwise print first and last name
     * if the $escape_markdown argument is true special characters are escaped from the output
     *
     * @param bool $escape_markdown
     *
     * @return string|null
     */
    public function tryMention($escape_markdown = false)
    {
        //TryMention only makes sense for the User and Chat entity.
        if (!($this instanceof UserEntity || $this instanceof ChatEntity)) {
            return null;
        }

        //Try with the username first...
        $name = $this->username;
        $is_username = $name !== null;

        if ($name === null) {
            //...otherwise try with the names.
            $name = $this->first_name;
            $last_name = $this->last_name;
            if ($last_name !== null) {
                $name .= ' ' . $last_name;
            }
        }

        if ($escape_markdown) {
            $name = $this->escapeMarkdown($name);
        }

        return ($is_username ? '@' : '') . $name;
    }

    /**
     * Get a property data_field
     *
     * @param mixed $property
     * @param mixed $default
     *
     * @return mixed
     */
    public function getProperty($property, $default = null)
    {
        $data_field = 'data_' . $property;
        if (isset($this->$data_field)) {
            return $this->$data_field;
        }

        return $default;
    }

    /**
     * Convert array of Entity items to a JSON array
     *
     * @param array|null $entities
     * @param mixed $default
     *
     * @return mixed
     * @todo Find a better way, as json_* functions are very heavy
     *
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

    public function humanize_type($string)
    {
        $listify = explode('_', $string);
        $stringify = "";

        foreach ($listify as $item) {
            if ($item == "id")
                break;
            $stringify .= strtoupper($item[0]);
            $stringify .= substr($item, 1);
        }

        return $stringify;
    }
}
