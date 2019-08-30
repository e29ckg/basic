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
    public $year;
    
    public function rules()
    {
        return [
            [['ven_com_num','ven_com_name','ven_month','year'],'required'],   

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
            'ven_com_date' => 'ลงวันที่',
            'ven_time' => 'หน้าที่',
            'ven_com_name' => 'ชื่อเวร',
            'ven_month' => 'ประจำเดือน',
            'year' => 'ปี',            
            'comment' => '',        
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
            '7' => 'ไม่ใช้งาน',            
        ];
    }    

    public function getVen_time(){   
        return [
            '08:30:00' => 'ผู้พิพากษา(กลางวัน)',
            '08:30:01' => 'ปชส.(ผอ/หัวหน้างาน)',
            '08:30:11' => 'รับฟ้อง(กลางวัน)',
            '08:30:22' => 'รับฟ้อง/จับ-ค้น(กลางวัน)',
            '16:30:00' => 'ผู้พิพากษา(กลางคืน)',
            '16:30:55' => 'จับ-ค้น(กลางคืน)',
        ];
    }

    public function getVen_month()
    {
        return [
            '01' => 'มกราคม',
            '02' => 'กุมภาพันธ์',
            '03' => 'มีนาคม',
            '04' => 'เมษายน',
            '05' => 'พฤษภาคม',
            '06' => 'มิถุนายน',
            '07' => 'กรกฎาคม',
            '08' =>  'สิงหาคม',
            '09' => 'กันยายน',
            '10' => 'ตุลาคม',
            '11' => 'พฤศจิกายน',
            '12' => 'ธันวาคม'
        ];
    }

    public function getAven(){   
        return [
            'ฟื้นฟู/แขวง/หมายจับ-ค้น' => 'ฟื้นฟู/แขวง/หมายจับ-ค้น',
            'ฟื้นฟู/แขวง' => 'ฟื้นฟู/แขวง',
            'หมายจับ-ค้น/รักษาการณ์' => 'หมายจับ-ค้น/รักษาการณ์',
            'หมายจับ-ค้น' => 'หมายจับ-ค้น',
        ];
    }
    

}
    
    
    


