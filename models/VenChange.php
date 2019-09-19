<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
/*
 */
class VenChange extends \yii\db\ActiveRecord
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
            [['ven_id1','ven_id2'],'required'],   
            [['ven_id2'], 'validateVen_id2'],
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
            'comment' => 'เนื่องจาก',      
        ];
    }  
    
    public function validateVen_id2()
    {
        
        // $this->addError('ven_id2', $this->ven_id1);
        $model2 = Ven::findOne($this->ven_id1);
        $model = Ven::findOne($this->ven_id2);
        $user_id = (string)$model2->user_id;

        if($model->ven_time == '08:30:01' || $model->ven_time == '08:30:11' || $model->ven_time == '08:30:22'){   
            $dB = date('Y-m-d', strtotime($model->ven_date));
            $dB1 = date('Y-m-d', strtotime('-1 day', strtotime($model->ven_date)));
            
            $modelVO = Ven::find()
                ->where([
                    'ven_date' => [$dB,$dB1],
                    'ven_time' => '16:30:55',
                ])
                ->orWhere([
                    'ven_date' => [$dB],
                    'ven_time' => ['08:30:01','08:30:11','08:30:22'],
                ])
                ->andWhere([
                    'status' => 1,
                    'user_id' => $user_id,
                    ])
                ->count();
               
            if($modelVO >= 1){
                $this->addError('ven_id2', 'เบิกไม่ได้นะ'.$user_id );
            }   
                        
        }

        // $this->addError('ven_id2', 'เบิกไม่ได้นะ'.$user_id );

        if($model->ven_time == '08:30:00'){   
            $dB = date('Y-m-d', strtotime($model->ven_date));
            $dB1 = date('Y-m-d', strtotime('-1 day', strtotime($model->ven_date)));

            $modelVO = Ven::find()
                ->where([
                    'ven_date' => [$dB,$dB1],
                    'ven_time' => '16:30:00',
                    'status' => 1,
                    'user_id' => Yii::$app->user->identity->id,
                ])->orWhere([
                    'ven_date' => [$dB],
                    'ven_time' => ['08:30:00'],
                    'status' => 1,
                    'user_id' => Yii::$app->user->identity->id,
                ])->count();
            if($modelVO >= 1){
                $this->addError('ven_id2', 'เบิกไม่ได้นะ');
            }               
        }

        if($model->ven_time == '16:30:55'){   
            
            $dB = date('Y-m-d', strtotime($model->ven_date));
            $dB1 = date('Y-m-d', strtotime('+1 day', strtotime($model->ven_date)));

            $modelVO = Ven::find()
            ->where([
                'ven_date' => [$dB,$dB1],
                'ven_time' => ['08:30:01','08:30:11','08:30:22'],
                'status' => 1,
                // 'status' => 1,
                'user_id' => Yii::$app->user->identity->id,
            ])->orWhere([
                'ven_date' => [$dB],
                'ven_time' => ['16:30:55'],
                'status' => 1,
                // 'status' => 1,
                'user_id' => Yii::$app->user->identity->id,
            ])->count();   
            if($modelVO >= 1){
                $this->addError('ven_id2', 'เบิกไม่ได้นะ');
            }
        }

        if($model->ven_time == '16:30:00'){   
            $dB = date('Y-m-d', strtotime($model->ven_date));
            $dB1 = date('Y-m-d', strtotime('+1 day', strtotime($model->ven_date)));

            $modelVO = Ven::find()
            ->where([
                'ven_date' => [$dB,$dB1],
                'ven_time' => ['08:30:00'],
                'status' => 1,
                // 'status' => 1,
                'user_id' => Yii::$app->user->identity->id,
            ])->orWhere([
                'ven_date' => [$dB],
                'ven_time' => ['16:30:00'],
                'status' => 1,
                // 'status' => 1,
                'user_id' => Yii::$app->user->identity->id,
            ])->count();   
            if($modelVO >= 1){
                $this->addError('ven_id2', 'เบิกไม่ได้นะ');
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

    public function getSBN1()
    {
        return $this->hasOne(SignBossName::className(), ['id' => 's_po','status' => 1]);
    }

    public function getSBN2()
    {
        return $this->hasOne(SignBossName::className(), ['id' => 's_bb','status' => 1]);
    }

    public function getProfileName(){
        $model=$this->profile;
        return $model ? $model->fname.$model->name.' '.$model->sname : '-';
    }

    public function getProfileName2(){
        $model=$this->profile2;
        return $model ? $model->fname.$model->name.' '.$model->sname : '-';
    } 

    public function getS_SS($id){
        $model = SignBossName::findOne($id);
        return  $model ? $model : '-';
         
    }

    public function getS_bb(){
        $model=$this->sBN2;
        return [
            'name' => $model->name,
            'dep1' => $model->dep1,
            'dep2' => $model->dep2,
            'dep3' => $model->dep3,
        ];
    }


    public function getSignList(){
        $model = SignBossName::find()->where(['status' => '1'])->orderBy('id')->all();
        return ArrayHelper::map($model,'id','name');
    }

    public function getQr($id){
        $source = Url::to('@webroot/uploads/ven/'.$id.'.png');
        if(is_file($source)){
            return Url::to('@web/uploads/ven/'.$id.'.png');
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
            '77' => 'ลบ',                        
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
    
    public function getVen_time(){   
        return [
            '08:30:00' => '8.30 น. ถึง 16.30 น.',
            '08:30:01' => '8.30 น. ถึง 16.30 น.',
            '08:30:11' => '8.30 น. ถึง 16.30 น.',
            '08:30:22' => '8.30 น. ถึง 16.30 น.',
            '16:30:00' => '16.30 น. ถึง 8.30 น.',
            '16:30:55' => '16.30 น. ถึง 8.30 น.',
        ];
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
