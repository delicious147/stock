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
    .red{color:#c63c26 }
    .box{ padding:10px;}
</style>

<div class="deal2-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Deal', ['create'], ['class' => 'btn btn-success']) ?>
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

    <?php
    $stock_map=\common\models\Stock::getMap();
    $stock_code=\common\models\Stock::getCode();
    ?>
    <div class="row clearfix">
        <div class="col-md-12 column">
            <div class="row clearfix">

                <div class="col-md-6 column">
                    <h3>Sell price</h3>
                    <?= GridView::widget([
                        'dataProvider' => $provider_sell,
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
                            'price',
                            [
                                'label'=>'Now Price',
                                'format'=>'raw',
                                'value' => function (){
                                    return '';
                                },
                                'contentOptions' => function ($model){
                                    return[
                                        'class'=>[$model['stock']['full_code'],'gray'],
                                    ] ;
                                },
                            ],
                            '1%_price',
                            '2%_price',
                            '4%_price',
                        ],
                    ]); ?>
                </div>

                <div class="col-md-6 column">
                    <h3>Buy price</h3>
                    <?= GridView::widget([
                        'dataProvider' => $provider_buy,
                        'options' => ['class'=>'buy_box'],
                        'columns' => [
                            [
                                'label'=>'Name',
                                'format'=>'raw',
                                'value' => function ($model) {
                                    return Html::a($model['stock']['stock_name'],'/deal-stock/index?stock_id='.$model['stock']['id']);
//                                    return $model['stock']['stock_name'];
                                },
                            ],
//                            'name',
                            'price',
                            [
                                'label'=>'Now Price',
                                'format'=>'raw',
                                'value' => function (){
                                    return '';
                                },
                                'contentOptions' => function ($model){
                                    return[
                                        'class'=>[$model['stock']['full_code'],'gray'],
                                    ] ;
                                },
                            ],
                            '1%_price',
                            '2%_price',
                            '4%_price',
                        ],
                    ]); ?>
                </div>

            </div>
        </div>
    </div>

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
//            ['class' => 'yii\grid\SerialColumn'],
            [
                'format'=>'raw',
                'label' => 'stock Name',
                'filter' => \common\models\Deal2::getMap(),
                'value' => function ($model) {
                    return $model->stock->stock_name;
                }
            ],
//            'stock_id',
            'price',
//            'num',
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
            'date',
        ],
    ]); ?>
</div>


<?php
$this->registerJsFile("http://hq.sinajs.cn/list=$stock");
$js = <<<JS
    var stock_str='$stock'
    var stock_arr = stock_str.split(",");
    for(let i=0;i<stock_arr.length;i++){
       var elements=eval("hq_str_"+stock_arr[i]).split(",")
       $('.'+stock_arr[i]).html(elements[3])
       // console.log($('.'+stock_arr[i]).next().html())
       if(Number($('.sell_box .'+stock_arr[i]).html())>Number($('.sell_box .'+stock_arr[i]).next().html())){
           $('.sell_box .'+stock_arr[i]).addClass('red')
       }
       if(Number($('.buy_box .'+stock_arr[i]).html())<Number($('.buy_box .'+stock_arr[i]).next().html())){
           $('.buy_box .'+stock_arr[i]).addClass('red')
       }
   }
   
   
JS;
$this->registerJs($js);

?>