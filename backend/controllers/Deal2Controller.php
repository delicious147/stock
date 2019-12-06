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

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCreate()
    {
        $model = new Deal2();

        if ($model->load(Yii::$app->request->post()) ) {
            $model->save();
            if($model->status==1){
                $lest_buy=Deal2::find()
                    ->andWhere(['status'=>0])
                    ->andWhere(['is_sell'=>0])
                    ->orderBy('price asc')
                    ->one();
                if($lest_buy){
                    $lest_buy->is_sell=1;
                    $lest_buy->save();
                }
            }
            return $this->redirect(['index']);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }
}
