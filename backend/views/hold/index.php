<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\Deal2Search */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Index';
$this->params['breadcrumbs'][] = $this->title;
?>
<style>
    .deal-index .red{color: #a94442;}
    .gray{color: #ccc;}
    .box{ padding:10px;}
</style>

<div class="deal2-index">

    <h3>持仓</h3>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('新建订单', ['/deal2/create'], ['class' => 'btn btn-success']) ?>
    </p>

    <div class="row clearfix">
        <div class="col-md-12 column">
            <div class="box center-block bg-success">
                <div class="row clearfix">
                    <div class="col-md-6 col-xs-6 column">
                        <p>获利</p>
                        <h4><?= $win_money.' &nbsp;&nbsp;&nbsp;('. \common\components\MyHelper::makePercentage($win_money,$in_money).')'?></h4>
                    </div>
                    <div class="col-md-6 col-xs-6 column">
                        <p>在场</p>
                        <h4><?= $in_money?></h4>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <div class="row clearfix">
        <div class="col-md-12 column">
            <div class="row clearfix">
                <div class="col-md-4 column">
                    <h3>Have Num</h3>
                    <?= GridView::widget([
                        'dataProvider' => $provider_stock,
                        'options' => ['class'=>'sell_box'],
                        'columns' => [
                            [
                                'class' => 'yii\grid\ActionColumn',
                                'header' => Yii::t('app', 'OPERATE'),
                                'template' => '{daily}',
                                'buttons' => [
                                    'daily'=>function ($url, $model, $key) {
                                        return Html::a($model['stock']['stock_name'],'/deal-stock/index?stock_id='.$model['stock']['id']);
                                    },

                                ],
                            ],
                            'count',

                        ],
                    ]); ?>
                </div>
            </div>
        </div>
    </div>


