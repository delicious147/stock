<?php

namespace backend\controllers;

use backend\models\search\Deal2Search;
use common\models\Deal2;
use common\models\Stock;
use Yii;
use common\models\core\Deal;
use backend\models\search\DealSearch;
use yii\data\ArrayDataProvider;
//use yii\web\Controller;
use yii\rest\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * DealController implements the CRUD actions for Deal model.
 */
class Deal2Controller extends Controller
{

    public $modelClass = 'common\models\Deal2';
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Deal models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new Deal2Search();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $provider_sell = new ArrayDataProvider([
            'allModels' => $searchModel->minSellMoney(),
            'sort' => false,
            'pagination' => false,
        ]);
        $provider_buy = new ArrayDataProvider([
            'allModels' => $searchModel->buyMoney(),
            'sort' => false,
            'pagination' => false,
        ]);

        $stock=Stock::getFullCode();
        $stock=implode(",", $stock);

        return $this->render('index', [
            'stock'=>$stock,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'provider_sell' => $provider_sell,
            'provider_buy' => $provider_buy,
            'win_money'=>$searchModel->winMoney(),
            'in_money'=>$searchModel->inMoney(),
        ]);
    }

    public function actionCreate()
    {
        $model = new Deal2();

        if ($model->load(Yii::$app->request->post())) {
            $num=$model->num;
            $count=$num/100;
            for ($i=0;$i<$count;$i++){
                $model = new Deal2();
                $model->load(Yii::$app->request->post());
                $model->num=100;
                $model->save();
                if($model->status==1){
                    $lest_buy=Deal2::find()
                        ->andWhere(['status'=>0])
                        ->andWhere(['is_sell'=>0])
                        ->andWhere(['stock_id'=>$model->stock_id])
                        ->orderBy('price asc')
                        ->one();
                    if($lest_buy){
                        $lest_buy->is_sell=1;
                        $lest_buy->save();
                    }
                }
            }
            return $this->redirect(['index']);
        }
        return $this->render('create', [
            'model' => $model,
        ]);
    }
}
