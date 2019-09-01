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
            [['ven_com_num','ven_com_name','ven_month'],'required'],   

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
            'ven_month' => 'อยู่เวรเดือน',
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
            date("Y-m", strtotime("-2 month")) =>  VenCom::DateThai_full(date("Y-m", strtotime("-2 month"))),
            date("Y-m", strtotime("-1 month")) =>  VenCom::dateThai_full(date("Y-m", strtotime("-1 month"))),
            date("Y-m") => VenCom::DateThai_full(date("Y-m")),
            date("Y-m", strtotime("+1")) =>  VenCom::DateThai_full(date("Y-m", strtotime("+1"))),
            date("Y-m", strtotime("+1 month")) =>  VenCom::DateThai_full(date("Y-m", strtotime("+1 month"))),
            date("Y-m", strtotime("+2 month")) =>  VenCom::DateThai_full(date("Y-m", strtotime("+2 month"))),
            date("Y-m", strtotime("+3 month")) =>  VenCom::DateThai_full(date("Y-m", strtotime("+3 month"))),
            date("Y-m", strtotime("+4 month")) =>  VenCom::DateThai_full(date("Y-m", strtotime("+4 month"))),
            date("Y-m", strtotime("+5 month")) =>  VenCom::DateThai_full(date("Y-m", strtotime("+5 month"))),
           
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

    public function DateThai_full($strDate)
	{
        if($strDate == ''){
            return "-";
        }
		$strYear = date("Y",strtotime($strDate))+543;
		$strMonth= date("n",strtotime($strDate));
		$strDay= date("j",strtotime($strDate));
		$strHour= date("H",strtotime($strDate));
		$strMinute= date("i",strtotime($strDate));
		$strSeconds= date("s",strtotime($strDate));
		$strMonthCut = Array("","มกราคม","กุมภาพันธ์","มีนาคม","เมษายน","พฤษภาคม","มิถุนายน","กรกฎาคม",
                            "สิงหาคม","กันยายน","ตุลาคม","พฤศจิกายน","ธันวาคม");
		$strMonthThai=$strMonthCut[$strMonth];
		return "$strMonthThai $strYear";
    }
    

}
    
    
    


