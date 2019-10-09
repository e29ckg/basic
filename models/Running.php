<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

class Running extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'running';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name','y','r'], 'required'],
            [['y','r'], 'integer'],
        ];
    }

    public function getRunNumber($name)
    {
        $year = date('Y') + 543;
        $model_run = Running::findOne(['name' => 'bila','y' => $year]);        
        if(empty($model_run)){
            $model_run = new Running();
            $model_run->name = 'bila';
            $model_run->y = $year;
            $model_run->r = date('y') + 43 . '000';
            $model_run->save();
        } 
        $model_run->r = $model_run->r + 1;
        $model_run->save();
        return $model_run->r;
    }

    public function getRunNumberOrBB()
    {
        $year = date('Y') + 543;
        $model_run = Running::findOne(['name' => 'คำสั่งศาล','y' => $year]);        
        if(empty($model_run)){
            $model_run = new Running();
            $model_run->name = 'คำสั่งศาล';
            $model_run->y = $year;
            $model_run->r = '0';
            $model_run->save();
        } 
        $model_run->r = $model_run->r + 1;
        $model_run->save();
        return $model_run->r;
    }

    public function getRunNumberOrB()
    {
        $year = date('Y') + 543;
        $model_run = Running::findOne(['name' => 'คำสั่งสำนักงาน','y' => $year]);        
        if(empty($model_run)){
            $model_run = new Running();
            $model_run->name = 'คำสั่งสำนักงาน';
            $model_run->y = $year;
            $model_run->r = '0';
            $model_run->save();
        } 
        $model_run->r = $model_run->r + 1;
        $model_run->save();
        return $model_run->r;
    }
    

}
