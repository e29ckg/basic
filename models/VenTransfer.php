<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
/*
 */
class VenTransfer extends \yii\db\ActiveRecord
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
            [['ven_id1','user_id2'],'required'],   

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
            'user_id1' => 'ผู้โอน',
            'user_id2' => 'ผู้รับโอน',
            's_po' => 'ผู้อำนวยการลงนาม',
            's_bb' => 'ผู้พิพากษาหัวหน้าฯลงนาม',   
            'comment' => 'เนื่องจาก',      
        ];
    }

    

    public function getProfile()
    {
        return $this->hasOne(Profile::className(), ['id' => 'user_id']);
    }

    
    public function getSignList(){
        $model = SignBossName::find()->where(['status' => '1'])->orderBy('id')->all();
        return ArrayHelper::map($model,'id','name');
    }

    public function getUserList(){
        $models = Profile::find()->where(['status' => '10'])->orderBy(['name' => SORT_ASC ])->all();
        return ArrayHelper::map($models,'id',function($model){
            return $model->fname.$model->name.' '.$model->sname;
        });        
    }

    public function getQr($id,$user_id){
        $source = Url::to('@webroot/uploads/VenChange/'.$user_id.'/'.$id.'/'.$id.'.png');
        if(is_file($source)){
            return Url::to('@web/uploads/VenChange/'.$user_id.'/'.$id.'/'.$id.'.png');
        }
        return  Url::to('@web/img/none.png'); 
        // return Url::to('@webroot/uploads/VenChange/'.$user_id.'/'.$id.'/'.$id.'.png');
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
