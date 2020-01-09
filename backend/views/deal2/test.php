<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\Deal2Search */
/* @var $dataProvider yii\data\ActiveDataProvider */

?>


<div class="deal2-index">
    <h3>List</h3>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
//            ['class' => 'yii\grid\SerialColumn'],
            [
                'format'=>'raw',
                'label' => 'stock Name',
                'value' => function ($model) {
                    return $model->stock->stock_name;
//                    return print_r($model->stock,true);
                }
            ],
            [
                'format'=>'raw',
                'label' => 'test',
                'value' => function ($model) {
                    return $model->test;
//                    return print_r($model,true);
                }
            ],
            'price',
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
            'date',
        ],
    ]); ?>
</div>

