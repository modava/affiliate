<?php

use backend\widgets\ToastrWidget;
use common\grid\MyGridView;
use modava\affiliate\widgets\NavbarWidgets;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel modava\affiliate\models\search\SmsLogSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('backend', 'Sms Logs');
$this->params['breadcrumbs'][] = $this->title;
?>
    <div class="container-fluid px-xxl-15 px-xl-10">
        <?= NavbarWidgets::widget(); ?>

        <!-- Row -->
        <div class="row">
            <div class="col-xl-12">
                <?= $this->render('_search', ['model' => $searchModel]); ?>
                <section class="hk-sec-wrapper index">
                    <?php Pjax::begin(['id' => 'dt-pjax', 'timeout' => false, 'enablePushState' => true, 'clientOptions' => ['method' => 'GET']]); ?>
                    <?= ToastrWidget::widget(['key' => 'toastr-' . $searchModel->toastr_key .
                        '-index']) ?>
                    <div class="row">
                        <div class="col-sm">
                            <div class="table-wrap">
                                <div class="dataTables_wrapper dt-bootstrap4">
                                    <?= MyGridView::widget([
                                        'dataProvider' => $dataProvider,
                                        'filterModel' => $searchModel,
                                        'layout' => '
                                    {errors}
                                    <div class="pane-single-table">
                                        {items}
                                    </div>
                                    <div class="pager-wrap clearfix">
                                        {summary}' .
                                            Yii::$app->controller->renderPartial('@backend/views/layouts/my-gridview/_pageTo',
                                                [
                                                    'totalPage' => $totalPage,
                                                    'currentPage' =>
                                                        Yii::$app->request->get($dataProvider->getPagination()->pageParam)
                                                ]) .
                                            Yii::$app->controller->renderPartial('@backend/views/layouts/my-gridview/_pageSize')
                                            .
                                            '{pager}
                                    </div>
                                    ',
                                        'tableOptions' => [
                                            'id' => 'dataTable',
                                            'class' => 'dt-grid dt-widget pane-hScroll',
                                        ],
                                        'myOptions' => [
                                            'class' => 'dt-grid-content my-content pane-vScroll',
                                            'data-minus' => '{"0":105,"1":".hk-navbar","2":".nav-tabs","3":".hk-pg-header","4":".hk-footer-wrap"}'
                                        ],
                                        'summaryOptions' => [
                                            'class' => 'summary pull-right',
                                        ],
                                        'pager' => [
                                            'firstPageLabel' => Yii::t('backend', 'First'),
                                            'lastPageLabel' => Yii::t('backend', 'Last'),
                                            'prevPageLabel' => Yii::t('backend', 'Previous'),
                                            'nextPageLabel' => Yii::t('backend', 'Next'),
                                            'maxButtonCount' => 5,

                                            'options' => [
                                                'tag' => 'ul',
                                                'class' => 'pagination pull-left',
                                            ],

                                            // Customzing CSS class for pager link
                                            'linkOptions' => ['class' => 'page-link'],
                                            'activePageCssClass' => 'active',
                                            'disabledPageCssClass' => 'disabled page-disabled',
                                            'pageCssClass' => 'page-item',

                                            // Customzing CSS class for navigating link
                                            'prevPageCssClass' => 'paginate_button page-item prev',
                                            'nextPageCssClass' => 'paginate_button page-item next',
                                            'firstPageCssClass' => 'paginate_button page-item first',
                                            'lastPageCssClass' => 'paginate_button page-item last',
                                        ],
                                        'columns' => [
                                            [
                                                'class' => 'yii\grid\SerialColumn',
                                                'header' => 'STT',
                                                'headerOptions' => [
                                                    'width' => 60,
                                                    'rowspan' => 2
                                                ],
                                                'filterOptions' => [
                                                    'class' => 'd-none',
                                                ],
                                            ],
                                            [
                                                'attribute' => 'message',
                                                'format' => 'raw',
                                                'value' => function ($model) {
                                                    return Html::a($model->message, Url::toRoute(['/affiliate/sms-log/view', 'id' => $model->primaryKey]));
                                                },

                                                'headerOptions' => [
                                                    'class' => 'header-300'
                                                ],
                                            ],
                                            [
                                                'attribute'=>'to_number',
                                                'label' => Yii::t('backend', 'Thông tin'),
                                                'format' => 'raw',
                                                'value' => function ($model) {
                                                    $content = '<strong>Gửi đến: </strong>' . $model->to_number . '<br/>';
                                                    $content .= '<strong>Khách hàng: </strong>' . ($model->customer_id ? Html::a($model->customer->full_name, Url::toRoute(['/affiliate/customer/view', 'id' => $model->customer_id])) : '') . '<br/>';
                                                    $content .= '<strong>Tình trạng: </strong>' . Yii::$app->getModule('affiliate')->params['sms_log_status'][$model->status] . '<br/>';

                                                    return $content;
                                                },
                                                'headerOptions' => [
                                                        'class' => 'header-300'
                                                ]
                                            ],
                                            [
                                                'attribute' => 'response_log',
                                                'format' => 'ntext',
                                                'headerOptions' => [
                                                    'class' => 'header-400'
                                                ],
                                                'contentOptions' => [
                                                    'class' => 'header-400'
                                                ]
                                            ],
                                            [
                                                'attribute' => 'request_log',
                                                'format' => 'ntext',
                                                'headerOptions' => [
                                                    'class' => 'header-400'
                                                ],
                                                'contentOptions' => [
                                                    'class' => 'header-400',
                                                ]
                                            ],
                                            [
                                                'attribute' => 'created_by',
                                                'value' => 'userCreated.userProfile.fullname',
                                                'headerOptions' => [
                                                    'width' => 150,
                                                ],
                                            ],
                                            [
                                                'attribute' => 'created_at',
                                                'format' => 'date',
                                                'headerOptions' => [
                                                    'width' => 150,
                                                ],
                                            ],
                                            /*[
                                                'class' => 'yii\grid\ActionColumn',
                                                'header' => Yii::t('backend', 'Actions'),
                                                'template' => '{update} {delete}',
                                                'buttons' => [
                                                    'update' => function ($url, $model) {
                                                        return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, [
                                                            'title' => Yii::t('backend', 'Update'),
                                                            'alia-label' => Yii::t('backend', 'Update'),
                                                            'data-pjax' => 0,
                                                            'class' => 'btn btn-info btn-xs'
                                                        ]);
                                                    },
                                                    'delete' => function ($url, $model) {
                                                        return Html::a('<span class="glyphicon glyphicon-trash"></span>', 'javascript:;', [
                                                            'title' => Yii::t('backend', 'Delete'),
                                                            'class' => 'btn btn-danger btn-xs btn-del',
                                                            'data-title' => Yii::t('backend', 'Delete?'),
                                                            'data-pjax' => 0,
                                                            'data-url' => $url,
                                                            'btn-success-class' => 'success-delete',
                                                            'btn-cancel-class' => 'cancel-delete',
                                                            'data-placement' => 'top'
                                                        ]);
                                                    }
                                                ],
                                                'headerOptions' => [
                                                    'width' => 150,
                                                ],
                                            ],*/
                                        ],
                                    ]); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php Pjax::end(); ?>
                </section>
            </div>
        </div>
    </div>
<?php
$urlChangePageSize = \yii\helpers\Url::toRoute(['perpage']);
$script = <<< JS
$('body').on('click', '.success-delete', function(e){
e.preventDefault();
var url = $(this).attr('href') || null;
if(url !== null){
$.post(url);
}
return false;
});
var customPjax = new myGridView();
customPjax.init({
pjaxId: '#dt-pjax',
urlChangePageSize: '$urlChangePageSize',
});
JS;
$this->registerJs($script, \yii\web\View::POS_END);