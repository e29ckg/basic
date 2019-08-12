<?php
namespace app\models;
use yii\base\Model;

class LineFormSend extends Model {
    public $name;
    public $token;
    public function rules() {
        return [
            [['name'], 'required']
        ];
    }

    public function attributeLabels()
    {
        return [
            'name' => 'ข้อความ'
        ];
    }
}