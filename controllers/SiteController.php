<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\CLetter;
use app\models\LineHome;
use app\models\Line;
use app\models\User;
use kartik\mpdf\Pdf;


class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    public function actionTest()
    {        
       
        $pdf = new Pdf();
        $mpdf = $pdf->api; // fetches mpdf api
        $mpdf->SetHeader('Kartik Header'); // call methods or set any properties
        $mpdf->WriteHtml("222"); // call mpdf write html
        // $mpdf->writeBarcode('9-123456-7890');
        // echo $mpdf->Output('filename.pdf', 'D'); // call the mpdf api output as needed
        return $mpdf->Output();

    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        $model = CLetter::find()->orderBy([
            // 'created_at'=>SORT_DESC,
            'id' => SORT_DESC,
            ])->limit(10)->all();
        
        $googleCal = LineHome::findOne(['name_ser' => 'googleCal']); 

        return $this->render('index',[
            'models' => $model,
            'googleCal' => $googleCal ,

        ]);

    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        $this->layout = 'login';
        
        if (!Yii::$app->user->isGuest) {

            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {

            $modelUser = User::findOne(Yii::$app->user->identity->id);
            Yii::$app->user->identity->username;
            $message = $modelUser->getProfileName().' เข้าสู่ระบบ ด้วย IP '.Yii::$app->getRequest()->getUserIP();
            
            if(Line::send_sms_to($modelUser->username,$message)['status'] == 200){
                Yii::$app->session->setFlash('info', 'ส่งไลน์เรียบร้อย');
            }
            Line::send_sms_to('admin',$message);
            Yii::$app->session->setFlash('success','เข้าสู่ระบบเรียบร้อย');
            return $this->goBack();
        }

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();
        Yii::$app->session->setFlash('success',' ออกจากระบบเรียบร้อย');
        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }
}
