<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

class Ven extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%Ven}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id','ven_date','ven_com_id'],'required'],             

        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [            
            'id' => 'ID',
            'ven_date' => 'วันที่',
            'ven_com_id' => 'คำสั่ง',
            'user_id' => 'ผู้อยู่เวร',
            'comment' => 'หมายเหตุ',            
        ];
    }

    public function getVenCom()
    {
        return $this->hasOne(VenCom::className(), ['id' => 'ven_com_id']);
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
        return Ven::find()->count();           
    }

    public function getCountA()
    {        
        return Ven::find()->where(['cat' => 'ลาป่วย'])->count();           
    }
    
    public function getCountB()
    {        
        return Ven::find()->where(['cat' => 'ลาพักผ่อน'])->count();           
    }

    public function getVenComList(){
        $model = VenCom::find()->where(['status' => '1'])->orderBy(['id' => SORT_DESC ])->all();
        return ArrayHelper::map($model,'id','comment');
    }

    public function getUserList(){
        $models = Profile::find()->where(['status' => '10'])->orderBy(['name' => SORT_ASC ])->all();
        return ArrayHelper::map($models,'id',function($model){
            return $model->fname.$model->name.' '.$model->sname;
        });        
    }

    public function getVen1($ven_com_id){
        $models = Ven::find()->where([
            'user_id' => Yii::$app->user->identity->id,
            'ven_com_id' => $ven_com_id,
            'status' => 1
            ])->orderBy(['ven_com_id' => SORT_ASC ])->all();
        return ArrayHelper::map($models,'id',function($model){
            return Ven::DateThai_full($model->ven_date).' ' .$model->getProfileName().'('.$model->id.')' ;
        });        
    }

    public function getVen2($models){
        
        return ArrayHelper::map($models,'id',function($model){
            return Ven::DateThai_full($model->ven_date).' ' .$model->getProfileName().'('.$model->id.')' ;
        });        
    }
    

    public function getCountVen($ven_com_id)
    {        
        return Ven::find()->where([
            'user_id' => Yii::$app->user->identity->id,
            'ven_com_id' => $ven_com_id,
            'status' => 1
            ])->count();           
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
		return "$strDay $strMonthThai $strYear";
    }
    
    public function DateThai_month_full($strDate)
	{
		$strYear = date("Y",strtotime($strDate))+543;
		$strMonth= date("n",strtotime($strDate));
		$strDay= date("j",strtotime($strDate));
		$strHour= date("H",strtotime($strDate));
		$strMinute= date("i",strtotime($strDate));
		$strSeconds= date("s",strtotime($strDate));
		$strMonthCut = Array("","มกราคม","กุมภาพันธ์","มีนาคม","เมษายน","พฤษภาคม","มิถุนายน","กรกฎาคม",
                            "สิงหาคม","กันยายน","ตุลาคม","พฤศจิกายน","ธันวาคม");
		$strMonthThai=$strMonthCut[$strMonth];
		return "$strMonthThai";
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
