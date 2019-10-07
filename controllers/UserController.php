<?php
namespace app\controllers;
use Yii;
use yii\web\Controller;
use app\models\User;
use app\models\RegForm;
use app\models\RegFormUpdate;
use app\models\Dep;
use app\models\Group;
use app\models\Fname;
use app\models\Profile;
use app\models\Line;
use app\models\LineHome;
use app\models\LineFormSend;
use yii\web\UploadedFile;
use yii\helpers\Url;
use yii\web\Response;
use yii\widgets\ActiveForm;
use yii\web\Session;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\base\Model;

class UserController extends Controller{

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['user_index','profile','dep','fname'],
                'rules' => [
                    [
                        'actions' => ['user_index','profile','dep','fname'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    // 'delete' => ['POST'],
                ],
            ],
        ];
    }
    
    public function actionUser_index($id = null){
        // $this->layout = 'main'; 
              
        if($id == 'dis'){
            $models = User::find()->where(['status' => 0])->limit(100)->all();     
        } elseif($id == 'active'){
            $models = User::find()->where(['status' => 10])->limit(100)->all(); 
        } else{
            $models = User::find()->limit(100)->all();
        }
        return $this->render('user_index',[
            'models' => $models,
            'userAll' => User::getCountAll(),
            'userDis' => User::getCountDis(),
            'userActive' => User::getCountActive(),
        ]);
    }

    public function actionUser_index2($id = null){
        // $this->layout = 'main'; 
        
        $models = User::find()->all();
        
        return $this->render('user_index2',[
            'models' => $models,
            'userAll' => User::getCountAll(),
            'userDis' => User::getCountDis(),
            'userActive' => User::getCountActive(),
        ]);
    }

    public function actionSearch($id = null) {

        if (!empty($id)) {
                $models = User::find()->where(['LIKE', 'username', $id])->all();
                // $modelPs = Profile::find()->where(['LIKE', 'name', $id])->all();
                // foreach ($modelPs as $modelP):
                //     $models = User::find()->where(['id' => $modelP->id])->all();
                // endforeach;
                // $models = User::find()->where(['status' => 0])->all();
                // $models = User::find()->all();
                
            }else{
                $models = User::find()->all();   
            }
            
            // if(empty($models)){
            //     $models = User::find()->all();
            // }
            
        
                
        if(Yii::$app->request->isAjax){
                return $this->renderAjax('user_search',[
                    'models' => $models,  
                ]);
        } 
                return $this->render('user_search',[
                    'models' => $models,  
                ]);
        
    }

    public function actionReg() {  
        $modelReg = new RegForm(); 
        $model = new User();   
        $modelP = new Profile();         
        //Add This For Ajax Email Exist Validation 
        if(Yii::$app->request->isAjax && $modelReg->load(Yii::$app->request->post()) ){
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($modelReg) ;
        } 
        if ($modelReg->load(Yii::$app->request->post()) && $modelReg->validate()){ 
            $f = UploadedFile::getInstance($modelReg, 'img');
            if(!empty($f)){                
                $dir = Url::to('@webroot/uploads/user/');
                if (!is_dir($dir)) {
                    mkdir($dir, 0777, true);
                }
                $fileName = md5($f->baseName . time()) . '.' . $f->extension;
                if($f->saveAs($dir . $fileName)){
                    $modelP->img = $fileName;
                }                            
            }
            $model->id = time();
            $model->username = Yii::$app->request->post('RegForm')['username'];
            $model->password_hash = Yii::$app->security->generatePasswordHash(Yii::$app->request->post('RegForm')['pwd1']);
            $model->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
            $model->auth_key = Yii::$app->security->generateRandomString() . '_' . time();
            $model->email = Yii::$app->request->post('RegForm')['email'];
            $model->created_at = date("Y-m-d H:i:s");
            $modelP->id = $model->id;
            $modelP->user_id = $model->id;
            $modelP->fname = Yii::$app->request->post('RegForm')['fname'];
            $modelP->name = Yii::$app->request->post('RegForm')['name'];
            $modelP->sname  = Yii::$app->request->post('RegForm')['sname'];
            $modelP->birthday = Yii::$app->request->post('RegForm')['birthday'];
            $modelP->id_card = Yii::$app->request->post('RegForm')['id_card'];
            $modelP->dep  = Yii::$app->request->post('RegForm')['dep'];
            $modelP->address = Yii::$app->request->post('RegForm')['address'];
            $modelP->phone = Yii::$app->request->post('RegForm')['phone'];
            if($model->save() && $modelP->save() ){  
                Yii::$app->session->setFlash('success', 'บันทึกข้อมูลเรียบร้อย');
                return $this->redirect(['user_index']);
            }
        }

        if(Yii::$app->request->isAjax){
            return $this->renderAjax('user_form_reg',[
                'model' => $modelReg,               
            ]);
        }
        return $this->render('user_form_reg',[
            'model' => $modelReg,                     
        ]);
    }
    
    public function actionReset_pass($id){
        $model = User::findOne($id);
        $model->password_hash = Yii::$app->security->generatePasswordHash('password');
        $model->updated_at = date("Y-m-d H:i:s");
        if($model->save()){
            Yii::$app->session->setFlash('success', 'ResetPassword เรียบร้อย');
            return $this->redirect(['user_index']);
       }else{
        Yii::$app->session->setFlash('warning', ['ไม่สามารถบันทึก']);
            return $this->redirect(['user_index']);
       } 
        
    }

    public function actionDel($id){
        $model = User::findOne($id);        
        $model->status = 0;        
        $model->updated_at = date("Y-m-d H:i:s");        
        $modelProfile = Profile::findOne($id);
        $modelProfile->status = 0;
        $modelProfile->updated_at = date("Y-m-d H:i:s");
        if($model->save() && $modelProfile->save()){
            Yii::$app->session->setFlash('success', 'ปรับปรุงข้อมูลเรียบร้อย');
        }
        return $this->redirect(['user_index']);
    }

    public function actionActive($id){        
        $model = User::findOne($id);
        $model->status = 10;
        $model->updated_at = date("Y-m-d H:i:s");
        $modelProfile = Profile::find()->where(['user_id' => $model->id])->one();
        $modelProfile->status = 10;
        $modelProfile->updated_at = date("Y-m-d H:i:s");
        if($model->save() && $modelProfile->save()){           
            Yii::$app->session->setFlash('success', 'ปรับปรุงข้อมูลเรียบร้อย');
        }
        return $this->redirect(['user_index']);
    }

    public function actionUpdate_profile($id){
        // $model->profile = Profile::findOne(['id'=>$id]);
        $model = User::findOne($id);
        $modelReg = new RegFormUpdate();        
        $fileName = $model->profile->img ;
        if(Yii::$app->request->isAjax && $modelReg->load(Yii::$app->request->post())){
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($modelReg) ;
            // return ActiveForm::validateMultiple([$model,$modelP]);
        } 
        if ($modelReg->load(Yii::$app->request->post())) {
            $f = UploadedFile::getInstance($modelReg, 'img');
            if(!empty($f)){                
                $dir = Url::to('@webroot/uploads/user/');
                if (!is_dir($dir)) {
                    mkdir($dir, 0777, true);
                }                
                if($fileName && is_file($dir.$fileName)){
                    unlink($dir.$fileName);// ลบ รูปเดิม;   
                }
                $fileName = md5($f->baseName . time()) . '.' . $f->extension;
                if($f->saveAs($dir . $fileName)){
                    $model->profile->img = $fileName;
                }
                // if($model->profile->save()){
                //     Yii::$app->session->setFlash('success', 'ปรับปรุงข้อมูลเรียบร้อย');
                // };                           
            }
            $model->profile->img = $fileName;
            $model->profile->fname = $_POST['RegFormUpdate']['fname'];
            $model->profile->name = $_POST['RegFormUpdate']['name'];
            $model->profile->sname = $_POST['RegFormUpdate']['sname'];
            $model->profile->id_card = $_POST['RegFormUpdate']['id_card'];
            $model->profile->dep = $_POST['RegFormUpdate']['dep'];
            $model->profile->address = $_POST['RegFormUpdate']['address'];
            $model->profile->phone = $_POST['RegFormUpdate']['phone'];
            $model->profile->birthday = Yii::$app->request->post('RegFormUpdate')['birthday'];
            $model->username = $_POST['RegFormUpdate']['username'];
            $model->email = $_POST['RegFormUpdate']['email'];
            if(!(Yii::$app->request->post('RegFormUpdate')['pwd1'] == 191919)){
                $model->password_hash = Yii::$app->security->generatePasswordHash(Yii::$app->request->post('RegFormUpdate')['pwd1']);
            }
            if($model->save() && $model->profile->save()){
                Yii::$app->session->setFlash('success', 'บันทึกข้อมูลเรียบร้อย');
            };          
            return $this->redirect(['user_index']);
        }
        $modelReg->username = $model->username;
        $modelReg->pwd1 = 191919;
        $modelReg->pwd2 = 191919;
        $modelReg->email = $model->email;
        $modelReg->fname = $model->profile->fname;
        $modelReg->name = $model->profile->name;
        $modelReg->sname = $model->profile->sname;
        $modelReg->id_card = $model->profile->id_card;
        $modelReg->dep = $model->profile->dep;
        $modelReg->address = $model->profile->address;
        $modelReg->phone = $model->profile->phone;
        $modelReg->birthday  = $model->profile->birthday ;
        if(Yii::$app->request->isAjax){
            return $this->renderAjax('user_form_reg',[
                    'model' => $modelReg,                    
            ]);
        }        
        return $this->render('user_form_reg',[
               'model' => $modelReg,                    
        ]);
    }

    public function actionProfile(){
        $model = Profile::findOne(Yii::$app->user->identity->id);  
        $modelLine = Line::findOne(['name' => $model->user->username]);

        $LineHome = LineHome::findOne(2);
        $client_id = $LineHome->client_id;
        $api_url = 'https://notify-bot.line.me/oauth/authorize?';
        $callback_url = $LineHome->callback_url;

        $query = [
            'response_type' => 'code',
            'client_id' => $client_id,
            'redirect_uri' => $callback_url,
            'scope' => 'notify',
            'state' => $model->user->username
        ];
                
        $result = $api_url . http_build_query($query);
        
        return $this->render('user_profile',[
            'model' => $model,
            'modelLine' => $modelLine,
            'result' => $result
        ]);
        
    }

    public function actionCallback()
    {
        if(!empty($_GET['error'])){
            Yii::$app->session->setFlash('warning', 'ไม่สามารถตั้งค่าได้'.$_GET['error']);
            return $this->redirect('profile');
            
        }

        $LineHome = LineHome::findOne(2);
        $client_id = $LineHome->client_id;
        $client_secret = $LineHome->client_secret;

        $api_url_token = 'https://notify-bot.line.me/oauth/token';
        $callback_url = $LineHome->callback_url;

        parse_str($_SERVER['QUERY_STRING'], $queries);

        //var_dump($queries);
        $fields = [
            'grant_type' => 'authorization_code',
            'code' => $queries['code'],
            'redirect_uri' => $callback_url,
            'client_id' => $client_id,
            'client_secret' => $client_secret
        ];
        
        try {
            $ch = curl_init();
        
            curl_setopt($ch, CURLOPT_URL, $api_url_token);
            curl_setopt($ch, CURLOPT_POST, count($fields));
            curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        
            $res = curl_exec($ch);
            curl_close($ch);

                    
            if ($res == false)
                throw new Exception(curl_error($ch), curl_errno($ch));
        
            $json = json_decode($res);

            if($json->status == 200){
                $model = new Line();
                !empty($_GET['state']) ? 
                    $model->name = $_GET['state']
                    : 
                    $model->name = Yii::$app->user->identity->username;
                $model->token =  $json->access_token;
                $model->status = 1;
                $model->save();
                Yii::$app->session->setFlash('success', 'บันทึกข้อมูล สำเร็จ');
            }
            
        
           echo var_dump($json);
        } catch(Exception $e) {
            throw new Exception($e->getMessage());
            //var_dump($e);
        }
        // return $this->render('callback', [
        //     'json' => $json
        // ]);
        return $this->redirect('profile');
    }

    public function actionProfile_show($id){
        $model = User::findOne($id);
        $modelLine = Line::findOne(['name' => $model->username]);

        if(Yii::$app->request->isAjax){
            return $this->renderAjax('user_profile_show',[
                'model' => $model,
                'mdLine' => $modelLine,                  
            ]);
        } 
        
        return $this->render('user_profile_show',[
            'model' => $model,
            'mdLine' => $modelLine,
        ]);
        
    }

    public function actionUpdate_role($id){
        
        $model = User::findOne($id);              
        
        if ($model->load(Yii::$app->request->post())) {
                      
            $model->role = $_POST['User']['role'];
            
            if($model->save()){
                Yii::$app->session->setFlash('success', 'บันทึกข้อมูลเรียบร้อย');
            };          
            return $this->redirect(['user_index']);
        }
        
        if(Yii::$app->request->isAjax){
            return $this->renderAjax('user_form_update_role',[
                    'model' => $model,                    
            ]);
        }        
        return $this->render('user_form_update_role',[
               'model' => $model,                    
        ]);
    }


    public function actionUser_line_send()
    {
        $modelLine = Line::findOne(['name' => Yii::$app->user->identity->username]);

        $api_url = 'https://notify-api.line.me/api/notify';
        $token = $modelLine->token;

        $model = new LineFormSend();
        $json = null;
        if($model->load(Yii::$app->request->post())){
            $headers = [
                'Authorization: Bearer ' . $token
            ];
            $fields = [
                'message' => Yii::$app->user->identity->username.' ทดสอบการส่งข้อความ :'. $model->name
            ];
            
            try {
                $ch = curl_init();
            
                curl_setopt($ch, CURLOPT_URL, $api_url);
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                curl_setopt($ch, CURLOPT_POST, count($fields));
                curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            
                $res = curl_exec($ch);
                curl_close($ch);
            
                if ($res == false)
                    throw new Exception(curl_error($ch), curl_errno($ch));
            
                $json = json_decode($res);
                //$status = $json->status;
                if(!empty($json->status) == 200){
                    Yii::$app->session->setFlash('success', 'แจ้งสำเร็จ'.$json->status);
                }
                //var_dump($status);                
                return $this->redirect(['profile']);

            } catch (Exception $e) {
                throw new Exception($e->getMessage);
            }
        }

        if(Yii::$app->request->isAjax){
            return $this->renderAjax('line_form_send',[
                'model' => $model,
                'json' => $json                   
            ]);
        } 
        return $this->render('line_form_send', [
            'model' => $model,
            'json' => $json
        ]);
    }
     
    public function actionUser_line_delete()
    {
        $model = Line::findOne(['name' => Yii::$app->user->identity->username]);
        $model->delete();
        return $this->redirect('profile');
    }
            
    public function actionEdit_profile($id){        
        // $model->profile = Profile::findOne($id);
        $model = User::findOne($id);
        $modelReg = new RegFormUpdate();      
        $fileName = $model->profile->img ;
        if(Yii::$app->request->isAjax && $modelReg->load(Yii::$app->request->post())){
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($modelReg) ;
            // return ActiveForm::validateMultiple([$model,$modelP]);
        } 
        if ($modelReg->load(Yii::$app->request->post())) {
            $f = UploadedFile::getInstance($modelReg, 'img');
            if(!empty($f)){                
                $dir = Url::to('@webroot/uploads/user/');
                if (!is_dir($dir)) {
                    mkdir($dir, 0777, true);
                }                
                if($fileName && is_file($dir.$fileName)){
                    unlink($dir.$fileName);// ลบ รูปเดิม;   
                }
                $fileName = md5($f->baseName . time()) . '.' . $f->extension;
                if($f->saveAs($dir . $fileName)){
                    $model->profile->img = $fileName;
                }
                // if($model->profile->save()){
                //     Yii::$app->session->setFlash('success', 'ปรับปรุงข้อมูลเรียบร้อย');
                // };                           
            }
            $model->profile->img = $fileName;
            $model->profile->fname = $_POST['RegFormUpdate']['fname'];
            $model->profile->name = $_POST['RegFormUpdate']['name'];
            $model->profile->sname = $_POST['RegFormUpdate']['sname'];
            $model->profile->id_card = $_POST['RegFormUpdate']['id_card'];
            $model->profile->dep = $_POST['RegFormUpdate']['dep'];
            $model->profile->address = $_POST['RegFormUpdate']['address'];
            $model->profile->phone = $_POST['RegFormUpdate']['phone'];
            $model->profile->birthday = Yii::$app->request->post('RegFormUpdate')['birthday'];
            $model->username = $_POST['RegFormUpdate']['username'];
            $model->email = $_POST['RegFormUpdate']['email'];
            if(!(Yii::$app->request->post('RegFormUpdate')['pwd1'] == 191919)){
                $model->password_hash = Yii::$app->security->generatePasswordHash(Yii::$app->request->post('RegFormUpdate')['pwd1']);
            }
            if($model->profile->save()&& $model->save()){
                Yii::$app->session->setFlash('success', 'บันทึกข้อมูลเรียบร้อย');
            };          
            return $this->redirect(['profile']);
        }
         
        $modelReg->username = $model->username;
        $modelReg->pwd1 = 191919;
        $modelReg->pwd2 = 191919;
        $modelReg->email = $model->email;
        $modelReg->fname = $model->profile->fname;
        $modelReg->name = $model->profile->name;
        $modelReg->sname = $model->profile->sname;
        $modelReg->id_card = $model->profile->id_card;
        $modelReg->dep = $model->profile->dep;
        $modelReg->address = $model->profile->address;
        $modelReg->phone = $model->profile->phone;
        $modelReg->birthday  = $model->profile->birthday ;
        if(Yii::$app->request->isAjax){
            return $this->renderAjax('user_form_reg',[
                    'model' => $modelReg,                    
            ]);
        }        
        return $this->render('user_form_reg',[
               'model' => $modelReg,                    
        ]);
    }

    public function actionDelete($id)
    {
        $model = User::findOne($id);
        $modelP = Profile::findOne($id);
        $filename = $modelP->img;
        $dir = Url::to('@webroot/uploads/user/');        
        if($filename && is_file($dir.$filename)){
            unlink($dir.$filename);// ลบ รูปเดิม;                    
        }
        if($model->delete() && $modelP->delete()){
            Yii::$app->session->setFlash('success', 'ลบข้อมูลเรียบร้อย');
        }
        return $this->redirect(['user_dis']);
    }

    public function actionDep(){        
        $models = Dep::find()->orderBy([
            'name' => SORT_ASC
            ])->all();

        if(Yii::$app->request->isAjax){
            return $this->renderAjax('dep_index',[
                    'models' => $models,                    
            ]);
        }        
        return $this->render('dep_index',[
               'models' => $models,                    
        ]);
    }

    public function actionDep_create(){        
        $model = new Dep();
        // if(Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())){
        //     Yii::$app->response->format = Response::FORMAT_JSON;
        //     return ActiveForm::validate($model);
        // }     
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if($model->save()){                
                Yii::$app->session->setFlash('success', 'บันทึกข้อมูลเรียบร้อย');
                return $this->redirect(['dep']);
            }  
        }
        if(Yii::$app->request->isAjax){
            return $this->renderAjax('dep_form',[
                    'model' => $model,                    
            ]);
        }        
        return $this->render('dep_form',[
               'model' => $model,                    
        ]);
    }

    public function actionDep_update($id){        
        $model = Dep::findOne($id);
              
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if($model->save()){                
                Yii::$app->session->setFlash('success', 'ปรับปรุงข้อมูลเรียบร้อย');
                return $this->redirect(['dep']);
            }  
        }
        if(Yii::$app->request->isAjax){
            return $this->renderAjax('dep_form',[
                    'model' => $model,                    
            ]);
        }        
        return $this->render('dep_form',[
               'model' => $model,                    
        ]);
    }

    public function actionDep_delete($id){
        $model = Dep::findOne($id);
        if($model->delete()){
            Yii::$app->session->setFlash('success', 'ลบข้อมูลเรียบร้อย');
            return $this->redirect(['dep']);
        }        
        return $this->redirect(['dep']);
    }

    public function actionFname(){        
        $models = Fname::find()->orderBy('name')->all();
        if(Yii::$app->request->isAjax){
            return $this->renderAjax('fname_index',[
                    'models' => $models,                    
            ]);
        }        
        return $this->render('fname_index',[
               'models' => $models,                    
        ]);
    }

    public function actionFname_create(){        
        $model = new Fname();
        // if(Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())){
        //     Yii::$app->response->format = Response::FORMAT_JSON;
        //     return ActiveForm::validate($model);
        //   }      
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {

            if($model->save()){                
                Yii::$app->session->setFlash('success', 'บันทึกข้อมูลเรียบร้อย');
                return $this->redirect(['fname']);
            }  
        }
        if(Yii::$app->request->isAjax){
            return $this->renderAjax('fname_form',[
                    'model' => $model,                    
            ]);
        }        
        return $this->render('fname_form',[
               'model' => $model,                    
        ]);
    }

    public function actionFname_update($id){        
        $model = Fname::findOne($id);
        if(Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())){
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
          }      
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if($model->save()){                
                Yii::$app->session->setFlash('success', 'ปรับปรุงข้อมูลเรียบร้อย');
                return $this->redirect(['fname']);
            }  
        }
        if(Yii::$app->request->isAjax){
            return $this->renderAjax('fname_form',[
                    'model' => $model,                    
            ]);
        }        
        return $this->render('fname_form',[
               'model' => $model,                    
        ]);
    }

    public function actionFname_delete($id){
        $model = Fname::findOne($id);
        if($model->delete()){
            Yii::$app->session->setFlash('success', 'ลบข้อมูลเรียบร้อย');
            return $this->redirect(['fname']);
        }        
        return $this->redirect(['fname']);
    }

    public function actionGroup(){        
        $models = Group::find()->orderBy('name')->all();
        if(Yii::$app->request->isAjax){
            return $this->renderAjax('Group_index',[
                    'models' => $models,                    
            ]);
        }        
        return $this->render('Group_index',[
               'models' => $models,                    
        ]);
    }

    public function actionGroup_create(){        
        $model = new Group();
        // if(Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())){
        //     Yii::$app->response->format = Response::FORMAT_JSON;
        //     return ActiveForm::validate($model);
        //   }      
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {

            if($model->save()){                
                Yii::$app->session->setFlash('success', 'บันทึกข้อมูลเรียบร้อย');
                return $this->redirect(['group']);
            }  
        }
        if(Yii::$app->request->isAjax){
            return $this->renderAjax('group_form',[
                    'model' => $model,                    
            ]);
        }        
        return $this->render('group_form',[
               'model' => $model,                    
        ]);
    }

    public function actionGroup_update($id){        
        $model = Group::findOne($id);
        if(Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())){
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
          }      
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if($model->save()){                
                Yii::$app->session->setFlash('success', 'ปรับปรุงข้อมูลเรียบร้อย');
                return $this->redirect(['group']);
            }  
        }
        if(Yii::$app->request->isAjax){
            return $this->renderAjax('group_form',[
                    'model' => $model,                    
            ]);
        }        
        return $this->render('group_form',[
               'model' => $model,                    
        ]);
    }

    public function actionGroup_delete($id){
        $model = Group::findOne($id);
        if($model->delete()){
            Yii::$app->session->setFlash('success', 'ลบข้อมูลเรียบร้อย');
            return $this->redirect(['group']);
        }        
        return $this->redirect(['group']);
    }

    // public function actionU()
    // {
    //     $models = User::find()->all();
        
    //     foreach ($models as $model):
    //         if($model->role == 0)

    //             $model->role = 1 ;
                
    //            $model->save() ;        
    //            Yii::$app->session->setFlash('success', 'อัพข้อมูลเรียบร้อย');
    //     endforeach;                

    //     return $this->redirect(['user_index']);
    // }
    
}