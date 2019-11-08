<?php
namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;
use backend\models\SignupForm;

/**
 * Site controller
 */
class TestController extends Controller
{


    public function actionIndex()
    {
        $arr=['aaa','bbb','ccc','dd'=>'ddd'];
        return $this->render('index',['arr'=>json_encode($arr)]);
    }


}
