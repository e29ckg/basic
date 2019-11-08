<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
/**
 * This is the model class for table "legal_c".
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
class LegalC extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'legal_c';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'fname', 'sname'], 'required'],
            [['created_at', 'updated_at'], 'safe'],
            [['id_card', 'name', 'sname', 'img', 'address', 'phone', 'status'], 'string', 'max' => 255],
            [['fname'], 'string', 'max' => 25],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_card' => 'เลขบัตรประชาชน',
            'fname' => 'คำนำหน้าชื่อ',
            'name' => 'ชื่อ',
            'sname' => 'สกุล',
            'img' => 'ภาพ',
            'address' => 'ที่อยู่',
            'phone' => 'โทรศัพท์',
            'status' => 'Status',
            'created_at' => '',
            'updated_at' => '',
        ];
    }

    public function getFnameList(){
        $model = Fname::find()->orderBy('name')->all();
        return ArrayHelper::map($model,'name','name');
        // return [
        //     'นาย' =>'นาย',
        //     'นาง' => 'นาง',
        //     'นางสาว' => 'นางสาว'
        // ];
    }

    public function getImg($img){        
        
        $dir = Url::to('@webroot/uploads/legal_c/');
        
        if(isset($img) && is_file($dir.$img)){
            return  Url::to('@web/uploads/legal_c/').$img;
        }
        return Url::to('@web/img/nopic.png') ;
        // return Yii::getAlias('@web').(!empty($model->img)  ? '/uploads/user/'.$model->img : '/img/nopic.png');
    }
}
