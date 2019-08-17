<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;
// use app\models\CLetterCaid;

/**
 * This is the model class for table "c_letter".
 *
 * @property int $id
 * @property string $name
 * @property int $caid
 * @property string $file
 * @property int $status
 * @property string $created_at
 * @property string $updated_at
 */
class CLetter extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'c_letter';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name','ca_name'], 'required'],
            [['status'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['name', 'ca_name', 'file'], 'string', 'max' => 255],
            [['name'], 'unique','message'=>'Name already exist. Please try another one.'],
            [['file'], 'file', 'extensions' => 'pdf, png, jpg, jpeg', 'maxSize'=> 1024 * 1024 * 10],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'ชื่อเรื่อง',
            'ca_name' => 'ประเภทเอกสาร',
            'file' => 'File',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
    
    public function getCountAll()
    {        
        return CLetter::find()->count();           
    }

    public function getCLetterCaid()
    {
        return $this->hasOne(CLetterCaid::className(), ['id' => 'caid']);
    }

    
    public function getCaidList(){
        $model = CLetterCaid::find()->orderBy(['name'=>SORT_ASC])->all();
        return ArrayHelper::map($model,'name','name');
    }

    
}
