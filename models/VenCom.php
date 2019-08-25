<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
/*
 */
class VenCom extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%ven_com}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['ven_com_num','ven_com_name','comment'],'required'],   

        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [            
            'id' => 'ID',
            'ven_com_num' => 'เลขที่คำสั่ง',
            'ven_com_name' => 'ชื่อคำสั่ง(ทางการ)',
            'comment' => 'ชื่อที่แสดงหน้าจัดเวร',        
        ];
    }

    

    public function getProfile()
    {
        return $this->hasOne(Profile::className(), ['id' => 'user_id']);
    }

    public function getProfileName(){
        $model=$this->profile;
        return $model ? $model->fname.$model->name.' '.$model->sname : '-';
    }

    public function getProfileDep(){
        $model=$this->profile;
        return !empty($model->dep) ? $model->dep : '-';
    }

    public function getProfilePhone(){
        $model=$this->profile;
        return !empty($model->phone) ? $model->phone : '-';
    }

    public function getProfileAddressById($id){
        $model = Profile::findOne($id);
        return !empty($model->address) ? $model->address : '-';
    }

    
    public function getCountAll()
    {        
        return VenCom::find()->count();           
    }

    public function getCountA()
    {        
        return VenCom::find()->where(['cat' => 'ลาป่วย'])->count();           
    }
    
    public function getCountB()
    {        
        return VenCom::find()->where(['cat' => 'ลาพักผ่อน'])->count();           
    }

    

    public function getSignList(){
        $model = SignBossName::find()->where(['status' => '1'])->orderBy('id')->all();
        return ArrayHelper::map($model,'id','name');
    }

    public function getQr($id,$user_id){
        $source = Url::to('@webroot/uploads/VenCom/'.$user_id.'/'.$id.'/'.$id.'.png');
        if(is_file($source)){
            return Url::to('@web/uploads/VenCom/'.$user_id.'/'.$id.'/'.$id.'.png');
        }
        return  Url::to('@web/img/none.png'); 
        // return Url::to('@webroot/uploads/VenCom/'.$user_id.'/'.$id.'/'.$id.'.png');
    }

    
    
    public function getStatusList(){
        return [
            
            '1' => 'ใช้งาน',
            '4' => 'ไม่ใช้งาน',
            
        ];
    }
    public function getStatusName($id){
        $role = [
            '1' => 'ใช้งาน',
            '4' => 'ไม่ใช้งาน',
        ];
        return $role[$id];
    }
    
    
    

}
