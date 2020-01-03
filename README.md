ติดตั้ง xampp //https://www.apachefriends.org/download.html

ติดตั้ง composer //https://getcomposer.org/download/

ติดตั้ง Github ด้วยก็ได้ https://desktop.github.com/
แล้วก็ Clone Project Basic ลงไป 
หรือ copy โฟล์เดอร์ main ไปไว้ที่ c:\xampp\htdocs\ 


- run apache Mysql
- สร้างฐานข้อมูล MySql ชื่อ main (ตาม /basic/config/db.php)


- เปิด CMD 

>cd c:\xampp\htdocs\basic   

>composer install

>yii migrate             

- เปิด http://127.0.0.1/basic/web

เข้าระบบ
admin/admin  
demo/demo

```
public function actionReport2($ven_com_num)
    {
    
        $this->layout = 'blank';             
        $modelVenMonth = VenCom::find()->select('ven_month')->where(['ven_com_num' => $ven_com_num])->one();                
        $models_ven_user = VenUser::find()
            // ->where(['DN'  => $DN])
            ->groupBy('user_id')
            ->orderBy(['order' => SORT_ASC])
            ->all();  
            foreach ($models_ven_user as $model_ven_user):               
            $datas[$model_ven_user->user_id]['name'] = $model_ven_user->getProfileName();            
            $datas[$model_ven_user->user_id]['d_price'] = VenUser::find()->select('price')->where(['user_id'  => $model_ven_user->user_id,'DN'=>2])->one()['price'];
            $datas[$model_ven_user->user_id]['d'] = Ven::find()->where(['status' => 1,'user_id' => $model_ven_user->user_id,'ven_month' => $modelVenMonth->ven_month,'ven_time' => ['08:30:00','08:30:01','08:30:11','08:30:22']])->count();
            $datas[$model_ven_user->user_id]['n_price'] = VenUser::find()->select('price')->where(['user_id'  => $model_ven_user->user_id,'DN'=>1])->one()['price'];
            $datas[$model_ven_user->user_id]['n'] = Ven::find()->where(['status' => 1,'user_id' => $model_ven_user->user_id,'ven_month' => $modelVenMonth->ven_month,'ven_time' => ['16:30:00','16:30:55']])->count();
        endforeach; 
        return $this->render('report2',[
            'VenMonth' => $modelVenMonth->ven_month,
            'models_ven_user' => $models_ven_user,
            'datas' => $datas
        ]);
    }
    
    ```
