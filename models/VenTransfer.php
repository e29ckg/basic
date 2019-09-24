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
            // [['ven_id1','user_id2',], 'integer'],
            [['user_id2'], 'validateUser_id2'],
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

    public function validateUser_id2($ven_id1,$user_id2, $params)
    {
    //     return false;
        // $this->addError('user_id2', $this->ven_id1);
        $model = Ven::findOne($this->ven_id1);
        $ven_time = $model->ven_time;
        $ven_date = $model->ven_date;
        // // $this->addError('user_id2',Yii::$app->session->get('v1_time'));
        // $ven_time = Yii::$app->session->get('v1_time');
        // $ven_date = Yii::$app->session->get('v1_date');
        if($ven_time == '08:30:01' || $ven_time  == '08:30:11' || $ven_time  == '08:30:22'){   
            $dB = date('Y-m-d', strtotime($ven_date));
            $dB1 = date('Y-m-d', strtotime('-1 day', strtotime($ven_date)));
            
            $modelVO = Ven::find()
                ->where([
                    'ven_date' => [$dB,$dB1],
                    'ven_time' => '16:30:55',
                    'status' => 1,
                    'user_id' => $this->user_id2,
                ])->orWhere([
                    'ven_date' => [$dB],
                    'ven_time' => ['08:30:01','08:30:11','08:30:22'],
                    'status' => 1,
                    'user_id' => $this->user_id2,
                ])->count();
            if($modelVO >= 1){
                $this->addError('user_id2', 'เบิกไม่ได้นะ');
            } 
            return true;                          
        }

        if($ven_time  == '08:30:00'){   
            $dB = date('Y-m-d', strtotime($ven_date));
            $dB1 = date('Y-m-d', strtotime('-1 day', strtotime($ven_date)));

            $modelVO = Ven::find()
                ->where([
                    'ven_date' => [$dB,$dB1],
                    'ven_time' => '16:30:00',
                    'status' => 1,
                    'user_id' => $this->user_id2,
                ])->orWhere([
                    'ven_date' => [$dB],
                    'ven_time' => ['08:30:00'],
                    'status' => 1,
                    'user_id' => $this->user_id2,
                ])->count();
            if($modelVO >= 1){
                $this->addError('user_id2', 'เบิกไม่ได้นะ');
            }               
        }

        if($ven_time  == '16:30:55'){               
            $dB = date('Y-m-d', strtotime($ven_date));
            $dB1 = date('Y-m-d', strtotime('+1 day', strtotime($ven_date)));
            $modelVO = Ven::find()
            ->where([
                'ven_date' => [$dB,$dB1],
                'ven_time' => ['08:30:01','08:30:11','08:30:22'],
                'status' => 1,
                // 'status' => 1,
                'user_id' => $this->user_id2,
            ])->orWhere([
                'ven_date' => [$dB],
                'ven_time' => ['16:30:55'],
                'status' => 1,
                // 'status' => 1,
                'user_id' => $this->user_id2,
            ])->count();   
            if($modelVO >= 1){
                $this->addError('user_id2', 'เบิกไม่ได้นะ');
            }
        }

        if($ven_time  == '16:30:00'){   
            $dB = date('Y-m-d', strtotime($ven_date));
            $dB1 = date('Y-m-d', strtotime('+1 day', strtotime($ven_date)));

            $modelVO = Ven::find()
            ->where([
                'ven_date' => [$dB,$dB1],
                'ven_time' => ['08:30:00'],
                'status' => 1,
                // 'status' => 1,
                'user_id' => $this->user_id2,
            ])->orWhere([
                'ven_date' => [$dB],
                'ven_time' => ['16:30:00'],
                'status' => 1,
                // 'status' => 1,
                'user_id' => $this->user_id2,
            ])->count();   
            if($modelVO >= 1){
                $this->addError('user_id2', 'เบิกไม่ได้นะ');
            }
        }
        
    }


    public function getProfile()
    {
        return $this->hasOne(Profile::className(), ['id' => 'user_id1']);
    }
    public function getProfile2()
    {
        return $this->hasOne(Profile::className(), ['id' => 'user_id2']);
    }

    public function getProfileName1(){
        $model=$this->profile;
        return $model ? $model->name: '-';
    } 

    public function getProfileName2(){
        $model=$this->profile2;
        return $model ? $model->name : '-';
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
