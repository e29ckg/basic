<?php

namespace app\controllers;
use Yii;
use app\models\Qrgen;
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
class QrgenController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['index'],
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
        $model = new Qrgen();
        $Qrgen = null;

        if(Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())){
                Yii::$app->response->format = Response::FORMAT_JSON;
                return ActiveForm::validate($model);
            } 
            if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $dir = Url::to('@webroot/uploads/Qrgen/');
            if (!is_dir($dir)) {
                mkdir($dir, 0777, true);
            } 

            $sms_qr = $model->url;
            $qrCode = (new QrCode($sms_qr))
                ->setSize(250)
                ->setMargin(5)
                ->useForegroundColor(1, 1, 1);              
            $qrCode->writeFile($dir.'Qrgen.png'); // writer defaults to PNG when none is specified
            $Qrgen = Url::to('@web/uploads/Qrgen/Qrgen.png');
        }           
        return $this->render('index',[
            'model' => $model,
            'Qrgen' => $Qrgen,
        ]);
    }

    public function actionDownload()
    {
        $completePath = Url::to('@webroot/uploads/Qrgen/Qrgen.png');
        return Yii::$app->response->sendFile($completePath, 'Qrgen.png', ['inline'=>false]);
    }
    

    
}
