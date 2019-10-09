<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "court_order_boss".
 *
 * @property int $id
 * @property string $year
 * @property string $num
 * @property string $name
 * @property string $file
 * @property string $create_at
 */
class CourtOrderBoss extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'court_order_boss';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            // [['create_at'], 'safe'],
            // [['year', 'num', 'name', 'file'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'year' => 'Year',
            'num' => 'Num',
            'name' => 'เรื่อง',
            'file' => 'File',
            'create_at' => 'Create At',
        ];
    }
}
