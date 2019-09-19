<?php

namespace app\controllers;
use Yii;
use app\models\Ven;
use app\models\VenCom;
use app\models\VenChange;
use app\models\VenAdminCreate;
use app\models\VenTransfer;
use app\models\VenChangeUpFile;
use app\models\SignBossName;
use app\models\VenChangeUpdate;
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
class VenController extends Controller
{
    /**
     * {@inheritdoc}
     */

    public $line_sms ='http://10.37.64.01';
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

   public function actionTest()
   {
    

       return $this->render('test');
   }
    
    public function actionIndex()
    {
        $models = Ven::find()->where(['status' => 1 ])
            ->orWhere(['status' => 2 ])
            // ->orWhere(['status' => 3 ])
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
                'title' => $model->profile->name.' '.VenChange::getStatusList()[$model->status],
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
        
        $modelDs = Ven::find()
            ->where(['ref1'=>$model->ref1]) 
            ->andWhere('id <> :id', [':id' => $model->id])
            ->andWhere('status <> 77')
            ->orderBy(['id' => SORT_DESC])->all();

        return $this->renderAjax('ven_show',[
            'model' => $model,
            'modelDs' => $modelDs,
        ]);
    }

    public function actionVen_user_index()
    {
        $models = Ven::find()->where([
                'user_id' => Yii::$app->user->identity->id,
            ])
            ->orderBy([
                'ven_date' => SORT_ASC
            ])->all();
        
        return $this->render('ven_user_index',[
            'models' => $models,
        ]);
    }

    public function actionVen_file_view($id)
    {
        $model = Ven::findOne($id);           
        
        // $completePath = Url::to('@webroot').$this->filePath.$model->file;
        $completePath  = Url::to('@webroot'.$this->filePath.'/'.$model->file);
        if(is_file($completePath)){
            return Yii::$app->response->sendFile($completePath, $model->file, ['inline'=>true]);                        
        }else{
            Yii::$app->session->setFlash('warning', 'ไม่พบ File... '.$completePath);            
        }
        return $this->redirect(['ven_index']);
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
                
                $id = (int)time();
                $ref_vc = Yii::$app->security->generateRandomString();

                $modelV = new Ven();
                $modelV->id = $id ;
                $modelV->ven_date = $modelV1->ven_date;  
                $modelV->ven_com_id = $modelV1->ven_com_id;
                $modelV->ven_time = $modelV1->ven_time;
                $modelV->ven_month = $modelV1->ven_month;
                $modelV->user_id = $modelV2->user_id;
                $modelV->status = 2;
                $modelV->ref1 = $modelV1->ref1;
                $modelV->ref2 = $ref_vc;
                $modelV->create_at = date("Y-m-d H:i:s");   
                $modelV->save();

                $modelVv = new Ven();
                $modelVv->id = $id + 1;
                $modelVv->ven_date =  $modelV2->ven_date;  
                $modelVv->ven_com_id = $modelV2->ven_com_id;
                $modelVv->ven_time = $modelV2->ven_time;
                $modelVv->ven_month = $modelV2->ven_month;
                $modelVv->user_id = $modelV1->user_id;
                $modelVv->status = 2 ;
                $modelVv->ref1 = $modelV2->ref1;
                $modelVv->ref2 = $ref_vc;                
                $modelVv->create_at = date("Y-m-d H:i:s"); 
                $modelVv->save(); 

                $model->id = $id;
                $model->ven_id1_old = $_POST['VenChange']['ven_id2'];
                $model->ven_id2_old = $_POST['VenChange']['ven_id1'];
                $model->ven_id1 = $id;
                $model->ven_id2 = $id + 1;
                $model->user_id1 = $modelV2->user_id;
                $model->user_id2 = $modelV1->user_id;
                $model->s_po = $_POST['VenChange']['s_po'];
                $model->s_bb = $_POST['VenChange']['s_bb'];
                $model->status = 2;
                $model->ref1 = $ref_vc;    
                $model->ref2 = null;                
                $model->comment = $_POST['VenChange']['comment'];
                $model->create_at = date("Y-m-d H:i:s");
                $model->save();

                $modelV1->status = 4; 
                $modelV1->save();   
                $modelV2->status = 4;
                $modelV2->save();
                
                $transaction->commit();   
                
                $dir = Url::to('@webroot'.$this->filePath.'/');
                    if (!is_dir($dir)) {
                        mkdir($dir, 0777, true);
                    } 
                $sms_qr = isset($this->line_sms) ? $this->line_sms : Yii::$app->getRequest()->hostInfo ;
                $sms_qr .= '/ven.php?ref='.$model->id;
                $qrCode = (new QrCode($sms_qr))
                    ->setSize(250)
                    ->setMargin(5)
                    ->useForegroundColor(1, 1, 1);              
                $qrCode->writeFile(Url::to('@webroot'.$this->filePath.'/'.$model->id.'.png')); // writer defaults to PNG when none is specified
                /*---------------------ส่ง line ไปยัง Admin--------------------*/
                $modelLine = Line::findOne(['name' => 'admin']);
                if(isset($modelLine->token)){
                    $message = $model->profile->name;
                    $message .= isset($model->ven_id2) ? 'เปลี่ยนเวร' : 'ยกเวร';
                    $message .= "\n".' รายละเอียดเพิ่มเติม' ."\n".$sms_qr;
                    $res = Line::notify_message($modelLine->token,$message);  
                    $res['status'] == 200 ? Yii::$app->session->setFlash('info', 'ส่งไลน์เรียบร้อย') :  Yii::$app->session->setFlash('info', 'ส่งไลน์ ไม่ได้') ;  
                } 

                Yii::$app->session->setFlash('success', 'บันทึกข้อมูลเรียบร้อย'); 
            } catch (\Exception $e) {
                $transaction->rollBack();
                throw $e;
            } 
            
            return $this->redirect(['change_user_index']);            
        }

        $ven_id1 = Ven::findOne($id);
        $ven_id2 = Ven::getVenForChangeAll($id);        
        
        return $this->renderAjax('_ven_change',[
            'model' => $model,
            'ven_id1' => [$ven_id1->id => Ven::dateThai_full($ven_id1->ven_date).' '.$ven_id1->profile->name.'('.$ven_id1->id.')'.$ven_id1->venCom->ven_com_num],
            'ven_id2' => $ven_id2,
        ]);
    }

    public function actionVen_transfer($id)
    {
        $model = new VenTransfer();

        if(Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())){
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        } 
        
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $transaction = Yii::$app->db->beginTransaction();
            try {              
                $modelV1 = Ven::findOne($_POST['VenTransfer']['ven_id1']);
                $modelV1->status = 6; 
                $modelV1->save();
                
                $id = (int)time();
                $ref_vc = Yii::$app->security->generateRandomString();

                $modelV = new Ven();
                $modelV->id = $id ;
                $modelV->ven_date = $modelV1->ven_date;  
                $modelV->ven_com_id = $modelV1->ven_com_id;
                $modelV->ven_time = $modelV1->ven_time;
                $modelV->ven_month = $modelV1->ven_month;
                $modelV->user_id = $_POST['VenTransfer']['user_id2'];
                $modelV->status = 2;
                $modelV->ref1 = $modelV1->ref1;
                $modelV->ref2 = $ref_vc;
                $modelV->create_at = date("Y-m-d H:i:s");   
                $modelV->save();

                $model->id = $id ;
                $model->ven_id1_old = $_POST['VenTransfer']['ven_id1'];
                $model->ven_id2_old = null;
                $model->ven_id1 = $id;
                $model->ven_id2 = null;
                $model->user_id1 = $modelV1->user_id;
                $model->user_id2 = $_POST['VenTransfer']['user_id2'];
                $model->s_po = $_POST['VenTransfer']['s_po'];
                $model->s_bb = $_POST['VenTransfer']['s_bb'];
                $model->status = 6;
                $model->ref1 = $ref_vc;    
                $model->ref2 = null;                
                $model->comment = $_POST['VenTransfer']['comment'];
                $model->create_at = date("Y-m-d H:i:s");
                $model->save();
                
                $transaction->commit();      
                
                
            } catch (\Exception $e) {
                $transaction->rollBack();
                throw $e;
            } 

            $dir = Url::to('@webroot'.$this->filePath.'/');
                    if (!is_dir($dir)) {
                        mkdir($dir, 0777, true);
                    } 
                $sms_qr = isset($this->line_sms) ? $this->line_sms : Yii::$app->getRequest()->hostInfo ;
                $sms_qr .= '/ven.php?ref='.$model->id;
                $qrCode = (new QrCode($sms_qr))
                    ->setSize(250)
                    ->setMargin(5)
                    ->useForegroundColor(1, 1, 1);              
                $qrCode->writeFile(Url::to('@webroot'.$this->filePath.'/'.$model->id.'.png')); // writer defaults to PNG when none is specified
                /*---------------------ส่ง line ไปยัง Admin--------------------*/
                $modelLine = Line::findOne(['name' => 'admin']);
                if(isset($modelLine->token)){
                    $message = $model->profile->name;
                    $message .= $model->ven_id2 ? 'เปลี่ยนเวร' : 'ยกเวร';
                    $message .= "\n".' รายละเอียดเพิ่มเติม' ."\n".$sms_qr;
                    $res = Line::notify_message($modelLine->token,$message);  
                    $res['status'] == 200 ? Yii::$app->session->setFlash('info', 'ส่งไลน์เรียบร้อย') :  Yii::$app->session->setFlash('info', 'ส่งไลน์ ไม่ได้') ;  
                } 

                Yii::$app->session->setFlash('success', 'บันทึกข้อมูลเรียบร้อย'); 
            return $this->redirect(['change_user_index']);            
        }

        $ven_id1 = Ven::findOne($id);
        // $ven_id2 = Ven::getVenForChangeAll($id); 
        
        return $this->renderAjax('_ven_transfer',[
            'model' => $model,
            'ven_id1' => [$ven_id1->id => Ven::dateThai_full($ven_id1->ven_date).' '.$ven_id1->profile->name.'('.$ven_id1->id.')'],
            // 'ven_id1' => $ven_id1,
        ]);
    }

    public function actionVen_change_update($id)
    {
        $model = VenChangeUpdate::findOne($id);
        

        if(Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())){
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        } 
        
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $transaction = Yii::$app->db->beginTransaction();
            try {              
                              
                // $model->ven_id1_old = $_POST['VenChange']['ven_id1'];
                // $model->ven_id2_old = $_POST['VenChange']['ven_id2'];
                // $model->ven_id1 = $id;
                // $model->ven_id2 = $id + 1;
                // $model->user_id1 = $modelV1->user_id;
                // $model->user_id2 = $modelV2->user_id;
                $model->s_po = $_POST['VenChangeUpdate']['s_po'];
                $model->s_bb = $_POST['VenChangeUpdate']['s_bb'];
                // $model->status = 2;
                // $model->ref1 = $ref_vc;    
                // $model->ref2 = null;                
                $model->comment = $_POST['VenChangeUpdate']['comment'];
                $model->create_at = date("Y-m-d H:i:s");
                $model->save();

                
                $transaction->commit();   
                
                $dir = Url::to('@webroot'.$this->filePath.'/');
                    if (!is_dir($dir)) {
                        mkdir($dir, 0777, true);
                    } 
                $sms_qr = isset($this->line_sms) ? $this->line_sms : Yii::$app->getRequest()->hostInfo ;
                $sms_qr .= '/ven.php?ref='.$model->id;
                $qrCode = (new QrCode($sms_qr))
                    ->setSize(250)
                    ->setMargin(5)
                    ->useForegroundColor(1, 1, 1);              
                $qrCode->writeFile(Url::to('@webroot'.$this->filePath.'/'.$model->id.'.png')); // writer defaults to PNG when none is specified
                /*---------------------ส่ง line ไปยัง Admin--------------------*/
                $modelLine = Line::findOne(['name' => 'admin']);
                if(isset($modelLine->token)){
                    $message = $model->profile->name.' แก้ไข ';
                    $message .= isset($model->ven_id2) ? 'เปลี่ยนเวร' : 'ยกเวร';
                    $message .= "\n".' รายละเอียดเพิ่มเติม' ."\n".$sms_qr;
                    $res = Line::notify_message($modelLine->token,$message);  
                    $res['status'] == 200 ? Yii::$app->session->setFlash('info', 'ส่งไลน์เรียบร้อย') :  Yii::$app->session->setFlash('info', 'ส่งไลน์ ไม่ได้') ;  
                } 

                Yii::$app->session->setFlash('success', 'บันทึกข้อมูลเรียบร้อย'); 
            } catch (\Exception $e) {
                $transaction->rollBack();
                throw $e;
            } 
            
            return $this->redirect(['change_user_index']);            
        }     
        
        return $this->renderAjax('_ven_change_update',[
            'model' => $model,
        ]);
                
    }

    public function actionChange_upfile($id) { 

        $model = VenChangeUpFile::findOne($id);
                          
        // Add This For Ajax Email Exist Validation 
        if(Yii::$app->request->isAjax && $model->load(Yii::$app->request->post()) ){
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model) ;
        } 
        
        if ($model->load(Yii::$app->request->post()) && $model->validate()){ 
            $f = UploadedFile::getInstance($model, 'file');
            
            if(!empty($f)){  
                $fileName = md5($f->baseName . time()) . '.' . $f->extension;
                $dir = Url::to('@webroot'.$this->filePath );
                // $dir = Url::to('@webroot'.$this->filePath);
                if (!is_dir($dir)) {
                    mkdir($dir, 0777, true);
                }
                
                if($f->saveAs($dir .'/'. $fileName)){
                    $model->file = $fileName;
                } 

                if(!($model->ven_id2 == null)){
                    $dir = Url::to('@webroot'.$this->filePath);              
                    // $dir = Url::to('@webroot'.$this->filePath);
                    if (!is_dir($dir)) {
                        mkdir($dir, 0777, true);
                    }
                    if($f->saveAs($dir .'/'. $fileName)){
                        $model->file = $fileName;
                    }
                }  
                             

                    $transaction = Yii::$app->db->beginTransaction();
                    try {
                        
                        $model->status = 5;
                        $model->save();

                        $modelV1 = Ven::findOne($model->ven_id1);
                        $modelV1->file = $fileName;
                        $modelV1->status = 1;
                        $modelV1->save();

                        if(!($model->ven_id2 == null)){
                            $modelV1 = Ven::findOne($model->ven_id2);
                            $modelV1->file = $fileName;
                            $modelV1->status = 1;
                            $modelV1->save();
                        }                        

                        $modelV3 = Ven::findOne($model->ven_id1_old);
                        $modelV3->status = 5;
                        $modelV3->save();

                        if(!($model->ven_id2_old == null)){
                            $modelV4 = Ven::findOne($model->ven_id2_old);
                            $modelV4->status = 5;
                            $modelV4->save();
                        }
                                                                    
                        Yii::$app->session->setFlash('success', 'บันทึกข้อมูลเรียบร้อย');
                                                  
                        $transaction->commit();
                        return $this->redirect(['change_user_index']);
                        
                    } catch (\Exception $e) {
                        $transaction->rollBack();
                        throw $e;
                    } 

            }else{
                Yii::$app->session->setFlash('warning', 'ไม่ได้บันทึกข้อมูล');
                return $this->redirect(['change_user_index']);
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
        $model = VenChangeUpFile::findOne($id);  
        
        $filename = $model->file;
        // $dir = Url::to('@webroot'.$this->filePath);
        $dir = Url::to('@webroot'.$this->filePath);
        
        if($filename && is_file($dir.'/'.$filename)){
            unlink($dir.'/'.$filename);// ลบ รูปเดิม;                    
        }
        
        $transaction = Yii::$app->db->beginTransaction();
            try {
                $modelV = Ven::findOne($model->ven_id1);
                $modelV->file = null;
                $modelV->status = 2;
                $modelV->save();

                if(!($model->ven_id2 == null)){
                    $modelV = Ven::findOne($model->ven_id2);
                    $modelV->file = null;
                    $modelV->status = 2;
                    $modelV->save();
                }                

                $modelV = Ven::findOne($model->ven_id1_old);
                if(isset($modelV)){
                    $modelV->status = 4;
                    $modelV->save();
                }
                
                if(!($model->ven_id2_old == null)){
                    $modelV = Ven::findOne($model->ven_id2_old);
                    $modelV->status = 4;
                    $modelV->save();
                }

                $model->file = null;
                $model->status = 2;
                $model->save();
                
                Yii::$app->session->setFlash('success', 'ลบข้อมูลเรียบร้อย');  
                                                
                $transaction->commit();
            } catch (\Exception $e) {
                $transaction->rollBack();
                throw $e;
            }
        return $this->redirect(['change_user_index']);
    }

    public function actionChange_del_user($id)
    {
        $model = VenChange::findOne($id);  
        
        $filename = $model->file;
        // $dir = Url::to('@webroot'.$this->filePath.$model->user_id1);
        $dir = Url::to('@webroot'.$this->filePath);
        
        if(is_file($dir.'/'.$model->id.'.png')){
            unlink($dir.'/'.$model->id.'.png');// ลบ รูปเดิม;                    
        }
         
        if($filename && is_file($dir.'/'.$filename)){
            unlink($dir.'/'.$filename);// ลบ รูปเดิม;   
                           
        }

        $transaction = Yii::$app->db->beginTransaction();
            try {
                
                $modelV = Ven::findOne($model->ven_id1);
                $modelV->status = 77;
                $modelV->save();
                
                if(!empty($model->ven_id2)){                    
                    $modelV = Ven::findOne($model->ven_id2);
                    $modelV->status = 77;
                    $modelV->save();
                }                

                $modelV = Ven::findOne($model->ven_id1_old);
                if(!empty($modelV)){
                    $modelV->status = 1;
                    $modelV->save();
                }                 

                if(!empty($model->ven_id2_old)){
                    $modelV = Ven::findOne($model->ven_id2_old);
                    $modelV->status = 1;
                    $modelV->save();
                }

                $model->delete();
                
                Yii::$app->session->setFlash('success', 'ลบข้อมูลเรียบร้อย');  
                                
                $transaction->commit();
            } catch (\Exception $e) {
                $transaction->rollBack();
                throw $e;
            }
        return $this->redirect(['change_user_index']);
    }

    public function actionChange_del($id)
    {
        $model = VenChange::findOne($id);  
        
        $filename = $model->file;
        $dir = Url::to('@webroot'.$this->filePath);
        if(is_file($dir.'/'.$model->id.'.png')){
            unlink($dir.'/'.$model->id.'.png');                
        }
        if($filename && is_file($dir.'/'.$filename)){
            unlink($dir.'/'.$filename);// ลบ รูปเดิม;                    
        }

        $transaction = Yii::$app->db->beginTransaction();
            try {
                Ven::findOne($model->ven_id1)->delete();

                if(!empty($model->ven_id2)){
                    Ven::findOne($model->ven_id2)->delete();
                }                

                $modelV = Ven::findOne($model->ven_id1_old);
                $modelV->status = 1;
                $modelV->save();

                if(!empty($model->ven_id2_old)){
                    $modelV = Ven::findOne($model->ven_id2_old);
                    $modelV->status = 1;
                    $modelV->save();
                }
        
                if($model->delete()){
                    $dir = Url::to('@webroot'.$this->filePath);
                    if(is_file($dir.'/'.$model->id.'.png')){
                        unlink($dir.'/'.$model->id.'.png');// ลบ รูปเดิม;   
                    } 
                    if(is_file($dir.'/'.$model->file.'.png')){
                        unlink($dir.'/'.$model->file);// ลบ ไฟล์  
                    }
                    if (is_dir($dir)) {
                        // mkdir($dir, 0777, true);
                        rmdir($dir);
                    } 
                    /*---------------------ส่ง line ไปยัง Admin--------------------*/
                    $modelLine = Line::findOne(['name' => 'bila_admin']);
                    if(isset($modelLine->token)){                
                        $res = Line::notify_message($modelLine->token,$message);  
                        $res['status'] == 200 ? Yii::$app->session->setFlash('info', 'ส่งไลน์เรียบร้อย') :  Yii::$app->session->setFlash('info', 'ส่งไลน์ ไม่ได้') ;  
                    } 
                }     
                
                Yii::$app->session->setFlash('success', 'ลบข้อมูลเรียบร้อย');  
                                
                $transaction->commit();
            } catch (\Exception $e) {
                $transaction->rollBack();
                throw $e;
            }
        return $this->redirect(['change_user_index']);
    }

    

/*------------------------------------Admin ------------------------------------------------------------*/

    public function actionVen_admin_change()
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
                
                $id = (int)time();
                $ref_vc = Yii::$app->security->generateRandomString();

                $modelV = new Ven();
                $modelV->id = $id ;
                $modelV->ven_date = $modelV1->ven_date;  
                $modelV->ven_com_id = $modelV1->ven_com_id;
                $modelV->ven_time = $modelV1->ven_time;
                $modelV->ven_month = $modelV1->ven_month;
                $modelV->user_id = $modelV2->user_id;
                $modelV->status = 2;
                $modelV->ref1 = $modelV1->ref1;
                $modelV->ref2 = $ref_vc;
                $modelV->create_at = date("Y-m-d H:i:s");   
                $modelV->save();

                $modelVv = new Ven();
                $modelVv->id = $id + 1;
                $modelVv->ven_date =  $modelV2->ven_date;  
                $modelVv->ven_com_id = $modelV2->ven_com_id;
                $modelVv->ven_time = $modelV2->ven_time;
                $modelVv->ven_month = $modelV2->ven_month;
                $modelVv->user_id = $modelV1->user_id;
                $modelVv->status = 2 ;
                $modelVv->ref1 = $modelV2->ref1;
                $modelVv->ref2 = $ref_vc;                
                $modelVv->create_at = date("Y-m-d H:i:s"); 
                $modelVv->save(); 

                $model->id = $id;
                $model->ven_id1_old = $_POST['VenChange']['ven_id1'];
                $model->ven_id2_old = $_POST['VenChange']['ven_id2'];
                $model->ven_id1 = $id;
                $model->ven_id2 = $id + 1;
                $model->user_id1 = $modelV1->user_id;
                $model->user_id2 = $modelV2->user_id;
                $model->s_po = $_POST['VenChange']['s_po'];
                $model->s_bb = $_POST['VenChange']['s_bb'];
                $model->status = 2;
                $model->ref1 = $ref_vc;    
                $model->ref2 = null;                
                $model->comment = $_POST['VenChange']['comment'];
                $model->create_at = date("Y-m-d H:i:s");
                $model->save();

                $modelV1->status = 4; 
                $modelV1->save();   
                $modelV2->status = 4;
                $modelV2->save();
                
                $transaction->commit();   
                
                $dir = Url::to('@webroot'.$this->filePath.'/');
                    if (!is_dir($dir)) {
                        mkdir($dir, 0777, true);
                    } 
                $sms_qr = isset($this->line_sms) ? $this->line_sms : Yii::$app->getRequest()->hostInfo ;
                $sms_qr .= '/ven.php?ref='.$model->id;
                $qrCode = (new QrCode($sms_qr))
                    ->setSize(250)
                    ->setMargin(5)
                    ->useForegroundColor(1, 1, 1);              
                $qrCode->writeFile(Url::to('@webroot'.$this->filePath.'/'.$model->id.'.png')); // writer defaults to PNG when none is specified
                /*---------------------ส่ง line ไปยัง Admin--------------------*/
                $modelLine = Line::findOne(['name' => 'admin']);
                if(isset($modelLine->token)){
                    $message = $model->profile->name;
                    $message .= isset($model->ven_id2) ? 'เปลี่ยนเวร' : 'ยกเวร';
                    $message .= "\n".' รายละเอียดเพิ่มเติม' ."\n".$sms_qr;
                    $res = Line::notify_message($modelLine->token,$message);  
                    $res['status'] == 200 ? Yii::$app->session->setFlash('info', 'ส่งไลน์เรียบร้อย') :  Yii::$app->session->setFlash('info', 'ส่งไลน์ ไม่ได้') ;  
                } 

                Yii::$app->session->setFlash('success', 'บันทึกข้อมูลเรียบร้อย'); 
            } catch (\Exception $e) {
                $transaction->rollBack();
                throw $e;
            } 
            
            return $this->redirect(['change_index']);            
        }
        
        return $this->renderAjax('_ven_change',[
            'model' => $model,
            'ven_id2' => Ven::getVen_all(),
            'ven_id1' => Ven::getVen_all(),
        ]);
    }

    public function actionVen_admin_transfer()
    {
        $model = new VenTransfer();

        if(Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())){
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        } 
        
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $transaction = Yii::$app->db->beginTransaction();
            try {              
                $modelV1 = Ven::findOne($_POST['VenTransfer']['ven_id1']);
                $modelV1->status = 6; 
                $modelV1->save();
                
                $id = (int)time();
                $ref_vc = Yii::$app->security->generateRandomString();

                $modelV = new Ven();
                $modelV->id = $id ;
                $modelV->ven_date = $modelV1->ven_date;  
                $modelV->ven_com_id = $modelV1->ven_com_id;
                $modelV->ven_time = $modelV1->ven_time;
                $modelV->ven_month = $modelV1->ven_month;
                $modelV->user_id = $_POST['VenTransfer']['user_id2'];
                $modelV->status = 2;
                $modelV->ref1 = $modelV1->ref1;
                $modelV->ref2 = $ref_vc;
                $modelV->create_at = date("Y-m-d H:i:s");   
                $modelV->save();

                $model->id = $id;
                $model->ven_id1_old = $_POST['VenTransfer']['ven_id1'];
                $model->ven_id2_old = null;
                $model->ven_id1 = $id;
                $model->ven_id2 = null;
                $model->user_id1 = $modelV1->user_id;
                $model->user_id2 = $_POST['VenTransfer']['user_id2'];
                $model->s_po = $_POST['VenTransfer']['s_po'];
                $model->s_bb = $_POST['VenTransfer']['s_bb'];
                $model->status = 6;
                $model->ref1 = $ref_vc;    
                $model->ref2 = null;                
                $model->comment = $_POST['VenTransfer']['comment'];
                $model->create_at = date("Y-m-d H:i:s");
                $model->save();
                
                $transaction->commit();      
                
                $dir = Url::to('@webroot'.$this->filePath.'/');
                    if (!is_dir($dir)) {
                        mkdir($dir, 0777, true);
                    } 
                $sms_qr = isset($this->line_sms) ? $this->line_sms : Yii::$app->getRequest()->hostInfo ;
                $sms_qr .= '/ven.php?ref='.$model->id;
                $qrCode = (new QrCode($sms_qr))
                    ->setSize(250)
                    ->setMargin(5)
                    ->useForegroundColor(1, 1, 1);              
                $qrCode->writeFile(Url::to('@webroot'.$this->filePath.'/'.$model->id.'.png')); // writer defaults to PNG when none is specified
                /*---------------------ส่ง line ไปยัง Admin--------------------*/
                $modelLine = Line::findOne(['name' => 'admin']);
                if(isset($modelLine->token)){
                    $message = $model->profile->name;
                    $message .= $model->ven_id2 ? 'เปลี่ยนเวร' : 'ยกเวร';
                    $message .= "\n".' รายละเอียดเพิ่มเติม' ."\n".$sms_qr;
                    $res = Line::notify_message($modelLine->token,$message);  
                    $res['status'] == 200 ? Yii::$app->session->setFlash('info', 'ส่งไลน์เรียบร้อย') :  Yii::$app->session->setFlash('info', 'ส่งไลน์ ไม่ได้') ;  
                } 

                Yii::$app->session->setFlash('success', 'บันทึกข้อมูลเรียบร้อย'); 
            } catch (\Exception $e) {
                $transaction->rollBack();
                throw $e;
            } 
            
            return $this->redirect(['change_user_index']);            
        }

        // $ven_id1 = Ven::findOne($id);
        // $ven_id2 = Ven::getVenForChangeAll($id);        
        
        return $this->renderAjax('_ven_transfer',[
            'model' => $model,
            'ven_id1' => Ven::getVen_all(),
            // [$ven_id1->id => Ven::dateThai_full($ven_id1->ven_date).' '.$ven_id1->profile->name.'('.$ven_id1->id.')'],
            // 'ven_id1' => $ven_id1,
        ]);
    }

    

    /****************************************Admin*********************************************** */

    public function actionAdmin_index()
    {
        $models = Ven::find()
            // ->where(['status' => 1 ])
            // ->orWhere(['status' => 2 ])
            // ->orWhere(['status' => 3 ])
            ->orderBy([
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
                'title' => $model->profile->name.' '.VenChange::getStatusList()[$model->status],
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
        $model = new VenAdminCreate();  
        
        if(Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())){
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        } 
     
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $transaction = Yii::$app->db->beginTransaction();
            try {

                $modelVC = VenCom::findOne($_POST['VenAdminCreate']['ven_com_id']); 

                $model->id = time();
                $model->ven_date = $_POST['VenAdminCreate']['ven_date'];
                $model->ven_com_id = $modelVC->id;
                $model->user_id = $_POST['VenAdminCreate']['user_id'];               
                $model->ven_time = $modelVC->ven_time;
                $model->ven_month = $modelVC->ven_month;
                $model->file = $modelVC->file;
                $model->ref1 = Yii::$app->security->generateRandomString();
                $model->ref2 = $modelVC->ref;
                $model->status = 1;                
                $model->comment = '';
                $model->create_at = date("Y-m-d H:i:s"); 

                if($model->save()){                                       
                    Yii::$app->session->setFlash('success', 'บันทึกข้อมูลเรียบร้อย');                   
                }  

                $transaction->commit();
               
                
            } catch (\Exception $e) {
                $transaction->rollBack();
                throw $e;
            }   
            
            return $this->redirect(['admin_index']);
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

    public function actionAdmin_ven_view($id)
    {
        $model = Ven::findOne($id);        
           
        return $this->renderAjax('admin_ven_view',[
            'model' => $model,
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

    public function actionAdmin_set_active($id)
    {
        $model = Ven::findOne($id);        
        $model->status = 1 ;  
        $model->save();          
        Yii::$app->session->setFlash('success', 'ลบข้อมูลเรียบร้อย');                                            
        
        return $this->redirect(['admin_index']);
    }


    // ---------------------------------------------------------Ven Com-------------------------------------

    public function actionCom_index()
    {
        $models = VenCom::find()->orderBy([
            // 'ven_month' => SORT_DESC,
            // 'ven_time' => SORT_ASC,
            'id' => SORT_DESC,
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
                $model->ven_month = $_POST['VenCom']['ven_month'];
                $model->ven_time = $_POST['VenCom']['ven_time'];
                $model->ven_com_date = $_POST['VenCom']['ven_com_date'];
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
                $model->ven_month = $_POST['VenCom']['ven_month'];
                $model->ven_time = $_POST['VenCom']['ven_time'];
                $model->ven_com_date = $_POST['VenCom']['ven_com_date'];
                $model->status = $_POST['VenCom']['status'];
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


    public function actionCom_del($id)
    {
        $model = VenCom::findOne($id);
        $modelV = Ven::findOne(['ven_com_id' => $model->id]);
        if(empty($modelV->id)){
            $filename = $model->file;
            $dir = Url::to('@webroot'.$this->filePath);
            
            if($filename && is_file($dir.$filename)){
                unlink($dir.$filename);// ลบ รูปเดิม;                    
            }
            if($model->delete()){
                
                Yii::$app->session->setFlash('success', 'ลบข้อมูลเรียบร้อย');    
                return $this->redirect(['com_index']);                          
            }   
           
        }

    Yii::$app->session->setFlash('danger', 'ไม่สามารถลบได้');    
            
    return $this->redirect(['com_index']);
        
    }


    // /********************************------------------Change-------******************************** */

    public function actionChange_index()
    {
        $models = VenChange::find()->orderBy([
            // 'date_create'=>SORT_DESC,
            'id' => SORT_DESC,
            ])->limit(100)->all();  

        // foreach ($models as $model):
            
        // endforeach;        

        return $this->render('change_index',[
            'models' => $models,
        ]);
    }

    public function actionChange_user_index()
    {
        $models = VenChange::find()
            ->where([
                'user_id1' => Yii::$app->user->identity->id,
            ])
            ->orWhere([
                'user_id2' => Yii::$app->user->identity->id,
            ])
            ->orderBy([
            // 'date_create'=>SORT_DESC,
            'id' => SORT_DESC,
            ])->limit(50)->all();  

        // foreach ($models as $model):
            
        // endforeach;        

        return $this->render('change_user_index',[
            'models' => $models,
        ]);
    }

    

    public function actionChange_file_view($id)
    {
        $model = VenChange::findOne($id);           
        
        // $completePath = Url::to('@webroot').$this->filePath.$model->file;
        $completePath  = Url::to('@webroot'.$this->filePath.'/'.$model->file);
        if(is_file($completePath)){
            return Yii::$app->response->sendFile($completePath, $model->file, ['inline'=>true]);                        
        }else{
            Yii::$app->session->setFlash('warning', 'ไม่พบ File... '.$completePath);            
        }
        return $this->redirect(['change_index']);
    }

    public function actionPrint($id=null)
    {
        $model = VenChange::findOne($id);
        // $Pdf_print = '_pdf_A';
        $sms = '';
        $sms2 = '';

        
        // $modelV = VenChange::findOne($model->ven_id1_old); 
        if(isset($model->ven_id1_old)){
            $modelV = VenChange::find()
            ->where(['ven_id1' => $model->ven_id1_old])
            ->orWhere(['ven_id2' => $model->ven_id1_old])
            ->andWhere("id <> '$model->id'")
            ->orderBy(['id' => SORT_DESC])
            ->one(); 

            if(isset($modelV)){
                // foreach ($model_VC as $modelV):                
                    $sms .= ' ตามใบเปลี่ยนเวร';
                    // $sms .= 'เลขที่ '.$modelV->id;
                    $sms .= 'ลงวันที่ '. Ven::DateThai_full($modelV->create_at);
                    $sms .= ' ('. $modelV->id .')';
                // endforeach;
                // $Pdf_print = '_pdf_AA';
            }
        }

        if(isset($model->ven_id1_old)){
            $modelV = VenChange::find()
            ->where(['ven_id1' => $model->ven_id2_old])
            ->orWhere(['ven_id2' => $model->ven_id2_old])
            ->andWhere("id <> '$model->id'")
            ->orderBy(['id' => SORT_DESC])
            ->one();             
        
            if(isset($modelV)){
                // foreach ($model_VC as $modelV):                
                    $sms2 .= 'และใบเปลี่ยนเวร';
                    // $sms .= 'เลขที่ '.$modelV->id;
                    $sms2 .= 'ลงวันที่ ';
                    $sms2 .= Ven::DateThai_full($modelV->create_at);
                    $sms2 .= ' ('. $modelV->id .')';
                // endforeach;
            
            }
        }
        

        if(empty($sms) && empty($sms2)){
            $Pdf_print = '_pdf_A';
        }elseif(empty($sms) || empty($sms2)){
            $sms .= $sms2; 
            $Pdf_print = '_pdf_AA';
        }else{
            $Pdf_print = '_pdf_AAA';
        }
        
        
        Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;
        $pdf = new Pdf([
            'mode' => Pdf::MODE_UTF8, // leaner size using standard fonts
            'destination' => Pdf::DEST_BROWSER,
            'content' => $this->renderPartial($Pdf_print,[
                'model'=>$model,
                'sms' => $sms,
                'sms2' => $sms2,
            ]),
            
            'cssFile' => 'css/pdf.css',
            'options' => [
                // any mpdf options you wish to set
            ],
            'methods' => [
                'SetTitle' => $model->id,
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

    public function actionUp()
    {
        // $models = VenChange::find()->all();
        // foreach ($models as $model) :    
        //     if (empty($model->month)){
        //         $model->month = date("Y-m",strtotime($model->create_at));
        //         $model->save();                
        //     }
        //     echo $model->id.'->'.$model->month.'<br>';

        // endforeach;
        // return 'ok';
        $models = Ven::find()->all();
        foreach ($models as $model) :    
            if (isset($model->status) == 3){
                $model->status = 1;
                $model->save();                
            }
            echo $model->id.'->'.$model->status.'<br>';

        endforeach;
        return 'ok';
    }

}
