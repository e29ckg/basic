<?php

namespace app\controllers;
use Yii;
use app\models\SomtopV;
use app\models\Somtop;
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
use Da\QrCode\QrCode;
use yii\db\Transaction;
use yii\db\Connection;

/**
 * Web_linkController implements the CRUD actions for Ven model.
 */
class Somtop_vController extends Controller
{
    /**
     * {@inheritdoc}
     */    

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
        $models = SomtopV::find()
            ->orderBy([
            // 'date_create'=>SORT_DESC,
            'ven_date' => SORT_DESC,
            ])->limit(500)->all(); 

        $event = [];
        foreach ($models as $model):
            
            $even = [
                'id' => $model->id,
                'title' => $model->getName(),
                // 'title' => $model->ven_date.' '.$model->ven_time,
                'start' => $model->ven_date,
                // 'textColor' => $model->somtop_id == Yii::$app->user->identity->id ? 'yellow' :'',
                // 'end' => $model->date_end.'T12:30:00',
                // 'backgroundColor' => $backgroundColor,
                // 'borderColor' => $model->status == 1 ? '' :'#f56954'
            ];
            $event[] = $even;
        endforeach;        
        $event = json_encode($event);
        return $this->render('index',[
            'event' => $event,
        ]);
    }

    public function actionCreate($date_id)
    {
        $model = new SomtopV();  
        
        if(Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())){
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        } 
     
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $transaction = Yii::$app->db->beginTransaction();
            try {
                $request = Yii::$app->request->post('SomtopV');
                $model->id = time();
                $model->ven_date = $request['ven_date'];
                $model->somtop_id = $request['somtop_id'];  
                $model->created_at = date("Y-m-d H:i:s");

                if($model->save()){                                       
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
                'date_id'   => $date_id,   
            ]);
        }
        
        return $this->render('_form',[
            'model' => $model,
            'date_id'   => $date_id,   
        ]);
    }

    public function actionUpdate($id)
    {
        $model = SomtopV::findOne($id);  
        
        if(Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())){
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        } 
     
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $transaction = Yii::$app->db->beginTransaction();
            try {
                $request = Yii::$app->request->post('SomtopV');
                $model->id = time();
                $model->ven_date = $request['ven_date'];
                $model->somtop_id = $request['somtop_id']; 
                $model->created_at = date("Y-m-d H:i:s");

                if($model->save()){                                       
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
                'date_id'   => $model->ven_date,   
            ]);
        }
        
        return $this->render('_form',[
            'model' => $model,
            'date_id'   => $model->ven_date,  
        ]);
    }

    
    public function actionView($id)
    {
        $model = SomtopV::findOne($id);        
           
        return $this->renderAjax('view',[
            'model' => $model,
        ]);

        return $this->render('view',[
            'model' => $model,  
        ]);
    }

    public function actionDel($id)
    {
        $model = SomtopV::findOne($id);        
        if($model->delete()){            
            Yii::$app->session->setFlash('success', 'ลบข้อมูลเรียบร้อย');                                            
        }   
        return $this->redirect(['index']);
    }

    protected function findModel($id)
    {
        if (($model = SomtopV::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionLine_alert($id) {
        $model = $this->findModel($id);        
        
        $sms = $model->ven_date;
        $sms .= "\n";
                
        $sms .= $model->getName() ;
        $sms .= "\n";
        $sms .= '(เวรที่ผู้พิพากษาสมทบ)';
        $sms .= "\n";                
         
        $modelLine = Line::findOne(['name' => 'LineGroup']);     //แจ้ง lineGroup 
        
        if(isset($modelLine->token)){                
            $res = Line::notify_message($modelLine->token,$sms);  
            $res['status'] == 200 ? Yii::$app->session->setFlash('info', 'lineGroup ส่งไลน์เรียบร้อย') : Yii::$app->session->setFlash('info', 'ส่งไลน์ ไม่ได้') ;  
        }
        
        Yii::$app->session->setFlash('success', 'เรียบร้อย'.$sms );   
            
        return $this->redirect(['index']);        
    }
    
}
