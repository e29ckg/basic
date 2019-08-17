<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;
/**
 * This is the model class for table "sign_boss_name".
 *
 * @property int $id
 * @property string $name
 * @property string $dep1
 * @property string $dep2
 * @property string $dep3
 * @property int $status
 * @property string $date_create
 */
class SignBossName extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sign_boss_name';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'status'], 'required'],
            [['status'], 'integer'],
            [['name', 'dep1', 'dep2', 'dep3', 'date_create'], 'string', 'max' => 255],
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
            'dep1' => 'ตำแหน่ง',
            'dep2' => 'ตำแหน่ง(บรรทัด2)',
            'dep3' => 'ตำแหน่ง(บรรทัด3)',
            'status' => 'สถานะ',
            'date_create' => 'Date Create',
        ];
    }
    public function getCountAll()
    {        
        return SignBossName::find()->count();           
    }

    public function getStName($st)
    {     
        return $st == 1 ? 'ใช้งาน' : 'ยกเลิก';
                  
    }
}
