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
        return '{{%line}}';
    }

    
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name','status'], 'required'],
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
            'status' => 'สถานะ',
        ];
    }

    public function getUser()
    {
        return $this->hasOne(User::className(), ['username' => 'name']);
    }

    public function getProfileName(){
        $model=$this->user;
        return $model ? $model->profile->fname.$model->profile->name.' '.$model->profile->sname : null ;
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

    public function actionCallback()
    {
        if(!empty($_GET['error'])){
            Yii::$app->session->setFlash('warning', 'ไม่สามารถตั้งค่าได้'.$_GET['error']);
            return $this->redirect('line_index');            
        }

        $client_id = '4FLzeUXbqtIa5moAG1wtel';
        $client_secret = 'zJyajyRcooJePqyLBzjWGJA9Zu7rRL6qTC0h8fYn0Xp';

        $api_url = 'https://notify-bot.line.me/oauth/token';
        $callback_url = 'http://192.168.0.15/basic/web/line/callback';

        parse_str($_SERVER['QUERY_STRING'], $queries);

        //var_dump($queries);
        $fields = [
            'grant_type' => 'authorization_code',
            'code' => $queries['code'],
            'redirect_uri' => $callback_url,
            'client_id' => $client_id,
            'client_secret' => $client_secret
        ];
        
        try {
            $ch = curl_init();
        
            curl_setopt($ch, CURLOPT_URL, $api_url);
            curl_setopt($ch, CURLOPT_POST, count($fields));
            curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        
            $res = curl_exec($ch);
            curl_close($ch);

            $model = new Line();

        
            if ($res == false){
                Yii::$app->session->setFlash('info', 'ส่งไม่ได้');
                return false;
                throw new Exception(curl_error($ch), curl_errno($ch));
            }
        
            $json = json_decode($res);

            if($json->status == 200){
                $model = new Line();
                !empty($_GET['state']) ? 
                    $model->name = $_GET['state']
                    : 
                    $model->name = Yii::$app->user->identity->username;
                $model->token =  $json->access_token;
                $model->status = 1;
                $model->save();
                Yii::$app->session->setFlash('success', 'บันทึกข้อมูล สำเร็จ');
            }
            
        
            //var_dump($json);
        } catch(Exception $e) {
            throw new Exception($e->getMessage());
            //var_dump($e);
        }
        // return $this->render('callback', [
        //     'json' => $json
        // ]);
        return $json;
    }

    public function getToken($name)
    {
        $modelLine = Line::findOne(['name' => $name,'status' => 1]);
        
        return $modelLine ? $modelLine->token : false;
    }
    
    public function getTokenbyid($id)
    {
        $model_user = User::findOne($id);
        $modelLine = Line::findOne(['name' => $model_user['username'],'status' => 1]);
        
        return $modelLine ? $modelLine->token : false;
    }
}
