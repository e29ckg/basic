<?php

namespace app\controllers;

use Yii;
use app\models\WebLink;
use app\models\WebLinkFile;
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
 * Web_linkController implements the CRUD actions for WebLink model.
 */
class Web_linkController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['index_admin'],
                'rules' => [
                    [
                        'actions' => ['index_admin'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                    'deletefile' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all WebLink models.
     * @return mixed
     */
    public function actionTest()
    {
       echo Yii::$app->google->shortUrl('http://www.pkkjc.coj.go.th/');
    echo Yii::$app->google->expandUrl('https://goo.gl/p1EnW1');
    return Yii::$app->google->shortUrl('https://pkkjc.coj.go.th/th/page/item/index/id/1');
    }

    public function actionIndex()
    {
        $models = WebLink::find()->orderBy([
            'create_at'=>SORT_DESC,
            'id' => SORT_DESC,
            ])->all();
                

        return $this->render('index', [
            'models' => $models,
        ]);
    }

    public function actionIndex_admin()
    {       
        $models = WebLink::find()->orderBy([
            'create_at'=>SORT_DESC,
            'id' => SORT_DESC,
            ])->all();
        
        return $this->render('index_admin', [
            'models' => $models,
        ]);
    }
    

    /**
     * Displays a single WebLink model.
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
     * Creates a new WebLink model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new WebLink();

        //Add This For Ajax Email Exist Validation 
        if(Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())){
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
          } 
     
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $model->id = time();
            $f = UploadedFile::getInstance($model, 'img');
            if(!empty($f)){
                $dir = Url::to('@webroot/uploads/weblink/'.$model->id.'/');
                if (!is_dir($dir)) {
                    mkdir($dir, 0777, true);
                } 
                                
                $fileName = md5($f->baseName . time()) . '.' . $f->extension;
                if($f->saveAs($dir . $fileName)){
                    $model->img = $fileName;
                }               
            } 
            $model->name = $_POST['WebLink']['name'];
            $model->link = $_POST['WebLink']['link'];
            $model->create_at = date("Y-m-d H:i:s");
            $model->update_at = date("Y-m-d H:i:s");
            if($model->save()){
                Yii::$app->session->setFlash('success', 'บันทึกข้อมูลเรียบร้อย');
                return $this->redirect(['index_admin']);
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

    
    public function actionCreatefile($id)
    {
        $model = $this->findModel($id);

        $modelFile = new WebLinkFile();

        //Add This For Ajax Email Exist Validation 
        if(Yii::$app->request->isAjax && $modelFile->load(Yii::$app->request->post())){
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($modelFile);
          } 
     
        if ($modelFile->load(Yii::$app->request->post()) && $modelFile->validate()) {

            $f = UploadedFile::getInstance($modelFile, 'file');
            if(!empty($f)){
                $dir = Url::to('@webroot/uploads/weblink/'.$model->id.'/');
                if (!is_dir($dir)) {
                    mkdir($dir, 0777, true);
                } 
                                
                $fileName = md5($f->baseName . time()) . '.' . $f->extension;
                if($f->saveAs($dir . $fileName)){
                    $modelFile->file = $fileName;                    
                    $modelFile->type = $f->extension;
                    $modelFile->name  = $f->baseName;
                }               
            } 

            $modelFile->web_link_id = $model->id;                      
            
            if(!($_POST['WebLinkFile']['name'])==''){
                $modelFile->name = $_POST['WebLinkFile']['name'];
            }
                
            $modelFile->sort = 1;
            if($modelFile->save()){
                Yii::$app->session->setFlash('success', 'บันทึกข้อมูลเรียบร้อย');
                return $this->redirect(['index_admin']);
            }   

        }

        if(Yii::$app->request->isAjax){
            return $this->renderAjax('_form_file',[
                    'modelFile' => $modelFile,                    
            ]);
        }else{
            return $this->render('_form_file',[
                'modelFile' => $modelFile,                    
            ]); 
        }
    }

    public function actionCreateurl($id)
    {
        $model = $this->findModel($id);

        $modelFile = new WebLinkFile();

        //Add This For Ajax Email Exist Validation 
        if(Yii::$app->request->isAjax && $modelFile->load(Yii::$app->request->post())){
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($modelFile);
          } 
     
        if ($modelFile->load(Yii::$app->request->post()) && $modelFile->validate()) {

            if($_POST['WebLinkFile']['name'] == ''){
                $modelFile->name = $_POST['WebLinkFile']['url'];
            }else{
                $modelFile->name = $_POST['WebLinkFile']['name']; 
            }

            $modelFile->web_link_id = $model->id; 
            $modelFile->url = $_POST['WebLinkFile']['url'];                    
            $modelFile->type = "url";
                
            $modelFile->sort = 1;
            if($modelFile->save()){
                Yii::$app->session->setFlash('success', 'บันทึกข้อมูลเรียบร้อย'.$_POST['WebLinkFile']['name']);
                return $this->redirect(['index_admin']);
            }   

        }

        if(Yii::$app->request->isAjax){
            return $this->renderAjax('_form_url',[
                    'modelFile' => $modelFile,                    
            ]);
        }else{
            return $this->render('_form_url',[
                'modelFile' => $modelFile,                    
            ]); 
        }
    }
    
    
    /**
     * Updates an existing WebLink model.
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
                $dir = Url::to('@webroot/uploads/weblink/'.$model->id.'/');
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
            $model->name = $_POST['WebLink']['name'];
            $model->link = $_POST['WebLink']['link'];
            $model->img = $fileName;
            $model->update_at = date("Y-m-d H:i:s");
            if($model->save()){
                Yii::$app->session->setFlash('success', 'บันทึกข้อมูลเรียบร้อย');
                return $this->redirect(['index_admin']);
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

    public function actionUpdatefile($id)
    {
        $modelFile = $this->findModelFileByWebLinkId($id);

        $fileName = $modelFile->file;

        //Add This For Ajax Email Exist Validation 
        if(Yii::$app->request->isAjax && $modelFile->load(Yii::$app->request->post())){
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($modelFile);
          } 
     
        if ($modelFile->load(Yii::$app->request->post()) && $modelFile->validate()) {

            $f = UploadedFile::getInstance($modelFile, 'file');
            if(!empty($f)){
                $dir = Url::to('@webroot/uploads/weblink/'.$modelFile->web_link_id.'/');
                if (!is_dir($dir)) {
                    mkdir($dir, 0777, true);
                }
                if($fileName && is_file($dir.$fileName)){
                    unlink($dir.$fileName);// ลบ รูปเดิม;   
                }
                $fileName = md5($f->baseName . time()) . '.' . $f->extension;
                if($f->saveAs($dir . $fileName)){
                    $modelFile->file = $fileName;                    
                    $modelFile->type = $f->extension;
                    $modelFile->name  = $f->baseName;
                }
            } 
            $modelFile->file = $fileName;
            
            if(!($_POST['WebLinkFile']['name'])==''){
                $modelFile->name = $_POST['WebLinkFile']['name'];
            }
                
            // $modelFile->sort = 1;
            if($modelFile->save()){
                Yii::$app->session->setFlash('success', 'บันทึกข้อมูลเรียบร้อย'.$_POST['WebLinkFile']['name']);
                return $this->redirect(['index_admin']);
            }   

        }

        if(Yii::$app->request->isAjax){
            return $this->renderAjax('_form_file_update',[
                    'modelFile' => $modelFile,                    
            ]);
        }else{
            return $this->render('_form_file_update',[
                'modelFile' => $modelFile,                    
            ]); 
        }
    }

public function actionUpdateurl($id)
    {
        $modelFile = $this->findModelFileByWebLinkId($id);

        $fileName = $modelFile->url;

        //Add This For Ajax Email Exist Validation 
        if(Yii::$app->request->isAjax && $modelFile->load(Yii::$app->request->post())){
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($modelFile);
          } 
            
        if ($modelFile->load(Yii::$app->request->post()) && $modelFile->validate()) {
           
            if($_POST['WebLinkFile']['name'] == ''){
                $modelFile->name = $_POST['WebLinkFile']['url'];
            }else{
                $modelFile->name = $_POST['WebLinkFile']['name']; 
            }            

            if($modelFile->save()){
                // Yii::$app->session->setFlash('success', 'บันทึกข้อมูลเรียบร้อย'.$_POST['WebLinkFile']['name']);
                return $this->redirect(['index_admin']);
            }   

        }

        if(Yii::$app->request->isAjax){
            return $this->renderAjax('_form_url',[
                    'modelFile' => $modelFile,                    
            ]);
        }else{
            return $this->render('_form_url',[
                'modelFile' => $modelFile,                    
            ]); 
        }
    }

    /**
     * Deletes an existing WebLink model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    

    public function actionDelete($id)
    {
        $modelFiles  = WebLinkFile::find()->where(['web_link_id' => $id])->all();
        $dir = Url::to('@webroot/uploads/weblink/'.$id.'/');
        foreach ($modelFiles as $modelFile):                   
            
            if (!is_dir($dir)) {
                mkdir($dir, 0777, true);
            }
            if($modelFile->name && is_file($dir.$modelFile->file)){
                unlink($dir.$modelFile->file);// ลบ รูปเดิม;   
            } 

        endforeach;          
        
        WebLinkFile::deleteAll(['web_link_id' => $id]);        

        $model = $this->findModel($id); 
        
        if($model->img && is_file($dir.$model->img)){
            unlink($dir.$model->img);// ลบ รูปเดิม;   
        }   

        $model->delete();

        if (is_dir($dir)) {
            // mkdir($dir, 0777, true);
            rmdir($dir);
        }
       
        return $this->redirect(['index_admin']);
    }
    
public function actionDeletefile($id)
    {
        $modelfile = $this->findModelFileByWebLinkId($id);
        $fileName = $modelfile->file;
        $dir = Url::to('@webroot/uploads/weblink/'.$modelfile->web_link_id.'/');
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }
        if($fileName && is_file($dir.$fileName)){
            unlink($dir.$fileName);// ลบ รูปเดิม;   
        }        
        
        $modelfile->delete();

        return $this->redirect(['index_admin']);
    }

    public function actionDeleteurl($id)
    {
        $modelfile = $this->findModelFileByWebLinkId($id);
        
        $modelfile->delete();

        return $this->redirect(['index_admin']);
    }

    public function actionShow($id=null){
        // $mdWebLink = WebLink::find()->where(['id' => $id])->one();
        $mdWebLink = $this->findModel($id);

        if(Yii::$app->request->isAjax){
            return $this->renderAjax('show',[
                    'model' => $mdWebLink,             
            ]);
        }
        
        return $this->render('show',[
               'model' => $mdWebLink,                    
        ]);
    }

    public function actionOpenfile($id=null){
        $modelfile = $this->findModelFileByWebLinkId($id);
        $filePath = '/web/uploads/weblink/'.$modelfile->web_link_id;
        // Might need to change '@app' for another alias
        $completePath = Yii::getAlias('@app'.$filePath.'/'.$modelfile->file);
        return Yii::$app->response->sendFile($completePath, $file, ['inline'=>true]);;
    }

    /**
     * Finds the WebLink model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return WebLink the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = WebLink::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    protected function findModelFile($id)
    {
        if (($model = WebLinkFile::find()->where(['web_link_id' => $id])->one()) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    protected function findModelFileByWebLinkId($id)
    {
        if (($model = WebLinkFile::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }


    public function actionPrint($id = null)
    {
        // $this->layout = 'cart_shop';   
        // $user_id = Yii::$app->user->id;
        // $model = $this->findModel($id);
        $user_id = Yii::$app->user->id;
        // $model_lists = OrderList::find()->where(['order_code'=> $model->order_code])->all();

        // $destination = Pdf::DEST_BROWSER;
        // $destination = Pdf::DEST_DOWNLOAD;
        $destination = Pdf::DEST_FILE;
// 
        $filename = Yii::$app->basePath .'/web/uploads/a1.pdf';
        Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;
    $pdf = new Pdf([
        'mode' => Pdf::MODE_UTF8, // leaner size using standard fonts
        'content' => $this->renderPartial('print',[]),
        // portrait orientation
        'orientation' => Pdf::ORIENT_PORTRAIT,
        // stream to browser inline
        'destination' => $destination,
        'filename' => $filename,
        // 'cssFile' => 'css/pdf.css',
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
    // $mpdf = $pdf->api;
     // fetches mpdf api
    //  $html = self::generateTablePdf($id);
    //  $mpdf->shrink_tables_to_fit = 8;
    //  $mpdf->use_kwt = true;
    //  $mpdf->table_keep_together = true;
    //  $mpdf->WriteHtml($html);
     // call mpdf write html
    //  $filename = \Yii::$app->basePath . '/web/uploads/1.pdf';
    //  $mpdf->Output($filename, 'F');
    $pdf->render();
    // return $pdf->render();
    return $this->redirect(['index']);
    }

    
    public function actionUpdate00(){
        $models = WebLink::find()->all();

    foreach ($models as $model):
        $source = Url::to('@webroot/uploads/weblink/'.$model->img);
        $dir = Url::to('@webroot/uploads/weblink/'.$model->id.'/');
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        } 

        $destination = $dir;
        echo $source.'<br>'; 
        echo $destination.'<br>'; 
        if(is_file($source))
        {
            echo ("$source is a regular file <br>");
            if (copy($source, $destination.$model->img)) {
                    echo "The file was moved successfully.<br>", "\n";
                    if(unlink($source)){
                        echo "File was Delete successfully.", "\n";
                    }
                    
                } else {
                    echo "The specified file could not be moved. Please try again.", "\n";
                }
        }else{
            echo ("$source is not a regular file");
        } 
    
    endforeach;

    }

    public function actionSearch($q = null) {

        // if (!empty($q)) {
            $query = WebLink::find()->where(['LIKE','name', $q]);
        // }
                
        $models = $query->orderBy(['id' => SORT_ASC])->all();
                
        if(Yii::$app->request->isAjax){
                return $this->renderAjax('_search',[
                    'models' => $models,
                ]);
        } else {
                
                return $this->render('index',[
                    'models' => $models,
                ]);
        }
    }


}
