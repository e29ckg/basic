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
        $model_run = Running::findOne(['name' => 'bila']);        
        if(empty($model_run)){
            $model_run = new Running();
            $model_run->name = 'bila';
            $model_run->y = $year;
            $model_run->r = date('y') + 43 . '001';
            $model_run->save();
            return $model_run->r ;
        } 
        if($model_run->y == $year){
            $model_run->r = $model_run->r + 1;
            $model_run->save();
            return $model_run->r;
        }
            $model_run->y = $year;
            $model_run->r =  date('y') + 43 . '001';
            $model_run->save();
        
        return $model_run->r;
    }

    public function getRunNumberOrBB()
    {
        $year = date('Y') + 543;
        $model_run = Running::findOne(['name' => 'คำสั่งศาล']);
        if (empty($model_run)) {
            $model_run = new Running();
            $model_run->name = 'คำสั่งศาล';
            $model_run->y = $year;
            $model_run->r = '0';
            $model_run->save();
            return ['r' => $model_run->r + 1, 'y' => $year];
        }

        if($model_run->y == $year ){
            return ['r' => $model_run->r + 1, 'y' => $year];
        }else{
            $model_run->y = $year;
            $model_run->r = '0';
            $model_run->save();
            return ['r' => $model_run->r + 1, 'y' => $year];
        }
        return ['r' => $model_run->r + 1, 'y' => $year];
    }

    public function addRunNumberOrBB($r, $y)
    {
        $model_run = Running::findOne(['name' => 'คำสั่งศาล']);
        $model_run->y = $y;
        $model_run->r = $r;
        $model_run->save();
        return true;
    }

    public function getRunNumberOrB()
    {
        $year = date('Y') + 543;
        $model_run = Running::findOne(['name' => 'คำสั่งสำนักงาน']);
        if (empty($model_run)) {
            $model_run = new Running();
            $model_run->name = 'คำสั่งสำนักงาน';
            $model_run->y = $year;
            $model_run->r = '0';
            $model_run->save();
            return ['r' => $model_run->r + 1, 'y' => $year];
        }

        if($model_run->y == $year ){
            return ['r' => $model_run->r + 1, 'y' => $year];
        }else{
            $model_run->y = $year;
            $model_run->r = '0';
            $model_run->save();
            return ['r' => $model_run->r + 1, 'y' => $year];
        }
        return ['r' => $model_run->r + 1, 'y' => $year];
    }

    public function addRunNumberOrB($r, $y)
    {
        $model_run = Running::findOne(['name' => 'คำสั่งสำนักงาน', 'y' => $y]);

        $model_run->r = $r;
        $model_run->save();
        return true;
    }
}
