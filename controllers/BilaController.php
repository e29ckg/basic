<?php

namespace app\controllers;
use Yii;
use app\models\Bila;
use app\models\BilaFileUp;
use app\models\User;
use app\models\profile;
use app\models\Line;
use app\models\SignBossName;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\UploadedFile;
use yii\helpers\Url;
use yii\web\Response;
use yii\widgets\ActiveForm;
use kartik\mpdf\Pdf;
use Da\QrCode\QrCode;
use yii\db\Transaction;
use yii\db\Connection;

/**
 * Web_linkController implements the CRUD actions for Bila model.
 */
class BilaController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['index','admin','create_a','create_b','sbm_index','sbn_create'],
                'rules' => [
                    [
                        // 'actions' => ['index'],
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

    
    public function actionIndex()
    {
        $models = Bila::find()
            ->where(['user_id' => Yii::$app->user->id])
            ->orderBy([
                // 'date_create'=>SORT_DESC,
                'id' => SORT_DESC,
            ])->limit(100)->all();        

        return $this->render('index', [
            'models' => $models,
        ]);
    }

    public function actionAdmin()
    {
        $models = Bila::find()->orderBy([
            // 'date_create'=>SORT_DESC,
            'id' => SORT_DESC,
            ])->limit(100)->all();        
        

        return $this->render('index_admin', [
            'models' => $models,
        ]);
    }

    /**
     * Displays a single Bila model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {        
        $model = $this->findModel($id);
                
        if(Yii::$app->request->isAjax){
            return $this->renderAjax('view',[
                'model' => $model,         
            ]);
        }

        return $this->render('view', [
            'model' => $model,
        ]);
    }

    /**
     * Creates a new Bila model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    

    public function actionCreate_c()
    {

    }
   
    public function actionCreate_a()
    {
        
        $model = new Bila();

        
        //Add This For Ajax Email Exist Validation 
        if(Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())){
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
          } 

     
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {

            $transaction = Yii::$app->db->beginTransaction();
            try {
                    
                $model->id = time();
                // $model->user_id =  $_POST['Bila']['user_id'];
                $model->user_id = Yii::$app->user->identity->id;
                $model->cat = $_POST['Bila']['cat'];
                $model->date_begin = $_POST['Bila']['date_begin'];
                $model->date_end = $_POST['Bila']['date_end'];
                $model->date_total = $_POST['Bila']['date_total'];
                $model->due = $_POST['Bila']['due'];
                $model->dateO_begin = $_POST['Bila']['dateO_begin'];
                $model->dateO_end = $_POST['Bila']['dateO_end'];
                $model->dateO_total = $_POST['Bila']['dateO_total'];
                $model->address = $_POST['Bila']['address'];
                $model->t1 = $_POST['Bila']['t1'];
                $model->t2 = $_POST['Bila']['date_total'];
                $model->t3 = $_POST['Bila']['date_total'] + $_POST['Bila']['t1'];
                $model->date_create = $_POST['Bila']['date_create'];
                if($model->save()){
                    $dir = Url::to('@webroot/uploads/bila/'.$model->user_id.'/'.$model->id.'/');
                    if (!is_dir($dir)) {
                        mkdir($dir, 0777, true);
                    } 

                    $sms_qr = 'http://'.$_SERVER['HTTP_HOST'].'/bila.php?ref='.$model->id;
                    $qrCode = (new QrCode($sms_qr))
                        ->setSize(250)
                        ->setMargin(5)
                        ->useForegroundColor(51, 153, 255);              
                    $qrCode->writeFile(Url::to('@webroot/uploads/bila/'.$model->user_id.'/'.$model->id.'/'.$model->id.'.png')); // writer defaults to PNG when none is specified
                    /*---------------------ส่ง line ไปยัง Admin--------------------*/
                    $modelLine = Line::findOne(['name' => 'admin']);
                    if(isset($modelLine->token)){
                        $message = $model->user_id.' '.$model->cat.' รายละเอียดเพิ่มเติม' .$sms_qr;
                        $res = Line::notify_message($modelLine->token,$message);  
                        $res['status'] == 200 ? Yii::$app->session->setFlash('info', 'ส่งไลน์เรียบร้อย') :  Yii::$app->session->setFlash('info', 'ส่งไลน์ ไม่ได้') ;  
                    } 
                    Yii::$app->session->setFlash('success', 'บันทึกข้อมูลเรียบร้อย');
                   
                }  
                $transaction->commit();
                return $this->redirect(['index']);
                
            } catch (\Exception $e) {
                $transaction->rollBack();
                throw $e;
            } catch (\Throwable $e) {
                $transaction->rollBack();
                throw $e;
            }
             
        }

        // $model->tel = explode(',', $model->tel);
        $model_cat = Bila::find()
            ->where(['user_id' => Yii::$app->user->id,
                'cat'=>'ลาป่วย'
                ])
            ->orderBy([
                // 'date_create'=>SORT_DESC,
                'id' => SORT_DESC,
            ])->one(); 
        
        
        if(!empty($model_cat)){
            $model->dateO_begin = $model_cat->date_begin;
            $model->dateO_end = $model_cat->date_end;
            $model->dateO_total = $model_cat->date_total;
            $model->t1 =  $model_cat->t3;
            }else{
                $model->dateO_begin = '';
                $model->dateO_end = '';
                $model->dateO_total = '';
                $model->t1 =  '';
            }        

        $model->address = User::getProfileAddressById(Yii::$app->user->identity->id);
        
        if(Yii::$app->request->isAjax){
            return $this->renderAjax('_form_a',[
                'model' => $model,         
            ]);
        }else{
            return $this->render('_form_a',[
                'model' => $model,                
            ]); 
        }
    }

    public function actionCreate_b()
    {
        $model = new Bila();

        //Add This For Ajax Email Exist Validation 
        if(Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())){
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
          } 
     
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $model->id = time();
            $model->user_id =  Yii::$app->user->identity->id;
            $model->cat = 'ลาพักผ่อน';
            $model->date_begin = $_POST['Bila']['date_begin'];
            $model->date_end = $_POST['Bila']['date_end'];
            $model->date_total = $_POST['Bila']['date_total'];
            if($_POST['Bila']['p1'] == ""){
                $model->p1 = 0;
                $model->p2 = 10;
            }else{
                $model->p1 = $_POST['Bila']['p1'];
                $model->p2 = $_POST['Bila']['p1'] + 10;
            }                       
            $model->address = $_POST['Bila']['address'];
            $model->t1 = $_POST['Bila']['t1'];
            $model->t2 = $_POST['Bila']['date_total'];
            $model->t3 = $_POST['Bila']['t1'] + $_POST['Bila']['date_total'] ;
            $model->date_create = $_POST['Bila']['date_create'];
            if($model->save()){
                $dir = Url::to('@webroot/uploads/bila/'.$model->user_id.'/'.$model->id.'/');
                if (!is_dir($dir)) {
                    mkdir($dir, 0777, true);
                } 

                $sms_qr = 'http://'.$_SERVER['HTTP_HOST'].'/bila.php?ref='.$model->id;
                $qrCode = (new QrCode($sms_qr))
                    ->setSize(250)
                    ->setMargin(5)
                    ->useForegroundColor(1, 1, 1);              
                $qrCode->writeFile(Url::to('@webroot/uploads/bila/'.$model->user_id.'/'.$model->id.'/'.$model->id.'.png')); // writer defaults to PNG when none is specified

                /*---------------------ส่ง line ไปยัง Admin--------------------*/
                $modelLine = Line::findOne(['name' => 'admin']);
                if(isset($modelLine->token)){
                    $message = $model->user_id.' '.$model->cat.' รายละเอียดเพิ่มเติม' .$sms_qr;
                    $res = Line::notify_message($modelLine->token,$message);  
                    $res['status'] == 200 ? Yii::$app->session->setFlash('info', 'ส่งไลน์เรียบร้อย') :  Yii::$app->session->setFlash('info', 'ส่งไลน์ ไม่ได้') ;  
                } 
                Yii::$app->session->setFlash('success', 'บันทึกข้อมูลเรียบร้อย');
                return $this->redirect(['index']);
            }   
        }
        // $model->tel = explode(',', $model->tel);
        $model_cat = Bila::find()
            ->where(['user_id' => Yii::$app->user->id,
                'cat'=>'ลาพักผ่อน'
                ])
            ->orderBy([
                'date_create'=>SORT_DESC,
                'id' => SORT_DESC,
            ])->one(); 

            if(!empty($model_cat)){                
                $model->p1 = $model_cat->p1;
                $model->t1 = $model_cat->t3;
                }else{
                    $model->p1 = null;
                    $model->t1 = null;
                } 
            
            $model->address = User::getProfileAddressById(Yii::$app->user->identity->id);

        if(Yii::$app->request->isAjax){
            return $this->renderAjax('_form_b',[
                    'model' => $model,                       
            ]);
        }else{
            return $this->render('_form_b',[
                'model' => $model,                
            ]); 
        }
    }
/**
     * Updates an existing Bila model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        //Add This For Ajax Email Exist Validation 
        if(Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())){
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            if($model->cat == 'ลาป่วย' || $model->cat == 'ลากิจส่วนตัว' || $model->cat == 'ลาคลอดบุตร'){
                $model->date_begin = $_POST['Bila']['date_begin'];
                $model->date_end = $_POST['Bila']['date_end'];
                $model->date_total = $_POST['Bila']['date_total'];
                $model->due = $_POST['Bila']['due'];
                $model->dateO_begin = $_POST['Bila']['dateO_begin'];
                $model->dateO_end = $_POST['Bila']['dateO_end'];
                $model->dateO_total = $_POST['Bila']['dateO_total'];
                $model->address = $_POST['Bila']['address'];
                $model->t1 = $_POST['Bila']['t1'];
                $model->t2 = $_POST['Bila']['date_total'];
                $model->t3 = $model->t1 + $model->t2;
                $model->date_create = $_POST['Bila']['date_create'];
            }

            if($model->cat == 'ลาพักผ่อน'){
                $model->date_begin = $_POST['Bila']['date_begin'];
                $model->date_end = $_POST['Bila']['date_end'];
                $model->date_total = $_POST['Bila']['date_total'];
                if($_POST['Bila']['p1'] == ""){
                    $model->p1 = 0;
                    $model->p2 = 10;
                }else{
                    $model->p1 = $_POST['Bila']['p1'];
                    $model->p2 = $_POST['Bila']['p1'] + 10;
                }              
                      
                $model->address = $_POST['Bila']['address'];
                $model->t1 = $_POST['Bila']['t1'];
                $model->t2 = $_POST['Bila']['date_total'];
                $model->t3 = $_POST['Bila']['t1'] + $_POST['Bila']['date_total'] ;
                $model->date_create = $_POST['Bila']['date_create'];
            } 

            if($model->save()){
                Yii::$app->session->setFlash('success', 'บันทึกข้อมูลเรียบร้อย');
                return $this->redirect(['index']);
            }   
        }

        if(Yii::$app->request->isAjax){
            return $this->renderAjax('update',[
                    'model' => $model,                    
            ]);
        }else{
            return $this->render('update',[
                'model' => $model,                    
            ]); 
        }
        
    }

    /**
     * Deletes an existing Bila model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    

    public function actionDelete($id)
    {
        $model = $this->findModel($id);

        $dir = Url::to('@webroot/uploads/bila/'.$model->user_id.'/'.$model->id);
        
        if($model->delete()){
            if(is_file($dir.'/'.$model->id.'.png')){
                unlink($dir.'/'.$model->id.'.png');// ลบ รูปเดิม;   
            } 
            if(is_file($dir.'/'.$model->id.'.png')){
                unlink($dir.'/'.$model->file);// ลบ ไฟล์  
            }
            if (is_dir($dir)) {
                // mkdir($dir, 0777, true);
                rmdir($dir);
            } 
        }
        
        return $this->redirect(['index']);
    }

    public function actionShow($id=null){
        $mdBila = Bila::find()->where(['id' => $id])->one();

        if(Yii::$app->request->isAjax){
            return $this->renderAjax('show',[
                    'model' => $mdBila,                    
            ]);
        }
        
        return $this->render('show',[
               'model' => $mdBila,                    
        ]);
    }

    /**
     * Finds the Bila model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Bila the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Bila::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    
    public function actionPrint1($id=null)
    {
        $model = $this->findModel($id);
        if($model->cat == 'ลาป่วย' || $model->cat == 'ลากิจส่วนตัว' || $model->cat == 'ลาคลอดบุตร'){
            $Pdf_print = '_pdf_A';
        }else if($model->cat =='ลาพักผ่อน'){
            $Pdf_print = '_pdf_B';
        }
        Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;
        $pdf = new Pdf([
            'mode' => Pdf::MODE_UTF8, // leaner size using standard fonts
            'destination' => Pdf::DEST_BROWSER,
            'content' => $this->renderPartial($Pdf_print,[
                'model'=>$model,
            ]),
            'cssFile' => 'css/pdf.css',
            'options' => [
                // any mpdf options you wish to set
            ],
            'methods' => [
                'SetTitle' => $model->id,
                // 'SetSubject' => 'Generating PDF files via yii2-mpdf extension has never been easy',
                // 'SetHeader' => ['Krajee Privacy Policy||Generated On: ' . date("r")],
                // 'SetFooter' => ['|Page {PAGENO}|'],
                // 'SetAuthor' => 'Kartik Visweswaran',
                // 'SetCreator' => 'Kartik Visweswaran',
                // 'SetKeywords' => 'Krajee, Yii2, Export, PDF, MPDF, Output, Privacy, Policy, yii2-mpdf',
            ]
        ]);
        return $pdf->render();
    }

    public function actionFile_up($id) { 
        $modelBila = $this->findModel($id);
        $model = new BilaFileUp();
                          
        // Add This For Ajax Email Exist Validation 
        if(Yii::$app->request->isAjax && $model->load(Yii::$app->request->post()) ){
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model) ;
        } 
        
        if ($model->load(Yii::$app->request->post()) && $model->validate()){ 
            $f = UploadedFile::getInstance($model, 'file');
            
            if(!empty($f)){                
                $dir = Url::to('@webroot/uploads/bila/'.$modelBila->user_id.'/'.$modelBila->id.'/');
                if (!is_dir($dir)) {
                    mkdir($dir, 0777, true);
                }
                $fileName = md5($f->baseName . time()) . '.' . $f->extension;
                if($f->saveAs($dir . $fileName)){
                    $modelBila->file = $fileName;
                }
                if($modelBila->save() ){  
                    Yii::$app->session->setFlash('success', 'บันทึกข้อมูลเรียบร้อย');
                    return $this->redirect(['admin']);
                }
            }else{
                Yii::$app->session->setFlash('warning', 'ไม่ได้บันทึกข้อมูล');
                return $this->redirect(['admin']);
            }
        }
        // $model->file = $modelBila->file;
        if(Yii::$app->request->isAjax){
            return $this->renderAjax('_file_up',[
                'model' => $model,               
            ]);
        }

        return $this->render('_file_up',[
            'model' => $model,                     
        ]);
    }

    public function actionFile_view($id) {
    
        $model = Bila::findOne($id);   
        
        // This will need to be the path relative to the root of your app.
        $filePath = Url::to('@webroot/uploads/bila/'.$model->user_id.'/'.$model->id.'/'.$model->file);
        // Might need to change '@app' for another alias
        // $completePath = Yii::getAlias('@app'.$filePath.'/'.$file);
        if(is_file($filePath)){
                                   
            return Yii::$app->response->sendFile( $filePath, $model->id, ['inline'=>true]);
            
        }else{
            Yii::$app->session->setFlash('error', 'ไม่พบ File... ');
            return $this->redirect(['admin']);
        }
    }

    public function actionFile_del($id) {
    
        $model = Bila::findOne($id);   

        $filePath = Url::to('@webroot/uploads/bila/'.$model->user_id.'/'.$model->id.'/'.$model->file);       
        if(is_file($filePath)){
            unlink($filePath);// ลบ ไฟล์;   
            Yii::$app->session->setFlash('success', 'ลบไฟล์เรียบร้อย');
            $model->file = null;
            $model->save();
            return $this->redirect(['admin']);                 
        }else{
            Yii::$app->session->setFlash('error', 'ไม่พบ File... ');
            return $this->redirect(['admin']);
        }
        $model->file = null;
        $model->save();
           
    }

    public function actionSbn_index()
    {        
        $models = SignBossName::find()->orderBy([
                'date_create'=>SORT_DESC,
                'id' => SORT_DESC,
                ])
                ->limit(100)
                ->all();       
        
        $countAll = SignBossName::getCountAll();

        return $this->render('sbn_index', [
            'models' => $models,
            'countAll' => $countAll,
        ]);
    }

    public function actionSbn_create()
    {
        $model = new SignBossName();

        //Add This For Ajax Email Exist Validation 
        if(Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())){
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
          } 
     
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {            
            $model->name = $_POST['SignBossName']['name'];
            $model->dep1 = $_POST['SignBossName']['dep1'];
            $model->dep2 = $_POST['SignBossName']['dep2'];
            $model->dep3 = $_POST['SignBossName']['dep3'];
            $model->status = $_POST['SignBossName']['status'];
            $model->date_create = date("Y-m-d H:i:s");
            if($model->save()){
                Yii::$app->session->setFlash('success', 'บันทึกข้อมูลเรียบร้อย');
                return $this->redirect(['sbn_index']);
            }   
        }
        
        if(Yii::$app->request->isAjax){
            return $this->renderAjax('sbn_create',[
                    'model' => $model,                    
            ]);
        }else{
            return $this->render('sbn_create',[
                'model' => $model,                    
            ]); 
        }
    }

    public function actionSbn_update($id)
    {
        $model = SignBossName::findOne($id);
       
        //Add This For Ajax Email Exist Validation 
        if(Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())){
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
          }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $model->name = $_POST['SignBossName']['name'];
            $model->dep1 = $_POST['SignBossName']['dep1'];
            $model->dep2 = $_POST['SignBossName']['dep2'];
            $model->dep3 = $_POST['SignBossName']['dep3'];
            $model->status = $_POST['SignBossName']['status'];
            $model->date_create = date("Y-m-d H:i:s");
            if($model->save()){
                Yii::$app->session->setFlash('success', 'บันทึกข้อมูลเรียบร้อย');
                return $this->redirect(['sbn_index']);
            }   
        }        

        if(Yii::$app->request->isAjax){
            return $this->renderAjax('sbn_update',[
                    'model' => $model,                    
            ]);
        }else{
            return $this->render('sbn_update',[
                'model' => $model,                    
            ]); 
        }        
    }

    public function actionSbn_delete($id)
    {
        $model = SignBossName::findOne($id);
        
        $model->delete();

        return $this->redirect(['sbn_index']);
    }

    public function actionCal()
    {
        $models = Bila::find()->orderBy([
            // 'date_create'=>SORT_DESC,
            'id' => SORT_DESC,
            ])->limit(100)->all();  
        $event = [];
        foreach ($models as $model):
            $even = [
                'id' => $model->id,
                'title' => User::getProfileNameById($model->user_id).' '
                            .$model->cat .' '.$model->date_total . ' วัน ตั้งแต่วันที่ '
                            .Bila::DateThai_full($model->date_begin).' ถึง '
                            .Bila::DateThai_full($model->date_end),
                'start' => $model->date_begin,
                'end' => $model->date_end.'T12:30:00',
                'backgroundColor' => $model->cat == 'ลาพักผ่อน'? '' :'#f56954',
                'borderColor' => $model->cat == 'ลาพักผ่อน'? '' :'#f56954'
            ];
            // $event['end'] = $model->date_end;
            // $event['backgroundColor'] #f56954; //red
            // $event['borderColor']  = #f56954; //red
            $event[] = $even;
        endforeach;        
        $event = json_encode($event);
        return $this->render('cal',[
            'event' => $event,
        ]);
    }

}
