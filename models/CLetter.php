<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;
// use app\models\CLetterCaid;

/**
 * This is the model class for table "c_letter".
 *
 * @property int $id
 * @property string $name
 * @property int $caid
 * @property string $file
 * @property int $status
 * @property string $created_at
 * @property string $updated_at
 */
class CLetter extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'c_letter';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'caid'], 'required'],
            [['caid', 'status'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['name', 'file'], 'string', 'max' => 255],
            [['name'], 'unique','message'=>'Name already exist. Please try another one.'],
            [['file'], 'file', 'extensions' => 'pdf, png, jpg', 'maxSize'=> 1024 * 1024 * 10],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'ชื่อเรื่อง',
            'caid' => 'ประเภทเอกสาร',
            'file' => 'File',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
    
    public function getCountAll()
    {        
        return CLetter::find()->count();           
    }

    public function getCLetterCaid()
    {
        return $this->hasOne(CLetterCaid::className(), ['id' => 'caid']);
    }

    public function getCaidName(){
        $model=$this->cLetterCaid;
        return $model ? $model->name : '';
    }

    public function getProfileName($id)
    {
        $model = Profile::find()->where(['user_id' => $id])->one();

        return $model->name ? $model->fname.$model->name.' '.$model->sname : $id ;
    }

    public function getCaidList(){
        $model = CLetterCaid::find()->select('id, name')->orderBy('id')->all();
        return ArrayHelper::map($model,'id','name');
    }

    //ส่งข้อความผ่าน line Notify
    public function notify_message_admin($message)
    {
        
        // $message = 'test send photo';    //text max 1,000 charecter
        
        $line_api = 'https://notify-api.line.me/api/notify';
        $line_token = 'ZdybtZEIVc4hBMBirpvTOFf8fBP4n3EIOFxgWhSFDwi'; //ส่วนตัว
        // $line_token = '4A51UznK0WDNjN1W7JIOMyvcsUl9mu7oTHJ1G1u8ToK';
        $queryData = array('message' => $message);
        $queryData = http_build_query($queryData,'','&');
        $headerOptions = array(
            'http'=>array(
                'method'=>'POST',
                'header'=> "Content-Type: application/x-www-form-urlencoded\r\n"
                    ."Authorization: Bearer ".$line_token."\r\n"
                    ."Content-Length: ".strlen($queryData)."\r\n",
                'content' => $queryData
            )
        );
        $context = stream_context_create($headerOptions);
        $result = file_get_contents($line_api, FALSE, $context);
        $res = json_decode($result);
        
        return $res;
    
    }
}
