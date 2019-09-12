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


}
