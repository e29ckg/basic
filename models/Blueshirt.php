<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;
// use app\models\CLetterCaid;

/**
 * This is the model class for table "blueshirt".
 *
 */
class Blueshirt extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'blueshirt';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id','user_id2','line_alert'], 'required'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'เวร',
            'user_id2' => 'ผู้ตรวจ',
            'file' => 'File',
            'line_alert' => 'วันที่',
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

    public function getProfile2()
    {
        return $this->hasOne(Profile::className(), ['id' => 'user_id2']);
    }
    
    public function getProfileName2(){
        $model=$this->profile2;
        return $model ? $model->fname.$model->name.' '.$model->sname : '-';
    }

    public function getUserList(){
        $model = Profile::find()->where(['status' => '10'])->orderBy(['name' => SORT_ASC ])->all();
        return ArrayHelper::map($model,'id',function($model){
            return $model->name.' '.$model->sname;
        });
    }    
    
    public function DateThai_full($strDate)
	{
        if($strDate == ''){
            return "-";
        }
		$strYear = date("y",strtotime($strDate))+43;
		$strMonth= date("n",strtotime($strDate));
		$strDay= date("j",strtotime($strDate));
		$strHour= date("H",strtotime($strDate));
		$strMinute= date("i",strtotime($strDate));
		$strSeconds= date("s",strtotime($strDate));
		$strMonthCut = Array("","ม.ค.","ก.พ.","มี.ค.","เม.ย.","พ.ค.","มิ.ย.","ก.ค.",
                            "ส.ค.","ก.ย.","ต.ค.","พ.ย","ธ.ค.");
		$strMonthThai=$strMonthCut[$strMonth];
		return "$strDay $strMonthThai $strYear $strHour:$strMinute";
    }

    
}
