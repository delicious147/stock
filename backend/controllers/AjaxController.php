<?php

namespace backend\controllers;

use common\components\MyHelper;
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
class AjaxController extends Controller
{
   public function actionSinaPrice($params){
       $url='http://hq.sinajs.cn/list=';
       $url=$url.$params;
       $response=MyHelper::get($url);
       $encode = mb_detect_encoding($response, array("ASCII",'UTF-8',"GB2312","GBK",'BIG5'));
       $str_encode = mb_convert_encoding($response, 'UTF-8', $encode);
       return $str_encode;
   }
}
