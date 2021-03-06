<?php

namespace modava\affiliate\models\table;

use cheatsheet\Time;
use modava\affiliate\models\query\UnsatisfiedReasonQuery;
use Yii;
use yii\db\ActiveRecord;

class UnsatisfiedReasonTable extends \yii\db\ActiveRecord
{
    const STATUS_DISABLED = 0;
    const STATUS_PUBLISHED = 1;
    const CACHE_KEY_GET_ALL = 'redis-affiliate-unsatisfied-reason-get-all';
    const CACHE_KEY_GET_ALL_ACTIVE = 'redis-affiliate-unsatisfied-reason-get-all-active';

    public static function tableName()
    {
        return 'affiliate_unsatisfied_reason';
    }

    public static function find()
    {
        return new UnsatisfiedReasonQuery(get_called_class());
    }

    public function afterDelete()
    {
        $cache = Yii::$app->cache;
        $keys = [
            self::CACHE_KEY_GET_ALL,
            self::CACHE_KEY_GET_ALL_ACTIVE,
        ];
        foreach ($keys as $key) {
            $cache->delete($key);
        }
        return parent::beforeDelete(); // TODO: Change the autogenerated stub
    }

    public function afterSave($insert, $changedAttributes)
    {
        $cache = Yii::$app->cache;
        $keys = [
            self::CACHE_KEY_GET_ALL,
            self::CACHE_KEY_GET_ALL_ACTIVE,
        ];
        foreach ($keys as $key) {
            $cache->delete($key);
        }
        parent::afterSave($insert, $changedAttributes); // TODO: Change the autogenerated stub
    }

    public static function getAllRecords()
    {
        $cache = Yii::$app->cache;
        $data = $cache->get(self::CACHE_KEY_GET_ALL);
        if (!$data) {
            $data = self::find()->all();
            $cache->set(self::CACHE_KEY_GET_ALL, $data, Time::SECONDS_IN_A_YEAR);
        }
        return $data;
    }

    /**
     * Lấy danh sách record hoạt động
     * @return array|mixed|ActiveRecord[]
     */
    public static function getAllRecordsActive()
    {
        $cache = Yii::$app->cache;
        $data = $cache->get(self::CACHE_KEY_GET_ALL_ACTIVE);
        if (!$data) {
            $data = self::find()->where(['status' => 1])->all();
            $cache->set(self::CACHE_KEY_GET_ALL_ACTIVE, $data, Time::SECONDS_IN_A_YEAR);
        }
        return $data;
    }
}
