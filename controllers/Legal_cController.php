<?php
namespace app\controllers;
use Yii;
use yii\web\Controller;
use app\models\LegalC;
use app\models\Line;
use yii\web\UploadedFile;
use yii\helpers\Url;
use yii\web\Response;
use yii\widgets\ActiveForm;
use yii\web\Session;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;
use yii\base\Model;

class Legal_cController extends Controller{

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['index','dep','fname'],
                'rules' => [
                    [
                        'actions' => ['index','dep','fname'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    // 'delete' => ['POST'],
                ],
            ],
        ];
    }
    
    public function actionIndex($id = null){
        // $this->layout = 'main'; 
        $models = LegalC::find()->all();      
        
        return $this->render('index',[
            'models' => $models,
        ]);
    }

      

    public function actionCreate() {
        $model = new LegalC();        
        //Add This For Ajax Email Exist Validation 
        if(Yii::$app->request->isAjax && $model->load(Yii::$app->request->post()) ){
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model) ;
        } 

        if ($model->load(Yii::$app->request->post()) && $model->validate()){ 
            $f = UploadedFile::getInstance($model, 'img');
            if(!empty($f)){                
                $dir = Url::to('@webroot/uploads/Legal_c/');
                if (!is_dir($dir)) {
                    mkdir($dir, 0777, true);
                }
                $fileName = md5($f->baseName . time()) . '.' . $f->extension;
                if($f->saveAs($dir . $fileName)){
                    $model->img = $fileName;
                }                            
            }
            $model->id = time();    
            $model->fname = Yii::$app->request->post('LegalC')['fname'];
            $model->name = Yii::$app->request->post('LegalC')['name'];
            $model->sname  = Yii::$app->request->post('LegalC')['sname'];
            $model->id_card = Yii::$app->request->post('LegalC')['id_card'];
            $model->address = Yii::$app->request->post('LegalC')['address'];
            $model->phone = Yii::$app->request->post('LegalC')['phone'];
            $model->status = '10';
            $model->created_at = date("Y-m-d H:i:s");
            if($model->save() && $model->save() ){  
                Yii::$app->session->setFlash('success', 'บันทึกข้อมูลเรียบร้อย');
                return $this->redirect(['index']);
            }
        }

        if(Yii::$app->request->isAjax){
            return $this->renderAjax('_form',[
                'model' => $model,               
            ]);
        }
        return $this->render('_form',[
            'model' => $model,                     
        ]);
    }
    
            
    public function actionUpdate($id){        
        // $model = Profile::findOne($id);
        $model = LegalC::findOne($id);  
        $fileName = $model->img ;
        if(Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())){
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model) ;
            // return ActiveForm::validateMultiple([$model,$model]);
        } 
        if ($model->load(Yii::$app->request->post())) {
            $f = UploadedFile::getInstance($model, 'img');
            if(!empty($f)){                
                $dir = Url::to('@webroot/uploads/Legal_c/');
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
            $model->img = $fileName;
            $model->fname = Yii::$app->request->post('LegalC')['fname'];
            $model->name = Yii::$app->request->post('LegalC')['name'];
            $model->sname  = Yii::$app->request->post('LegalC')['sname'];
            $model->id_card = Yii::$app->request->post('LegalC')['id_card'];
            $model->address = Yii::$app->request->post('LegalC')['address'];
            $model->phone = Yii::$app->request->post('LegalC')['phone'];
            $model->updated_at = date("Y-m-d H:i:s");
            if($model->save()){
                Yii::$app->session->setFlash('success', 'บันทึกข้อมูลเรียบร้อย');
            };          
            return $this->redirect(['index']);
        }         
        
        if(Yii::$app->request->isAjax){
            return $this->renderAjax('_form',[
                    'model' => $model,                    
            ]);
        }        
        return $this->render('_form',[
               'model' => $model,                    
        ]);
    }

    public function actionDelete($id)
    {
        $model = LegalC::findOne($id);
        $filename = $model->img;
        $dir = Url::to('@webroot/uploads/Legal_c/');        
        if($filename && is_file($dir.$filename)){
            unlink($dir.$filename);// ลบ รูปเดิม;                    
        }
        if($model->delete()){
            Yii::$app->session->setFlash('success', 'ลบข้อมูลเรียบร้อย');
        }
        return $this->redirect(['index']);
    }

    public function actionStatus($id)
    {
        $model = LegalC::findOne($id);
                
        if($model->status == '10'){
            $model->status = '0';                    
        }else{
            $model->status = '10';  
        }
        $model->updated_at = date("Y-m-d H:i:s");
        $model->save();
        return $this->redirect(['index']);
    }
    
}