<?php

namespace common\models\core;

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
            [['price'], 'number'],
            [['num', 'type', 'f_id'], 'integer'],
            [['date'], 'safe'],
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
            'name' => 'Name',
            'price' => 'Price',
            'num' => 'Num',
            'date' => 'Date',
            'type' => 'Type',
            'f_id' => 'F ID',
        ];
    }
}
