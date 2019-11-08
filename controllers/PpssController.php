<?php

namespace app\controllers;

use Yii;
use app\models\Ppss;
use app\models\PpssSearch;
use app\models\profile;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\UploadedFile;
use yii\data\Pagination;
use yii\web\Session;

use app\models\UploadForm;
use yii\helpers\Url;
use yii\web\Response;
use yii\widgets\ActiveForm;
use kartik\mpdf\Pdf;

/**
 * PpssController implements the CRUD actions for Ppss model.
 */
class PpssController extends Controller {

    /**
     * @inheritdoc
     */
    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['index', 'create', 'delete', 'update', 'view_download'], //action ทั้งหมดที่มี
                'rules' => [
                    [
                        'actions' => ['index', 'create', 'delete', 'update', 'view_download'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
//                    [
//                        'actions' => ['index'],
//                        'allow' => true,
//                        'roles' => ['?', '@'],
//                    ],
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
     * Lists all Ppss models.
     * @return mixed
     */
    public function actionIndex($q = null) {
               
        $models = Ppss::find()->orderBy([
            'create_at'=>SORT_ASC,
            'id' => SORT_DESC,
            ])->limit(100)->all();     
                

        return $this->render('index', [
                    'models' => $models,
                    'countAll' => Ppss::getCountAll(),
                    'NameM1' => Ppss::getNameM1(),
                    'countM1' => Ppss::getCountM1(),    
                    'NameM2' => Ppss::getNameM2(),                
                    'countM2' => Ppss::getCountM2(),
        ]);
    }

    public function actionView($id) {
        if(Yii::$app->request->isAjax){
            return $this->renderAjax('view',[
                    'model' => $this->findModel($id),                   
            ]);
        }
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    public function actionViewm1() {        
        return $this->renderAjax('viewm', [
                    'All' => Ppss::getViewM1(),
                    'id' => 'M1',
        ]);
        return $this->render('view', [
            'All' => Ppss::getViewM1(),
            'id' => 'M1',
        ]);
    }

    public function actionViewm2() {        
        return $this->renderAjax('viewm', [
                    'All' => Ppss::getViewM2(),
                    'id' => 'M2',
        ]);
        return $this->render('view', [
            'All' => Ppss::getViewM2(),
            'id' => 'M2',
        ]);
    }

    /**
     * Creates a new Ppss model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
     
        $model = new Ppss();

        //Add This For Ajax Email Exist Validation 
        if(Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())){
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        } 

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {            
            $f = UploadedFile::getInstance($model, 'file');
            $f2 = UploadedFile::getInstance($model, 'file2');   

            if(!empty($f2)){
                $dir = Url::to('@webroot/uploads/ppss/');
                if (!is_dir($dir)) {
                    mkdir($dir, 0777, true);
                }
                $fileName2 = md5($f2->baseName . time()) . '.' . $f2->extension;
                if($f2->saveAs($dir . $fileName2)){
                    $model->file2 = $fileName2;
                } 
            }
            if(!empty($f)){
                $dir = Url::to('@webroot/uploads/ppss/');
                if (!is_dir($dir)) {
                    mkdir($dir, 0777, true);
                }
                $fileName = md5($f->baseName . time()) . '.' . $f->extension;
                if($f->saveAs($dir . $fileName)){
                    $model->file = $fileName;
                } 
            }
            $model->create_own = Yii::$app->user->identity->id;
            $model->create_at = date("Y-m-d H:i:s");

            if($model->save()){
                Yii::$app->session->setFlash('success', ['บันทึกเรียบร้อย']);
                return $this->redirect(['index']);
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
     * Updates an existing Ppss model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id) {
        
        $model = $this->findModel($id);

        $filenameOld = $model->file !== null ? $model->file : '';
        $filenameOld2 = $model->file2 !== null ? $model->file2 : '' ;
        //Add This For Ajax Email Exist Validation 
        if(Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())){
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        } 

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $f = UploadedFile::getInstance($model, 'file');
            $f2 = UploadedFile::getInstance($model, 'file2');

            $dir = Url::to('@webroot/uploads/ppss/');
                if (!is_dir($dir)) {
                    mkdir($dir, 0777, true);
                } 

            if(!empty($f)){                 
                if($filenameOld && is_file($dir.$filenameOld)){
                    unlink($dir.$filenameOld);// ลบ รูปเดิม;  
                }
                $fileName = md5($f->baseName . time()) . '.' . $f->extension;
                if($f->saveAs($dir . $fileName)){
                    $model->file = $fileName;
                }                        
            }else{
                $model->file = $filenameOld;
            }
            if(!empty($f2)){
                if($filenameOld2 && is_file($dir.$filenameOld2)){
                    unlink($dir.$filenameOld2);// ลบ รูปเดิม;  
                }
                $fileName2 = md5($f2->baseName . time()) . '.' . $f2->extension;
                if($f2->saveAs($dir . $fileName2)){
                    $model->file2 = $fileName2;
                } 
            }else{
                $model->file2 = $filenameOld2;
            }        
            // $model->file = $filename;
            $model->save();   
            Yii::$app->session->setFlash('success', ['บันทึกเรียบร้อย']);       
            return $this->redirect(['index', ['id' => $filenameOld,'id2' => $filenameOld2]]);
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

    public function actionUpdate_admin($id) {
        
        $model = $this->findModel($id);

        $filenameOld = $model->file !== null ? $model->file : '';
        $filenameOld2 = $model->file2 !== null ? $model->file2 : '' ;
        //Add This For Ajax Email Exist Validation 
        if(Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())){
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        } 

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $f = UploadedFile::getInstance($model, 'file');
            $f2 = UploadedFile::getInstance($model, 'file2');

            $dir = Url::to('@webroot/uploads/ppss/');
                if (!is_dir($dir)) {
                    mkdir($dir, 0777, true);
                } 

            if(!empty($f)){                 
                if($filenameOld && is_file($dir.$filenameOld)){
                    unlink($dir.$filenameOld);// ลบ รูปเดิม;  
                }
                $fileName = md5($f->baseName . time()) . '.' . $f->extension;
                if($f->saveAs($dir . $fileName)){
                    $model->file = $fileName;
                }                        
            }else{
                $model->file = $filenameOld;
            }
            if(!empty($f2)){
                if($filenameOld2 && is_file($dir.$filenameOld2)){
                    unlink($dir.$filenameOld2);// ลบ รูปเดิม;  
                }
                $fileName2 = md5($f2->baseName . time()) . '.' . $f2->extension;
                if($f2->saveAs($dir . $fileName2)){
                    $model->file2 = $fileName2;
                } 
            }else{
                $model->file2 = $filenameOld2;
            }        
            // $model->file = $filename;
            Yii::$app->session->setFlash('success', ['บันทึกเรียบร้อย']);
            $model->save();          
            return $this->redirect(['index', ['id' => $filenameOld,'id2' => $filenameOld2]]);
        }
        if(Yii::$app->request->isAjax){
            return $this->renderAjax('_form_update_admin',[
                    'model' => $model,                    
            ]);
        }else{
            return $this->render('_form_update_admin',[
                'model' => $model,                    
            ]); 
        }
        
    }

    /**
     * Deletes an existing Ppss model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id) {
        $model = $this->findModel($id);
        $filename = $model->file;
        $filename2 = $model->file2;
        $dir = Url::to('@webroot/uploads/ppss/');
        
        if($filename && is_file($dir.$filename)){
            unlink($dir.$filename);// ลบ รูปเดิม;                    
        }
        if($filename2 && is_file($dir.$filename2)){
            unlink($dir.$filename2);// ลบ รูปเดิม;                    
        }

        $model->delete();
        Yii::$app->session->setFlash('success', 'ลบข้อมูลเรียบร้อย');
        return $this->redirect(['index']);
    }

    /**
     * Finds the Ppss model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Ppss the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = Ppss::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionShow($id) {

        $model = $this->findModel($id);
    
        // This will need to be the path relative to the root of your app.
        $filePath = '/web/uploads/ppss';
        // Might need to change '@app' for another alias
        $completePath = Yii::getAlias('@app'.$filePath.'/'.$model->file);
        if(is_file($completePath)){
            return Yii::$app->response->sendFile($completePath, $model->file, ['inline'=>true]);
        }else{
            Yii::$app->session->setFlash('error', 'ไม่พบไฟล์ No File');
            if(Yii::$app->request->isAjax){
                return $this->redirect(['index']);
            }else{
                return $this->redirect(['index']);
            }
        }
        
    }

    public function actionShow2($id) {

        $model = $this->findModel($id);
    
        // This will need to be the path relative to the root of your app.
        $filePath = '/web/uploads/ppss';
        // Might need to change '@app' for another alias
        $completePath = Yii::getAlias('@app'.$filePath.'/'.$model->file2);
        if(is_file($completePath)){
            return Yii::$app->response->sendFile($completePath, $model->file2, ['inline'=>true]);
        }else{
            Yii::$app->session->setFlash('error', 'ไม่พบไฟล์ No File');
            if(Yii::$app->request->isAjax){
                return $this->redirect(['index']);
            }else{
                return $this->redirect(['index']); 
            }
        }
        
    }
    
    public function actionDownload_file($i,$file_name,$name_save) {
        $filePath  = Url::to('@webroot/uploads/ppss/');
        $name_save = str_replace("/","-",$name_save);
        $filePath .= $file_name;
        $name_s = $i.'_';
        $name_s .= $name_save;
        if (is_file($filePath)) {
            $file_parts = pathinfo($filePath);
            $name_s .= '.'.$file_parts['extension'];
            Yii::$app->response->sendFile($filePath,$name_s);
        }else{
            Yii::$app->session->setFlash('error', 'ไม่พบไฟล์ No File');
        }        
    }

    public function actionBcheck($id) {
        $model = Ppss::findOne($id);
        $model->note = '1';
        $model->save();
        return $this->redirect(['index']);
    }

    public function actionPrint($id = null)
    {
        if($id == 'M1'){
            $models = Ppss::getViewM1();
            $Mname = Ppss::getMthai1();
        }else{
            $models = Ppss::getViewM2();
            $Mname = Ppss::getMthai2();
        }
        
        Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;
    $pdf = new Pdf([
        'mode' => Pdf::MODE_UTF8, // leaner size using standard fonts
        'content' => $this->renderPartial('print',['models' => $models,'Mname'=>$Mname]),
        'cssFile' => 'css/pdf.css',
        'options' => [
            // any mpdf options you wish to set
        ],
        'methods' => [
            // 'SetTitle' => 'Privacy Policy - Krajee.com',
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
    
}
