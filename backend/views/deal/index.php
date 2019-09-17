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
<style>
    .deal-index .red{color: #a94442;}
    .gray{color: #ccc;}
</style>
<div class="deal-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Deal', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
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
                                'label'=>'Name',
                                'format'=>'raw',
                                'value' => function ($model) {
                                    return $model['stock']['stock_name'];
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
                                    return $model['stock']['stock_name'];
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
                            '2%_price',
                            '4%_price',
                        ],
                    ]); ?>
                </div>
            </div>
        </div>
    </div>

    <h3>Deal list</h3>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
//        'filterModel' => $searchModel,
        'columns' => [
//            'stock_name',
            [
                'label'=>'Name',
                'format'=>'raw',
                'value' => function ($model) use($stock_map) {
                    return isset($stock_map[$model['stock_id']])?$stock_map[$model['stock_id']]:'';
                },
            ],
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




<?php
$this->registerJsFile("http://hq.sinajs.cn/list=$stock");
$js = <<<JS
    var stock_str='$stock'
    var stock_arr = stock_str.split(",");
    for(let i=0;i<stock_arr.length;i++){
       var elements=eval("hq_str_"+stock_arr[i]).split(",")
       $('.'+stock_arr[i]).html(elements[3])
       // console.log($('.'+stock_arr[i]).next().html())
       if($('.sell_box .'+stock_arr[i]).next().html()<$('.sell_box .'+stock_arr[i]).html()){
           $('.sell_box .'+stock_arr[i]).addClass('red')
       }
       if($('.buy_box .'+stock_arr[i]).next().html()>$('.buy_box .'+stock_arr[i]).html()){
           $('.buy_box .'+stock_arr[i]).addClass('red')
       }
   }
   
   
JS;
$this->registerJs($js);

?>


