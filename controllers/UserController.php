<?php
namespace app\controllers;
use Yii;
use yii\web\Controller;
use app\models\User;
use app\models\RegForm;
use app\models\RegFormUpdate;
use app\models\Dep;
use app\models\Fname;
use app\models\Profile;
use app\models\Line;
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
        } elseif($id == 'act'){
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
        $mdProfile = Profile::findOne(['user_id'=>$id]);
        $modelU = User::findOne($id);
        $modelReg = new RegFormUpdate();        
        $fileName = $mdProfile->img ;
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
                    $mdProfile->img = $fileName;
                }
                // if($mdProfile->save()){
                //     Yii::$app->session->setFlash('success', 'ปรับปรุงข้อมูลเรียบร้อย');
                // };                           
            }
            $mdProfile->img = $fileName;
            $mdProfile->fname = $_POST['RegFormUpdate']['fname'];
            $mdProfile->name = $_POST['RegFormUpdate']['name'];
            $mdProfile->sname = $_POST['RegFormUpdate']['sname'];
            $mdProfile->id_card = $_POST['RegFormUpdate']['id_card'];
            $mdProfile->dep = $_POST['RegFormUpdate']['dep'];
            $mdProfile->address = $_POST['RegFormUpdate']['address'];
            $mdProfile->phone = $_POST['RegFormUpdate']['phone'];
            $mdProfile->birthday = Yii::$app->request->post('RegFormUpdate')['birthday'];
            $modelU->username = $_POST['RegFormUpdate']['username'];
            $modelU->email = $_POST['RegFormUpdate']['email'];
            if(!(Yii::$app->request->post('RegFormUpdate')['pwd1'] == 191919)){
                $modelU->password_hash = Yii::$app->security->generatePasswordHash(Yii::$app->request->post('RegFormUpdate')['pwd1']);
            }
            if($mdProfile->save()&& $modelU->save()){
                Yii::$app->session->setFlash('success', 'บันทึกข้อมูลเรียบร้อย');
            };          
            return $this->redirect(['user_index']);
        }
        $modelReg->username = $modelU->username;
        $modelReg->pwd1 = 191919;
        $modelReg->pwd2 = 191919;
        $modelReg->email = $modelU->email;
        $modelReg->fname = $mdProfile->fname;
        $modelReg->name = $mdProfile->name;
        $modelReg->sname = $mdProfile->sname;
        $modelReg->id_card = $mdProfile->id_card;
        $modelReg->dep = $mdProfile->dep;
        $modelReg->address = $mdProfile->address;
        $modelReg->phone = $mdProfile->phone;
        $modelReg->birthday  = $mdProfile->birthday ;
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
        $mdUser = User::findOne(Yii::$app->user->id);
        $mdProfile = Profile::findOne(['user_id' => Yii::$app->user->id]);   
        $modelLine = Line::findOne(['name' => $mdUser->username]);

        $client_id = '4FLzeUXbqtIa5moAG1wtel';
        $api_url = 'https://notify-bot.line.me/oauth/authorize?';
        $callback_url = 'http://localhost/basic/web/user/callback';

        $query = [
            'response_type' => 'code',
            'client_id' => $client_id,
            'redirect_uri' => $callback_url,
            'scope' => 'notify',
            'state' => $mdUser->username
        ];
        
        $result = $api_url . http_build_query($query);
        return $this->render('user_profile',[
            'mdProfile' => $mdProfile,
            'mdUser' => $mdUser,
            'model' => $modelLine,
            'result' => $result
            ]);
    }

    public function actionCallback()
    {
        if(!empty($_GET['error'])){
            Yii::$app->session->setFlash('warning', 'ไม่สามารถตั้งค่าได้'.$_GET['error']);
            return $this->redirect('profile');
            
        }
        $client_id = '4FLzeUXbqtIa5moAG1wtel';
        $client_secret = 'zJyajyRcooJePqyLBzjWGJA9Zu7rRL6qTC0h8fYn0Xp';

        $api_url_token = 'https://notify-bot.line.me/oauth/token';
        $callback_url = 'http://localhost/basic/web/user/callback';

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

            $model = new Line();
        
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
            
        
            //var_dump($json);
        } catch(Exception $e) {
            throw new Exception($e->getMessage());
            //var_dump($e);
        }
        // return $this->render('callback', [
        //     'json' => $json
        // ]);
        return $this->redirect('profile');
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
            
                //var_dump($status);
            } catch (Exception $e) {
                throw new Exception($e->getMessage);
            }
        }
        return $this->render('line_form_send', [
            'model' => $model,
            'json' => $json
        ]);
    }
      
            
    public function actionEdit_profile($id=null){        
        $mdProfile = Profile::findOne($id);
        $modelU = User::findOne($id);
        $modelReg = new RegFormUpdate();      
        $fileName = $mdProfile->img ;
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
                    $mdProfile->img = $fileName;
                }
                // if($mdProfile->save()){
                //     Yii::$app->session->setFlash('success', 'ปรับปรุงข้อมูลเรียบร้อย');
                // };                           
            }
            $mdProfile->img = $fileName;
            $mdProfile->fname = $_POST['RegFormUpdate']['fname'];
            $mdProfile->name = $_POST['RegFormUpdate']['name'];
            $mdProfile->sname = $_POST['RegFormUpdate']['sname'];
            $mdProfile->id_card = $_POST['RegFormUpdate']['id_card'];
            $mdProfile->dep = $_POST['RegFormUpdate']['dep'];
            $mdProfile->address = $_POST['RegFormUpdate']['address'];
            $mdProfile->phone = $_POST['RegFormUpdate']['phone'];
            $mdProfile->birthday = Yii::$app->request->post('RegFormUpdate')['birthday'];
            $modelU->username = $_POST['RegFormUpdate']['username'];
            $modelU->email = $_POST['RegFormUpdate']['email'];
            if(!(Yii::$app->request->post('RegFormUpdate')['pwd1'] == 191919)){
                $modelU->password_hash = Yii::$app->security->generatePasswordHash(Yii::$app->request->post('RegFormUpdate')['pwd1']);
            }
            if($mdProfile->save()&& $modelU->save()){
                Yii::$app->session->setFlash('success', 'บันทึกข้อมูลเรียบร้อย');
            };          
            return $this->redirect(['profile']);
        }
         
        $modelReg->username = $modelU->username;
        $modelReg->pwd1 = 191919;
        $modelReg->pwd2 = 191919;
        $modelReg->email = $modelU->email;
        $modelReg->fname = $mdProfile->fname;
        $modelReg->name = $mdProfile->name;
        $modelReg->sname = $mdProfile->sname;
        $modelReg->id_card = $mdProfile->id_card;
        $modelReg->dep = $mdProfile->dep;
        $modelReg->address = $mdProfile->address;
        $modelReg->phone = $mdProfile->phone;
        $modelReg->birthday  = $mdProfile->birthday ;
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
        $modelU = User::findOne($id);
        $modelP = Profile::findOne($id);
        $filename = $modelP->img;
        $dir = Url::to('@webroot/uploads/user/');        
        if($filename && is_file($dir.$filename)){
            unlink($dir.$filename);// ลบ รูปเดิม;                    
        }
        if($modelU->delete() && $modelP->delete()){
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

    public function actionU()
    {
        $models = Profile::find()->all();        

        
        foreach ($models as $model):
           
                $model->id = $model->user_id ;
                
               $model->save() ;
        
         
        endforeach;
                

        return $this->redirect(['index_admin']);
    }
    
}