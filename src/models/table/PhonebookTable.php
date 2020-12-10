<?php

namespace modava\affiliate\models\table;

use backend\components\MyModel;
use modava\affiliate\models\query\PhonebookQuery;
use Yii;

class PhonebookTable extends MyModel
{
    const STATUS_DISABLED = 0;
    const STATUS_PUBLISHED = 1;

    public static function tableName()
    {
        return 'affiliate_phonebook';
    }

    public static function find()
    {
        return new PhonebookQuery(get_called_class());
    }

    public function afterDelete()
    {
        $cache = Yii::$app->cache;
        $keys = [];
        foreach ($keys as $key) {
            $cache->delete($key);
        }
        return parent::beforeDelete(); // TODO: Change the autogenerated stub
    }

    public function afterSave($insert, $changedAttributes)
    {
        $cache = Yii::$app->cache;
        $keys = [];
        foreach ($keys as $key) {
            $cache->delete($key);
        }
        parent::afterSave($insert, $changedAttributes); // TODO: Change the autogenerated stub
    }
}
