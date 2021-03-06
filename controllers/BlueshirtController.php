<?php

namespace app\controllers;

use Yii;
use app\models\Blueshirt;
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
 * BlueshirtController implements the CRUD actions for Blueshirt model.
 */
class BlueshirtController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public $line_sms ='10.37.64.01';
    public $upload ='/uploads/user/';
    public $filePath = '/uploads/Blueshirt/';
    public $smsLineAlert = ' http://10.37.64.01/main/web/Blueshirt/show/';

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
     * Lists all Blueshirt models.
     * @return mixed
     */
    public function actionIndex()
    {
        $model = Blueshirt::find()
            ->orderBy([
            // // 'created_at'=>SORT_DESC,
            'line_alert' => SORT_DESC,
            ])
            ->limit(100)
            ->all();
        
        return $this->render('index',[
            'models' => $model,
        ]);

    }

    public function actionCreate()
    {
        $model = new Blueshirt();

        //Add This For Ajax Email Exist Validation 
        if(Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())){
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
          } 
     
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            
            $model->user_id = $_POST['Blueshirt']['user_id'];
            $model->user_id2 = $_POST['Blueshirt']['user_id2'];
            $model->line_alert = $_POST['Blueshirt']['line_alert'];
            if($model->save()){
                Yii::$app->session->setFlash('success', 'บันทึกข้อมูลเรียบร้อย'); 
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
     * Updates an existing Blueshirt model.
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

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            
            if($model->save()){
                Yii::$app->session->setFlash('success', 'บันทึกข้อมูลเรียบร้อย');
            }; 
      
            return $this->redirect(['index']);
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
     * Deletes an existing Blueshirt model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        
        if($model->delete()){
            Yii::$app->session->setFlash('success', 'ลบข้อมูลเรียบร้อย');    
                                            
        }        

        return $this->redirect(['index']);
    }

    
    /**
     * Finds the Blueshirt model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Blueshirt the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Blueshirt::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionLine_alert($id) {
        $model = $this->findModel($id);        
                  
            $message = 'เวรเสื้อฟ้า '.$model->line_alert."\n";
            $message .= $model->getProfileName() .'(เวร)'."\n".$model->getProfileName2().'(ตรวจ)';
            if($token = Line::getToken('blueshirt')){                
                $res = Line::notify_message($token,$message);  
                $res['status'] == 200 ? Yii::$app->session->setFlash('info', 'ส่งไลน์เรียบร้อย') :  Yii::$app->session->setFlash('info', 'ส่งไลน์ ไม่ได้') ;  
            }                
              
        return $this->redirect(['index']);        
    }
    

    public function actionCaid_index()
    {
        $model = BlueshirtCaid::find()->orderBy([
            'name'=>SORT_ASC,
            // 'id' => SORT_DESC,
            ])->all();
                
        return $this->render('caid_index',[
            'models' => $model,
        ]);

    }

    public function actionCaid_create(){
        
        $model = new BlueshirtCaid();

        //Add This For Ajax Email Exist Validation 
        if(Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())){
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
          } 
     
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
             
            $model->name = $_POST['BlueshirtCaid']['name'];
            $model->status = $_POST['BlueshirtCaid']['status'];

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
        
        $model = BlueshirtCaid::findOne($id);

        //Add This For Ajax Email Exist Validation 
        if(Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())){
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
          } 
     
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
             
            $model->name = $_POST['BlueshirtCaid']['name'];
            $model->status = $_POST['BlueshirtCaid']['status'];

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
        $model = BlueshirtCaid::findOne($id);
        
        if($model->delete()){
            Yii::$app->session->setFlash('success', 'ลบข้อมูลเรียบร้อย');  
        }        

        return $this->redirect(['caid_index']);
    }

    
    public function actionCaid_update_to_name()
    {
        $models = Blueshirt::find()->all();

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
