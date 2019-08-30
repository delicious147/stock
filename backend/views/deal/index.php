<?php

use yii\helpers\Html;
use yii\grid\GridView;
//use kartik\grid\GridView;
use kartik\editable\Editable;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\DealSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Deals';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="deal-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Deal', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <div class="row clearfix">
        <div class="col-md-12 column">
            <div class="row clearfix">
                <div class="col-md-6 column">
                    <?= GridView::widget([
                        'dataProvider' => $provider_sell,
                        'columns' => [
                            'name',
                            'price',
                            '2%_price',
                            '4%_price',
                        ],
                    ]); ?>
                </div>
                <div class="col-md-6 column">
                    <?= GridView::widget([
                        'dataProvider' => $provider_buy,
                        'columns' => [
                            'name',
                            'price',
                            '2%_price',
                            '4%_price',
                        ],
                    ]); ?>
                </div>
            </div>
        </div>
    </div>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
//        'filterModel' => $searchModel,
        'columns' => [
            'name',
            'price',
            'num',
//            'date',
//            'sell_price',
            [
                'label'=>'Sell Price',
                'format'=>'raw',
                'value' => function ($model) {
                    return \kartik\editable\Editable::widget([
                        'name' => 'sell_price',
                        'value' => $model['sell_price'],
//                        'attribute' => 'sell_price',
                        'formOptions' => [
                            'method' => 'post',
                            'action' => Yii::$app->urlManager->createAbsoluteUrl(['deal/sell', 'id' => $model['id']])
                        ],
                    ]);
                },
            ],
//            'sell_date',
            'win_money',
            '1%_price',
            '2%_price',
            '3%_price',
            '4%_price',
            '5%_price',
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
