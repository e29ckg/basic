<?php

namespace app\controllers;
use Yii;
use app\models\Running;
use app\models\BilaFileUp;
// use app\models\User;
// use app\models\profile;
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
class RunningController extends Controller
{

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
        $models = Running::find()->all();        

        return $this->render('index', [
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
       
    public function actionCreate()
    {        
        $model = new Running();
        
        //Add This For Ajax Email Exist Validation 
        if(Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())){
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
          } 

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {

            $transaction = Yii::$app->db->beginTransaction();
            try {                    
               
                if($model->save()){
                    Yii::$app->session->setFlash('success', 'บันทึกข้อมูลเรียบร้อย');                   
                }  
                $transaction->commit();
                return $this->redirect(['index']);
                
            } catch (\Exception $e) {
                $transaction->rollBack();
                throw $e;
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

        //Add This For Ajax Email Exist Validation 
        if(Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())){
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
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

        if($model->delete()){
            Yii::$app->session->setFlash('success', 'บันทึกข้อมูลเรียบร้อย');
        }        
        return $this->redirect(['index']);
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
        if (($model = Running::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    
    
}
