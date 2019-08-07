<?php

namespace app\controllers;

use Yii;
use app\models\Log;
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
use kartik\mpdf\Pdf;

/**
 * LogController implements the CRUD actions for Log model.
 */
class LogController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Log models.
     * @return mixed
     */
    public function actionTest()
    {
        $this->layout = 'main_cal';
        return $this->render('test');
    }

    public function actionTest1()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;
        $pdf = new Pdf([
            'mode' => Pdf::MODE_UTF8, // leaner size using standard fonts
            'content' => $this->renderPartial('_preview1',[]),
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

    public function actionIndex()
    {
        $sql = 'SELECT * FROM Log';
        // $models = Yii::$app->db->createCommand($sql)->queryAll();
        $models = Log::find()->orderBy([
            'create_at'=>SORT_DESC,
            'id' => SORT_DESC,
            ])->limit(100)->all();        
        
        $countAll = Log::getCountAll();

        return $this->render('index', [
            'models' => $models,
            'countAll' => $countAll,
        ]);
    }

    public function actionAdmin()
    {
        $sql = 'SELECT * FROM Log';
        // $models = Yii::$app->db->createCommand($sql)->queryAll();
        $models = Log::find()->orderBy([
            'create_at'=>SORT_DESC,
            'id' => SORT_DESC,
            ])->limit(100)->all();        
        
        $countAll = Log::getCountAll();

        return $this->render('index_admin', [
            'models' => $models,
            'countAll' => $countAll,
        ]);
    }
    

    /**
     * Displays a single Log model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Log model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Log();

        //Add This For Ajax Email Exist Validation 
        if(Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())){
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
          } 
     
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            
            $f = UploadedFile::getInstance($model, 'img');
            if(!empty($f)){
                $dir = Url::to('@webroot/uploads/Log/');
                if (!is_dir($dir)) {
                    mkdir($dir, 0777, true);
                } 
                                
                $fileName = md5($f->baseName . time()) . '.' . $f->extension;
                if($f->saveAs($dir . $fileName)){
                    $model->img = $fileName;
                }               
            } 
            $model->name = $_POST['Log']['name'];
            $model->link = $_POST['Log']['link'];
            $model->create_at = date("Y-m-d H:i:s");
            $model->update_at = date("Y-m-d H:i:s");
            if($model->save()){
                Yii::$app->session->setFlash('success', 'บันทึกข้อมูลเรียบร้อย');
                return $this->redirect(['admin']);
            }   
        }

        // $model->tel = explode(',', $model->tel);
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
     * Updates an existing Log model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        $fileName = $model->img;

        //Add This For Ajax Email Exist Validation 
        if(Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())){
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
          }


        if ($model->load(Yii::$app->request->post()) && $model->save()) {
           if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            
            $f = UploadedFile::getInstance($model, 'img');
            if(!empty($f)){
                $dir = Url::to('@webroot/uploads/Log/');
                if (!is_dir($dir)) {
                    mkdir($dir, 0777, true);
                }
                if($fileName && is_file($dir.$fileName)){
                    unlink($dir.$fileName);// ลบ รูปเดิม;   
                }
                $fileName = md5($f->baseName . time()) . '.' . $f->extension;
                if($f->saveAs($dir . $fileName)){
                    $model->img = $fileName;
                }               
            } 
            $model->name = $_POST['Log']['name'];
            $model->link = $_POST['Log']['link'];
            $model->img = $fileName;
            $model->update_at = date("Y-m-d H:i:s");
            if($model->save()){
                Yii::$app->session->setFlash('success', 'บันทึกข้อมูลเรียบร้อย');
                return $this->redirect(['admin']);
            }   
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
     * Deletes an existing Log model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    

    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $fileName = $model->img;
        $dir = Url::to('@webroot/uploads/Log/');
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }
        if($fileName && is_file($dir.$fileName)){
            unlink($dir.$fileName);// ลบ รูปเดิม;   
        }        
        
        $model->delete();

        return $this->redirect(['admin']);
    }

    public function actionShow($id=null){
        $mdLog = Log::find()->where(['id' => $id])->one();

        if(Yii::$app->request->isAjax){
            return $this->renderAjax('show',[
                    'model' => $mdLog,                    
            ]);
        }
        
        return $this->render('show',[
               'model' => $mdLog,                    
        ]);
    }

    /**
     * Finds the Log model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Log the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Log::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
