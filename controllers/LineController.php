<?php

namespace app\controllers;

use Yii;
use app\models\Line;
use app\models\Notify;
use KS\Line\LineNotify;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use app\models\UploadForm;
use yii\web\UploadedFile;
use yii\helpers\Url;
use yii\web\Response;
use yii\widgets\ActiveForm;
use yii\db\Query;

/**
 * LineController implements the CRUD actions for Line model.
 */
class LineController extends Controller
{
    /**
     * {@inheritdoc}
     * 
     * 
     */
    public $client_id = '4FLzeUXbqtIa5moAG1wtel';
    public $client_secret = 'zJyajyRcooJePqyLBzjWGJA9Zu7rRL6qTC0h8fYn0Xp';
    public $callback_url = 'http://192.168.0.15/basic/web/line/callback';

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['index11','index','create','show','all'],
                'rules' => [
                    [
                        // 'actions' => ['index','create','show','all'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Line models.
     * @return mixed
     */
    public function actionIndex()
    {
        $model = Line::find()->orderBy([
            'name'=>SORT_ASC,
            // 'id' => SORT_DESC,
            ])->limit(100)->all();
        
        return $this->render('line_index',[
            'models' => $model,
        ]);

    }


    /**
     * Displays a single Line model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    
    public function actionLine_alert($id) {
        $model = $this->findModel($id);
        $message = $model->name .' ดูรายละเอียดเพิ่มเติมได้ที่ เว็บภายใน http://10.37.64.01/Line.php?ref='.$model->file;
        $res = $this->notify_message($message);
        if($res->status == 200){
            Yii::$app->session->setFlash('success', 'Line Notify '.$res->message);
        }else{
            Yii::$app->session->setFlash('error', 'Line Notify '.$res->message);
        }
        
        return $this->redirect(['index', 'ses' => $res]);
        // $token = 'FVJfvOHD7nkd9mSTxN5573tVSpVuiK8JTEAIgSAOYZx';
        // $ln = new KS\Line\LineNotify($token);

        // $text = 'Hello Line Notify';
        // $ln->send($text);
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

    /*
    public function notify_message($message)
    {
        
        // $message = 'test send photo';    //text max 1,000 charecter
        
        $line_api = 'https://notify-api.line.me/api/notify';
        // $line_token = 'FVJfvOHD7nkd9mSTxN5573tVSpVuiK8JTEAIgSAOYZx'; //แบบแซบ
        $line_token = '4A51UznK0WDNjN1W7JIOMyvcsUl9mu7oTHJ1G1u8ToK';
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
    
    }*/

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

     
    public function actionLine_index()
    {
        $model = Line::find()->orderBy([
            'name'=>SORT_ASC,
            // 'id' => SORT_DESC,
            ])->all();
                
            // $client_id = '4FLzeUXbqtIa5moAG1wtel';
            $api_url = 'https://notify-bot.line.me/oauth/authorize?';
            // $callback_url = 'http://192.168.0.15/basic/web/line/callback';
    
            $query = [
                'response_type' => 'code',
                'client_id' => $this->client_id,
                'redirect_uri' => $this->callback_url,
                'scope' => 'notify',
                'state' => 'MyApp'
            ];
            
            $result = $api_url . http_build_query($query);    
            
        return $this->render('line_index',[
            'models' => $model,
            'result' => $result
        ]);

    }

    public function actionLine_create(){
        
        $model = new Line();

        //Add This For Ajax Email Exist Validation 
        if(Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())){
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
          } 
     
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
             
            // $model->name = 'name';
            $model->name = $_POST['Line']['name'];
            $model->status = $_POST['Line']['status'];

            if($model->save()){                          
                Yii::$app->session->setFlash('success', 'บันทึกข้อมูลเรียบร้อย');                
                return $this->redirect(['line_index','name'=>$model->name]);
            }   
        }

        if(Yii::$app->request->isAjax){
            return $this->renderAjax('line_create',[
                    'model' => $model,                    
            ]);
        }else{
            return $this->render('line_create',[
                'model' => $model,                    
            ]); 
        }

    }

    public function actionLine_update($id){
        
        $model = Line::findOne($id);

        //Add This For Ajax Email Exist Validation 
        if(Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())){
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
          } 
     
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
             
            $model->name = $_POST['Line']['name'];
            $model->status = $_POST['Line']['status'];

            if($model->save()){                          
                Yii::$app->session->setFlash('success', 'บันทึกข้อมูลเรียบร้อย');                
                return $this->redirect(['line_index']);
            }   
        }

        if(Yii::$app->request->isAjax){
            return $this->renderAjax('line_update',[
                    'model' => $model,                    
            ]);
        }else{
            return $this->render('line_update',[
                'model' => $model,                    
            ]); 
        }
    }

    public function actionLine_del($id)
    {
        $model = Line::findOne($id);
        
        if($model->delete()){
            Yii::$app->session->setFlash('success', 'ลบข้อมูลเรียบร้อย');  
        }        

        return $this->redirect(['line_index']);
    }

    public function actionIndex11()
    {
        $client_id = '4FLzeUXbqtIa5moAG1wtel';
        $api_url = 'https://notify-bot.line.me/oauth/authorize?';
        $callback_url = 'http://192.168.0.15/basic/web/line/callback';

        $query = [
            'response_type' => 'code',
            'client_id' => $client_id,
            'redirect_uri' => $callback_url,
            'scope' => 'notify',
            'state' => 'Cletter'
        ];
        
        $result = $api_url . http_build_query($query);

        return $this->render('index11',[
            'result' => $result
        ]);
    }

    public function actionCallback()
    {
        if(!empty($_GET['error'])){
            Yii::$app->session->setFlash('warning', 'ไม่สามารถตั้งค่าได้'.$_GET['error']);
            return $this->redirect('line_index');
            
        }
        // $client_id = '4FLzeUXbqtIa5moAG1wtel';
        // $client_secret = 'zJyajyRcooJePqyLBzjWGJA9Zu7rRL6qTC0h8fYn0Xp';

        $api_url_token = 'https://notify-bot.line.me/oauth/token';
        // $callback_url = 'http://192.168.0.15/basic/web/line/callback';

        parse_str($_SERVER['QUERY_STRING'], $queries);

        //var_dump($queries);
        $fields = [
            'grant_type' => 'authorization_code',
            'code' => $queries['code'],
            'redirect_uri' => $this->callback_url,
            'client_id' => $this->client_id,
            'client_secret' => $this->client_secret
        ];
        
        try {
            $ch = curl_init();
        
            curl_setopt($ch, CURLOPT_URL, $api_url_token);
            curl_setopt($ch, CURLOPT_POST, count($fields));
            curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        
            $res = curl_exec($ch);
            curl_close($ch);

            $model = new Line();

        
            if ($res == false)
                throw new Exception(curl_error($ch), curl_errno($ch));
        
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
        return $this->redirect('line_index');
    }

    public function actionNotify($token)
    {
        $api_url = 'https://notify-api.line.me/api/notify';

        $model = new Notify();
        $json = null;
        if($model->load(Yii::$app->request->post())){
            $headers = [
                'Authorization: Bearer ' . $token
            ];
            $fields = [
                'message' => 'ทดสอบการส่งข้อความไปยังผู้ใช้งาน '. $model->name
            ];
            
            try {
                $ch = curl_init();
            
                curl_setopt($ch, CURLOPT_URL, $api_url);
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                curl_setopt($ch, CURLOPT_POST, count($fields));
                curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            
                $res = curl_exec($ch);
                curl_close($ch);
            
                if ($res == false)
                    throw new Exception(curl_error($ch), curl_errno($ch));
            
                $json = json_decode($res);
                //$status = $json->status;
            
                //var_dump($status);
            } catch (Exception $e) {
                throw new Exception($e->getMessage);
            }
        }
        return $this->render('notify', [
            'model' => $model,
            'json' => $json
        ]);
    }
           

}
