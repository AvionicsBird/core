<?php
namespace Longman\TelegramBot\Models;


use Illuminate\Database\Eloquent\Model;

class BaseTelegramModel extends Model
{
    /* Table Name is stored in Configuration config.data.<filename> */
    public function getTable()
    {
        $class_name = strtolower(class_basename($this));
        $table_name = config('telegram.database.'.$class_name);
        return $table_name;
    }
}
