<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "legal_c_ven".
 *
 * @property int $id
 * @property string $ven_date
 * @property string $legal_c_id
 * @property string $comment
 * @property string $create_at
 */
class LegalCVen extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'legal_c_ven';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['legal_c_id'], 'required'],
            [['ven_date', 'create_at'], 'safe'],
            [['legal_c_id', 'comment'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'ven_date' => 'Ven Date',
            'legal_c_id' => 'ชื่อที่ปรึกษากฎหมาย',
            'comment' => 'Comment',
            'create_at' => 'Create At',
        ];
    }

    public function getUserList(){
        $models = LegalC::find()->where(['status' => '10'])->orderBy(['name' => SORT_ASC ])->all();
        return ArrayHelper::map($models,'id',function($model){
            return $model->fname.$model->name.' '.$model->sname;
        });        
    }

    public function getLegal()
    {
        return $this->hasOne(LegalC::className(), ['id' => 'legal_c_id']);
    }
    
    public function getName(){
        $model=$this->legal;
        // return $model ? $model->name: '-';
        return $model ? $model->fname.$model->name.' '.$model->sname : '-';
    }
}
