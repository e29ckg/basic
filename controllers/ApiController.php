<?php

namespace app\controllers;

use yii\rest\ActiveController;
use yii\web\Response;
use app\models\User;

class ApiController extends ActiveController
{
    public $modelClass = 'app\models\CLetter';
    public function actionTest(){
        \Yii::$app->response->format = Response::FORMAT_JSON;

        return [
            'success'=>true,
            'data'=>[1,2,3],
            'msg'=>'....'
            ];
    }

    public function actionUser(){
        \Yii::$app->response->format = Response::FORMAT_JSON;
        $model = User::find()->all();
        return $model;
    }   
    
}