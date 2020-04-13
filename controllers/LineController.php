<?php

namespace app\controllers;

use Yii;
use app\models\Bila;
use app\models\Ven;
use app\models\CLetter;
use app\models\Line;
use app\models\LineFormSend;
use app\models\LineHome;
use app\models\Blueshirt;
use app\models\LegalCVen;
use app\models\SomtopV;
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

    // public $client_id = '4FLzeUXbqtIa5moAG1wtel';
    // public $client_secret = 'zJyajyRcooJePqyLBzjWGJA9Zu7rRL6qTC0h8fYn0Xp';
    // public $callback_url = 'http://localhost/basic/web/line/callback';

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
        $models = Line::find()->orderBy([
            'name'=>SORT_ASC,
            // 'id' => SORT_DESC,
            ])->all();
        $LineGroup = Line::findOne(['name' => 'LineGroup']);      

            $LineHome = LineHome::findOne(1);
            $client_id = $LineHome->client_id;
            $api_url = 'https://notify-bot.line.me/oauth/authorize?';
            $callback_url = $LineHome->callback_url;
    
            $query = [
                'response_type' => 'code',
                'client_id' => $client_id,
                'redirect_uri' => $callback_url,
                'scope' => 'notify',
                'state' => 'LineGroup'
            ];
            
            $result = $api_url . http_build_query($query);                         
        
        if ($LineHome->load(Yii::$app->request->post()) && $LineHome->validate()) {            
            // $model->name = 'name';
            $LineHome->client_id = $_POST['LineHome']['client_id'];
            $LineHome->client_secret = $_POST['LineHome']['client_secret'];
            $LineHome->name_ser = $_POST['LineHome']['name_ser'];
            $LineHome->api_url = $_POST['LineHome']['api_url'];
            $LineHome->callback_url = $_POST['LineHome']['callback_url'];
            if($LineHome->save()){                          
                Yii::$app->session->setFlash('success', 'บันทึกข้อมูลเรียบร้อย');                
                return $this->redirect(['line_index']);
            }   
        }

        $LineHomeAll = LineHome::find()->all();        
            
        return $this->render('line_index',[
            'LineGroup' => $LineGroup,
            'LineHome' => $LineHome,
            'models' => $models,
            'result' => $result,
            'LineHomeAll' => $LineHomeAll
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

    public function actionLine_delete($id)
    {
        $model = Line::findOne($id);
        
        if($model->delete()){
            Yii::$app->session->setFlash('success', 'ลบข้อมูลเรียบร้อย');  
        }        

        return $this->redirect(['line_index']);
    }

    
    public function actionCallback()
    {
        if(!empty($_GET['error'])){
            Yii::$app->session->setFlash('warning', 'ไม่สามารถตั้งค่าได้'.$_GET['error']);
            return $this->redirect('line_index');            
        }

        $LineHome = LineHome::findOne(1);
        $client_id = $LineHome->client_id;
        $client_secret = $LineHome->client_secret;

        $api_url_token = 'https://notify-bot.line.me/oauth/token';
        $callback_url = $LineHome->callback_url;

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

    public function actionLine_send($token)
    {
        
        $model = new LineFormSend();
        $json = null;
        if($model->load(Yii::$app->request->post())){
            $api_url = 'https://notify-api.line.me/api/notify';
            $headers = [
                'Authorization: Bearer ' . $token
            ];
            $fields = [
                'message' => $model->name
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
            
                if ($res == false){
                    Yii::$app->session->setFlash('info', 'ส่งไม่ได้');
                    return $this->redirect(['line_index']);
                    throw new Exception(curl_error($ch), curl_errno($ch));
                }
                    
            
                $json = json_decode($res);
                if($json->status == 200){
                    Yii::$app->session->setFlash('success', 'ส่งข้อความเรียบร้อย '.$json->status);
                    return $this->redirect(['line_index']);
                }
                //var_dump($status);
            } catch (Exception $e) {
                throw new Exception($e->getMessage);
            }
        }
        if(Yii::$app->request->isAjax){
            return $this->renderAjax('line_form_send',[
                    'model' => $model,  
                    'json' => $json                  
            ]);
        }
        return $this->render('line_form_send', [
            'model' => $model,
            'json' => $json
        ]);
    }  
    
    public function actionLinehome_create(){
        
        $LineHome = new LineHome();
            
        if ($LineHome->load(Yii::$app->request->post()) && $LineHome->validate()) {
            $LineHome->client_id = $_POST['LineHome']['client_id'];
            $LineHome->client_secret = $_POST['LineHome']['client_secret'];
            $LineHome->name_ser = $_POST['LineHome']['name_ser'];
            $LineHome->api_url = $_POST['LineHome']['api_url'];
            $LineHome->callback_url = $_POST['LineHome']['callback_url'];
            if($LineHome->save()){                          
                Yii::$app->session->setFlash('success', 'บันทึกข้อมูลเรียบร้อย');                
                return $this->redirect(['line_index']);
            }   
        }

        if(Yii::$app->request->isAjax){
            return $this->renderAjax('line_form_linehome',[
                    'LineHome' => $LineHome,                    
            ]);
        }else{
            return $this->render('line_form_linehome',[
                'LineHome' => $LineHome,                    
            ]); 
        }
    }

    public function actionLinehome_update($id){
        
        $LineHome = LineHome::findOne($id);
            
        if ($LineHome->load(Yii::$app->request->post()) && $LineHome->validate()) {
            $LineHome->client_id = $_POST['LineHome']['client_id'];
            $LineHome->client_secret = $_POST['LineHome']['client_secret'];
            $LineHome->name_ser = $_POST['LineHome']['name_ser'];
            $LineHome->api_url = $_POST['LineHome']['api_url'];
            $LineHome->callback_url = $_POST['LineHome']['callback_url'];
            if($LineHome->save()){                          
                Yii::$app->session->setFlash('success', 'บันทึกข้อมูลเรียบร้อย');                
                return $this->redirect(['line_index']);
            }   
        }

        if(Yii::$app->request->isAjax){
            return $this->renderAjax('line_form_linehome',[
                    'LineHome' => $LineHome,                    
            ]);
        }else{
            return $this->render('line_form_linehome',[
                'LineHome' => $LineHome,                    
            ]); 
        }
    }

    public function actionLinehome_delete($id)
    {
        $model = Linehome::findOne($id);
        
        if($model->delete()){
            Yii::$app->session->setFlash('success', 'ลบข้อมูลเรียบร้อย');  
        }        

        return $this->redirect(['line_index']);
    }

    /*---------------------------------------ส่ง line ประจำวัน--------------------------------------------*/

    public function actionLine_send_daily()
    {
        $strDate = date("Y-m-d",strtotime(date("Y-m-d"))) ;
        $thaiDate = Bila::DateThai_full($strDate); 
        $sms = $thaiDate;        

        $Q_model = Bila::find()
                        ->where("date_begin <= '$strDate'")
                        ->andWhere("date_end >= '$strDate'")
                        ->andWhere("status <> 4");
        if($Q_model->count() >= 1){
            $models = $Q_model->all();  
            $sms .= "\n";             
            $sms .= '--------E-La่---------'."\n";
            foreach ($models as $model):        
                $sms .= $model->profile->name .'->';
                $sms .= $model->cat.'('.$model->date_total.')';
                $model->comment ? $sms .= "\n".$model->comment : '' ;
                $sms .= "\n";
            endforeach;  
            $sms .= "\n";
        }
                
        $Q_model = Ven::find()->where(['ven_date' => $strDate,'status' => [1,2]]);        
        if($Q_model->count() >= 1){
            $models = $Q_model->orderBy(['ven_time'=>SORT_ASC])->all();  
            $sms .= "\n";
            $sms .= '--------E-VeN---------'."\n";
            foreach ($models as $model):

                if($model->ven_time == '08:30:00'){
                    $ven_boss['name'] = $model->getProfileName();
                    $ven_boss['phone'] = $model->getProfilePhone();
                }
                if($model->ven_time == '08:30:01'){
                    $ven_boss_h['name'] = $model->getProfileName();
                    $ven_boss_h['phone'] = $model->getProfilePhone();
                }  

                $sms .= date("H:i ",strtotime($model->ven_time));
                $sms .=' '.$model->getProfileNameCal();
                $sms .= $model->status == 1 ? '':' (รออนุมัติ)';
                $sms .= "\n";
                $sms_a = $thaiDate;
                $sms_a .= "\n";
                $sms_a .= $model->getProfileNameCal().' '.date("H:i ",strtotime($model->ven_time)).$model->venCom->ven_com_name;
                
                $modelLine = Line::findOne(['name' => $model->user->username]);     //แจ้งส่วนตัว- เวร
                if(isset($modelLine->token)){                
                    $res = Line::notify_message($modelLine->token,$sms_a);  
                    $res['status'] == 200 ? Yii::$app->session->setFlash('info', 'ส่งไลน์เรียบร้อย') : Yii::$app->session->setFlash('info', 'ส่งไลน์ ไม่ได้') ;  
                }
            endforeach;  
            $sms .= '--------------------------';
        }
        if(isset($ven_boss['name']) && isset($ven_boss_h['name'])){
            $sms_to_group = 'สวัสดีค่ะ วันนี้'.$ven_boss_h['name'];
            $sms_to_group .= '.เป็นหัวหน้าเวรธุรการหากมีเหตุขัดข้องหรือประสานงานติดต่อเบอร์ ';
            $sms_to_group .= $ven_boss_h['phone']."\n";
            $sms_to_group .= 'สภ.ใดมีคดีรบกวนแจ้ง';
            $sms_to_group_g = $sms_to_group;
            $sms_to_group_g .= "\n".$ven_boss['name'].' ('.$ven_boss['phone'].')';
            $sms_to_group .= 'ล่วงหน้าด้วยนะค่ะ ขอบคุณค่ะ';
            $sms_to_group_g .= 'ล่วงหน้าด้วยนะค่ะ ขอบคุณค่ะ';
            Line::send_sms_to('LineGroup',$sms_to_group);
            Line::notify_img('LineGroupG',$sms_to_group_g);
        }        
        Line::send_sms_to('bila_admin',$sms);                
    
/*------------------------------------แจ้ง เสี้อฟ้า ---------------------------------------------*/
        $modelBS = Blueshirt::findOne(['line_alert' => $strDate]);
        if($modelBS){
            $message = 'เวรเสื้อฟ้า '.$modelBS->line_alert."\n";
            $message .= $modelBS->getProfileName() .'(เวร)'."\n".$modelBS->getProfileName2().'(ตรวจ)';
            Line::send_sms_to('LineGroupG',$message);
        }
/*------------------------------------แจ้ง เวรที่ปรึกษา lineGroup ---------------------------------------------*/
        $Q_model = LegalCVen::find()->where(['ven_date' => $strDate]);
        if($Q_model->count() >= 1){
            $models = $Q_model->all();   
            $sms_c = $thaiDate;
            $sms_c .= "\n";
            foreach ($models as $model):        
                $sms_c .= $model->getName() ;
                $sms_c .= "\n";
                $sms_c .= '(เวรที่ปรึกษากฎหมาย)';
                $sms_c .= "\n";                
            endforeach;            
            Line::send_sms_to('ที่ปรึกษากฎหมาย',$sms_c);
            Line::send_sms_to('LineGroup',$sms_c);              
        }
/*------------------------------------แจ้ง เวรผู้พิพากษาสมทบ lineGroup ---------------------------------------*/
        $Q_model = SomtopV::find()->where(['ven_date' => $strDate]);
        if($Q_model->count() >= 1){
            $models = $Q_model->all(); 
            $sms_c = $thaiDate; 
            $sms_c = "\n";
            $sms_c .= 'เวรผู้พิพากษาสมทบ';
            $sms_c .= "\n";
            foreach ($models as $model):        
                $sms_c .= $model->getName() ;
                $sms_c .= "\n";                            
            endforeach;             
            Line::send_sms_to('LineGroup',$sms_c);
        }
/*------------------------------------แจ้ง หนังสือเวียน lineGroup ---------------------------------------------*/
        $Q_model = CLetter::find()->where(['line_alert' => $strDate]);
        if($Q_model->count() >= 1){
            $models = $Q_model->all();
            $sms_c = 'วันที่ '.$thaiDate;
            $sms_c .= "\n";
            foreach ($models as $model):        
                $sms_c .= $model->name ;
                $sms_c .= "\n";
                $sms_c .= '(http://10.37.64.01/cletter.php?ref='.$model->id.')';
                $sms_c .= "\n";
                $sms_c .= '-------------------'."\n";
            endforeach; 
            Line::send_sms_to('LineGroupG',$sms_c);
        }
        Yii::$app->session->setFlash('success', 'เรียบร้อย');   
        return $this->render('test',['id' => $sms]);
    }

    public function actionTest()
    {
        $strDate = date("Y-m-d",strtotime(date("Y-m-d"))) ;
        $thaiDate = Bila::DateThai_full($strDate);
        $sms = $thaiDate; 
        $sms .= "\n";

        $Q_model = Ven::find()->where(['ven_date' => $strDate,'status' => [1,2]]);  
              
        if($Q_model->count() >= 1){
            $models = $Q_model->orderBy(['ven_time'=>SORT_ASC])->all();  
            $sms .= '--------E-VeN---------'."\n";
            foreach ($models as $model): 

                if($model->ven_time == '08:30:00'){
                    $ven_boss['name'] = $model->getProfileName();
                    $ven_boss['phone'] = $model->getProfilePhone();
                }
                if($model->ven_time == '08:30:01'){
                    $ven_boss_h['name'] = $model->getProfileName();
                    $ven_boss_h['phone'] = $model->getProfilePhone();
                }  

                $sms .= date("H:i ",strtotime($model->ven_time));
                $sms .=' '.$model->getProfileNameCal();
                $sms .= $model->status == 1 ? '':' (รออนุมัติ)';
                $sms .= "\n";
                $sms_a = $thaiDate;
                $sms_a .= "\n";
                $sms_a .= $model->getProfileNameCal().' '.date("H:i ",strtotime($model->ven_time)).$model->venCom->ven_com_name;
                
                $modelLine = Line::findOne(['name' => $model->user->username]);     //แจ้งส่วนตัว- เวร
                if(isset($modelLine->token)){                
                    $res = Line::notify_message($modelLine->token,$sms_a);  
                    $res['status'] == 200 ? Yii::$app->session->setFlash('info', 'ส่งไลน์เรียบร้อย') : Yii::$app->session->setFlash('info', 'ส่งไลน์ ไม่ได้') ;  
                }
            endforeach;  
            $sms .= '--------------------------';
        }

        if(isset($ven_boss['name']) && isset($ven_boss_h['name'])){
            $sms_to_group = 'สวัสดีค่ะ วันนี้'.$ven_boss_h['name'];
            $sms_to_group .= '.เป็นหัวหน้าเวรธุรการหากมีเหตุขัดข้องหรือประสานงานติดต่อเบอร์ ';
            $sms_to_group .= $ven_boss_h['phone']."\n";
            $sms_to_group .= 'สภ.ใดมีคดีรบกวนแจ้ง';
            $sms_to_group_g = $sms_to_group;
            $sms_to_group_g .= "\n".$ven_boss['name'].' ('.$ven_boss['phone'].')';
            $sms_to_group .= 'ล่วงหน้าด้วยนะค่ะ ขอบคุณค่ะ';
            $sms_to_group_g .= 'ล่วงหน้าด้วยนะค่ะ ขอบคุณค่ะ';
            // $sms_to_group_g = $sms_to_group.$sms_to_group_g;
            Line::notify_img('9929',$sms_to_group);
            // Line::send_sms_to('LineGroupG',$sms_to_group); 
        }        
        return 'ok'; 
    }
}
