<?php

namespace app\models;
use Yii;

/**
 * This is the model class for table "log".
 *
 * @property int $id
 * @property string $name
 * @property string $link
 * @property string $img
 * @property string $create_at
 */
class Log extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'log';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            // [['name'], 'required'],
            // [['name' , 'link'],'unique','message'=>'Name already exist. Please try another one.'],
            // [['create_at'], 'safe'],
            // [['name', 'img', 'link'], 'string', 'max' => 255],
            // [['img'], 'file', 'extensions' => 'png, jpg', 'maxSize'=> 1024 * 1024 * 5],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            // 'id' => 'ID',
            // 'name' => 'Name',
            // 'link' => 'link',
            // 'img' => 'Img',
            // 'create_at' => 'Create At',
            // 'update_at' => 'Update At',
        ];
    }

    public function getCountAll()
    {        
        return CLetter::find()->count();           
    }
}
