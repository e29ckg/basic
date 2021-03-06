<?php

namespace app\controllers;
use Yii;
use app\models\Ven;
use app\models\VenCom;
use app\models\VenUser;
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

    public function actionReport($ven_com_num)
    {
        $this->layout = 'blank';
        $modelComs = VenCom::find()->select('id')->where(['ven_com_num' => $ven_com_num])->all();        
        $com_id = [ ];
        foreach ($modelComs as $modelcom): 
            $com_id[]=$modelcom->id;
        endforeach;       

        $modelVenMonth = VenCom::find()->select('ven_month')->where(['ven_com_num' => $ven_com_num])->one();
        
        $modelHoliday = Ven::find()->select('ven_date')->where(['ven_month' => $modelVenMonth,'ven_time'=>'08:30:01'])->all();
         
        $models = Ven::find()
            ->where([
                'status' => 1,
                'ven_month' => $modelVenMonth,
                'ven_com_id' => $com_id])
            ->orderBy(['ven_date' => SORT_ASC,'ven_time' => SORT_ASC])
            ->all();

        $modelVenMonthCount = VenCom::find()->select('ven_month')->where(['ven_com_num' => $ven_com_num])->count();
        
        $modelVenMonthCount > 2 ?  $DN = 2 : $DN = 1;
        
        $models_ven_user = VenUser::find()
            ->where(['DN'  => $DN])
            ->orderBy(['order' => SORT_ASC])
            ->all(); 
        
        $model_ven_user_count = VenUser::find()->where(['DN'  => $DN])->count();
       
        $VenMonth = Ven::DateThai_month_full($modelVenMonth->ven_month).' ';  
        $VenMonth .= date('Y', strtotime($modelVenMonth->ven_month)) + 543;     
        $x = 1;
        foreach ($models_ven_user as $model_ven_user):
            $datas[$x]['name'] = $model_ven_user->getProfileName();
            $datas[$x]['price'] = $model_ven_user->price;
                for ($y = 1; $y <= 31; $y++) {
                    $dateY = date('Y-m-d', strtotime(date('Y-m-', strtotime($modelVenMonth->ven_month)) . $y));
                    $datas[$x][$y] = Ven::find()->where([
                        'status' => 1,
                        'ven_date' => $dateY,
                        'user_id' => $model_ven_user->user_id,
                        'ven_com_id' =>$com_id])->count();
                }
            $x++;
        endforeach;

        return $this->render('report',[
            'models' =>$models,
            'models_ven_user' => $models_ven_user,
            'modelHoliday'  => $modelHoliday, 
            'modelVenMonth' => $VenMonth,
            'ven_com_num' => $ven_com_num,
            'modelVenMonthCount' => $modelVenMonthCount,
            'model_ven_user_count' => $model_ven_user_count,
            'datas' => $datas,
            
        ]);
        
    }

    public function actionReport_l65($ven_com_num)
    {
        $this->layout = 'blank';
        $modelComs = VenCom::find()->select('id')->where(['ven_com_num' => $ven_com_num])->all();        
        $com_id = [ ];
        $data = [];
        foreach ($modelComs as $modelcom): 
            $com_id[]=$modelcom->id;
        endforeach;       

        $modelVenMonth = VenCom::find()->select('ven_month')->where(['ven_com_num' => $ven_com_num])->one();
                 
        $models = Ven::find()
            ->where([
                'status' => 1,
                'ven_month' => $modelVenMonth,
                'ven_com_id' => $com_id])
            ->orderBy(['ven_date' => SORT_ASC,'ven_time' => SORT_ASC])
            ->all();

        $modelVenMonthCount = VenCom::find()->select('ven_month')->where(['ven_com_num' => $ven_com_num])->count();
        
        $modelVenMonthCount > 2 ?  $DN = 2 : $DN = 1;
        
        $models_ven_user = VenUser::find()
            ->where(['DN'  => $DN])
            ->orderBy(['order' => SORT_ASC])
            ->all(); 
        
        $model_ven_user_count = VenUser::find()->where(['DN'  => $DN])->count();
       
        $VenMonth = Ven::DateThai_month_full($modelVenMonth->ven_month).' ';  
        $VenMonth .= date('Y', strtotime($modelVenMonth->ven_month)) + 543;     
       
        $data = [];
        $totals = 0;
        foreach ($models_ven_user as $model_ven_user):            
            $day = [];
            $day_na = 0;
            $day_off = 0;
            
            for ($y = 1; $y <= 31; $y++) {
                $dateY = date('Y-m-d', strtotime(date('Y-m-', strtotime($modelVenMonth->ven_month)) . $y));                
                $st = Ven::find()->where([
                        'status' => 1,
                        'ven_date' => $dateY,
                        'user_id' => $model_ven_user->user_id,
                        'ven_com_id' =>$com_id])->count();
                $h = Ven::find()->where(['ven_date' => $dateY,'ven_month' => $modelVenMonth,'ven_time'=>'08:30:01'])->count();
                $day[] = [
                    'd' => $dateY,
                    'y' => $y,
                    'st' => $st,
                    'h' => $h,
                ] ;
                if($h && $st){$day_off++;}
                if($h == 0 && $st == 1){$day_na++;}
            }
            $money = ($day_na + $day_off ) * $model_ven_user->price ;
            $totals = $totals + $money;
            $data[] =[
                'id' => $model_ven_user->order,
                'name' => $model_ven_user->getProfileName(),
                'price' => $model_ven_user->price,
                'day' =>  $day,
                'day_na' => $day_na,
                'day_off' => $day_off,
                'money' => $money                 
            ] ;
        endforeach;

        // return $this->render('report_l65',[
        //     'modelVenMonth' => $VenMonth,
        //     'ven_com_num' => $ven_com_num,
        //     'modelVenMonthCount' => $modelVenMonthCount,
        //     'data' => json_encode($data),
        //     'totals' => $totals
        // ]);
        $Pdf_print = 'report_l65';
        
        Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;
        $pdf = new Pdf([
            'mode' => Pdf::MODE_UTF8, // leaner size using standard fonts
            'format' => Pdf::FORMAT_A4,
            'orientation' => Pdf::ORIENT_LANDSCAPE,
            'destination' => Pdf::DEST_BROWSER,
            'content' => $this->renderPartial($Pdf_print,[
                'modelVenMonth' => $VenMonth,
                'ven_com_num' => $ven_com_num,
                'modelVenMonthCount' => $modelVenMonthCount,
                'data' => json_encode($data),
                'totals' => $totals
            ]),
            
            'cssFile' => 'css/pdf_l65.css',
            'options' => [
                // any mpdf options you wish to set
            ],
            'methods' => [
                'SetTitle' => 'ค่าเวรเดือน '.$VenMonth.' ('.$ven_com_num.')',
                // 'SetSubject' => 'ใบขอเปลี่ยนเวร '.$model->id,
                // 'SetHeader' => ['Krajee Privacy Policy||Generated On: ' . date("r")],
                // 'SetFooter' => ['|Page {PAGENO}|'],
                // 'SetFooter' => ['Pkkjc WebApp'],
                'SetAuthor' => 'ศาลเยาวชนและครอบครัวจังหวัดประจวบคีรีขันธ์',
                'SetCreator' => 'Pkkjc-Web',
                // 'SetKeywords' => 'Krajee, Yii2, Export, PDF, MPDF, Output, Privacy, Policy, yii2-mpdf',
            ]
        ]);
        return $pdf->render();
        
    }

    public function actionReport2($ven_com_num)
    {
        $this->layout = 'blank';             
        $modelVenMonth = VenCom::find()->select('ven_month')->where(['ven_com_num' => $ven_com_num])->one();
                
        $models_ven_user = VenUser::find()
            // ->where(['DN'  => $DN])
            ->groupBy('user_id')
            ->orderBy(['order' => SORT_ASC])
            ->all(); 

        foreach ($models_ven_user as $model_ven_user):               
            $datas[$model_ven_user->user_id]['name'] = $model_ven_user->getProfileName();            
            $datas[$model_ven_user->user_id]['d_price'] = VenUser::find()->select('price')->where(['user_id'  => $model_ven_user->user_id,'DN'=>2])->one()['price'];
            $datas[$model_ven_user->user_id]['d'] = Ven::find()->where(['status' => 1,'user_id' => $model_ven_user->user_id,'ven_month' => $modelVenMonth->ven_month,'ven_time' => ['08:30:00','08:30:01','08:30:11','08:30:22']])->count();
            $datas[$model_ven_user->user_id]['n_price'] = VenUser::find()->select('price')->where(['user_id'  => $model_ven_user->user_id,'DN'=>1])->one()['price'];
            $datas[$model_ven_user->user_id]['n'] = Ven::find()->where(['status' => 1,'user_id' => $model_ven_user->user_id,'ven_month' => $modelVenMonth->ven_month,'ven_time' => ['16:30:00','16:30:55']])->count();
        endforeach; 
        $x =1;
$totals = 0;
$total_n = 0;
$total_d = 0;
        $data = [];
        foreach ($models_ven_user as $model_ven_user):            
            $d_price = VenUser::find()->select('price')->where(['user_id'  => $model_ven_user->user_id,'DN'=>2])->one()['price'];
            $d = Ven::find()->where(['status' => 1,'user_id' => $model_ven_user->user_id,'ven_month' => $modelVenMonth->ven_month,'ven_time' => ['08:30:00','08:30:01','08:30:11','08:30:22']])->count();
            $n_price = VenUser::find()->select('price')->where(['user_id'  => $model_ven_user->user_id,'DN'=>1])->one()['price'];
            $n = Ven::find()->where(['status' => 1,'user_id' => $model_ven_user->user_id,'ven_month' => $modelVenMonth->ven_month,'ven_time' => ['16:30:00','16:30:55']])->count();
            
            $total_d = $d_price * $d;
            $total_n = $n_price * $n;
            $total = $total_d + $total_n;
            $data[] = [
                'name' => $model_ven_user->getProfileName(),
                'd_text' => 'กลางวัน '.$d_price.' X '.$d,
                'd_price' => $total_d,
                'n_text' => 'กลางคืน '.$n_price.' X '.$n,
                'n_price' => $total_n,
                'total' => $total,
            ];
        endforeach; 
        
        // return $this->render('report2',[
        //     'VenMonth' => $modelVenMonth->ven_month,
        //     'models_ven_user' => $models_ven_user,
        //     'datas' => $datas
        // ]);

        $Pdf_print = 'report2';
        
        Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;
        $pdf = new Pdf([
            'mode' => Pdf::MODE_UTF8, // leaner size using standard fonts
            'format' => Pdf::FORMAT_A4,
            'orientation' => Pdf::ORIENT_PORTRAIT,
            'destination' => Pdf::DEST_BROWSER,
            'content' => $this->renderPartial($Pdf_print,[
                'VenMonth' => $modelVenMonth->ven_month,
                'models_ven_user' => $models_ven_user,
                'datas' => $datas,
                'data' => json_encode($data),
            ]),
            
            'cssFile' => 'css/pdf_l65.css',
            'options' => [
                // any mpdf options you wish to set
            ],
            'methods' => [
                'SetTitle' => 'ค่าเวรเดือน ',
                // 'SetSubject' => 'ใบขอเปลี่ยนเวร '.$model->id,
                // 'SetHeader' => ['Krajee Privacy Policy||Generated On: ' . date("r")],
                // 'SetFooter' => ['|Page {PAGENO}|'],
                // 'SetFooter' => ['Pkkjc WebApp'],
                'SetAuthor' => 'ศาลเยาวชนและครอบครัวจังหวัดประจวบคีรีขันธ์',
                'SetCreator' => 'Pkkjc-Web',
                // 'SetKeywords' => 'Krajee, Yii2, Export, PDF, MPDF, Output, Privacy, Policy, yii2-mpdf',
            ]
        ]);
        return $pdf->render();
    }
    

    public function actionTest()
    {
       return $this->render('test',[
           'models' => $models
       ]);
    }
    
    public function actionIndex()
    {
        $models = Ven::find()->where(['status' => 1 ])
            ->orWhere(['status' => 2 ])
            // ->orWhere(['status' => 3 ])
            ->orderBy([
            // 'date_create'=>SORT_DESC,
            'ven_date' => SORT_DESC,
            ])->limit(500)->all(); 
        $i = 1 ;
        $event = [];
        foreach ($models as $model):
            
            $even = [
                'id' => $model->id,
                'title' => $model->profile->name.' '.VenChange::getStatusList()[$model->status],
                // 'title' => $model->ven_date.' '.$model->ven_time,
                'start' => $model->ven_date.' '.$model->ven_time,
                'textColor' => $model->user_id == Yii::$app->user->identity->id ? 'yellow' :'',
                // 'end' => $model->date_end.'T12:30:00',
                'backgroundColor' => $model->backgroundColor($model->ven_time,$model->status),
                // 'backgroundColor' => $backgroundColor,
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

        if(Yii::$app->request->isAjax){
            return $this->renderAjax('ven_show',[
                'model' => $model,
                'modelDs' => $modelDs,
            ]);
        }else{
            return $this->render('ven_show',[
                'model' => $model,
                'modelDs' => $modelDs,
            ]);
        }               
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
                $model->ven_id1_old = $modelV1->id;
                $model->ven_id2_old = $modelV2->id;
                $model->ven_id1 = $id;
                $model->ven_id2 = $id + 1;
                $model->user_id1 = $modelV2->user_id;
                $model->user_id2 = $modelV1->user_id;
                $model->ven_month = $modelV1->ven_month;
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
                $sms_qr = $this->line_sms;
                $sms_qr .= '/ven.php?ref='.$model->id;
                $qrCode = (new QrCode($sms_qr))
                    ->setSize(250)
                    ->setMargin(5)
                    ->useForegroundColor(1, 1, 1);              
                $qrCode->writeFile(Url::to('@webroot'.$this->filePath.'/'.$model->id.'.png')); // writer defaults to PNG when none is specified
                /*---------------------ส่ง line ไปยัง Admin--------------------*/
                                
                if($token = Line::getToken('ven')){
                    $message = 'ven'."\n";
                    $message .= $modelV2->getProfileNameCal().'<--เปลี่ยนเวร-->'.$modelV1->getProfileNameCal();                   
                    $message .= "\n".' รายละเอียดเพิ่มเติม ' ."\n".$sms_qr;
                    $res = Line::notify_message($token,$message);  
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
            'ven_id1' => [$ven_id1->id => Ven::dateThai_full($ven_id1->ven_date).' '.$ven_id1->profile->fname.$ven_id1->profile->name.' '.$ven_id1->profile->sname.'('.$ven_id1->id.')'],
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
        
        if ($model->load(Yii::$app->request->post()) ) {
            $transaction = Yii::$app->db->beginTransaction();
            try {              
                $modelV1 = Ven::findOne($id);
                                
                $id_vn = (int)time();
                $ref_vc = Yii::$app->security->generateRandomString();

                $modelV = new Ven();
                $modelV->id = $id_vn ;
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

                $model->id = $id_vn;
                $model->ven_month = $modelV1->ven_month;
                $model->ven_id1 = $id_vn;
                $model->ven_id2 =  null;
                $model->ven_id1_old = null;
                $model->ven_id2_old = $id;                
                $model->user_id1 = $modelV1->user_id;                
                $model->user_id2 = $_POST['VenTransfer']['user_id2'];
                $model->s_po = $_POST['VenTransfer']['s_po'];
                $model->s_bb = $_POST['VenTransfer']['s_bb'];
                $model->status = 6;
                $model->ref1 = $ref_vc;    
                // // $model->ref2 = null;                
                $model->comment = $_POST['VenTransfer']['comment'];
                $model->create_at = date("Y-m-d H:i:s");
                
                $modelV1->status = 6; 
                if($modelV1->save() && $model->save()){
                    Yii::$app->session->setFlash('success', 'บันทึกข้อมูลเรียบร้อย'); 
                }
                
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
                // $modelLine = Line::findOne(['name' => 'ven','status' => 1]);
                if($token = Line::getToken('ven')){
                    $message = $model->getProfileName1().'--ยกเวร-->'.$model->getProfileName2();                    
                    $message .= "\n".' รายละเอียดเพิ่มเติม' ."\n".$sms_qr;
                    $res = Line::notify_message($token,$message);  
                    $res['status'] == 200 ? Yii::$app->session->setFlash('info', 'ส่งไลน์เรียบร้อย') :  Yii::$app->session->setFlash('info', 'ส่งไลน์ ไม่ได้') ;  
                } 

                
            return $this->redirect(['change_user_index']);            
        }

        $ven_id1 = Ven::findOne($id);
        
        return $this->renderAjax('_ven_transfer',[
            'model' => $model,
            'ven_id1' => [$ven_id1->id => Ven::dateThai_full($ven_id1->ven_date).' '.$ven_id1->profile->fname.$ven_id1->profile->name.' '.$ven_id1->profile->sname.'('.$ven_id1->id.')'],
           
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

                Yii::$app->session->setFlash('success', 'บันทึกข้อมูลเรียบร้อย'); 
            } catch (\Exception $e) {
                $transaction->rollBack();
                throw $e;
            } 
                /*---------------------ส่ง line ไปยัง Admin--------------------*/
                
                if($token = Line::getToken('ven')){
                    $message = 'ven'."\n";
                    $message .= Ven::getNameById(Yii::$app->user->id).' แก้ไขเวร '.$model->id;
                    $res = Line::notify_message($token,$message);  
                    $res['status'] == 200 ? Yii::$app->session->setFlash('info', 'ส่งไลน์เรียบร้อย') :  Yii::$app->session->setFlash('info', 'ส่งไลน์ ไม่ได้') ;  
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
                        if(!($model->ven_id1 == null)){
                            $modelV1 = Ven::findOne($model->ven_id1);
                            $modelV1->file = $fileName;
                            $modelV1->status = 1;
                            $modelV1->save();
                        }

                        if(!($model->ven_id2 == null)){
                            $modelV1 = Ven::findOne($model->ven_id2);
                            $modelV1->file = $fileName;
                            $modelV1->status = 1;
                            $modelV1->save();
                        } 

                        if(!($model->ven_id1_old == null)){
                            $modelV3 = Ven::findOne($model->ven_id1_old);
                            $modelV3->status = 5;
                            $modelV3->save();
                        }
                        if(!($model->ven_id2_old == null)){
                            $modelV4 = Ven::findOne($model->ven_id2_old);
                            $modelV4->status = 5;
                            $modelV4->save();
                        }
                                                                    
                        Yii::$app->session->setFlash('success', 'บันทึกข้อมูลเรียบร้อย');
                                                  
                        $transaction->commit();
                        
                        if($token = Line::getTokenbyid($model->ven_id1)){                            
                            $message = 'ใบเปลี่ยนเวรเลขที่ '.$model->id;                    
                            $message .= "\n".' อนุมัติแล้ว';
                            $res = Line::notify_message($token,$message);  
                            $res['status'] == 200 ? Yii::$app->session->setFlash('info', 'ส่งไลน์เรียบร้อย1') :  Yii::$app->session->setFlash('info', 'ส่งไลน์ ไม่ได้') ;  
                        } 
                        if($token = Line::getTokenbyid($model->ven_id2)){                            
                            $res = Line::notify_message($token,$message);  
                            $res['status'] == 200 ? Yii::$app->session->setFlash('info', 'ส่งไลน์เรียบร้อย2') :  Yii::$app->session->setFlash('info', 'ส่งไลน์ ไม่ได้') ;  
                        } 
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
            /*---------------------ส่ง line ไปยัง Admin--------------------*/
            if($token = Line::getToken('ven')){
                $message = 'ven'."\n";
                $message .= $model->profile->name.' ลบไฟล์เวร '.$model->id;
                $res = Line::notify_message($token,$message);  
                $res['status'] == 200 ? Yii::$app->session->setFlash('info', 'ส่งไลน์เรียบร้อย') :  Yii::$app->session->setFlash('info', 'ส่งไลน์ ไม่ได้') ;  
            } 
        return $this->redirect(['change_index']);
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
             /*---------------------ส่ง line ไปยัง Admin--------------------*/
             $modelLine = Line::findOne(['name' => 'ven']);
             if($token = Line::getToken('ven')){
                 $message = Ven::getNameById(Yii::$app->user->id).' ลบใบเปลี่ยนเวร '.$model->id;
                 $res = Line::notify_message($token,$message);  
                 $res['status'] == 200 ? Yii::$app->session->setFlash('info', 'ส่งไลน์เรียบร้อย') :  Yii::$app->session->setFlash('info', 'ส่งไลน์ ไม่ได้') ;  
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
                    
                    if($token = Line::getToken('ven')){
                        $message = 'ven'."\n";
                        $message .= Ven::getNameById(Yii::$app->user->id).' ลบใบเปลี่ยนเวร '.$model->id;
                        $res = Line::notify_message($token,$message);  
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
                $model->ven_month = $modelV1->ven_month;
                $model->ven_id1_old = $_POST['VenChange']['ven_id1'];
                $model->ven_id2_old = $_POST['VenChange']['ven_id2'];
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
                // $modelLine = Line::findOne(['name' => 'ven']);
                if($token = Line::getToken('ven')){
                    $message = $model->profile->name;                    
                    $message .= isset($model->ven_id2) ? 'เปลี่ยนเวร' : 'ยกเวร';
                    $message .= "\n".' รายละเอียดเพิ่มเติม' ."\n".$sms_qr;
                    $res = Line::notify_message($token,$message);  
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
                
                $id_vn = (int)time();
                $ref_vc = Yii::$app->security->generateRandomString();

                $modelV = new Ven();
                $modelV->id = $id_vn ;
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

                $model->id = $id_vn;
                $model->ven_month = $modelV1->ven_month;
                $model->ven_id1 = $id_vn;
                $model->ven_id2 = null;
                $model->ven_id1_old = null;
                $model->ven_id2_old = $_POST['VenTransfer']['ven_id1'];                
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
                // $modelLine = Line::findOne(['name' => 'ven']);
                if($token = Line::getToken('ven')){
                    $message = $model->profile->name;
                    $message .= $model->ven_id2 ? 'เปลี่ยนเวร' : 'ยกเวร';
                    $message .= "\n".' รายละเอียดเพิ่มเติม' ."\n".$sms_qr;
                    $res = Line::notify_message($token,$message);  
                    $res['status'] == 200 ? Yii::$app->session->setFlash('info', 'ส่งไลน์เรียบร้อย') :  Yii::$app->session->setFlash('info', 'ส่งไลน์ ไม่ได้') ;  
                } 

                Yii::$app->session->setFlash('success', 'บันทึกข้อมูลเรียบร้อย'); 
            } catch (\Exception $e) {
                $transaction->rollBack();
                throw $e;
            } 
            
            return $this->redirect(['change_user_index']);            
        }

        
        return $this->renderAjax('_ven_transfer',[
            'model' => $model,
            'ven_id1' => Ven::getVen_all(),
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
            // 'id' => SORT_DESC,
            'ven_date' => SORT_DESC,
            // 'status' => SORT_ASC,
            ])
            ->limit(500)
            ->all();  
        $event = [];
        foreach ($models as $model):
            
            $even = [
                'id' => $model->id,
                'title' => $model->profile->name.' '.VenChange::getStatusList()[$model->status],
                // 'title' => $model->ven_date.' '.$model->ven_time,
                'start' => $model->ven_date.' '.$model->ven_time,
                'textColor' => $model->user_id == Yii::$app->user->identity->id ? 'yellow' :'',
                // 'end' => $model->date_end.'T12:30:00',
                'backgroundColor' => $model->backgroundColor($model->ven_time,$model->status),
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
                $model->create_at = $modelVC->ven_com_date; 

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
                // $model->create_at = date("Y-m-d H:i:s");  

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
        Yii::$app->session->setFlash('success', 'Active ข้อมูลเรียบร้อย');                                            
        
        return $this->redirect(['admin_index']);
    }

    public function actionAdmin_set_dis($id)
    {
        $model = Ven::findOne($id);        
        $model->status = 77 ;  
        $model->save();          
        Yii::$app->session->setFlash('success', '77 เรียบร้อย');                                            
        
        return $this->redirect(['admin_index']);
    }


    // ---------------------------------------------------------Ven Com-------------------------------------

    public function actionCom_index_ba()
    {
        $models = VenCom::find()->orderBy([
            // 'ven_month' => SORT_DESC,
            // 'ven_time' => SORT_ASC,
            'id' => SORT_DESC,
            ])->limit(100)->all();  
        
        return $this->render('com_index_ba',[
            'models' => $models,
        ]);
    }
    
    public function actionCom_index()
    {
        $models = VenCom::find()->orderBy(['id' => SORT_DESC])->groupBy('ven_month')->limit(100)->all();  
        $data = [];
        // $data1 = [];
        // $num = [];
        foreach ($models as $model):
                                
                $model_nums = VenCom::find()->select(['ven_com_num','ven_time','ven_month'])->where(['ven_month'=> $model->ven_month])->groupBy('ven_com_num')->orderBy(['ven_com_num' => SORT_DESC])->all();
                $num =[];
                
                foreach ($model_nums as $model_num):  

                    $moedel_com_names = VenCom::find()->where(['ven_com_num' => $model_num->ven_com_num,'ven_month'=> $model->ven_month])->orderBy(['ven_time' => SORT_ASC])->all();
                    $com_name =[];
                    foreach ($moedel_com_names as $moedel_com_name):    
                        $modelVen = Ven::findOne(['ven_com_id'=>$moedel_com_name->id]);    
                        if(isset($modelVen->id)){
                            $status_del = true;
                        }else{
                            $status_del = false;
                        }          
                        $com_name[] = [
                            'id' => $moedel_com_name->id,
                            'ven_com_num' => $moedel_com_name->ven_com_num,
                            'ven_time'=>$moedel_com_name->ven_time,
                            'ven_month'=>$moedel_com_name->ven_month,
                            'status'=>$moedel_com_name->status,
                            'status_del'=>$status_del
                        ];
                    endforeach;
                    $num[] = [
                        'ven_com_num' => $model_num->ven_com_num,
                        'com_name' => $com_name
                    ];
                endforeach;

                $data[] = [
                    'month' => $model->ven_month,
                    'num' => $num,
                ];
                // $data[] = ['com' => VenCom::find()->select(['ven_com_num'])->where(['ven_month'=> $model->ven_month])->groupBy('ven_com_num')->orderBy(['id' => SORT_DESC])->all()];
                // $data[] = ['com_name' => VenCom::find()->select(['ven_com_name'])->where(['ven_month'=> $model->ven_month])->orderBy(['id' => SORT_DESC])->all()];
                
        endforeach;

        return $this->render('com_index',[
            // 'data' => $data,
            'data' => json_encode($data),
            'num' => json_encode($num)
            // 'models' => $models,
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

    public function actionCom_set_status($id)
    {
        $model = VenCom::findOne($id);
        if($model->status == 1){
            $model->status = 0;
        }else{
            $model->status = 1;
        }        
        $model->save();

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
                'user_id1' => Yii::$app->user->id,
            ])
            ->orWhere([
                'user_id2' => Yii::$app->user->id,
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
            if(Yii::$app->request->isAjax){
                return $this->renderAjax('show',[
                        'completePath' => Url::to('@web').$this->filePath.$model->file,                    
                ]);
            }
            return Yii::$app->response->sendFile($completePath, $model->file, ['inline'=>true]);                        
        }else{
            Yii::$app->session->setFlash('warning', 'ไม่พบ File... '.$completePath);            
        }
        return $this->redirect(['change_index']);
    }

    public function actionPrint($id=null)
    {
        $model = VenChange::findOne($id);
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
                    $sms .= ' (เลขอ้างอิง '. $modelV->id .')';
                // endforeach;
                // $Pdf_print = '_pdf_AA';
            }
        }

        if(isset($model->ven_id2_old)){
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
                    $sms2 .= ' (เลขอ้างอิง '. $modelV->id .')';
                // endforeach;
            
            }
        }
       
        $Pdf_print = '_pdf_A';
        
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
                'SetTitle' => 'ใบขอเปลี่ยนเวร '.$model->id,
                'SetSubject' => 'ใบขอเปลี่ยนเวร '.$model->id,
                // 'SetHeader' => ['Krajee Privacy Policy||Generated On: ' . date("r")],
                // 'SetFooter' => ['|Page {PAGENO}|'],
                'SetFooter' => ['Pkkjc WebApp'],
                'SetAuthor' => 'ศาลเยาวชนและครอบครัวจังหวัดประจวบคีรีขันธ์',
                'SetCreator' => 'Pkkjc-Web',
                // 'SetKeywords' => 'Krajee, Yii2, Export, PDF, MPDF, Output, Privacy, Policy, yii2-mpdf',
            ]
        ]);
        return $pdf->render();
    }


    public function actionShow_ven_change($id){

        $model = VenChange::findOne($id);

        if($model){
            return $this->render('show_ven_change',[
                'model' => $model
            ]);
        }

        return 'ไม่พบข้อมูล';
    }

    
    public function actionUp()
    {
        // $models = VenChange::find()->all();
        // foreach ($models as $model) :    
        //     if (empty($model->ven_month)){
        //         $model->ven_month = date("Y-m",strtotime($model->create_at));
        //         $model->save();                
        //     }
        //     echo $model->id.'->'.$model->ven_month.'<br>';

        // endforeach;
        // // return 'ok';

        // $models = Ven::find()->all();
        // foreach ($models as $model) :    
        //     if (isset($model->status) == 3){
        //         $model->status = 1;
        //         $model->save();                
        //     }
        //     echo $model->id.'->'.$model->status.'<br>';

        // endforeach;
        return 'ok';
    }

    // ---------------------------------------------------------Ven User-------------------------------------

    public function actionUser_index()
    {
        $models_N = VenUser::find()->where(['DN' => 1])->orderBy([
            'order' => SORT_ASC,
            ])->all();
        
        $models_D = VenUser::find()->where(['DN' => 2])->orderBy([
            'order' => SORT_ASC,
            ])->all();  
        
        return $this->render('user_index',[
            'models_D' => $models_D,
            'models_N' => $models_N,
        ]);
    }

    public function actionUser_create()
    {
        $model = new VenUser();          

        if(Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())){
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        } 
     
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $model->user_id = $_POST['VenUser']['user_id'];
            $model->order = $_POST['VenUser']['order'];
            $model->DN = $_POST['VenUser']['DN'];
            $model->price = $_POST['VenUser']['price'];
                if($model->save()){    
                    Yii::$app->session->setFlash('success', 'บันทึกข้อมูลเรียบร้อย');                   
                }  
               
                return $this->redirect(['user_index']);
                      
        }
        
        if(Yii::$app->request->isAjax){
            return $this->renderAjax('_user_create',[
                'model' => $model,         
            ]);
        }
        
        return $this->render('_user_create',[
            'model' => $model,
        ]);
    }

    public function actionUser_update($id)
    {
        $model = VenUser::findOne($id);  


        if(Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())){
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        } 
     
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $model->user_id = $_POST['VenUser']['user_id'];
            $model->order = $_POST['VenUser']['order'];
            $model->DN = $_POST['VenUser']['DN'];
            $model->price = $_POST['VenUser']['price'];
                if($model->save()){                                       
                    Yii::$app->session->setFlash('success', 'บันทึกข้อมูลเรียบร้อย');                   
                }  
                
            return $this->redirect(['user_index']);                
                     
        }

        if(Yii::$app->request->isAjax){
            return $this->renderAjax('_user_create',[
                'model' => $model,         
            ]);
        }        
        return $this->render('_user_create',[
            'model' => $model,
        ]);
    }

    public function actionUser_del($id)
    {
        $model = VenUser::findOne($id);
        
           
            if($model->delete()){
                
                Yii::$app->session->setFlash('success', 'ลบข้อมูลเรียบร้อย');    
                return $this->redirect(['user_index']);                          
            }   
 
    Yii::$app->session->setFlash('danger', 'ไม่สามารถลบได้');    
            
    return $this->redirect(['user_index']);
        
    }

}
