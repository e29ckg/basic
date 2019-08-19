<?php
 
namespace app\models;
 
use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
 
/**
 * User model
 *
 * @property integer $id
 * @property string $username
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property string $auth_key
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $password write-only password
 */
class User extends ActiveRecord implements IdentityInterface
{
    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 10;

    const ROLE_ADMIN = 9;
    const ROLE_USER = 1;
    const ROLE_MODERATOR = 5;

    public $pwd1;
    public $pwd2;
    public $fname;
    public $name;
    public $sname;
    public $birthday;
    public $id_card;
    public $dep;
    public $address;
    public $phone;
    public $img;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user}}';
    }
 
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }
 
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username'], 'required'],
            [['username'],'unique','message'=>'Username already exist. Please try another one.'],
            [['email'],'unique','message'=>'Email already exist. Please try another one.'],
            ['email', 'email'],
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_DELETED]],
        ];
    }

    public function attributeLabels()
    {
        return [
            'username' => 'Username(ใช้สำหรับเข้าสู่ระบบ)',
            'password_hash' => 'Password(กรอกรหัสผ่าน)',
            'email' => 'RePassword(กรอกรหัสผ่านอีกครั้ง)',
        ];
    }
 
    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }
 
    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }
 
    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username, 'status' => self::STATUS_ACTIVE]);
    }

    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }
        return static::findOne([
            'password_reset_token' => $token,
            'status' => self::STATUS_ACTIVE,
        ]);
    }

    public static function isPasswordResetTokenValid($token){
    if (empty($token)) {
        return false;
    }
        $timestamp = (int) substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        return $timestamp + $expire >= time();
    }
 
    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }
 
    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }
 
    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }
 
    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }
 
    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }
 
    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    
    public function generatePasswordResetToken(){
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }
    
    public function removePasswordResetToken(){
        $this->password_reset_token = null;
    }

    public static function findByEmail($email){        
        return static::findOne(['email' => $email, 'status' => self::STATUS_ACTIVE]);
    }
 
    public function getProfile()
    {
        return $this->hasOne(Profile::className(), ['id' => 'id']);
    }

    public function getProfileName(){
        $model=$this->profile;
        return $model ? $model->fname.$model->name.' '.$model->sname : '-';
    }

    public function getProfileImg(){
        $model=$this->profile;
        $dir = Url::to('@webroot/uploads/user/');
        
        if(isset($model->img) && is_file($dir.$model->img)){
            return  '/uploads/user/'.$model->img;
        }
        return Url::to('@web/img/nopic.png') ;
        // return Yii::getAlias('@web').(!empty($model->img)  ? '/uploads/user/'.$model->img : '/img/nopic.png');
    }

    public function getProfileAddress(){
        $model=$this->profile;
        return $model->address ? $model->address : '-';
    }

    public function getProfileDep(){
        $model=$this->profile;
        return !empty($model->dep) ? $model->dep : '-';
    }

    public function getProfilePhone(){
        $model=$this->profile;
        return $model ? $model->phone : '-';
    }

    public function getCountAll()
    {        
        return User::find()->count();           
    }

    public function getCountDis()
    {        
        return User::find()->where(['status' => 0])->count();           
    }

    public function getCountActive()
    {        
        return User::find()->where(['status' => 10])->count();           
    }

    public function getProfileNameById($id)
    {
        $model = Profile::findOne(['user_id' => $id]);
        return $model->name ? $model->fname.$model->name.' '.$model->sname : '' ;
    }
    public function getProfileDepById($id)
    {
        $model = Profile::find()->where(['user_id' => $id])->one();
        return $model->dep ? $model->dep : '' ;
    }

    public function getProfileAddressById($id){
        $model = Profile::find()->where(['user_id' => $id])->one();
        return $model->address ? $model->address : '' ;
    }
    
    public function getProfilePhoneById($id){
        $model = Profile::find()->where(['user_id' => $id])->one();
        return $model->phone ? 'โทร.'.$model->phone : '' ;
    }

    public function getDepList(){
        $model = Dep::find()->orderBy(['name' => SORT_ASC])->all();
        return ArrayHelper::map($model,'name','name');
    }

    public function getFnameList(){
        $model = Fname::find()->orderBy('name')->all();
        return ArrayHelper::map($model,'name','name');
        // return [
        //     'นาย' =>'นาย',
        //     'นาง' => 'นาง',
        //     'นางสาว' => 'นางสาว'
        // ];
    }
    public function getRoleList(){
        return [
            // '0' => '-',
            '1' => 'user',
            // '2' => 'Operator',
            // '3' => 'Manager',
            '9' => 'Administrator'
        ];
    }
    public function getRoleName($id){
        $role = [
            // '0' => '-',
            '1' =>'user',
            // '2' => 'Operator',
            // '3' => 'Manager',
            '9' => 'Administrator'
        ];
        return $role[$id];
    }
}