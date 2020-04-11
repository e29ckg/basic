<?php

namespace app\controllers;
use Yii;
use app\models\CLetter;
use yii\web\Response;

use yii\rest\ActiveController;
use yii\web\Controller;

class ApisController extends ActiveController
{
    public $modelClass = 'app\models\CLetter';

    // public function init(){
    //     //parent::init();
    //     \Yii::$app->response->format = Response::FORMAT_JSON;
    // }

    public function actionIndex()
    {
        $model = CLetter::find()
            ->all();
        
        return $model;

    }
    public function actionCletter($id)
    {
        $model = CLetter::findOne($id);
        
        return $model;

    }

    public function actionA()
    {
        echo "sss";
        
        // return $model;

    }

}

