<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "fname".
 *
 * @property int $id
 * @property string $name
 * @property string $date_create
 */
class Fname extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'fname';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name', 'date_create'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'คำนำหน้าชื่อ',
            'date_create' => 'Date Create',
        ];
    }
}
