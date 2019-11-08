<?php

namespace app\models;
use yii\web\UploadedFile;

use Yii;

/**
 * This is the model class for table "ppss".
 *
 * @property integer $id
 * @property string $black
 * @property string $red
 * @property string $persecutor
 * @property string $accused
 * @property integer $page
 * @property string $note
 * @property string $file
 * @property string $create_at
 * @property string $create_own
 */
class Ppss extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public $f;

    public $thai_day_arr=array("อาทิตย์","จันทร์","อังคาร","พุธ","พฤหัสบดี","ศุกร์","เสาร์");
    public $thai_month_arr=array(
    "0"=>"",
    "1"=>"มกราคม",
    "2"=>"กุมภาพันธ์",
    "3"=>"มีนาคม",
    "4"=>"เมษายน",
    "5"=>"พฤษภาคม",
    "6"=>"มิถุนายน", 
    "7"=>"กรกฎาคม",
    "8"=>"สิงหาคม",
    "9"=>"กันยายน",
    "10"=>"ตุลาคม",
    "11"=>"พฤศจิกายน",
    "12"=>"ธันวาคม"                 
);
    
    public static function tableName()
    {
        return 'ppss';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['page'], 'integer'],
            [['note'], 'string'],
            [['create_at'], 'safe'],
            [['file'], 'file', 'extensions' => 'pdf,PDF', 'maxSize'=> 1024 * 1024 * 10],
            [['file2'], 'file', 'extensions' => 'pdf,PDF', 'maxSize'=> 1024 * 1024 * 10],
            [['name','file' ,'black', 'red', 'persecutor', 'accused'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'เลขดำ',
            'black' => 'Black',
            'red' => 'เลขแดง',
            'persecutor' => 'โจทย์',
            'accused' => 'จำเลย',
            'page' => 'จำนวนหน้า',
            'note' => 'Note',
            'file' => 'File คำพิพากษา',
            'file2' => 'File ร่างจากภาค',
            'create_at' => 'Create At',
            'create_own' => 'Create Own',
        ];
    }
    public function upload()
    {
        if ($this->validate()) {
            $this->file->saveAs('ppss/' . $this->file->baseName . '.' . $this->file->extension);
            return true;
        } else {
            return false;
        }
    }

    public function getCountAll()
    {        
        return  Ppss::find()->count();           
    }

    public function getCountM1()
    {     
        $y = date('Y');
        $m = date('m');   
        return  Ppss::find()->where("YEAR(create_at) = $y AND MONTH(create_at) = $m")->count();           
    }

    public function getCountM2()
    {     
        $y = date('Y');
        $m = (date('m') - 1);
        if ($m  == 0) {
            $y = date('Y')-1 ;
            $m = 12;   
              
        }  
        return  Ppss::find()->where("YEAR(create_at) = $y AND MONTH(create_at) = $m")->count();           
    }

    public function getNameM1()
    {   
        $m = date("F-Y");   
        return $m ;           
    }

    public function getNameM2()
    {     
        $y = date('Y');
        $m = date('m') - 1;
        if ($m == 0) {
            $y = date('Y') - 1;
            $m = 12;            
        }             
        $a = $y."-".$m;
        $m = date("F-Y",strtotime($a));
        return  $m;           
    }
    public function getViewM1()
    {     
        $y = date('Y');
        $m = date('m');   
        
        // $y = date("Y", strtotime($year));
        // $m = date("m", strtotime($year));        
        return  Ppss::find()->where("YEAR(create_at) = $y AND MONTH(create_at) = $m")->all();           
    }

    public function getViewM2()
    {     
        $y = date('Y');
        $m = date('m') - 1;
        if ($m  == 0) {
            $y = date('Y') - 1;
            $m = 12;            
        } 
        // $y = date("Y", strtotime($year));
        // $m = date("m", strtotime($year));        
        return  Ppss::find()->where("YEAR(create_at) = $y AND MONTH(create_at) = $m")->all();           
    }

    public function getDownloadFile($fileName)
    {    
        // $dir = Url::to('@webroot/uploads/ppss/'); 
        $PathFile = Url::to('@webroot/uploads/ppss/').$fileName; 
        if (file_exists($filename)) {
            // return Yii::$app->response->sendFile($completePath, $model->file2, ['inline'=>true]);   
            return Yii::$app->response->sendFile($Path, $fileName);
        }  
        return 'No File';        
    }
    
    public function getProfile()
    {
        return $this->hasOne(Profile::className(), ['user_id' => 'create_own']);
    }

    public function getProfileName(){
        $model = $this->profile;
        return $model ? $model->fname.$model->name.' '.$model->sname : '';
    }

    public function getMthai1(){
        $thai_month_arr=array(
            "0"=>"",
            "1"=>"มกราคม",
            "2"=>"กุมภาพันธ์",
            "3"=>"มีนาคม",
            "4"=>"เมษายน",
            "5"=>"พฤษภาคม",
            "6"=>"มิถุนายน", 
            "7"=>"กรกฎาคม",
            "8"=>"สิงหาคม",
            "9"=>"กันยายน",
            "10"=>"ตุลาคม",
            "11"=>"พฤศจิกายน",
            "12"=>"ธันวาคม"                 
        );
        $y = date('Y');
        $m = date('m');   
        $eng_date=strtotime("$y-$m-1"); 
        $y = $y + 543;
        $thai_date_return =$thai_month_arr[date("n",$eng_date)] .' '.$y;
        return $thai_date_return;
    }
    public function getMthai2(){
        $thai_month_arr=array(
            "0"=>"",
            "1"=>"มกราคม",
            "2"=>"กุมภาพันธ์",
            "3"=>"มีนาคม",
            "4"=>"เมษายน",
            "5"=>"พฤษภาคม",
            "6"=>"มิถุนายน", 
            "7"=>"กรกฎาคม",
            "8"=>"สิงหาคม",
            "9"=>"กันยายน",
            "10"=>"ตุลาคม",
            "11"=>"พฤศจิกายน",
            "12"=>"ธันวาคม"                 
        );
        $y = date('Y');
        $m = date('m') - 1;
        if ($m == 0) {
            $y = date('Y') - 1;
            $m = 12;            
        }
       
        $eng_date=strtotime("$y-$m"); 
         $y = $y + 543;
        $thai_date_return = $thai_month_arr[date("n",$eng_date)] .' '.$y;
        return $thai_date_return;
    }
}
