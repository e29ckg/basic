<?php

namespace app\controllers;

use Yii;
use app\models\Line;
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
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['index','create','show','all'],
                'rules' => [
                    [
                        'actions' => ['index','create','show','all'],
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

     public function actionLine_index()
    {
        $model = Line::find()->orderBy([
            'name'=>SORT_ASC,
            // 'id' => SORT_DESC,
            ])->all();
                
        return $this->render('line_index',[
            'models' => $model,
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
             
            $model->name = $_POST['Line']['name'];
            $model->status = $_POST['Line']['status'];

            if($model->save()){                          
                Yii::$app->session->setFlash('success', 'บันทึกข้อมูลเรียบร้อย');                
                return $this->redirect(['line_index']);
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
           

}
