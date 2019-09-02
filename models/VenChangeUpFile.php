<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
/*
 */
class VenChangeUpFile extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%ven_change}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            // [['ven_id1','ven_id2'],'required'],   

        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [            
            'ven_id1' => 'เวรแรก',
            'ven_id2' => 'เวรที่2',
            'ven_id1_old' => 'เวรแรก',
            'ven_id2_old' => 'เวรที่2',
            's_po' => 'ผู้อำนวยการลงนาม',
            's_bb' => 'ผู้พิพากษาหัวหน้าฯลงนาม',   
            'comment' => 'หมายเหตุ',      
        ];
    }

    

    public function getProfile()
    {
        return $this->hasOne(Profile::className(), ['id' => 'user_id1']);
    }
    

    public function getVen2()
    {
        return $this->hasOne(Ven::className(), ['id' => 'ven_id2']);
    }
    
    public function getVen1()
    {
        return $this->hasOne(Ven::className(), ['id' => 'ven_id1']);
    }
   

    public function getVen1_old()
    {
        return $this->hasOne(Ven::className(), ['id' => 'ven_id1_old']);
    }

    public function getVen2_old()
    {
        return $this->hasOne(Ven::className(), ['id' => 'ven_id2_old']);
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

    public function getSignList(){
        $model = SignBossName::find()->where(['status' => '1'])->orderBy('id')->all();
        return ArrayHelper::map($model,'id','name');
    }

    public function getQr($id,$user_id){
        $source = Url::to('@webroot/uploads/VenChange/'.$user_id.'/'.$id.'/'.$id.'.png');
        if(is_file($source)){
            return Url::to('@web/uploads/VenChange/'.$user_id.'/'.$id.'/'.$id.'.png');
        }
        return  Url::to('@web/img/none.png'); 
        // return Url::to('@webroot/uploads/VenChange/'.$user_id.'/'.$id.'/'.$id.'.png');
    }

    
    
    public function getStatusList(){
        return [
            '1' => '',
            '2' => 'รออนุมัติ',
            '3' => '',
            '4' => 'รออนุมัติการเปลี่ยน',
            '5' => 'เรียบร้อย(เปลี่ยน)',
            '6' => 'รออนุมัติการยก',
            '7' => 'เรียบร้อย(ยก)',                          
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
		return "$strDay $strMonthThai $strYear";
    }
    
    

}
