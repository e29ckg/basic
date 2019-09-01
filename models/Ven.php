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
        // return $model ? $model->name: '-';
        return $model ? $model->fname.$model->name.' '.$model->sname : '-';
    }

    public function getProfileNameCal(){
        $model=$this->profile;
        return $model ? $model->name: '-';
        // return $model ? $model->fname.$model->name.' '.$model->sname : '-';
    }
       
    public function getVenComList(){
        $model = VenCom::find()->where(['status' => '1'])->orderBy(['id' => SORT_DESC ])->all();
        return ArrayHelper::map($model,'id',function($model){
            return $model->ven_com_name.' '.$model->getVen_time()[$model->ven_time];
        });
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

    public function getVen2($models)
    {      
        return ArrayHelper::map($models,'id',function($model){
            return Ven::DateThai_full($model->ven_date).' ' .$model->getProfileName().'('.$model->id.')' ;
        });        
    }    

    public function getVenForChange($ven_com_id)
    {        
        $modelVC = VenCom::findOne($ven_com_id);
        $strDate = (string)date("Y-m-d") ;
        if($modelVC->ven_time == '08:30:11' || $modelVC->ven_time == '08:30:22'){
            $models = Ven::find()->where([
                'user_id' => Yii::$app->user->identity->id,
                'ven_month' => $modelVC->ven_month,
                'ven_time' => '08:30:11',
                'status' => 1,
                ])
                ->orWhere([
                    'user_id' => Yii::$app->user->identity->id,
                'ven_month' => $modelVC->ven_month,
                'ven_time' => '08:30:22',
                'status' => 1
                ])
                ->andWhere("ven_date >= '$strDate'")
                ->all();
        }else{
            $models = Ven::find()->where([
                'user_id' => Yii::$app->user->identity->id,
                'ven_com_id' => $ven_com_id,
                'status' => 1,
                ])
                ->andWhere("ven_date >= '$strDate'")
                ->all();
        }
        return ArrayHelper::map($models,'id',function($model){
            return Ven::DateThai_full($model->ven_date).' ' .$model->getProfileName().'('.$model->id.')' ;
        });         
    }

    


    public function getCountVen($ven_com_id)
    {        
        $modelVC = VenCom::findOne($ven_com_id);
        $strDate = (string)date("Y-m-d") ;
        
    
        if($modelVC->ven_time == '08:30:11' || $modelVC->ven_time == '08:30:22'){
            return Ven::find()->where([
                'user_id' => Yii::$app->user->identity->id,
                'ven_month' => $modelVC->ven_month,
                'ven_time' => '08:30:11',
                'status' => 1,
                ])
                ->orWhere([
                    'user_id' => Yii::$app->user->identity->id,
                'ven_month' => $modelVC->ven_month,
                'ven_time' => '08:30:22',
                'status' => 1
                ])
                ->andWhere("ven_date >= '$strDate'")
                ->count(); 
        }else{
            return Ven::find()->where([
                'user_id' => Yii::$app->user->identity->id,
                'ven_com_id' => $ven_com_id,
                'status' => 1,
                ])
                ->andWhere("ven_date >= '$strDate'")
                ->count(); 
            }
            // ->count();           
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

    public function getCheck($id)              //เช้คการชน
    { 
        $model = Ven::findOne($id);
        
        if($model->ven_time == '16:30:55'){   
            $dB = date('Y-m-d', strtotime($model->ven_date));
            $dB1 = date('Y-m-d', strtotime('+1 day', strtotime($model->ven_date)));

            $modelVO = Ven::find()
            ->where([
                'ven_date' => [$dB,$dB1],
                'ven_time' => ['08:30:01','08:30:11','08:30:22'],
                'status' => [1, 2, 3,],
                // 'status' => 1,
                'user_id' => Yii::$app->user->identity->id,
            ])->count();
            return  $modelVO ? $modelVO : null ;        ///            
        }

        if($model->ven_time == '08:30:01' || $model->ven_time == '08:30:11' || $model->ven_time == '08:30:22'){   
            $dB = date('Y-m-d', strtotime($model->ven_date));
            $dB1 = date('Y-m-d', strtotime('-1 day', strtotime($model->ven_date)));

            $modelVO = Ven::find()
            ->where([
                'ven_date' => [$dB],
                'ven_time' => ['08:30:01','08:30:11','08:30:22'],
                'status' => [1, 2, 3,],
                // 'status' => 1,
                'user_id' => Yii::$app->user->identity->id,
            ])->orWhere([
                'ven_date' => $dB1,
                'ven_time' => ['16:30:55'],
                'status' => [1,2,3],
                'user_id' => 23,
                ])
            ->count();
            return  $modelVO ? $modelVO : null ;        ///            
        }

    }

    
}
