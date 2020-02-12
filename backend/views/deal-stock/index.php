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

    <h3><?= $stock['stock_name'] ?></h3>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Deal', '/deal-stock/create?stock_id='.$searchModel->stock_id, ['class' => 'btn btn-success']) ?>
    </p>

    <div class="row clearfix">
        <div class="col-md-3 column">
            <div class="box center-block bg-success">
                <p>Win money</p>
                <h4><?= $win_money.' &nbsp;&nbsp;&nbsp;('. \common\components\MyHelper::makePercentage($win_money,$in_money).')'?></h4>
            </div>
        </div>

        <div class="col-md-3 column">
            <div class="box center-block bg-success">
                <p>In money</p>
                <h5><?= $in_money?></h5>
            </div>
        </div>

    </div>
    <div class="row clearfix">
        <div class="col-md-12 column">
            <h4>现价：<span id="now_price"></span></h4>
            <div id="echarts_pic" style="height: 220px;padding-top: 10px;">
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
//            ['class' => 'yii\grid\SerialColumn'],
            [
                'format'=>'raw',
                'label' => 'stock Name',
                'value' => function ($model) {
                    return $model->stock->stock_name;
                }
            ],
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
//获取现价
$stock_fullcode=$stock['full_code'];
//图表
$_count=count($pic['date']);
$_date=json_encode(array_reverse($pic['date']));

foreach ($pic['sell'] as $k=>$v){
    if(!empty($v)){
        $last_sell=$pic['sell'][$k];
        $last_sell=array(
            'value'=>$last_sell,
            'label'=> array(
                'show'=>true
            )
        );
        $pic['sell'][$k]=$last_sell;
        break;
    }
}
$_sell=json_encode(array_reverse($pic['sell']));

foreach ($pic['buy'] as $k=>$v){
    if(!empty($v)){
        $last_buy=$pic['buy'][$k];
        $last_buy=array(
            'value'=>$last_buy,
            'label'=> array(
                'show'=>true
            )
        );
        $pic['buy'][$k]=$last_buy;
        break;
    }
}

$_buy=json_encode(array_reverse($pic['buy']));


//js
$this->registerJsFile("http://hq.sinajs.cn/list=$stock_fullcode");
$this->registerJsFile("https://cdn.bootcss.com/echarts/4.4.0-rc.1/echarts.min.js");
$js = <<<JS
// 现价
    var elements=eval("hq_str_"+'$stock_fullcode').split(",")
    $('#now_price').html(elements[3])
   
// 图表
    var myChart = echarts.init(document.getElementById('echarts_pic'));
    var option = {
            legend: {
                data: ['买', '卖']
            },
            grid: {
                top:'12%',
                left: 0,
                right: '8%',
                bottom: 0,
                containLabel: true
            },
            xAxis: {
                type: 'category',
                boundaryGap:false,
                data: $_date
            },
            yAxis: {
                type: 'value',
                scale:true,
            },
            series: [
            {
                name: '买',
                data: $_buy,
                // data: [10,20,15,{value:20,label: {show: true}},],
                type: 'line',
                connectNulls:true,
                label: {
                    normal: {
                        show: false,
                        position: 'bottom'
                    }
                },
            },
            {
                name: '卖',
                data: $_sell,
                type: 'line',
                connectNulls:true,
                label: {
                    normal: {
                        show: false,
                        position: 'top'
                    }
                },
            },
            {
                name: '现价',
                data:  [elements[3]],
                type: 'line',
                markLine:{
                    data: [
                        {
                            name: '现价',
                            yAxis: [elements[3]]
                        }
                    ],
                    label:{position:'middle'},
                    symbol: 'none'
                },
            },
            ]
        };
    myChart.setOption(option);
   
JS;
$this->registerJs($js);

?>