<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;

$this->title = 'สร้าง QrCode Tracking';
$this->params['breadcrumbs'][] = $this->title;


$db = 'D:\data\DataForCIOS\data.mdb';
$odbc = odbc_connect("Driver={Microsoft Access Driver (*.mdb)};Dbq=$db", null, null);

$name = '3';
$variable = "แผนกรับฟ้อง";
$strA = iconv("utf-8", "tis-620","หมายเลขดำที่/พศ");
// $strA = iconv("tis-620", "utf-8",'หมายเลขดำที่/พศ');
$strAA = iconv("utf-8", "tis-620",'ยชอ1/63');
$strB = iconv('utf-8', 'tis-620','ผ/ฝ');
// echo $strA;
$table =strval( $variable ) ;
// echo $name;
$sql = "Select * From $variable Where $strA = '$strAA'"; //�֧�����Ũҡ���ҧ tableName
// $strSQL = iconv("utf-8", "tis-620", $sql ); //แปลงเป็น tis-620
// $strSQL = iconv("tis-620", "utf-8", $sql ); //แปลงเป็น tis-620
// $rs = odbc_exec($odbc, $sql) ;
// $rs = odbc_exec($odbc, $strSQL) ;
date_default_timezone_set('Asia/Bangkok');
// echo iconv( 'TIS-620', 'UTF-8',$strSQL);
 //แปลงเป็นtis
// echo $strA;
// var_dump($rs);

// While(odbc_fetch_row($rs))
// {
    // echo iconv( 'TIS-620', 'UTF-8', odbc_result($rs, $strA)); 
    // echo iconv( 'TIS-620', 'UTF-8', odbc_result($rs, $strB));  
    // echo '<br>';
    // echo iconv( 'UTF-8', 'TIS-620', $BlackN=odbc_result($rs, $strA));
		// echo iconv( 'TIS-620', 'UTF-8', $khoha=odbc_result($rs, 'id'));
// echo iconv( 'TIS-620', 'UTF-8', $Amount=odbc_result($rs, 'เลขทะเบียนรับ'));
// echo iconv( 'TIS-620', 'UTF-8', $WAppoint=odbc_result($rs, 'WhyAppointment'));
// echo $DateA = date("d/m/Y",strtotime(odbc_result($rs,"DateAppointment")));
// echo $DateR = date("d/m/Y",strtotime(odbc_result($rs,"Daterubfong")));
// echo iconv( 'TIS-620', 'UTF-8', $NJudge=odbc_result($rs, 'NameJudge'));
// echo iconv( 'TIS-620', 'UTF-8', $tot=odbc_result($rs, 'tot'));
// echo $DateHC = date("d/m/Y",strtotime(odbc_result($rs,"DateHCheck")));
// echo iconv( 'TIS-620', 'UTF-8', $HCheck=odbc_result($rs, 'HCheck'));

//echo iconv( 'TIS-620', 'UTF-8', $Amounth2=odbc_result($rs, 'AMounth2'));

// if ($HCheck == 1)
// {
	
// 	// $txt = "�����Ţ��շ�� $BlackN �Ѻ��ͧ�ѹ��� $DateR ����ͧ $khoha ��ҧ�� $Amount ��͹���� ������������ҧ $WAppoint �Ѵ�ѹ��� $DateA ��Ңͧ�ӹǹ $NJudge ���Ծҡ�����˹����ŵ�Ǩ�ӹǹ���� �ѹ��� $DateHC";
	
// }
// else
// {	
// 	// $txt = "�����Ţ��շ�� $BlackN �Ѻ��ͧ�ѹ��� $DateR ����ͧ $khoha ��ҧ�� $Amount ��͹���� ������������ҧ $WAppoint �Ѵ�ѹ��� $DateA ��Ңͧ�ӹǹ $NJudge";		
	
// }
// ?>



<?php
	// }

odbc_close($odbc); //��觻Դ�����������
?>
<style>
/* @page {
  size: A4;
  margin: 0;
}
@media print {
  html, body {
    width: 210mm;
  
    height: 297mm;
  }
  
} */
</style>
<!-- <div class="col-md-12">
    <div class="box box-danger">    
        <div class="box-body"> 
        <table class="table table-bordered table-condensed">
                    <tbody>
                        <tr>
                            <th colspan="3" class="success">เลขคดี <?=$title_track?></th>
                        </tr>
                        <tr>
                            <th rowspan="6" style="text-align: center;vertical-align: middle;width:30%;">
                                <div class="avatar-container">
                                    <img src="<?=Url::to('@web/uploads/Qrgen/Qrgen1.png')?>" alt="<?=$title_track?>" title="<?=$title_track?>" width="200" height="200" class="ava3">
                                    <br><p class="lead text-center"><?=$title_track?></p>
                                </div>
                                                            </th>
                            <th style="width:10%;">เลขคดี</th>
                            <td><?=$title_track?></td>
                        </tr>
                        <tr>
                            <th>โจทก์</th>
                            <td></td>
                        </tr>
                        <tr>
                            <th>จำเลย</th>
                            <td></td>
                        </tr>
                        <tr>
                            <th>ข้อหา</th>
                            <td></td>
                        </tr>
                        <tr>
                            <th></th>
                            <td></td>
                        </tr>
                        
                    </tbody>
                </table>                
            
        </div>
        <div class ="text-center">
            <a href= "<?= Url::to($sms)?>" class="btn btn-success btn-md"> Tracking CIOS</a>
        </div>
    </div>
</div>
<div class="text-center">
    <a href="javascript:window.print();" class="btn btn-primary">พิมพ์</a>
</div> -->

<table class="table table-bordered table-condensed">
    <tbody>                        
        <tr>
            <th rowspan="6" style="text-align: center;vertical-align: middle;width:50%;">
                <div class="avatar-container">
                    <img src="<?=$Qrgen;?>" alt="<?=$title_track?>" title="<?=$title_track?>" width="200" height="200" class="ava3">
                    <br><p class="lead text-center"><?=$title_track?></p>
                </div>
            </th>
            <th style="text-align: center;width:50%;">
                <img src="<?=Url::to('@web/img/coj.png')?>" alt="<?=$title_track?>" title="<?=$title_track?>" width="100" height="100" class="ava3">
               
               <br> ศาลเยาวชนและครอบครัวจังหวัดประจวบคีรีขันธ์
               <br>กลุ่มงานช่วยพิจารณาคดี
               <br>โทร.032-600806-11 ต่อ 204(ศูนย์ให้คำปรึกษาแนะนำฯ)
            </th>
        </tr>
        <tr>
            <th style="text-align: center;width:100%;">คดีอาญา</th>            
        </tr>
        <tr>
            <th>
                     
                หมายเลขคดีดำที่......................../.......................
               <br>หมายเลขคดีดำที่...................../.....................
                <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;พนักงานอัยการคดีเยาวชนและครอบครัวฯ
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;โจทก์
               <br>ระหว่าง
                <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                ........................................................&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;จำเลย
               <br> <br> ศาลมีคำสั่งเมื่อวันที่............................................
               <br>กำหนดเงื่อนไขให้เข้ารับคำปรึกษาแนะนำ
               <br>[ &nbsp;&nbsp;].................เดือนต่อครั้ง  เป็นเวลา........เดือน........ปี
               <br>[ &nbsp;&nbsp;] ตามระยะเวลาที่ผู้ให้คำปรึกษาแนะนำเห็นสมควรเป็นเวลา
               <br>.................เดือน.............ปี
                <br>[ &nbsp;&nbsp;] อื่น ๆ ระบุ................................................
            </th>
        </tr>
                
    </tbody>
</table>  