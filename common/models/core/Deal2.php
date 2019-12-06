<?php

namespace common\models\core;

use Yii;

/**
 * This is the model class for table "deal2".
 *
 * @property int $id
 * @property int $stock_id
 * @property double $price
 * @property int $num
 * @property string $date
 * @property string $remark
 * @property int $status 0:买 1:卖
 * @property int $is_sell
 */
class Deal2 extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'deal2';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['stock_id', 'num', 'status', 'is_sell'], 'integer'],
            [['price'], 'number'],
            [['date'], 'safe'],
            [['remark'], 'string', 'max' => 255],
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
            'price' => 'Price',
            'num' => 'Num',
            'date' => 'Date',
            'remark' => 'Remark',
            'status' => 'Status',
            'is_sell' => 'Is Sell',
        ];
    }
}
