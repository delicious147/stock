<?php

namespace backend\controllers;

use backend\models\search\Deal2Search;
use common\models\Deal2;
use common\models\Stock;
use Yii;
use common\models\core\Deal;
use backend\models\search\DealSearch;
use yii\data\ArrayDataProvider;
use yii\web\Controller;
//use yii\rest\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * DealController implements the CRUD actions for Deal model.
 */
class HoldController extends Controller
{
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

        $provider_stock=new ArrayDataProvider([
            'allModels' => $searchModel->inStock(),
            'sort' => false,
            'pagination' => false,
        ]);

        return $this->render('index', [
            'win_money'=>$searchModel->winMoney(),
            'in_money'=>$searchModel->inMoney(),
            'provider_stock'=>$provider_stock,
//            'stock'=>Stock::find()->andWhere(['id'=>$stock_id])->one(),
        ]);
    }


}
