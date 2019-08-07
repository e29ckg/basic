<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "dep".
 *
 * @property int $id
 * @property string $name
 * @property string $date_create
 */
class Dep extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'dep';
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
            'name' => 'ชื่อตำแหน่ง',
            'date_create' => 'Date Create',
        ];
    }
}
