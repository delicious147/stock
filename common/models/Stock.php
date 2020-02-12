<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "stock".
 *
 * @property int $id
 * @property string $stock_name
 * @property int $stock_code
 */
class Stock extends core\Stock
{
    public static function getMap(){
        return Stock::find()->select('stock_name')->indexBy('id')->column();
    }

    public static function getCode(){
        return Stock::find()->select('stock_code')->indexBy('id')->column();
    }

    public static function getFullCode(){
        return Stock::find()->select(['CONCAT(type,stock_code)'])->column();
    }

    public static function getFullCodeOne($id){
        return Stock::find()->select(['CONCAT(type,stock_code)'])->andWhere(['id'=>$id])->scalar();
    }

    public static function getFullCodeMap(){
        return Stock::find()->select('full_code')->indexBy('id')->column();
    }

}
