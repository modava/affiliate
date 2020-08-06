<?php

namespace modava\affiliate\models;

use common\helpers\MyHelper;
use common\models\User;
use modava\affiliate\AffiliateModule;
use modava\affiliate\models\table\CustomerTable;
use modava\location\models\LocationCountry;
use modava\location\models\LocationDistrict;
use modava\location\models\LocationProvince;
use modava\location\models\LocationWard;
use yii\behaviors\AttributeBehavior;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\SluggableBehavior;
use yii\db\ActiveRecord;
use modava\affiliate\helpers\Utils;

/**
* This is the model class for table "affiliate_customer".
*
    * @property int $id
    * @property string $slug
    * @property string $full_name Họ và tên Khách hàng
    * @property string $phone Số điện thoại - Không trùng
    * @property string $email Email khách hàng - không quan tâm trùng
    * @property string $face_customer Link facebook của KH
    * @property int $partner_id Partner tích hợp affiliate
    * @property int $partner_customer_id id Customer trên hệ thống partner
    * @property string $description Mô tả
    * @property int $created_at
    * @property int $updated_at
    * @property int $created_by Người gọi
    * @property int $updated_by
    * @property int $sex
    * @property date $birthday
    * @property date $date_checkin
    * @property date $date_accept_do_service
    *
            * @property AffiliateCoupon[] $affiliateCoupons
            * @property User $createdBy
            * @property User $updatedBy
            * @property AffiliatePartner $partner
            * @property AffiliateNote[] $affiliateNotes
    */
class Customer extends CustomerTable
{
    public $toastr_key = 'customer';

    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'slug' => [
                    'class' => SluggableBehavior::class,
                    'immutable' => false,
                    'ensureUnique' => true,
                    'value' => function () {
                        return MyHelper::createAlias($this->full_name);
                    }
                ],
                [
                    'class' => BlameableBehavior::class,
                    'createdByAttribute' => 'created_by',
                    'updatedByAttribute' => 'updated_by',
                ],
                'timestamp' => [
                    'class' => 'yii\behaviors\TimestampBehavior',
                    'preserveNonEmptyValues' => false,
                    'attributes' => [
                        ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                        ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
                    ],
                ],
                [
                    'class' => AttributeBehavior::class,
                    'attributes' => [
                        ActiveRecord::EVENT_BEFORE_INSERT => ['birthday'],
                        ActiveRecord::EVENT_BEFORE_UPDATE => ['birthday'],
                    ],
                    'value' => function ($event) {
                        return Utils::convertDateToDBFormat($this->birthday);
                    },
                ],
                [
                    'class' => AttributeBehavior::class,
                    'attributes' => [
                        ActiveRecord::EVENT_BEFORE_INSERT => ['date_checkin'],
                        ActiveRecord::EVENT_BEFORE_UPDATE => ['date_checkin'],
                    ],
                    'value' => function ($event) {
                        return Utils::convertDateToDBFormat($this->date_checkin);
                    },
                ],
                [
                    'class' => AttributeBehavior::class,
                    'attributes' => [
                        ActiveRecord::EVENT_BEFORE_INSERT => ['date_accept_do_service'],
                        ActiveRecord::EVENT_BEFORE_UPDATE => ['date_accept_do_service'],
                    ],
                    'value' => function ($event) {
                        return Utils::convertDateToDBFormat($this->date_accept_do_service);
                    },
                ],
            ]
        );
    }

    /**
    * {@inheritdoc}
    */
    public function rules()
    {
        return [
			[['full_name', 'phone', 'partner_id', 'partner_customer_id'], 'required'],
			[['partner_id', 'sex', 'partner_customer_id', 'country_id', 'province_id', 'district_id', 'ward_id'], 'integer'],
			[['description', 'address'], 'string'],
			[['full_name', 'email', 'face_customer'], 'string', 'max' => 255],
			[['phone'], 'string', 'max' => 15],
			[['slug', 'partner_customer_id', 'phone'], 'unique'],
			[['email'], 'email'],
			[['birthday', 'date_accept_do_service', 'date_checkin'], 'safe'],
			[['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['created_by' => 'id']],
			[['updated_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['updated_by' => 'id']],
			[['partner_id'], 'exist', 'skipOnError' => true, 'targetClass' => Partner::class, 'targetAttribute' => ['partner_id' => 'id']],
			[['country_id'], 'exist', 'skipOnError' => true, 'targetClass' => LocationCountry::class, 'targetAttribute' => ['country_id' => 'id']],
			[['province_id'], 'exist', 'skipOnError' => true, 'targetClass' => LocationProvince::class, 'targetAttribute' => ['province_id' => 'id']],
			[['district_id'], 'exist', 'skipOnError' => true, 'targetClass' => LocationDistrict::class, 'targetAttribute' => ['district_id' => 'id']],
			[['ward_id'], 'exist', 'skipOnError' => true, 'targetClass' => LocationWard::class, 'targetAttribute' => ['ward_id' => 'id']],
		];
    }

    /**
    * {@inheritdoc}
    */
    public function attributeLabels()
    {
        return [
            'id' => AffiliateModule::t('affiliate', 'ID'),
            'slug' => AffiliateModule::t('affiliate', 'Slug'),
            'full_name' => AffiliateModule::t('affiliate', 'Full Name'),
            'phone' => AffiliateModule::t('affiliate', 'Phone'),
            'email' => AffiliateModule::t('affiliate', 'Email'),
            'face_customer' => AffiliateModule::t('affiliate', 'Face Customer'),
            'partner_id' => AffiliateModule::t('affiliate', 'Partner ID'),
            'description' => AffiliateModule::t('affiliate', 'Description'),
            'created_at' => AffiliateModule::t('affiliate', 'Created At'),
            'updated_at' => AffiliateModule::t('affiliate', 'Updated At'),
            'created_by' => AffiliateModule::t('affiliate', 'Created By'),
            'updated_by' => AffiliateModule::t('affiliate', 'Updated By'),
            'birthday' => AffiliateModule::t('affiliate', 'Birthday'),
            'sex' => AffiliateModule::t('affiliate', 'Sex'),
            'partner_customer_id' => AffiliateModule::t('affiliate', 'Partner Customer Id'),
            'date_accept_do_service' => AffiliateModule::t('affiliate', 'Date Accept Do Service'),
            'date_checkin' => AffiliateModule::t('affiliate', 'Date Checkin'),
            'country_id' => AffiliateModule::t('affiliate', 'Country'),
            'province_id' => AffiliateModule::t('affiliate', 'Province'),
            'district_id' => AffiliateModule::t('affiliate', 'District'),
            'ward_id' => AffiliateModule::t('affiliate', 'Ward'),
            'address' => AffiliateModule::t('affiliate', 'Address'),
        ];
    }

    /**
    * Gets query for [[User]].
    *
    * @return \yii\db\ActiveQuery
    */
    public function getUserCreated()
    {
        return $this->hasOne(User::class, ['id' => 'created_by']);
    }

    /**
    * Gets query for [[User]].
    *
    * @return \yii\db\ActiveQuery
    */
    public function getUserUpdated()
    {
        return $this->hasOne(User::class, ['id' => 'updated_by']);
    }

    public function getPartner() {
        return $this->hasOne(Partner::class, ['id' => 'partner_id']);
    }

    public function getNotes() {
        return $this->hasMany(Note::class, ['customer_id' => 'id']);
    }

    public function getCoupons() {
        return $this->hasMany(Coupon::class, ['customer_id' => 'id']);
    }

    public function getCountry() {
        return $this->hasOne(LocationCountry::class, ['id' => 'country_id']);
    }

    public function getProvince() {
        return $this->hasOne(LocationProvince::class, ['id' => 'province_id']);
    }

    public function getDistrict() {
        return $this->hasOne(LocationDistrict::class, ['id' => 'district_id']);
    }

    public function getWard() {
        return $this->hasOne(LocationWard::class, ['id' => 'ward_id']);
    }
}