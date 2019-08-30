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

}
