<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
/*
 */
class VenUser extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%ven_user}}';
    }

    /**
     * {@inheritdoc}
     */
    public $year;
    
    public function rules()
    {
        return [
            [['user_id','order','price'],'required'],   
            [['order'], 'integer'],
            [['price'], 'number']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [            
            'id' => 'ID',
            'user_id' => 'ชื่อ',
            'order' => 'ลำดับ',
            'DN' => 'กลางคืน/กลางวัน',
            'price' => 'ค่าตอบแทน',
            'comment' => 'รายละเอียด',
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

    public function getUserList(){
        $models = Profile::find()->where(['status' => '10'])->orderBy(['name' => SORT_ASC ])->all();
        return ArrayHelper::map($models,'id',function($model){
            return $model->fname.$model->name.' '.$model->sname;
        });        
    }

    public function getVen_DN(){   
        return [
            '1' => 'กลางคืน',
            '2' => 'กลางวัน',
        ];
    }
    

    public function getVen_time(){   
        return [
            '08:30:00' => 'ฟื้นฟู/จับ-ค้น/ตรวจสอบการจับ(ผู้พิพากษา-กลางวัน)',
            '08:30:01' => 'ฟื้นฟู/ตรวจสอบการจับ(ผอ/แทน-กลางวัน)',
            '08:30:11' => 'ฟื้นฟู/ตรวจสอบการจับ(จนท.1-กลางวัน)',
            '08:30:22' => 'ฟื้นฟู/ตรวจสอบการจับ(จนท.2-กลางวัน)',
            '16:30:00' => 'จับ-ค้น-(ผู้พิพากษา-กลางคืน)',
            '16:30:55' => 'จับ-ค้น(จนท-กลางคืน)',
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
    
    
    


