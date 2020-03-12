<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "law_ban".
 *
 * @property int $id
 * @property string $name
 * @property string $license
 * @property string $ban
 * @property string $created_owner
 * @property string $created_at
 * @property string $updated_at
 */
class LawBan extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'law_ban';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['created_at', 'updated_at'], 'safe'],
            [['name', 'license', 'ban', 'created_owner'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'ชื่อ-สกุล',
            'license' => 'เลขที่ใบอนุญาต',
            'ban' => 'ระยะเวลาห้ามทำการเป็นทนาย',
            'created_owner' => 'Created Owner',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
