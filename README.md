- ติดตั้ง xampp 
- copy โฟล์เดอร์ main ไปไว้ที่ c:\xampp\htdocs\ 
- run apache Mysql
- สร้างฐานข้อมูล MySql ชื่อ main (ตาม /basic/config/db.php)

Database
Edit the file config/db.php with real data, for example:

return [
    'class' => 'yii\db\Connection',
    'dsn' => 'mysql:host=localhost;dbname=main',
    'username' => 'root',
    'password' => '1234',
    'charset' => 'utf8',
];

- เปิด CMD 
- >cd c:\xampp\htdocs\basic
- >yii migrate 
- เปิด http://127.0.0.1/basic/web

เข้าระบบ admin/admin   demo/demo
