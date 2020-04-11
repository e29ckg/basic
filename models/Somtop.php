<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
/**
 * This is the model class for table "somtop".
 *
 * @property int $id
 * @property string $id_card
 * @property string $fname
 * @property string $name
 * @property string $sname
 * @property string $img
 * @property string $address
 * @property string $phone
 * @property string $status
 * @property string $created_at
 * @property string $updated_at
 */
class Somtop extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'somtop';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name'],'unique','message'=>'ชื่อซ้ำ.'],
            [['created_at', 'updated_at'], 'safe'],
            [['name'], 'string', 'max' => 255],
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
            'st' => 'Status',
            'created_at' => '',
            'updated_at' => '',
        ];
    }

    
}
