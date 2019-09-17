<?php

namespace common\models;

use Yii;
/**
 * This is the model class for table "deal".
 *
 * @property int $id
 * @property int $stock_id
 * @property double $price
 * @property int $num
 * @property string $date
 * @property double $sell_price
 * @property string $sell_date
 * @property int $is_sell
 */

class Deal extends core\Deal
{
    public function getStock()
    {
        return $this->hasOne(Stock::className(), ['id' => 'stock_id']);
    }

}
