<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "deal".
 *
 * @property int $id
 * @property string $name
 * @property double $price
 * @property int $num
 * @property string $date
 * @property int $type 1买 2卖
 * @property int $f_id
 */
class Deal extends core\Deal
{

}
