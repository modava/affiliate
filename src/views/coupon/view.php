<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\DetailView;
use backend\widgets\ToastrWidget;
use modava\affiliate\widgets\NavbarWidgets;
use modava\affiliate\AffiliateModule;

/* @var $this yii\web\View */
/* @var $model modava\affiliate\models\Coupon */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Coupons'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<?= ToastrWidget::widget(['key' => 'toastr-' . $model->toastr_key . '-view']) ?>
<div class="container-fluid px-xxl-25 px-xl-10">
    <?= NavbarWidgets::widget(); ?>

    <!-- Title -->
    <div class="hk-pg-header">
        <h4 class="hk-pg-title"><span class="pg-title-icon"><span
                        class="ion ion-md-apps"></span></span><?= Html::encode($this->title) ?>
        </h4>
        <p>
            <a class="btn btn-outline-light btn-sm" href="<?= Url::to(['create']); ?>"
                title="<?= Yii::t('backend', 'Create'); ?>">
                <i class="fa fa-plus"></i> <?= Yii::t('backend', 'Create'); ?></a>
            <?= Html::a(Yii::t('backend', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-sm btn-primary']) ?>
            <?= $model->count_sms_sent > 0 ? '' : Html::a(Yii::t('backend', 'Delete'), ['delete', 'id' => $model->id], [
                'class' => 'btn btn-danger btn-sm',
                'data' => [
                    'confirm' => Yii::t('backend', 'Are you sure you want to delete this item?'),
                    'method' => 'post',
                ],
            ]) ?>
        </p>
    </div>
    <!-- /Title -->

    <!-- Row -->
    <div class="row">
        <div class="col-xl-12">
            <section class="hk-sec-wrapper">
                <?= DetailView::widget([
                    'model' => $model,
                    'attributes' => [
						'title',
						'coupon_code',
						'quantity',
                        'quantity_used',
						[
						    'attribute' => 'expired_date',
                            'value' => function ($model) {
                                return $model->expired_date
                                    ? date('d-m-Y', strtotime($model->expired_date))
                                    : '';
                            }
                        ],
                        [
                            'attribute' => 'customer_id',
                            'format' => 'raw',
                            'value' => function ($model) {
                                return $model->customer_id ? Html::a($model->customer->full_name, Url::toRoute(['/affiliate/customer/view', 'id' => $model->customer_id])) : '';
                            }
                        ],
						[
						    'attribute'   => 'coupon_type_id',
                            'format' => 'raw',
                            'value' => function ($model) {
                                return $model->coupon_type_id ? Html::a($model->couponType->title, Url::toRoute(['/affiliate/coupon-type/view', 'id' => $model->coupon_type_id])) : '';
                            }
                        ],
                        [
                            'attribute' => 'promotion_type',
                            'value' => function ($model) {
                                return Yii::t('backend', Yii::$app->getModule('affiliate')->params["promotion_type"][$model->promotion_type]);
                            }
                        ],
						'min_discount',
						'max_discount',
                        'promotion_value',
						'commission_for_owner',
                        'count_sms_sent',
                        [
                            'attribute' => 'commissionFor.userProfile.fullname',
                            'label' => Yii::t('backend', 'Hoa hồng cho sales')
                        ],
						'created_at:datetime',
						'updated_at:datetime',
                        [
                            'attribute' => 'userCreated.userProfile.fullname',
                            'label' => Yii::t('backend', 'Created By')
                        ],
                        [
                            'attribute' => 'userUpdated.userProfile.fullname',
                            'label' => Yii::t('backend', 'Updated By')
                        ],
                        'description:raw',
                    ],
                ]) ?>
            </section>
        </div>
    </div>
</div>
