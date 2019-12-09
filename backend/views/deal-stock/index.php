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

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Deal', '/deal-stock/create?stock_id='.$searchModel->stock_id, ['class' => 'btn btn-success']) ?>
    </p>


    <div class="row clearfix">
        <div class="col-md-3 column">
            <div class="box center-block bg-success">
                <p>Win money</p>
                <h4><?= $win_money?></h4>
            </div>
        </div>

        <div class="col-md-3 column">
            <div class="box center-block bg-success">
                <p>In money</p>
                <h4><?= $in_money?></h4>
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

    <?php
    $stock_map=\common\models\Stock::getMap();
    $stock_code=\common\models\Stock::getCode();
    ?>

    <h3>List</h3>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'rowOptions' => function($model, $key, $index, $grid) {
            if ($model['status']=='0'){
                return ['style' => 'color:#c63c26'];  //红色
            }elseif ($model['status']=='1'){
                return ['style' => 'color:#5c7a29'];  //绿色
            }
        },
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'format'=>'raw',
                'label' => 'stock Name',
                'value' => function ($model) {
                    return $model->stock->stock_name;
                }
            ],
            'price',
            'num',
            'date',
            //'remark',
            [
                'attribute' => 'status',
                'filter' => \common\models\Deal2::getMap(),
                'value' => function ($model) {
                    $state_txt = \common\models\Deal2::getMap();
                    return $state_txt[$model->status];
                }

            ],
            [
                'attribute' => 'is_sell',
                'filter' => \common\models\Deal2::getIsSellMap(),
                'value' => function ($model) {
                    $state_txt = \common\models\Deal2::getIsSellMap();
                    return $state_txt[$model->is_sell];
                }

            ],
        ],
    ]); ?>
</div>

