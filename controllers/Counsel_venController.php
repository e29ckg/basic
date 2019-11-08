<?php

namespace app\controllers;

use Yii;
use app\models\Counsel_ven;
use app\models\Line;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\UploadedFile;
use yii\helpers\Url;
use yii\web\Response;
use yii\widgets\ActiveForm;

/**
 * Counsel_venController implements the CRUD actions for Counsel_ven model.
 */
class Counsel_venController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public $line_sms ='10.37.64.01';
    public $filePath = '/uploads/Counsel_ven/';

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
     * Lists all Counsel_ven models.
     * @return mixed
     */
    public function actionIndex()
    {
        $model = Counsel_ven::find()
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
        $model = new Counsel_ven();

        //Add This For Ajax Email Exist Validation 
        if(Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())){
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
          } 
     
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            
            $model->user_id = $_POST['Counsel_ven']['user_id'];
            $model->user_id2 = $_POST['Counsel_ven']['user_id2'];
            $model->line_alert = $_POST['Counsel_ven']['line_alert'];
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
     * Updates an existing Counsel_ven model.
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
     * Deletes an existing Counsel_ven model.
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
     * Finds the Counsel_ven model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Counsel_ven the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Counsel_ven::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionLine_alert($id) {
        $model = $this->findModel($id);        
                  
            $message = 'เวรเสื้อฟ้า '.$model->line_alert."\n";
            $message .= $model->getProfileName() .'(เวร)'."\n".$model->getProfileName2().'(ตรวจ)';
            if($token = Line::getToken('Counsel_ven')){                
                $res = Line::notify_message($token,$message);  
                $res['status'] == 200 ? Yii::$app->session->setFlash('info', 'ส่งไลน์เรียบร้อย') :  Yii::$app->session->setFlash('info', 'ส่งไลน์ ไม่ได้') ;  
            }                
              
        return $this->redirect(['index']);        
    }
    

    public function actionCaid_index()
    {
        $model = Counsel_venCaid::find()->orderBy([
            'name'=>SORT_ASC,
            // 'id' => SORT_DESC,
            ])->all();
                
        return $this->render('caid_index',[
            'models' => $model,
        ]);

    }

    public function actionCaid_create(){
        
        $model = new Counsel_venCaid();

        //Add This For Ajax Email Exist Validation 
        if(Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())){
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
          } 
     
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
             
            $model->name = $_POST['Counsel_venCaid']['name'];
            $model->status = $_POST['Counsel_venCaid']['status'];

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
        
        $model = Counsel_venCaid::findOne($id);

        //Add This For Ajax Email Exist Validation 
        if(Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())){
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
          } 
     
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
             
            $model->name = $_POST['Counsel_venCaid']['name'];
            $model->status = $_POST['Counsel_venCaid']['status'];

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
        $model = Counsel_venCaid::findOne($id);
        
        if($model->delete()){
            Yii::$app->session->setFlash('success', 'ลบข้อมูลเรียบร้อย');  
        }        

        return $this->redirect(['caid_index']);
    }

    
    public function actionCaid_update_to_name()
    {
        $models = Counsel_ven::find()->all();

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
