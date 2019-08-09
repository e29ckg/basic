<?php

namespace app\models;
use yii\helpers\Url;
use yii\helpers\Html;
use Yii;

/**
 * This is the model class for table "web_link".
 *
 * @property int $id
 * @property string $name
 * @property string $link
 * @property string $img
 * @property string $create_at
 */
class WebLink extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'web_link';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name' , 'link'], 'required'],
            [['name' , 'link'],'unique','message'=>'Name already exist. Please try another one.'],            
            [['create_at'], 'safe'],
            [['link'],'url', 'defaultScheme' => 'http'],
            [['name', 'img', 'link'], 'string', 'max' => 255],
            [['img'], 'file', 'extensions' => 'png, jpg', 'maxSize'=> 1024 * 1024 * 5],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'link' => 'link',
            'img' => 'Img',
            'create_at' => 'Create At',
            'update_at' => 'Update At',
        ];
    }

    public function getCountAll()
    {        
        return  WebLink::find()->count();           
    }

    public function getWebLinkFile()
    {
        // return $this->hasOne(WebLinkFile::className(), ['web_link_id' => 'id']);
        return $this->hasMany(WebLinkFile::className(), ['web_link_id' => 'id']);
    }

    public function getImg($id)
    {    
        $model = WebLink::findOne(['id' => $id]);  
        $source = Url::to('@webroot/uploads/weblink/'.$model->id.'/'.$model->img);
        if(is_file($source)){
            $link = '/uploads/weblink/'.$model->id.'/'.$model->img;
        }else{
            $link = '/adminlte2/dist/img/default-50x50.gif';
        } 
        return $link ;        
    }

}
