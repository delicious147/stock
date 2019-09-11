<?php

namespace common\models\core;

use Yii;

/**
 * This is the model class for table "stock".
 *
 * @property int $id
 * @property string $stock_name
 * @property string $stock_code
 * @property string $full_code
 * @property string $type
 */
class Stock extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'stock';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['stock_name', 'stock_code', 'full_code', 'type'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'stock_name' => 'Stock Name',
            'stock_code' => 'Stock Code',
            'full_code' => 'Full Code',
            'type' => 'Type',
        ];
    }
}
