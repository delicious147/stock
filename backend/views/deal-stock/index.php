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
        <?= Html::a('新建订单', '/deal-stock/create?stock_id='.$searchModel->stock_id, ['class' => 'btn btn-success']) ?>
        <?= Html::a('查看持仓', ['hold/index'], ['class' => 'btn btn-success']) ?>
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

        <div class="col-md-12 column">
            <div class="box center-block bg-success">
                <div class="row clearfix">
                    <div class="col-md-6 col-xs-6 column">
                        <p>现价</p>
                        <h4 id="now_price"></h4>
                    </div>
                    <div class="col-md-6 col-xs-6 column">
                        <p>持仓</p>
                        <h4><?= $stock_num?></h4>
                    </div>
                </div>
            </div>
        </div>



        <div class="col-md-12 column">
            <div id="echarts_pic" style="height: 220px;padding-top: 10px;">
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
$pic['sell'][0]=array(
    'value'=>isset($pic['sell'][0])?$pic['sell'][0]:0,
    'label'=> array(
        'show'=>true
    )
);
$pic['buy'][0]=array(
    'value'=>isset($pic['buy'][0])?$pic['buy'][0]:0,
    'label'=> array(
        'show'=>true
    )
);
$_count=count($pic['buy']);

$shao=count($pic['buy'])-count($pic['sell']);
for($i=0;$i<$shao;$i++){
    $pic['sell'][]='';
}
$_sell=json_encode(array_reverse($pic['sell']));
$_buy=json_encode(array_reverse($pic['buy']));


//js
$this->registerJsFile("http://hq.sinajs.cn/list=$stock_fullcode");
$this->registerJsFile("https://cdn.bootcss.com/echarts/4.4.0-rc.1/echarts.min.js");
$js = <<<JS
// 现价
//console.log($stock_fullcode);
_stock_fullcode='$stock_fullcode'
    if(_stock_fullcode){
        var elements=eval("hq_str_"+'$stock_fullcode').split(",")
        $('#now_price').html(elements[3])
        _now_price=elements[3]
    }else {
        _now_price=$_buy
    }

   
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
                // type: 'time',
                boundaryGap:false,
                data: [...new Array($_count).keys()]
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
                data:  [_now_price],
                type: 'line',
                markLine:{
                    data: [
                        {
                            name: '现价',
                            yAxis: [_now_price]
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