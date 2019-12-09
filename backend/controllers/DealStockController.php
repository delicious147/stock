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
class DealStockController extends Controller
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
    public function actionIndex($stock_id)
    {
        $searchModel = new Deal2Search();
        $searchModel->stock_id=$stock_id;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $provider_stock=new ArrayDataProvider([
            'allModels' => $searchModel->inStock(),
            'sort' => false,
            'pagination' => false,
        ]);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'win_money'=>$searchModel->winMoney(),
            'in_money'=>$searchModel->inMoney(),
            'provider_stock'=>$provider_stock,
        ]);
    }

    public function actionCreate($stock_id)
    {
        $model = new Deal2();
        $model->stock_id=$stock_id;
        if ($model->load(Yii::$app->request->post())) {
            $num=$model->num;
            $count=$num/100;
            for ($i=0;$i<$count;$i++){
                $model = new Deal2();
                $model->load(Yii::$app->request->post());
                $model->num=100;
                if($model->status==1){
                    $model->is_sell=1;
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
                $model->save();
            }
            return $this->redirect(['index']);
        }
        return $this->render('create', [
            'model' => $model,
            'stock_name'=>$model->getStockName(),
        ]);
    }
}
