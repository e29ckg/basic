<?php

namespace app\controllers;

use Yii;
use app\models\CLetter;
use app\models\CLetterCaid;
use app\models\Log;
use app\models\Line;
use app\models\Profile;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\UploadedFile;
use yii\helpers\Url;
use yii\web\Response;
use yii\widgets\ActiveForm;

/**
 * CletterController implements the CRUD actions for CLetter model.
 */
class CletterController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public $line_sms ='10.37.64.01';
    public $upload ='/uploads/user/';
    public $filePath = '/uploads/cletter/';
    public $smsLineAlert = ' http://10.37.64.01/main/web/cletter/show/';

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['create','show','all','index_admin','caid_index','caid_create'],
                'rules' => [
                    [
                        // 'actions' => ['create','show','all'],
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
     * Lists all CLetter models.
     * @return mixed
     */
    public function actionIndex()
    {
        $model = CLetter::find()
            ->orderBy([
            // // 'created_at'=>SORT_DESC,
            'id' => SORT_DESC,
            ])
            ->limit(100)
            ->all();
        
        return $this->render('index',[
            'models' => $model,
        ]);

    }
    public function actionIndex_admin()
    {
        $model = CLetter::find()->orderBy([
            // 'created_at'=>SORT_DESC,
            'id' => SORT_DESC,
            ])
            ->limit(100)
            ->all();        
        
        return $this->render('index_admin',[
            'models' => $model,
        ]);

    }
    public function actionAll()
    {
        $model = CLetter::find()->orderBy([
            // 'created_at'=>SORT_ASC,
            'id' => SORT_DESC,
            ])
            // ->limit(10)
            ->all();
        
            $countAll = CLetter::getCountAll();
        
        return $this->render('index',[
            'models' => $model,
            'countAll' => $countAll,
        ]);

    }

    /**
     * Displays a single CLetter model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        if(Yii::$app->request->isAjax){
            return $this->renderAjax('view',[
                    'model' => $this->findModel($id),                   
            ]);
        }
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new CLetter model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new CLetter();

        //Add This For Ajax Email Exist Validation 
        if(Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())){
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
          } 
     
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            
            $f = UploadedFile::getInstance($model, 'file');
            if(!empty($f)){
                $dir = Url::to('@webroot'.$this->filePath);
                if (!is_dir($dir)) {
                    mkdir($dir, 0777, true);
                }
                $fileName = md5($f->baseName . time()) . '.' . $f->extension;
                if($f->saveAs($dir . $fileName)){
                    $model->file = $fileName;
                }               
            } 

            $model->name = $_POST['CLetter']['name'];
            $model->created_at = date("Y-m-d H:i:s");
            $_POST['CLetter']['line_alert'] == null ? $model->line_alert = null
                : $model->line_alert = date("Y-m-d",strtotime($_POST['CLetter']['line_alert']));

            if($model->save()){
                if(!empty($model->file)){                    
                    // $res = $this->notify_message($message);
                    
                } else{
                    Yii::$app->session->setFlash('warning', 'ไม่มี ไฟล์ข้อมูลนะ'); 
                }        

                Yii::$app->session->setFlash('success', 'บันทึกข้อมูลเรียบร้อย'); 
                
                // $modelLine = Line::findOne(['name' => 'LineGroup']);        
                if($token = Line::getToken('LineGroup')){
                    // $message = $model->name.' ดูรายละเอียดที่เว็บภายใน.';
                    $message = $model->name.' ดูรายละเอียดที่เว็บภายใน.'.$this->smsLineAlert.$model->id;
                
                    $res = Line::notify_message($token,$message);
                    
                    if($res['status'] == 200){
                        Yii::$app->session->setFlash('info', 'Line Notify ส่งได้');
                    }else{
                        Yii::$app->session->setFlash('warning', 'Line Notify ส่งไม่ได้ Error'.$res['status']);
                    }
                }               
                return $this->redirect(['index_admin']);
            }   
        }

        if(Yii::$app->request->isAjax){
            return $this->renderAjax('create',[
                    'model' => $model,                    
            ]);
        }else{
            return $this->render('create',[
                'model' => $model,                    
            ]); 
        }

    }

    /**
     * Updates an existing CLetter model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        $fileName = $model->file;

        //Add This For Ajax Email Exist Validation 
        if(Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())){
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
          } 

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $f = UploadedFile::getInstance($model, 'file');

            if(!empty($f)){
                
                $dir = Url::to('@webroot'.$this->filePath);
                if (!is_dir($dir)) {
                    mkdir($dir, 0777, true);
                }  

                if($fileName && is_file($dir.$fileName)){
                    unlink($dir.$fileName);// ลบ รูปเดิม;                   
                    
                }

                $fileName = md5($f->baseName . time()) . '.' . $f->extension;
                if($f->saveAs($dir . $fileName)){
                    $model->file = $fileName;
                    $model->save(); 
                }                
                                            
            }

            if($_POST['CLetter']['line_alert'] == null){
                $model->line_alert = null;
            }else{
                $model->line_alert = date("Y-m-d",strtotime($_POST['CLetter']['line_alert']));
            }                 

            $model->file = $fileName;
            if($model->save()){
                Yii::$app->session->setFlash('success', 'บันทึกข้อมูลเรียบร้อย');
            }; 
      
            return $this->redirect(['index_admin', 'id' => $fileName]);
        }

        if(Yii::$app->request->isAjax){
            return $this->renderAjax('update',[
                    'model' => $model,                    
            ]);
        }        
        return $this->render('update',[
               'model' => $model,                    
        ]); 
    }

    /**
     * Deletes an existing CLetter model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $filename = $model->file;
        $dir = Url::to('@webroot'.$this->filePath);
        
        if($filename && is_file($dir.$filename)){
            unlink($dir.$filename);// ลบ รูปเดิม;                    
        }
        
        if($model->delete()){
            $message = Yii::$app->user->identity->id .' ลบ '.$model->name;
            $modelLog = new Log();
            $modelLog->user_id = Yii::$app->user->identity->id;
            $modelLog->manager = 'Cletter_delete';
            $modelLog->detail =  'ลบ '.$model->name;
            $modelLog->create_at = date("Y-m-d H:i:s");
            $modelLog->ip = Yii::$app->getRequest()->getUserIP();
            Yii::$app->session->setFlash('success', 'ลบข้อมูลเรียบร้อย');    
            // $modelLine = Line::findOne(['name' => 'admin']);        
                    if($token = Line::getToken('admin')){
                        $message = Profile::getProfileNameById(Yii::$app->user->identity->id).' ลบ '.$model->name.' '.date("Y-m-d H:i:s");
                        Line::notify_message($token,$message);                        
                    }                                
        }        

        return $this->redirect(['index_admin']);
    }

    public function actionShow($id) {
        
        $model = Cletter::findOne($id);           
                
        // This will need to be the path relative to the root of your app.
        // $filePath = '/web/uploads/cletter';
        // Might need to change '@app' for another alias
        $completePath = Url::to('@webroot').$this->filePath.$model->file;
        if(is_file($completePath)){
            
            // $modelLog = new Log();
            // $modelLog->user_id = Yii::$app->user->identity->id;
            // $modelLog->manager = 'Cletter_Read';
            // $modelLog->detail =  'เปิดอ่าน '.$model->name;
            // $modelLog->create_at = date("Y-m-d H:i:s");
            // $modelLog->ip = Yii::$app->getRequest()->getUserIP();
            // if($modelLog->save()){
            //     // $modelLine = Line::findOne(['name' => 'admin']);        
            //         if($token = Line::getToken('admin')){
            //             $message = Profile::getProfileNameById(Yii::$app->user->identity->id).' เปิดอ่าน '.$model->name.' '.date("Y-m-d H:i:s");
            //             Line::notify_message($token,$message);                        
            //         }                        
                return Yii::$app->response->sendFile($completePath, $model->file, ['inline'=>true]);                
            // }
        // $stylesheet = file_get_contents(Url::to('@webroot/css/pdf.css'));

        // $mpdf = new \Mpdf\Mpdf();
        // $mpdf->WriteHTML($stylesheet,\Mpdf\HTMLParserMode::HEADER_CSS);
        // // // $mpdf->SetImportUse();
        // $mpdf->SetDocTemplate(Url::to('@webroot/uploads/cletter/'.$model->file),true);
        // $mpdf->SetTitle('webApp '.$model->id);
        // $mpdf->SetCreator('pkkjc webApp');
        // $mpdf->SetKeywords('My Keywords, More Keywords');
       
        // // $mpdf->SetHTMLHeader('<div style="color:red;">ศาลเยาวชนและครอบครัวจังหวัดประจวบคีรีขันธ์</div>');
        // // $mpdf->SetWatermarkText('http://pkkjc.coj.go.th');
        // // $mpdf->showWatermarkText = true;
        // // $mpdf->watermark_font = 'thsarabun';
        // // $mpdf->WriteHTML($html,\Mpdf\HTMLParserMode::HTML_BODY);
        // // $mpdf->AddPage();
        // $html = '<b>Hello world! ทดส่อบ</b>';
        // $mpdf->WriteHTML($html,\Mpdf\HTMLParserMode::HTML_BODY);
        // $mpdf->Output();
            
        }else{
            Yii::$app->session->setFlash('warning', 'ไม่พบ File... '.$completePath);
            return $this->redirect(['index']);;
        }
    }

    /**
     * Finds the CLetter model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return CLetter the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = CLetter::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionLine_alert($id) {
        $model = $this->findModel($id);        
        if($model->name){            
            $message = $model->name .' ดูรายละเอียดเพิ่มเติมได้ที่ เว็บภายใน ';
            $modelLine = Line::findOne(['name' => 'LineGroup']);        
            if(!empty($modelLine->token) && $modelLine->status == 1){
                // $message = $model->name.' ดูรายละเอียดที่เว็บภายใน.'.Yii::$app->request->hostInfo.Url::to(['cletter/show','id'=>$model->id]);
                $message = $model->name.' ดูรายละเอียดที่เว็บภายใน.'.$this->smsLineAlert.$model->id;
                $res = Line::notify_message($modelLine->token,$message);

                if($res['status'] == 200){
                    Yii::$app->session->setFlash('info', 'Line Notify ส่งได้');
                }else{
                    Yii::$app->session->setFlash('warning', 'Line Notify ส่งไม่ได้ Error'.$res['status']);
                }
            }                 
        }        
        return $this->redirect(['index_admin']);        
    }
    

    public function actionCaid_index()
    {
        $model = CLetterCaid::find()->orderBy([
            'name'=>SORT_ASC,
            // 'id' => SORT_DESC,
            ])->all();
                
        return $this->render('caid_index',[
            'models' => $model,
        ]);

    }

    public function actionCaid_create(){
        
        $model = new CLetterCaid();

        //Add This For Ajax Email Exist Validation 
        if(Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())){
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
          } 
     
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
             
            $model->name = $_POST['CLetterCaid']['name'];
            $model->status = $_POST['CLetterCaid']['status'];

            if($model->save()){                          
                Yii::$app->session->setFlash('success', 'บันทึกข้อมูลเรียบร้อย');                
                return $this->redirect(['caid_index']);
            }   
        }

        if(Yii::$app->request->isAjax){
            return $this->renderAjax('caid_create',[
                    'model' => $model,                    
            ]);
        }else{
            return $this->render('caid_create',[
                'model' => $model,                    
            ]); 
        }

    }

    public function actionCaid_update($id){
        
        $model = CLetterCaid::findOne($id);

        //Add This For Ajax Email Exist Validation 
        if(Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())){
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
          } 
     
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
             
            $model->name = $_POST['CLetterCaid']['name'];
            $model->status = $_POST['CLetterCaid']['status'];

            if($model->save()){                          
                Yii::$app->session->setFlash('success', 'บันทึกข้อมูลเรียบร้อย');                
                return $this->redirect(['caid_index']);
            }   
        }

        if(Yii::$app->request->isAjax){
            return $this->renderAjax('caid_update',[
                    'model' => $model,                    
            ]);
        }else{
            return $this->render('caid_update',[
                'model' => $model,                    
            ]); 
        }
    }

    public function actionCaid_del($id)
    {
        $model = CLetterCaid::findOne($id);
        
        if($model->delete()){
            Yii::$app->session->setFlash('success', 'ลบข้อมูลเรียบร้อย');  
        }        

        return $this->redirect(['caid_index']);
    }

    
    public function actionCaid_update_to_name()
    {
        $models = CLetter::find()->all();

        foreach ($models as $model):
            if($model->ca_name == 1){                
                $model->ca_name = 'หนังเวียนสำนักงานศาล';
                $model->save();
            }elseif($model->ca_name == 2){                
                $model->ca_name = 'ภายใน';
                $model->save();
            }elseif($model->ca_name == 3){                
                $model->ca_name = 'ตารางเวร';
                $model->save();
            }elseif($model->ca_name == 4){                
                $model->ca_name = '	คำสั่งศาลฯ';
                $model->save();
            }
        endforeach;                

        return $this->redirect(['index']);
    }
           

}
