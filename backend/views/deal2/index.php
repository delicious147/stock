<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\Deal2Search */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Deal2s';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="deal2-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Deal', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

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
            'stock_id',
            'price',
            'num',
            'date',
            //'remark',
            'status',
            'is_sell',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
