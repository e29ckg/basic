<?php

namespace app\controllers;
use Yii;
use app\models\Emeeting;
use app\models\Line;
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
 * Web_linkController implements the CRUD actions for Ven model.
 */
class EmeetingController extends Controller
{
    /**
     * {@inheritdoc}
     */    

    public $line_sms ='http://10.37.64.01';
    public $filePath = '/uploads/emeeting/';
    public $line_name = 'emeeting';

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['test'],
                'rules' => [
                    [
                        'actions' => ['test'],
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
        $models = Emeeting::find()
            ->orderBy([
            // 'date_create'=>SORT_DESC,
            'id' => SORT_DESC,
            ])->limit(500)->all(); 

        $event = [];
        foreach ($models as $model):
            
            $even = [
                'id' => $model->id,
                'title' => date('H:i', strtotime($model->start)).' '.$model->cname,
                // 'title' => $model->ven_date.' '.$model->ven_time,
                'start' => $model->start,
                // 'textColor' => $model->legal_c_id == Yii::$app->user->identity->id ? 'yellow' :'',
                'end' => $model->end,
                // 'backgroundColor' => $backgroundColor,
                // 'borderColor' => $model->status == 1 ? '' :'#f56954'
            ];
            $event[] = $even;
        endforeach;        
        $event = json_encode($event);
        return $this->render('index',[
            'event' => $event,
            'models' => $models
        ]);
    }

    public function actionCreate()
    {
        $model = new Emeeting();  
        
        if(Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())){
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        } 
     
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $transaction = Yii::$app->db->beginTransaction();
            try {
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
                }else{
                    $model->file = null;
                } 

                $request = Yii::$app->request->post('Emeeting');
                // $model->id = time();
                $model->title = $request['title'];
                $model->start = $request['start'];
                $model->end = $request['end'];
                $model->cname = $request['cname'];
                $model->fname = $request['fname'];
                $model->tel = $request['tel'];
                $model->detail = $request['detail'];            
                $model->created_at = date("Y-m-d H:i:s");
                $message = $model->cname .' '.$model->title.' '.$model->start .' โดย '.$model->fname.':'.$model->tel;
                if($model->save()){          
                    Line::send_sms_to($this->line_name,$message);                           
                    Yii::$app->session->setFlash('success', 'บันทึกข้อมูลเรียบร้อย');                   
                }  

                $transaction->commit();               
                
            } catch (\Exception $e) {
                $transaction->rollBack();
                throw $e;
            }   
            
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

    public function actionUpdate($id)
    {
        $model = Emeeting::findOne($id);  
        $fileName = $model->file;
        if(Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())){
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        } 
     
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $transaction = Yii::$app->db->beginTransaction();
            try {
                $f = UploadedFile::getInstance($model, 'file');
                if(!empty($f)){
                    $dir = Url::to('@webroot'.$this->filePath);
                    if (!is_dir($dir)) {
                        mkdir($dir, 0777, true);
                    }
                    if($fileName && is_file($dir.$fileName)){
                        unlink($dir.$fileName);// ลบ ไฟล์เดิม; 
                    }
                    $fileName = md5($f->baseName . time()) . '.' . $f->extension;
                    if($f->saveAs($dir . $fileName)){
                        $model->file = $fileName;
                    }               
                }

                $request = Yii::$app->request->post('Emeeting');
                
                $model->title = $request['title'];
                $model->start = $request['start'];
                $model->end = $request['end'];
                $model->cname = $request['cname'];
                $model->fname = $request['fname'];
                $model->tel = $request['tel'];
                $model->detail = $request['detail'];            
                // $model->created_at = date("Y-m-d H:i:s");
                $message = '[แก้ไข]'.$model->title.$model->start;
                if($model->save()){                      
                    Line::send_sms_to($this->line_name,$message);                                        
                    Yii::$app->session->setFlash('success', 'บันทึกข้อมูลเรียบร้อย');                   
                }  

                $transaction->commit();               
                
            } catch (\Exception $e) {
                $transaction->rollBack();
                throw $e;
            }   
            
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

    
    public function actionView($id)
    {
        $model = Emeeting::findOne($id);        
        $completePath = Url::to('@web'.'/uploads/emeeting/').$model->file;
        return $this->renderAjax('view',[
            'model' => $model,
            'completePath' => $completePath
        ]);

        return $this->render('view',[
            'model' => $model, 
            'completePath' => $completePath 
        ]);
    }

    public function actionDel($id)
    {
        $model = Emeeting::findOne($id);  
        $fileName = $model->file; 
        $message = Yii::$app->user->id.' [ลบ] '.$model->cname.$model->title.' วันที่ '.$model->start;
        $filePath = Url::to('@webroot'.$this->filePath.'/'.$fileName);     
        // $dir = Url::to('@webroot'.$this->filePath);
        if($fileName && is_file($filePath)){
            unlink($filePath);// ลบ ไฟล์เดิม;        
        } 
       
        if($model->delete()){ 
            Line::send_sms_to($this->line_name,$message);              
            Yii::$app->session->setFlash('success', 'ลบข้อมูลเรียบร้อย');   
        }   
        return $this->redirect(['index']);
    }

    protected function findModel($id)
    {
        if (($model = Emeeting::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

}
