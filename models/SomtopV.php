<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "somtop_v".
 *
 * @property int $id
 * @property string $ven_date
 * @property string $legal_c_id
 * @property string $comment
 * @property string $create_at
 */
class SomtopV extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'somtop_v';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['ven_date','somtop_id'], 'required'],
            [['ven_date', 'create_at'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'ven_date' => 'Ven Date',
            'somtop_id' => 'ชื่อที่ปรึกษากฎหมาย',
            'created_at' => 'Create At',
        ];
    }

    public function getUserList(){
        $models = Somtop::find()->where(['st' => '1'])->orderBy(['name' => SORT_ASC ])->all();
        return ArrayHelper::map($models,'id',function($model){
            return $model->name;
        });        
    }

    public function getSomtop()
    {
        return $this->hasOne(Somtop::className(), ['id' => 'somtop_id']);
    }
    
    public function getName(){
        $model=$this->somtop;
        // return $model ? $model->name: '-';
        return $model ? $model->name : '-';
    }

    // public function getPhone(){
    //     $model=$this->legal;
    //     // return $model ? $model->name: '-';
    //     return $model ? $model->phone:'';
    // }

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
