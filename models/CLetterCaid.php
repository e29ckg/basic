<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "c_letter_caid".
 *
 * @property int $id
 * @property string $name
 * @property int $order
 * @property int $status
 */
class CLetterCaid extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'c_letter_caid';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name','status'], 'required'],
            [['order', 'status'], 'integer'],
            [['name'], 'string', 'max' => 255],
            [['name'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'ชื่อ',
            'order' => 'ลำดับ',
            'status' => 'สถานะ',
        ];
    }
}
