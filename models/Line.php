<?php

namespace app\models;

use Yii;
use yii\web\NotFoundHttpException;
/**
 * This is the model class for table "line".
 *
 * @property int $id
 * @property string $name
 * @property string $token
 */
class Line extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'line';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name', 'token'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'programe/user_id',
            'token' => 'Token',
            'stutus' => 'สถานะ',
        ];
    }

    public function notify_message($token,$message){
        $mms =  trim($message);
        date_default_timezone_set("Asia/Bangkok");
        $line_api = $token;
        
        $chOne = curl_init();
        curl_setopt($chOne, CURLOPT_URL, "https://notify-api.line.me/api/notify");
        // SSL USE 
        curl_setopt( $chOne, CURLOPT_SSL_VERIFYHOST, 0); 
        curl_setopt( $chOne, CURLOPT_SSL_VERIFYPEER, 0); 

        //POST 
        curl_setopt( $chOne, CURLOPT_POST, 1); 
        curl_setopt( $chOne, CURLOPT_POSTFIELDS, "message=$mms"); 
        curl_setopt( $chOne, CURLOPT_FOLLOWLOCATION, 1); 
        $headers = array( 'Content-type: application/x-www-form-urlencoded', 'Authorization: Bearer '.$line_api.'', );
        curl_setopt($chOne, CURLOPT_HTTPHEADER, $headers); 
        curl_setopt( $chOne, CURLOPT_RETURNTRANSFER, 1); 
        
        $result = curl_exec($chOne);
        // Check Error
        if(curl_error($chOne))
        {
           $res = ['status' => '000: send fail', 'message' => curl_error($chOne)]; 
        }
        else
        {
           $res = json_decode($result, true);
        }
        curl_close($chOne);
     return $res;
    }
}
