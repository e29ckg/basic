<?php

namespace app\controllers;
use Yii;
use app\models\LawBan;
use yii\web\Controller;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\Response;
use yii\widgets\ActiveForm;
use yii\web\UploadedFile;
use kartik\mpdf\Pdf;
use Da\QrCode\QrCode;
use yii\db\Transaction;
use yii\db\Connection;

/**
 * Web_linkController implements the CRUD actions for Ven model.
 */
class Law_banController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public $filePath = '/uploads/law_ban/';

    public $line_sms ='http://10.37.64.01';

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['change_user_index','index','change_del_user','change_del','admin_index','change_index','com_index','show_ven_change','show_ven'],
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

   public function actionTest()
   {
    
       return $this->render('test');
   }
    
    public function actionIndex()
    {
        $models = LawBan::find()->orderBy([
            'updated_at'=>SORT_DESC,
            'created_at' => SORT_DESC])->limit(500)->all(); 
        
        return $this->render('index',[
            'models' => $models,
        ]);
    }

    public function actionCreate()
    {
        $model = new LawBan();

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
            $model->name = $_POST['LawBan']['name'];
            $model->license = $_POST['LawBan']['license'];
            $model->created_at = date("Y-m-d H:i:s");
           

            if($model->save()){   
                Yii::$app->session->setFlash('success', 'บันทึกข้อมูลเรียบร้อย'); 
                return $this->redirect(['index']);
            }   
        }

        if(Yii::$app->request->isAjax){
            return $this->renderAjax('_form',[
                    'model' => $model,                    
            ]);
        }else{
            return $this->render('_form',[
                'model' => $model,                    
            ]); 
        }

    }

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
            
            $model->name = $_POST['LawBan']['name'];
            $model->license = $_POST['LawBan']['license'];
            $model->updated_at = date("Y-m-d H:i:s");           

            if($model->save()){   
                Yii::$app->session->setFlash('success', 'บันทึกข้อมูลเรียบร้อย'); 
                return $this->redirect(['index']);
            }   
        }

        if(Yii::$app->request->isAjax){
            return $this->renderAjax('_form',[
                    'model' => $model,                    
            ]);
        }else{
            return $this->render('_form',[
                'model' => $model,                    
            ]); 
        }
    }

    public function actionDel($id)
    {
        $model = $this->findModel($id);
        $filePath = Url::to('@webroot'.$this->filePath.$model->file);       
        if(is_file($filePath)){
            unlink($filePath);// ลบ ไฟล์;   
            Yii::$app->session->setFlash('success', 'ลบไฟล์เรียบร้อย');                         
        }
        if($model->delete()){   
            Yii::$app->session->setFlash('success', 'ลบข้อมูลเรียบร้อย'); 
            return $this->redirect(['index']);
        }  
        return $this->redirect(['index']);        

    }

    protected function findModel($id)
    {
        if (($model = LawBan::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionShow($id) {
        
        $model = $this->findModel($id);         
        
        $completePath = Url::to('@webroot').$this->filePath.$model->file;
        if(is_file($completePath)){   
            if(Yii::$app->request->isAjax){
                return $this->renderAjax('show',[
                        'completePath' => Url::to('@web').$this->filePath.$model->file,                    
                ]);
            }         
            
        }else{
            Yii::$app->session->setFlash('warning', 'ไม่พบ File... '.$completePath);
            return $this->redirect(['index']);;
        }
    }

}
