<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "court_order_boss".
 *
 * @property int $id
 * @property string $year
 * @property string $num
 * @property string $name
 * @property string $file
 * @property string $create_at
 */
class CourtOrderBoss extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'court_order_boss';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['num', 'name', 'year', 'date_write', 'date_write'], 'required'],
            [['num','year'], 'integer','message' => 'ตัวเลขเท่านั้นนะ'],
            [['num'], 'unique', 'targetAttribute' => ['num', 'year'],'message' => 'เลขซ้ำ'],
            ['name', 'string', 'max' => 255],
            // ['year', 'default', 'value' => date('Y-m-d', strtotime('today'))],
            // [['create_at'], 'safe'],
            // [['year', 'num', 'name', 'file'], 'string', 'max' => 255],
            ['file', 'file', 'extensions' => ['png', 'jpg', 'gif','pdf'], 'maxSize' => 1024*1024*5],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'year' => 'ปี',
            'num' => 'เลขคำสั่ง',
            'date_write' => 'ลงวันที่',
            'name' => 'เรื่อง',
            'file' => 'File',
            'create_at' => 'Create At',
        ];
    }
    public function getProfile()
    {
        return $this->hasOne(Profile::className(), ['id' => 'owner']);
    }

    public function getProfileName()
    {
        $model = $this->profile;
        // return $model ? $model->name: '-';
        return $model ? $model->fname . $model->name . ' ' . $model->sname : '-';
    }

    public function getProfileGroup()
    {
        $model = $this->profile;
        // return $model ? $model->name: '-';
        return $model ? $model->workgroup : '-';
    }
    
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'owner']);
    }

    public function getUserRole()
    {
        $model = $this->user;
        // return $model ? $model->name: '-';
        return $model ? $model->role : '9';
    }
    public function DateThai($strDate)
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
		$strMonthCut = Array("","ม.ค.","ก.พ","มี.ค.","เม.ย","พ.ค.","มิ.ย.","ก.ค.",
                            "ส.ค","ก.ย.","ต.ค.","พ.ย.","ธ.ค.");
		$strMonthThai=$strMonthCut[$strMonth];
		return "$strDay $strMonthThai $strYear";
    }
}
