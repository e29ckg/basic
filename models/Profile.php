<?php

namespace app\models;
use yii\helpers\Html;
use yii\helpers\Url;
use Yii;

/**
 * This is the model class for table "profile".
 *
 * @property int $id
 * @property string $user_id
 * @property string $fname
 * @property string $name
 * @property string $sname
 * @property string $photo
 * @property string $birthday
 * @property int $idc
 * @property string $dep
 * @property string $address
 * @property string $tel
 * @property int $created_at
 * @property int $updated_at
 */
class Profile extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public $nopic ='/adminlte2/dist/img/user2-160x160.jpg';
    public $upload ='/uploads/user/';
    
    public static function tableName()
    {
        return 'profile';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['fname','name','sname'], 'required'],
            // [['birthday'], 'safe'],
            // // [['created_at', 'updated_at'], 'integer'],
            // [['img', 'dep', 'address', 'phone'], 'string', 'max' => 255],
            // [['fname'], 'string', 'max' => 25],
            // [['name', 'sname'], 'string', 'max' => 50],
            // [['name','id_card'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'fname' => 'คำนำหน้าชื่อ',
            'name' => 'ชื่อ',
            'sname' => 'นามสกุล',
            'img' => 'รูปภาพ',
            'birthday' => 'วันเกิด',
            'id_card' => 'เลขบัตรประชาชน',
            'dep' => 'ตำแหน่ง',
            'address' => 'ที่อยู่',
            'phone' => 'เบอร์โทรศัพท์',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'id']);
    }

    public function getProfileActive(){
        if(isset(Yii::$app->user->identity->id)){
            $model = Profile::findOne(Yii::$app->user->identity->id);
            $dir = Url::to('@webroot/uploads/user/');
        
            if(isset($model->img) && is_file($dir.$model->img)){
                $model->img = Url::to('@web/uploads/user/').$model->img;
            }else{
                $model->img = Url::to('@web/img/nopic.png'); 
            }

            return [
                'fullname' => $model->fname.$model->name.' '.$model->sname,
                'dep' => $model->dep,
                'img'   => $model->img
            ]; 
        } 
        return [
            'fullname' => 'Guest',
            'dep' => '-',
            'img'   => Yii::getAlias('@web').'/img/nopic.png'
        ];        
    }

    public function getProfileImg($img){        
        
        $dir = Url::to('@webroot/uploads/user/');
        
        if(isset($img) && is_file($dir.$img)){
            return  Url::to('@web/uploads/user/').$img;
        }
        return Url::to('@web/img/nopic.png') ;
        // return Yii::getAlias('@web').(!empty($model->img)  ? '/uploads/user/'.$model->img : '/img/nopic.png');
    }
        
}
