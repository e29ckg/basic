<?php

namespace app\controllers;
use Yii;
use app\models\Ven;
use app\models\VenCom;
use app\models\VenChange;
// use app\models\User;
// use app\models\profile;
// use app\models\Line;
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
 * Web_linkController implements the CRUD actions for Ven model.
 */
class VenController extends Controller
{
    /**
     * {@inheritdoc}
     */

    public $line_sms ='10.37.64.01';
    public $upload ='/uploads/ven/';
    public $filePath = '/uploads/ven/';
    // public $smsLineAlert = ' http://10.37.64.01/main/web/cletter/show/';

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['index','admin','create_a','create_b','sbm_index','com_del'],
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
        $models = Ven::find()->where(['status' => 1 ])
            ->orWhere(['status' => 2 ])
            ->orWhere(['status' => 3 ])
            ->orderBy([
            // 'date_create'=>SORT_DESC,
            'ven_date' => SORT_DESC,
            ])->limit(100)->all(); 
        $i = 1 ;
        $event = [];
        foreach ($models as $model):
            if($model->status == 2){
                $backgroundColor = 'orange';  
            }elseif($model->ven_time == '16:30:55'){
                $backgroundColor = 'blue';
            }elseif($model->ven_time == '08:30:01'){
                $backgroundColor = '#FF6347';
            }else{
                $backgroundColor = 'green';
            }
            $even = [
                'id' => $model->id,
                'title' => $model->getProfileNameCal().' '.VenChange::getStatusList()[$model->status],
                // 'title' => $model->ven_date.' '.$model->ven_time,
                'start' => $model->ven_date.' '.$model->ven_time,
                'textColor' => $model->user_id == Yii::$app->user->identity->id ? 'yellow' :'',
                // 'end' => $model->date_end.'T12:30:00',
                'backgroundColor' => $backgroundColor,
                'borderColor' => $model->status == 1 ? '' :'#f56954'
            ];
            $event[] = $even;
        endforeach;        
        $event = json_encode($event);
        return $this->render('ven_index',[
            'event' => $event,
        ]);
    }

    public function actionVen_show($id)
    {
        $model = Ven::findOne($id);
        
        Ven::getCountVen($model->ven_com_id);

        return $this->renderAjax('ven_show',[
            'model' => $model,
            'countVen' => Ven::getVenForChange($model->ven_com_id),
        ]);
    }

    public function actionVen_change($id)
    {
        $model = new VenChange();

        if(Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())){
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        } 
        
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $transaction = Yii::$app->db->beginTransaction();
            try {                
                $modelV1 = Ven::findOne($_POST['VenChange']['ven_id1']);
                $modelV2 = Ven::findOne($_POST['VenChange']['ven_id2']);
                                                
                $modelV1->status = 4; 
                $modelV1->save();   
                $modelV2->status = 4;
                $modelV2->save();

                $id = (int)time();

                $modelV = new Ven();
                $modelV->id = $id;
                $modelV->ven_date = $modelV1->ven_date;  
                $modelV->ven_com_id = $modelV1->ven_com_id;
                $modelV->ven_time = $modelV1->ven_time;
                $modelV->ven_month = $modelV1->ven_month;
                $modelV->user_id = $modelV2->user_id;
                $modelV->status = 2;
                $modelV->ref1 = $modelV1->ref1;
                $modelV->ref2 = Yii::$app->security->generateRandomString();
                $modelV->create_at = date("Y-m-d H:i:s");   
                $modelV->save();

                $modelV = new Ven();
                $modelV->id = $id + 1;
                $modelV->ven_date =  $modelV2->ven_date;  
                $modelV->ven_com_id = $modelV2->ven_com_id;
                $modelV->ven_time = $modelV2->ven_time;
                $modelV->ven_month = $modelV2->ven_month;
                $modelV->user_id = $modelV1->user_id;
                $modelV->status = 2 ;
                $modelV->ref1 = $modelV2->ref2;
                $modelV->ref2 = Yii::$app->security->generateRandomString();                
                $modelV->create_at = date("Y-m-d H:i:s"); 
                $modelV->save(); 

                $model->ven_id1_old = $_POST['VenChange']['ven_id1'];
                $model->ven_id2_old = $_POST['VenChange']['ven_id2'];
                $model->ven_id1 = $id;
                $model->ven_id2 = $id + 1;
                $model->user_id1 = $modelV1->user_id;
                $model->user_id2 = $modelV2->user_id;
                $model->s_po = $_POST['VenChange']['s_po'];
                $model->s_bb = $_POST['VenChange']['s_bb'];
                $model->status = 2;
                $model->ref1 = $modelV1->ref2;    
                $model->ref2 = $modelV2->ref2;                
                $model->comment = null;
                $model->create_at = date("Y-m-d H:i:s");
                $model->save();

                $transaction->commit();
                Yii::$app->session->setFlash('success', 'บันทึกข้อมูลเรียบร้อย'); 
                return $this->redirect(['change_index']);
                
            } catch (\Exception $e) {
                $transaction->rollBack();
                throw $e;
            }             
        }

        $ven_id2 = Ven::findOne($id);
        $ven_id1 = Ven::getVenForChange($ven_id2->ven_com_id);        
        
        return $this->renderAjax('_ven_change',[
            'model' => $model,
            'ven_id2' => [$ven_id2->id => Ven::dateThai_full($ven_id2->ven_date).' '.$ven_id2->getProfileName().'('.$ven_id2->id.')'],
            'ven_id1' => $ven_id1,
        ]);
    }

    /****************************************Admin*********************************************** */

    public function actionAdmin_index()
    {
        $models = Ven::find()->orderBy([
            // 'date_create'=>SORT_DESC,
            'id' => SORT_DESC,
            ])->limit(100)->all();  
        $event = [];
        foreach ($models as $model):
            if($model->status == 2){
                $backgroundColor = 'red';  
            }elseif($model->ven_time == '16:30:55'){
                $backgroundColor = 'blue';
            }elseif($model->ven_time == '08:30:01'){
                $backgroundColor = '#FF6347';
            }else{
                $backgroundColor = 'green';
            }
            $even = [
                'id' => $model->id,
                'title' => $model->getProfileName(),
                // 'title' => $model->ven_date.' '.$model->ven_time,
                'start' => $model->ven_date.' '.$model->ven_time,
                'textColor' => $model->user_id == Yii::$app->user->identity->id ? 'yellow' :'',
                // 'end' => $model->date_end.'T12:30:00',
                'backgroundColor' => $backgroundColor,
                'borderColor' => $model->status == 1 ? '' :'#f56954'
            ];
            $event[] = $even;
        endforeach;        
        $event = json_encode($event);

        $modelVC = VenCom::find()->orderBy(['create_at' => SORT_DESC])->one();

        return $this->render('admin_index',[
            'event' => $event,
            'defaultDate' => isset($modelVC->ven_month) ? $modelVC->ven_month : date("Y-m-d"),
        ]);
    }

    public function actionAdmin_create($date_id)
    {
        $model = new Ven();  
        
        if(Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())){
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        } 
     
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $transaction = Yii::$app->db->beginTransaction();
            try {

                $modelVC = VenCom::findOne($_POST['Ven']['ven_com_id']); 

                $model->id = time();
                $model->ven_date = $_POST['Ven']['ven_date'];
                $model->ven_com_id = $modelVC->id;
                $model->user_id = $_POST['Ven']['user_id'];               
                $model->ven_time = $modelVC->ven_time;
                $model->ven_month = $modelVC->ven_month;
                $model->file = $modelVC->file;
                $model->ref1 = $modelVC->ref;
                $model->ref2 = Yii::$app->security->generateRandomString();
                $model->status = 1;                
                $model->comment = '';
                $model->create_at = date("Y-m-d H:i:s"); 

                if($model->save()){                                       
                    Yii::$app->session->setFlash('success', 'บันทึกข้อมูลเรียบร้อย');                   
                }  

                $transaction->commit();
                return $this->redirect(['admin_index']);
                
            } catch (\Exception $e) {
                $transaction->rollBack();
                throw $e;
            }             
        }
        
        if(Yii::$app->request->isAjax){
            return $this->renderAjax('_admin_create',[
                'model' => $model,  
                'date_id'   => $date_id,   
            ]);
        }
        
        return $this->render('_admin_create',[
            'model' => $model,
            'date_id'   => $date_id,   
        ]);
    }

    public function actionAdmin_update($id)
    {
        $model = Ven::findOne($id);  
        // $model->id = time();

        if(Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())){
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        } 
     
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $transaction = Yii::$app->db->beginTransaction();
            try {

                $modelVC = VenCom::findOne($_POST['Ven']['ven_com_id']); 

                $model->ven_date = $_POST['Ven']['ven_date'];
                $model->ven_com_id = $modelVC->id;
                $model->user_id = $_POST['Ven']['user_id'];               
                $model->ven_time = $modelVC->ven_time;
                $model->ven_month = $modelVC->ven_month;
                $model->file = $modelVC->file;
                $model->status = 1;                
                $model->comment = '';
                $model->create_at = date("Y-m-d H:i:s");  

                if($model->save()){
                                       
                    Yii::$app->session->setFlash('success', 'บันทึกข้อมูลเรียบร้อย');
                   
                }  

                $transaction->commit();
                return $this->redirect(['admin_index']);
                
            } catch (\Exception $e) {
                $transaction->rollBack();
                throw $e;
            }             
        }
        
        if(Yii::$app->request->isAjax){
            return $this->renderAjax('_admin_create',[
                'model' => $model,  
                'date_id'   => $model->ven_date,   
            ]);
        }        
        return $this->render('_admin_create',[
            'model' => $model,
            'date_id'   => $model->ven_date,   
        ]);
    }

    public function actionAdmin_del($id)
    {
        $model = Ven::findOne($id);        
        if($model->delete()){            
            Yii::$app->session->setFlash('success', 'ลบข้อมูลเรียบร้อย');                                            
        }   
        return $this->redirect(['admin_index']);
    }


    // ---------------------------------------------------------Ven Com-------------------------------------

    public function actionCom_index()
    {
        $models = VenCom::find()->orderBy([
            'ven_month' => SORT_DESC,
            'ven_time' => SORT_ASC,
            // 'id' => SORT_DESC,
            ])->limit(100)->all();  
        
        return $this->render('com_index',[
            'models' => $models,
        ]);
    }

    public function actionCom_create()
    {
        $model = new VenCom();          

        if(Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())){
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        } 
     
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $transaction = Yii::$app->db->beginTransaction();
            try {
                $model->id = time();
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
                // $model->ven_com_num = $_POST['VenCom']['ven_com_num'];
                // $model->ven_com_name = $_POST['VenCom']['ven_com_name'];
                $model->ven_month = $_POST['VenCom']['year'].'-'.$_POST['VenCom']['ven_month'];
                $model->ven_time = $_POST['VenCom']['ven_time'];
                $model->status = 1;
                $model->ref = Yii::$app->security->generateRandomString();
                $model->create_at = date("Y-m-d H:i:s");  

                if($model->save()){    
                    Yii::$app->session->setFlash('success', 'บันทึกข้อมูลเรียบร้อย');                   
                }  

                $transaction->commit();
                return $this->redirect(['com_index']);
                
            } catch (\Exception $e) {
                $transaction->rollBack();
                throw $e;
            }             
        }
        
        if(Yii::$app->request->isAjax){
            return $this->renderAjax('_com_create',[
                'model' => $model,         
            ]);
        }
        
        return $this->render('_com_create',[
            'model' => $model,
        ]);
    }

    public function actionCom_update($id)
    {
        $model = VenCom::findOne($id);  

        $filename = $model->file;

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
                    if($filename && is_file($dir.$filename)){
                        unlink($dir.$filename);// ลบ รูปเดิม;                    
                        
                    }
                    $fileName = md5($f->baseName . time()) . '.' . $f->extension;
                    if($f->saveAs($dir . $fileName)){
                        $model->file = $fileName;
                    }
                    
                }   
                
                // $model->ven_com_num = $_POST['VenCom']['ven_com_num'];
                // $model->ven_com_name = $_POST['VenCom']['ven_com_name'];
                $model->ven_month = $_POST['VenCom']['year'].'-'.$_POST['VenCom']['ven_month'];
                $model->ven_time = $_POST['VenCom']['ven_time'];
                $model->status = 1;
                $model->create_at = date("Y-m-d H:i:s");  
                if($model->save()){                                       
                    Yii::$app->session->setFlash('success', 'บันทึกข้อมูลเรียบร้อย');                   
                }  
                $transaction->commit();
                return $this->redirect(['com_index']);
                
            } catch (\Exception $e) {
                $transaction->rollBack();
                throw $e;
            }           
        }

        if(Yii::$app->request->isAjax){
            return $this->renderAjax('_com_create',[
                'model' => $model,         
            ]);
        }        
        return $this->render('_com_create',[
            'model' => $model,
        ]);
    }

    public function actionCom_update_status($id)
    {
        $model = VenCom::findOne($id);  

        // if(Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())){
        //     Yii::$app->response->format = Response::FORMAT_JSON;
        //     return ActiveForm::validate($model);
        // } 
     
        // if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            // $transaction = Yii::$app->db->beginTransaction();
            // try {
                //  if($model->status == 1){
                    $model->comment = '';
                //  } else{                 
                    // $model->status = 1;
                //  } 

                if($model->save()){
                    Yii::$app->session->setFlash('success', 'บันทึกข้อมูลเรียบร้อย'.$model->status);                   
                
                }   
                $model->save();
                Yii::$app->session->setFlash('success', $model->id);                                    
                // echo $model->status;
                // $transaction->commit();
                // return $this->redirect(['com_index']);
                
            // } catch (\Exception $e) {
            //     $transaction->rollBack();
            //     throw $e;
            // }           
        // }
            // return $this->redirect(['com_index']);
        
    }

    public function actionCom_del($id)
    {
        $model = VenCom::findOne($id);
        $filename = $model->file;
        $dir = Url::to('@webroot'.$this->filePath);
        
        if($filename && is_file($dir.$filename)){
            unlink($dir.$filename);// ลบ รูปเดิม;                    
        }
        if($model->delete()){
            
            Yii::$app->session->setFlash('success', 'ลบข้อมูลเรียบร้อย');    
                                          
        }   
        return $this->redirect(['com_index']);
    }


    // /********************************------------------Change-------******************************** */

    public function actionChange_index()
    {
        $models = VenChange::find()->orderBy([
            // 'date_create'=>SORT_DESC,
            'id' => SORT_DESC,
            ])->limit(50)->all();  

        // foreach ($models as $model):
            
        // endforeach;        

        return $this->render('change_index',[
            'models' => $models,
        ]);
    }

    public function actionChange_del($id)
    {
        $model = VenChange::findOne($id);  
        
        $filename = $model->file;
        $dir = Url::to('@webroot'.$this->filePath);
        
        if($filename && is_file($dir.$filename)){
            unlink($dir.$filename);// ลบ รูปเดิม;                    
        }

        $transaction = Yii::$app->db->beginTransaction();
            try {
                $modelV = Ven::findOne($model->ven_id1);
                $modelV->delete();

                $modelV = Ven::findOne($model->ven_id2);
                $modelV->delete();

                $modelV = Ven::findOne($model->ven_id1_old);
                $modelV->status = 1;
                $modelV->save();

                $modelV = Ven::findOne($model->ven_id2_old);
                $modelV->status = 1;
                $modelV->save(); 

                $model->delete();
                
                Yii::$app->session->setFlash('success', 'ลบข้อมูลเรียบร้อย');  
                                
                $transaction->commit();
            } catch (\Exception $e) {
                $transaction->rollBack();
                throw $e;
            }
        return $this->redirect(['change_index']);
    }

    public function actionChange_upfile($id) { 

        $model = VenChange::findOne($id);
                          
        // Add This For Ajax Email Exist Validation 
        if(Yii::$app->request->isAjax && $model->load(Yii::$app->request->post()) ){
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model) ;
        } 
        
        if ($model->load(Yii::$app->request->post()) && $model->validate()){ 
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

                    $transaction = Yii::$app->db->beginTransaction();
                    try {
                        
                        $model->status = 5;
                        $model->save();

                        $modelV1 = Ven::findOne($model->ven_id1);
                        $modelV1->file = $fileName;
                        $modelV1->status = 3;
                        $modelV1->save();

                        $modelV1 = Ven::findOne($model->ven_id2);
                        $modelV1->file = $fileName;
                        $modelV1->status = 3;
                        $modelV1->save();

                        $modelV3 = Ven::findOne($model->ven_id1_old);
                        $modelV3->status = 5;
                        $modelV3->save();

                        $modelV4 = Ven::findOne($model->ven_id2_old);
                        $modelV4->status = 5;
                        $modelV4->save();
                                                                    
                        Yii::$app->session->setFlash('success', 'บันทึกข้อมูลเรียบร้อย');
                                                  
                        $transaction->commit();
                        return $this->redirect(['change_index']);
                        
                    } catch (\Exception $e) {
                        $transaction->rollBack();
                        throw $e;
                    } 

            }else{
                Yii::$app->session->setFlash('warning', 'ไม่ได้บันทึกข้อมูล');
                return $this->redirect(['change_index']);
            }
        }
        // $model->file = $model->file;
        if(Yii::$app->request->isAjax){
            return $this->renderAjax('_change_upfile',[
                'model' => $model,               
            ]);
        }

        return $this->render('_change_upfile',[
            'model' => $model,                     
        ]);
    }

    public function actionChange_del_file($id)
    {
        $model = VenChange::findOne($id);  
        
        $filename = $model->file;
        $dir = Url::to('@webroot'.$this->filePath);
        
        if($filename && is_file($dir.$filename)){
            unlink($dir.$filename);// ลบ รูปเดิม;                    
        }
        
        $transaction = Yii::$app->db->beginTransaction();
            try {
                $modelV = Ven::findOne($model->ven_id1);
                $modelV->file = null;
                $modelV->status = 4;
                $modelV->save();

                $modelV = Ven::findOne($model->ven_id2);
                $modelV->file = null;
                $modelV->status = 4;
                $modelV->save();

                $modelV = Ven::findOne($model->ven_id1);
                $modelV->status = 2;
                $modelV->save();

                $modelV = Ven::findOne($model->ven_id2);
                $modelV->status = 2;
                $modelV->save();
 
                $model->file = null;
                $model->status = 2;
                $model->save();
                
                Yii::$app->session->setFlash('success', 'ลบข้อมูลเรียบร้อย');  
                                
                $transaction->commit();
            } catch (\Exception $e) {
                $transaction->rollBack();
                throw $e;
            }
        return $this->redirect(['change_index']);
    }

    public function actionChange_file_view($id)
    {
        $model = VenChange::findOne($id);           
        
        $completePath = Url::to('@webroot').$this->filePath.$model->file;
        if(is_file($completePath)){
            return Yii::$app->response->sendFile($completePath, $model->file, ['inline'=>true]);                        
        }else{
            Yii::$app->session->setFlash('warning', 'ไม่พบ File... '.$completePath);            
        }
        return $this->redirect(['change_index']);
    }

}
