<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
/**
 * This is the model class for table "bila".
 *
 * @property int $id
 * @property int $user_id
 * @property string $cat
 * @property string $date_begin
 * @property string $date_end
 * @property string $date_total
 * @property string $dateO_begin
 * @property string $dateO_end
 * @property string $dateO_total
 * @property string $address
 * @property string $t1
 * @property string $t2
 * @property string $t3
 * @property string $po
 * @property string $bigboss
 * @property string $date_create
 */
class Bila extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%bila}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'date_begin', 'date_end', 'date_total','t1'], 'required'],
            [['user_id',], 'integer'],
            [['p1','date_total','dateO_total', 't1'], 'number'],
            [['t3','p2'], 'safe'],
            [['file'], 'file', 'extensions' => 'pdf, png, jpg, jpeg', 'maxSize'=> 1024 * 1024 * 5],
            [['cat', 'date_begin', 'comment','date_end', 'dateO_begin', 'dateO_end', 'due', 'address', 't2', 'po', 'bigboss', 'date_create'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'cat' => 'ประเภทการลา',
            'p1' => 'มีวันลาพักผ่อนสะสม(วัน)',
            'p2' => 'พักผ่อนสะสมรวม',
            'date_begin' => 'ลาตั้งแต่วันที่',
            'date_end' => 'ถึงวันที่',
            'date_total' => 'มีกำหนด(วัน)',
            'due' => 'เนื่องจาก',
            'dateO_begin' => 'ลาครั้งสุดท้าย ตั้งแต่วันที่',
            'dateO_end' => 'ถึงวันที่',
            'dateO_total' => 'มีกำหนด(วัน)',
            'address' => 'ระหว่างลาติดต่อได้ที่',
            't1' => 'เคยลามาแล้ว(วัน)',
            't2' => 'T2',
            't3' => 'T3',
            'comment' => 'หมายเหตุ',
            'po' => 'ผู้อำนวยการลงนาม',
            'bigboss' => 'ผู้พิพากษาศาลหัวหน้าศาลลงนาม',
            'date_create' => 'Date Create',
        ];
    }

    public function getCat()
    {        
        return [
            'ลาป่วย' => 'ลาป่วย',
            'ลากิจส่วนตัว' => 'ลากิจส่วนตัว',
            'ลาคลอดบุตร' => 'ลาคลอดบุตร',
        ];           
    }

    public function getCountAll()
    {        
        return Bila::find()->count();           
    }

    public function getCountA()
    {        
        return Bila::find()->where(['cat' => 'ลาป่วย'])->count();           
    }
    
    public function getCountB()
    {        
        return Bila::find()->where(['cat' => 'ลาพักผ่อน'])->count();           
    }

    public function getSignList(){
        $model = SignBossName::find()->where(['status' => '1'])->orderBy('id')->all();
        return ArrayHelper::map($model,'id','name');
    }

    public function getQr($id,$user_id){
        $source = Url::to('@webroot/uploads/bila/'.$user_id.'/'.$id.'/'.$id.'.png');
        if(is_file($source)){
            $link = Url::to('@web/uploads/bila/'.$user_id.'/'.$id.'/'.$id.'.png');
        }else{
            $link = Url::to('@web/img/none.png');
        } 
        return $link ;   
        // return Url::to('@webroot/uploads/bila/'.$user_id.'/'.$id.'/'.$id.'.png');
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

    public function getProfile()
    {
        return $this->hasOne(Profile::className(), ['id' => 'user_id']);
    }
    public function getProfileName(){
        $model=$this->profile;
        return $model ? $model->fname.$model->name.' '.$model->sname : '-';
    }

}
