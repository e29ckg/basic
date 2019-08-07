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
    public function notify_message($token,$message)
    {
        
        // $message = 'test send photo';    //text max 1,000 charecter
        
        $line_api = 'https://notify-api.line.me/api/notify';
        // $line_token = 'FVJfvOHD7nkd9mSTxN5573tVSpVuiK8JTEAIgSAOYZx'; //แบบแซบ
        // $line_token = '4A51UznK0WDNjN1W7JIOMyvcsUl9mu7oTHJ1G1u8ToK';
        $line_token = $token;
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
        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function send_notify_message($line_api, $access_token, $message_data){
        $headers = array('Method: POST', 'Content-type: multipart/form-data', 'Authorization: Bearer '.$access_token );
     
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $line_api);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $message_data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $result = curl_exec($ch);
        // Check Error
        if(curl_error($ch))
        {
           $return_array = array( 'status' => '000: send fail', 'message' => curl_error($ch) ); 
        }
        else
        {
           $return_array = json_decode($result, true);
        }
        curl_close($ch);
     return $return_array;
     }
}
