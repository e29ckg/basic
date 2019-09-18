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
            // [['user_id','ven_date','ven_com_id'],'required'],             
            // [['ven_date'], 'validateVen_date'],
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

    public function validateVen_date()
    {
        $model = VenCom::findOne($this->ven_com_id);

        if($model->ven_time == '08:30:01' || $model->ven_time == '08:30:11' || $model->ven_time == '08:30:22'){   
            $dB = date('Y-m-d', strtotime($this->ven_date));
            $dB1 = date('Y-m-d', strtotime('-1 day', strtotime($this->ven_date)));

            $modelVO = Ven::find()
                ->where([
                    'ven_date' => [$dB,$dB1],
                    'ven_time' => '16:30:55',
                    'status' => 1,
                    'user_id' => $this->user_id,
                ])->orWhere([
                    'ven_date' => [$dB],
                    'ven_time' => ['08:30:01','08:30:11','08:30:22'],
                    'status' => 1,
                    'user_id' => $this->user_id,
                ])->count();
            if($modelVO >= 1){
                $this->addError('ven_date', 'เบิกไม่ได้นะ');
            }               
        }
        if($model->ven_time == '08:30:00'){   
            $dB = date('Y-m-d', strtotime($this->ven_date));
            $dB1 = date('Y-m-d', strtotime('-1 day', strtotime($this->ven_date)));

            $modelVO = Ven::find()
                ->where([
                    'ven_date' => [$dB,$dB1],
                    'ven_time' => '16:30:00',
                    'status' => 1,
                    'user_id' => $this->user_id,
                ])->orWhere([
                    'ven_date' => [$dB],
                    'ven_time' => ['08:30:00'],
                    'status' => 1,
                    'user_id' => $this->user_id,
                ])->count();
            if($modelVO >= 1){
                $this->addError('ven_date', 'เบิกไม่ได้นะ');
            }               
        }
        if($model->ven_time == '16:30:55'){   
            $dB = date('Y-m-d', strtotime($this->ven_date));
            $dB1 = date('Y-m-d', strtotime('+1 day', strtotime($this->ven_date)));

            $modelVO = Ven::find()
            ->where([
                'ven_date' => [$dB,$dB1],
                'ven_time' => ['08:30:01','08:30:11','08:30:22'],
                'status' => [1, 2, 3,],
                // 'status' => 1,
                'user_id' => $this->user_id,
            ])->orWhere([
                'ven_date' => [$dB],
                'ven_time' => ['16:30:55'],
                'status' => [1, 2, 3,],
                // 'status' => 1,
                'user_id' => $this->user_id,
            ])->count();   
            if($modelVO >= 1){
                $this->addError('ven_date', 'เบิกไม่ได้นะ');
            }
        }
        if($model->ven_time == '16:30:00'){   
            $dB = date('Y-m-d', strtotime($this->ven_date));
            $dB1 = date('Y-m-d', strtotime('+1 day', strtotime($this->ven_date)));

            $modelVO = Ven::find()
            ->where([
                'ven_date' => [$dB,$dB1],
                'ven_time' => ['08:30:00'],
                'status' => [1, 2, 3,],
                // 'status' => 1,
                'user_id' => $this->user_id,
            ])->orWhere([
                'ven_date' => [$dB],
                'ven_time' => ['16:30:00'],
                'status' => [1, 2, 3,],
                // 'status' => 1,
                'user_id' => $this->user_id,
            ])->count();   
            if($modelVO >= 1){
                $this->addError('ven_date', 'เบิกไม่ได้นะ');
            }
        }
        
    }

    public function getVenChange()
    {
        return $this->hasOne(VenChange::className(), ['ref1' => 'ref2']);
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

    public function getVenComNum(){
        $model = $this->venCom;
        return $model ? $model->ven_com_num : '-';
    }

    public function getVenComDate(){
        $model = $this->venCom;
        return $model ? $model->ven_com_date : '-';
    }
 
    public function getVenComName(){
        $model = $this->venCom;
        return $model ? $model->ven_com_name : '-';
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

    public function getVenForChangeAll($id)
    {      
        $dB = date('Y-m-d');         
        $models = Ven::getVenForChange($id)->all();
        if(isset($models)){
            return ArrayHelper::map($models,'id',function($model){
                return Ven::DateThai_full($model->ven_date).' ' .$model->getProfileName().'('.$model->id.')' ;
            });
        } 
        return [
            '00' => '-'
        ] ;     
    }

    public function getVenForChangeCount($id)
    {        
        $model = Ven::getVenForChange($id);
        return isset($model) ? $model->count() : 0 ;     
    }

    public function getVenForChange($id)  //จำนวนเวรที่สามารถเปลียนได้
    {        
        $model = Ven::findOne($id);
        $dB = date('Y-m-d');       
    
        if($model->ven_time == '08:30:11' || $model->ven_time == '08:30:22'){
            $modelVO = Ven::find()->where([
                'user_id' => Yii::$app->user->identity->id,
                'ven_month' => $model->ven_month,
                'ven_time' => ['08:30:11','08:30:22'],
                'status' => 1,
                ])                
                ->andWhere("ven_date >= '$dB'");
                // ->count();             
                return  $modelVO ? $modelVO : null ; //จำนวนเวรที่สามารถเปลียนได้
        } 

       
        if($model->ven_time == '16:30:55'){
            $modelVO = Ven::find()->where([
                'user_id' => Yii::$app->user->identity->id,
                'ven_month' => $model->ven_month,
                'ven_time' => $model->ven_time,
                'status' => 1,
                ])                
                ->andWhere("ven_date >= '$dB'");
                // ->count();             
                return  $modelVO ? $modelVO : null ; //จำนวนเวรที่สามารถเปลียนได้
        }
                
        if($model->ven_time == '08:30:01'){
            $modelVO = Ven::find()->where([
                'user_id' => Yii::$app->user->identity->id,
                'ven_month' => $model->ven_month,
                'ven_time' => $model->ven_time,
                'status' => 1,
                ])                
                ->andWhere("ven_date >= '$dB'");
                // ->count();             
                return  $modelVO ? $modelVO : null ; //จำนวนเวรที่สามารถเปลียนได้
        }

        if($model->ven_time == '08:30:00'){
            $modelVO = Ven::find()->where([
                'user_id' => Yii::$app->user->identity->id,
                'ven_month' => $model->ven_month,
                'ven_time' => $model->ven_time,
                'status' => 1,
                ])                
                ->andWhere("ven_date >= '$dB'");
                // ->count();             
                return  $modelVO ? $modelVO : null ; //จำนวนเวรที่สามารถเปลียนได้
        }

        if($model->ven_time == '16:30:00'){
            $modelVO = Ven::find()->where([
                'user_id' => Yii::$app->user->identity->id,
                'ven_month' => $model->ven_month,
                'ven_time' => $model->ven_time,
                'status' => 1,
                ])                
                ->andWhere("ven_date >= '$dB'");
                // ->count();             
                return  $modelVO ? $modelVO : null ; //จำนวนเวรที่สามารถเปลียนได้
        }
        return  $modelVO = null ;
    }

    public function getCheck($id)              // เช็คว่าสามารถเปลี่ยนตามวันที่เลือกได้หรือไม่  true  สามารเปลี่ยนได้ 
    { 
        $model = Ven::findOne($id);

        $dB = date('Y-m-d', strtotime($model->ven_date));
        
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
            ])
            ->orWhere([
                'ven_date' => [$dB],
                'ven_time' => '16:30:55',
                'status' => [1, 2, 3,],
                'user_id' => Yii::$app->user->identity->id,
            ])->count();
            return  $modelVO ? false : true ;        ///   true  สามารเปลี่ยนได้          
        }

        if($model->ven_time == '16:30:00'){   
            $dB = date('Y-m-d', strtotime($model->ven_date));
            $dB1 = date('Y-m-d', strtotime('+1 day', strtotime($model->ven_date)));

            $modelVO = Ven::find()
            ->where([
                'ven_date' => [$dB,$dB1],
                'ven_time' => ['08:30:00'],
                'status' => [1, 2, 3,],
                // 'status' => 1,
                'user_id' => Yii::$app->user->identity->id,
            ])
            ->orWhere([
                'ven_date' => [$dB],
                'ven_time' => '16:30:00',
                'status' => [1, 2, 3,],
                'user_id' => Yii::$app->user->identity->id,
            ])->count();
            return  $modelVO ? false : true ;        ///   true  สามารเปลี่ยนได้          
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
                'ven_date' => [$dB,$dB1],
                'ven_time' => ['16:30:55'],
                'status' => [1,2,3],
                'user_id' => Yii::$app->user->identity->id,
                ])
            ->count();
            return  $modelVO ? false : true ;        ///                
        }

        if($model->ven_time == '08:30:00'){   
            $dB = date('Y-m-d', strtotime($model->ven_date));
            $dB1 = date('Y-m-d', strtotime('-1 day', strtotime($model->ven_date)));

            $modelVO = Ven::find()
            ->where([
                'ven_date' => [$dB],
                'ven_time' => ['08:30:00'],
                'status' => [1, 2, 3,],
                // 'status' => 1,
                'user_id' => Yii::$app->user->identity->id,
            ])->orWhere([
                'ven_date' => [$dB,$dB1],
                'ven_time' => ['16:30:00'],
                'status' => [1,2,3],
                'user_id' => Yii::$app->user->identity->id,
                ])
            ->count();
            return  $modelVO ? false : true ;        ///                
        }
    }

    public function getVen_all(){
        $models = Ven::find()->where(['status' => 1])->orderBy(['ven_date' => SORT_ASC ])->all();
        return ArrayHelper::map($models,'id',function($model){
            return Ven::DateThai_full($model->ven_date).' ' .$model->getProfileName().'('.$model->id.')'.VenCom::getVen_time()[$model->ven_time].VenChange::getVen_time()[$model->ven_time] ;
        });        
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

    
}
