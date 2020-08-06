<?php
use modava\affiliate\AffiliateModule;

return [
    'availableLocales' => [
        'vi' => 'Tiếng Việt',
        'en' => 'English',
        'jp' => 'Japan',
    ],
    'affiliateName' => 'Affiliate',
    'affiliateVersion' => '1.0',
    'status' => [
        '0' => AffiliateModule::t('affiliate', 'Tạm ngưng'),
        '1' => AffiliateModule::t('affiliate', 'Hiển thị'),
    ],
    'sex' => [
        '0' => AffiliateModule::t('affiliate', 'Female'),
        '1' => AffiliateModule::t('affiliate', 'Male'),
        '2' => AffiliateModule::t('affiliate', 'Other'),
    ],
    'promotion_type' => [
        '0' => AffiliateModule::t('affiliate', 'Discount Percent In Order'),
        '1' => AffiliateModule::t('affiliate', 'Discount Amount In Order'),
    ],
    'myauris_config' => [
        'uri' => "http://dashboard.myauris.vn/api/v2/affiliate/",
        'endpoint' => [
            'complete_customer_service' => "complete-customer-service",
            'list_thao_tac' => "list-thao-tac",
        ],
        'header' => ['X-Api-Key: qWnUiio9_xxRpExYzqSyzCqn3Gz3ZjP6jN_pxKUX'],
        'row_per_page' => 10
    ],
    'not_release_object' => [
        'Coupon',
        'CouponType',
        'Order',
    ]
];
