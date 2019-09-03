<?php

namespace backend\controllers;

use Yii;
use common\models\core\Deal;
use backend\models\search\DealSearch;
use yii\data\ArrayDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * DealController implements the CRUD actions for Deal model.
 */
class DealController extends Controller
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
        $searchModel = new DealSearch();
        $query = $searchModel->search(Yii::$app->request->queryParams);
        $list=$query->asArray()->all();
        $sell=$searchModel->minSellMoney();
        $buy=$searchModel->buyMoney();
//        $now_price=$searchModel->nowPrice();
        $provider_sell = new ArrayDataProvider([
            'allModels' => $sell,
            'sort' => false,
            'pagination' => false,
        ]);
        $provider_buy = new ArrayDataProvider([
            'allModels' => $buy,
            'sort' => false,
            'pagination' => false,
        ]);
        $provider = new ArrayDataProvider([
            'allModels' => $list,
            'sort' => false,
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);
        return $this->render('index', [
            'dataProvider' => $provider,
            'provider_sell' => $provider_sell,
            'provider_buy' => $provider_buy,
        ]);
    }

    public function actionSell($id){
        $model = $this->findModel($id);
        $sell_price=Yii::$app->request->post('sell_price');
        $model->sell_price=$sell_price;
        if($sell_price){
            $model->is_sell=1;
            $model->sell_date=date('Y-m-d H:i:s');
        }else{
            $model->is_sell=1;
            $model->sell_date='';
        }
        $model->save();

        return $this->redirect(['index']);
    }

    /**
     * Displays a single Deal model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Deal model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Deal();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Deal model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Deal model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Deal model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Deal the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Deal::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
