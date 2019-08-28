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
