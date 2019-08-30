<?php

namespace common\models\core;

use Yii;

/**
 * This is the model class for table "deal".
 *
 * @property int $id
 * @property int $stock_id
 * @property string $name
 * @property double $price
 * @property int $num
 * @property string $date
 * @property double $sell_price
 * @property string $sell_date
 * @property int $is_sell
 */
class Deal extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'deal';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['stock_id', 'num', 'is_sell'], 'integer'],
            [['price', 'sell_price'], 'number'],
            [['date', 'sell_date'], 'safe'],
            [['name'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'stock_id' => 'Stock ID',
            'name' => 'Name',
            'price' => 'Price',
            'num' => 'Num',
            'date' => 'Date',
            'sell_price' => 'Sell Price',
            'sell_date' => 'Sell Date',
            'is_sell' => 'Is Sell',
        ];
    }
}
