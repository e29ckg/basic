<?php

namespace app\controllers;

use Yii;
use app\models\CourtOrderBigboss;
use app\models\CourtOrderBoss;
use app\models\Log;
use app\models\Line;
use app\models\Running;
use app\models\Profile;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\UploadedFile;
use yii\helpers\Url;
use yii\web\Response;
use yii\widgets\ActiveForm;
use kartik\mpdf\Pdf;

/**
 * CourtorderController implements the CRUD actions for CourtOrderBigboss model.
 */
class CourtorderController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public $line_sms ='10.37.64.01';
    public $upload ='/uploads/user/';
    public $filePath = '/uploads/CourtOrderBigboss/';
    public $smsLineAlert = ' http://10.37.64.01/main/web/CourtOrderBigboss/show/';
    public $line_name = 'court_order';

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['index'],
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
     * Lists all CourtOrderBigboss models.
     * @return mixed
     */
    public function actionIndex()
    {
        $model = CourtOrderBigboss::find()
            ->orderBy([
            'year'=>SORT_DESC,
            'num' => SORT_DESC,
            ])
            // ->limit(100)
            ->all();
        $model2 = CourtOrderBoss::find()
            ->orderBy([
                'year'=>SORT_DESC,
                'num' => SORT_DESC,
            ])
            // ->limit(100)
            ->all();
        
        return $this->render('index',[
            'models' => $model,
            'models2' => $model2,
        ]);

    }
    public function actionBigboss()
    {
        $model = CourtOrderBigboss::find()->orderBy([
            // 'created_at'=>SORT_DESC,
            'id' => SORT_DESC,
            ])
            ->limit(100)
            ->all();        
        
        return $this->render('bigboss',[
            'models' => $model,
        ]);

    }

    public function actionBoss()
    {
        $model = CourtOrderBoss::find()->orderBy([
            // 'created_at'=>SORT_DESC,
            'id' => SORT_DESC,
            ])
            ->limit(100)
            ->all();        
        
        return $this->render('bigboss',[
            'models' => $model,
        ]);

    }

    
    /**
     * Displays a single CourtOrderBigboss model.
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
     * Creates a new CourtOrderBigboss model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreatebb()
    {
        $model = new CourtOrderBigboss();

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

            $model->year = $_POST['CourtOrderBigboss']['year'];
            $model->num = $_POST['CourtOrderBigboss']['num'];
            $model->date_write = $_POST['CourtOrderBigboss']['date_write'];
            $model->owner = Yii::$app->user->identity->id;
            $model->name = $_POST['CourtOrderBigboss']['name'];
            $model->create_at = date("Y-m-d H:i:s");
            
            if($model->save() && Running::addRunNumberOrBB($model->num,$model->year)){
                if(!empty($model->file)){                    
                    // $res = $this->notify_message($message);
                    
                } else{
                    Yii::$app->session->setFlash('warning', 'ไม่มี ไฟล์ข้อมูลนะ'); 
                }        

                Yii::$app->session->setFlash('success', 'บันทึกข้อมูลเรียบร้อย'); 
                   
                
                    $message = 'เพิ่ม-คำสั่งศาลฯ '. $model->num.'/'.$model->year.' '.$model->name.'#'. Profile::getProfileNameById(Yii::$app->user->identity->id).'#';
            
                    $res = Line::send_sms_to($this->line_name,$message);
                    
                    if($res['status'] == 200){
                        Yii::$app->session->setFlash('info', 'Line Notify ส่งได้');
                    }else{
                        Yii::$app->session->setFlash('warning', 'Line Notify ส่งไม่ได้ Error'.$res['status']);
                    }
                             
                return $this->redirect(['index']);
            }   
        }
        $RN = Running::getRunNumberOrBB();
        $model->num = $RN['r'];
        $model->year = $RN['y'];
        // $model->date_write = date("Y-m-d"); 
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

    public function actionCreateb()
    {
        $model = new CourtOrderBoss();

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
            $model->num = $_POST['CourtOrderBoss']['num'];
            $model->year = $_POST['CourtOrderBoss']['year'];
            $model->date_write = $_POST['CourtOrderBoss']['date_write']; 
            $model->owner = Yii::$app->user->identity->id;
            $model->name = $_POST['CourtOrderBoss']['name'];
            $model->create_at = date("Y-m-d H:i:s");
            
            if(Running::addRunNumberOrB($model->num,$model->year) && $model->save()){
                
                if(!empty($model->file)){                    
                    // $res = $this->notify_message($message);
                    
                } else{
                    Yii::$app->session->setFlash('warning', 'ไม่มี ไฟล์ข้อมูลนะ'); 
                }        

                Yii::$app->session->setFlash('success', 'บันทึกข้อมูลเรียบร้อย'); 
                
                
                    $message = 'เพิ่ม-คำสั่งสำนักงานฯ '. $model->num.'/'.$model->year.' '.$model->name.'#'. Profile::getProfileNameById(Yii::$app->user->identity->id).'#';
            
                    $res = Line::send_sms_to($this->line_name,$message);
                    
                    if($res['status'] == 200){
                        Yii::$app->session->setFlash('info', 'Line Notify ส่งได้');
                    }else{
                        Yii::$app->session->setFlash('warning', 'Line Notify ส่งไม่ได้ Error'.$res['status']);
                    }
                             
                return $this->redirect(['index']);
            }   
        }
        $RN = Running::getRunNumberOrB();
        $model->num = $RN['r'];
        $model->year = $RN['y'];
        $model->date_write = date("Y-m-d"); 
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
     * Updates an existing CourtOrderBigboss model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */

    public function actionUpdatebb($id)
    {
        $model = CourtOrderBigboss::findOne($id);

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
            $model->name = $_POST['CourtOrderBigboss']['name'];  
            $model->num = $_POST['CourtOrderBigboss']['num'];  
            $model->year = $_POST['CourtOrderBigboss']['year'];     
            $model->date_write = $_POST['CourtOrderBigboss']['date_write'];
            $model->file = $fileName;
            if($model->save()){
                                
                $message = 'แก้ไข-คำสั่งศาลฯ '. $model->num.'/'.$model->year.' '.$model->name.'#'. Profile::getProfileNameById(Yii::$app->user->identity->id).'#';
        
                $res = Line::send_sms_to($this->line_name,$message);
                
                if($res['status'] == 200){
                    Yii::$app->session->setFlash('info', 'Line Notify ส่งได้');
                }else{
                    Yii::$app->session->setFlash('warning', 'Line Notify ส่งไม่ได้ Error'.$res['status']);
                }
                Yii::$app->session->setFlash('success', 'บันทึกข้อมูลเรียบร้อย');
            }; 
      
            return $this->redirect(['index', 'id' => $fileName]);
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

    public function actionUpdateb($id)
    {
        $model = CourtOrderBoss::findOne($id);

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
            $model->name = $_POST['CourtOrderBoss']['name'];        
            $model->date_write = $_POST['CourtOrderBoss']['date_write'];
            $model->file = $fileName;
            if($model->save()){
                Yii::$app->session->setFlash('success', 'บันทึกข้อมูลเรียบร้อย');
                
                    $message = 'แก้ไข-คำสั่งสำนักงานฯ '. $model->num.'/'.$model->year.' '.$model->name.'#'. Profile::getProfileNameById(Yii::$app->user->identity->id).'#';
            
                    $res = Line::send_sms_to($this->line_name,$message);
                    
                    if($res['status'] == 200){
                        Yii::$app->session->setFlash('info', 'Line Notify ส่งได้');
                    }else{
                        Yii::$app->session->setFlash('warning', 'Line Notify ส่งไม่ได้ Error'.$res['status']);
                    }
                
            }; 
      
            return $this->redirect(['index', 'id' => $fileName]);
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
     * Deletes an existing CourtOrderBigboss model.
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
            unlink($dir.$filename);// ลบ;                    
        }
        
        if($model->delete()){

            Yii::$app->session->setFlash('success', 'ลบข้อมูลเรียบร้อย');   
           
            $message = 'ลบ-คำสั่งศาลฯ '. $model->num.'/'.$model->year.' '.$model->name.'#'. Profile::getProfileNameById(Yii::$app->user->identity->id).'#';
        
            $res = Line::send_sms_to($this->line_name,$message);
            
            if($res['status'] == 200){
                Yii::$app->session->setFlash('info', 'Line Notify ส่งได้');
            }else{
                Yii::$app->session->setFlash('warning', 'Line Notify ส่งไม่ได้ Error'.$res['status']);
            }
                                           
        }        

        return $this->redirect(['index']);
    }

    public function actionDelete2($id)
    {
        $model = CourtOrderBoss::findOne($id);
        $filename = $model->file;
        $dir = Url::to('@webroot'.$this->filePath);
        
        if($filename && is_file($dir.$filename)){
            unlink($dir.$filename);// ลบ;                    
        }
        
        if($model->delete()){
                        
            Yii::$app->session->setFlash('success', 'ลบข้อมูลเรียบร้อย');    
            
            $message = 'ลบ-คำสั่งสำนักงานฯ '. $model->num.'/'.$model->year.' '.$model->name.'#'. Profile::getProfileNameById(Yii::$app->user->identity->id).'#';
        
            $res = Line::send_sms_to($this->line_name,$message);
            
            if($res['status'] == 200){
                Yii::$app->session->setFlash('info', 'Line Notify ส่งได้');
            }else{
                Yii::$app->session->setFlash('warning', 'Line Notify ส่งไม่ได้ Error'.$res['status']);
            }
                                            
        }        

        return $this->redirect(['index']);
    }

    public function actionShow($id) {
        
        $model = CourtOrderBigboss::findOne($id);           
         
        $completePath = Url::to('@webroot').$this->filePath.$model->file;
        if(is_file($completePath)){                                  
                return Yii::$app->response->sendFile($completePath, $model->file, ['inline'=>true]);               
           
            
        }else{
            Yii::$app->session->setFlash('warning', 'ไม่พบ File... '.$completePath);
            return $this->redirect(['index']);;
        }
    }
    public function actionShow2($id) {
        
        $model = CourtOrderBoss::findOne($id);           
     
        $completePath = Url::to('@webroot').$this->filePath.$model->file;
        if(is_file($completePath)){
                        
                return Yii::$app->response->sendFile($completePath, $model->file, ['inline'=>true]);   
            
        }else{
            Yii::$app->session->setFlash('warning', 'ไม่พบ File... '.$completePath);
            return $this->redirect(['index']);
        }
    }

    /**
     * Finds the CourtOrderBigboss model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return CourtOrderBigboss the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = CourtOrderBigboss::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionReport()
    {
        $models = CourtOrderBigboss::find()
            ->select(['id','year','num','date_write','name','owner'])
            ->groupBy('year')->all(); 
        $data = [];
        foreach ($models as $model):
            $data[] = [
                'year' => $model->year
            ];
        endforeach;
        $models = CourtOrderBoss::find()
            ->select(['id','year','num','date_write','name','owner'])
            ->groupBy('year')->all(); 
        
            $data2 = [];
        foreach ($models as $model):
            $data2[] = [
                'year' => $model->year
            ];
        endforeach;

        return $this->render('report',[
            'data' => json_encode($data),
            'data2' => json_encode($data2)]);    
    }

    public function actionReport_a($y = null)
    {
        $this->layout = 'blank';
        $y == null ? $y = date('Y') + 543 : $y = $y + 543;
        $models = CourtOrderBigboss::find()
            ->select(['id','year','num','date_write','name','owner'])
            ->where(['year' => $y])
            ->all(); 
        $data = [];
        
        foreach ($models as $model): 
            $data[]=[
                'id' => $model->id,
                'year' => $model->year,
                'num' => $model->num,
                'date_write' => $model->DateThai($model->date_write),
                'name' => $model->name,
                'owner' => $model->getProfileName().'<br>'.$model->getProfileGroup()
            ];
        endforeach;       
 
        $Pdf_print = 'report_a';
        
        Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;
        $pdf = new Pdf([
            'mode' => Pdf::MODE_UTF8, // leaner size using standard fonts
            'format' => Pdf::FORMAT_A4,
            // 'orientation' => Pdf::ORIENT_LANDSCAPE,
            'destination' => Pdf::DEST_BROWSER,
            'content' => $this->renderPartial($Pdf_print,[
                'data' => json_encode($data)
                ]),
            
            'cssFile' => 'css/pdf_l65.css',
            'options' => [
                // any mpdf options you wish to set
            ],
            'methods' => [
                'SetTitle' => 'คำสั่งศาล ',
                // 'SetSubject' => 'ใบขอเปลี่ยนเวร '.$model->id,
                // 'SetHeader' => ['Krajee Privacy Policy||Generated On: ' . date("r")],
                // 'SetFooter' => ['|Page {PAGENO}|'],
                // 'SetFooter' => ['Pkkjc WebApp'],
                'SetAuthor' => 'ศาลเยาวชนและครอบครัวจังหวัดประจวบคีรีขันธ์',
                'SetCreator' => 'Pkkjc-Web',
                // 'SetKeywords' => 'Krajee, Yii2, Export, PDF, MPDF, Output, Privacy, Policy, yii2-mpdf',
            ]
        ]);
        return $pdf->render();        
    }
    public function actionReport_b($y = null)
    {
        $this->layout = 'blank';
        $y == null ? $y = date('Y') + 543 : $y = $y + 543;
        $models = CourtOrderBoss::find()
            ->select(['id','year','num','date_write','name','owner'])
            ->where(['year' => $y])
            ->all(); 
        $data = [];
        
        foreach ($models as $model): 
            $data[]=[
                'id' => $model->id,
                'year' => $model->year,
                'num' => $model->num,
                'date_write' => $model->DateThai($model->date_write),
                'name' => $model->name,
                'owner' => $model->getProfileName().'<br>'.$model->getProfileGroup()
            ];
        endforeach;       
 
        $Pdf_print = 'report_b';
        
        Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;
        $pdf = new Pdf([
            'mode' => Pdf::MODE_UTF8, // leaner size using standard fonts
            'format' => Pdf::FORMAT_A4,
            // 'orientation' => Pdf::ORIENT_LANDSCAPE,
            'destination' => Pdf::DEST_BROWSER,
            'content' => $this->renderPartial($Pdf_print,[
                'data' => json_encode($data)
                ]),
            
            'cssFile' => 'css/pdf_l65.css',
            'options' => [
                // any mpdf options you wish to set
            ],
            'methods' => [
                'SetTitle' => 'คำสั่งศาล ',
                // 'SetSubject' => 'ใบขอเปลี่ยนเวร '.$model->id,
                // 'SetHeader' => ['Krajee Privacy Policy||Generated On: ' . date("r")],
                // 'SetFooter' => ['|Page {PAGENO}|'],
                // 'SetFooter' => ['Pkkjc WebApp'],
                'SetAuthor' => 'ศาลเยาวชนและครอบครัวจังหวัดประจวบคีรีขันธ์',
                'SetCreator' => 'Pkkjc-Web',
                // 'SetKeywords' => 'Krajee, Yii2, Export, PDF, MPDF, Output, Privacy, Policy, yii2-mpdf',
            ]
        ]);
        return $pdf->render();        
    }

}
