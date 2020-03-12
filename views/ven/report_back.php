<?php
use yii\helpers\Url;

$Holiday=[];
for ($x = 1; $x <= 31; $x++) {
  foreach ($modelHoliday as $modelHoli):
    $dateH = date('j', strtotime($modelHoli->ven_date));
    if($dateH == $x){
      $C = $dateH ;
    }
  endforeach; 

  if(!empty($C) && $C == $x ){                                                                         
    $Holiday[$x] = 'background-color: #dddddd;';                          
  }else{
    $Holiday[$x] = null;    
  }
}

$this->title = 'คำสั่งที่ '.$ven_com_num;
?> 
<style>

table {
  /* font-family: arial, sans-serif; */
  border-collapse: collapse;
  width: 100%;
}

p {
  padding: 1px;
}

td, th {
  border: 1px solid black;
  text-align: center;
  padding: 3px;
}

</style>

<!-- <div class="row">
  <div class="col-md-12">
    <div class="box box-primary">
      <div class="box-body"> -->
        <div style="font-size: 16px;text-align: center;">
          <?=$modelVenMonthCount <= 2 ?
                'หลักฐานการจ่ายเงินค่าตอบแทนการปฏิบัติงานนอกเวลาราชการเพื่อการออกหมายจับ'
                 :
                'หลักฐานการจ่ายเงินค่าตอบแทนการปฏิบัติงานนอกเวลาราชการกรณีเปิดทำการศาลในวันหยุด'
                ?></div>
        <div style="font-size: 16px;text-align: center;" > ส่วนราชการ ศาลเยาวชนและครอบครัวจังหวัดประจวบคีรีขันธ์ ประจำเดือน <?= $modelVenMonth;?></div>
        <div style="font-size: 16x;text-align: center;"> คำสั่งศาลเยาวชนและครอบครัวจังหวัดประจวบคีรีขันธ์ ที่ <?=$ven_com_num?><?=$modelVenMonthCount <= 2 ?
                ' (เวลา 08:30 - 16:30 น., เวลา 16:30 - 08:30 น.)'
                 :
                ' (ฟื้นฟู/แขวง/ตรวจสอบการจับ เวลา 08:30 - 16:30 น.)'
                ?></div>
                
        <table id="example1" class="table table-bordered" style="font-size: 14px;">
          <thead>
            <tr>
             <th colspan="1" rowspan="2" style="text-align: center; width:10px; font-size: 11px;"> ลำดับ</th>
             <th colspan="1" rowspan="2" style=" width:150px">ชื่อ</th>
             <th colspan="1" rowspan="2" style="width:10px; font-size: 10px;">อัตราเงินค่าตอบแทน</th>
             <th colspan="31" style="text-align: center;">วันที่ที่ปฏิบัติงานนอกเวลาราชการ</th>
             <th colspan="3" style="width:200px; font-size: 12px;">รวมระยะเวลาปฏิบัติงาน</th>
             <th colspan="1" rowspan="2" style="width:50px; font-size: 12px;">จำนวนเงิน</th>
             <th colspan="1" rowspan="2" style="width:50px; font-size: 12px;">วัน เดือน ปี ที่รับเงิน</th>
             <th colspan="1" rowspan="2" style="width:50px; font-size: 12px;">ลายมือชื่อผู้รับเงิน</th>
             <th colspan="1" rowspan="2" style="width:10px; font-size: 12px;" >หมายเหตุ</th>
            </tr>
            <tr role="row">		
              <!-- <th></th>								
              <th></th>
              <th></th> -->
              <?php
                for ($x = 1; $x <= 31; $x++) {
                  foreach ($modelHoliday as $modelHoli):
                    $dateH = date('j', strtotime($modelHoli->ven_date));
                    if($dateH == $x){
                      $C = $dateH ;
                    }
                  endforeach;                                                                                           
                  echo '<td style="width:10px; '.$Holiday[$x].'">'.$x.'</td>'; 
                   
                }
                ?> 
              <!-- <th style="width:10px">รวม</th> -->
              <th style="width:16px; font-size: 12px;">วันปกติ</th>
              <th style="width:16px; font-size: 12px;">วันหยุด</th>
              <th style="width:16px; font-size: 12px;">ชั่วโมง</th>
              <!-- <th>จำนวนเงิน</th>              -->
            </tr>
            
          </thead>
          <tbody>  
          <?php 
            $i = 1;
            $totals = 0;
            foreach ($models_ven_user as $model_ven_user): ?>           
            <tr>
              <td class="text-center"><?=$model_ven_user->order?></td>
              <td style="text-align: left;"><?=$model_ven_user->getProfileName()?></td>  
              <td style="text-align: right;">
                <?=number_format( $model_ven_user->price , 0 );?>
              </td>    
                  <?php
                    $total = 0 ;                    
                    $H = 0 ;
                    $user_id = '';
                    for ($x = 1; $x <= 31; $x++) { 
                      $V = 0;
                      foreach ($models as $model): 
                        $dateX = date('Y-m-d', strtotime(date('Y-m-', strtotime($model->ven_month)).$x));
                        if($model->user_id == $model_ven_user->user_id && $dateX == $model->ven_date){
                          $V = 1; //วันตรงกับเวร
                          if($model->user_id == $model_ven_user->user_id && !empty($Holiday[$x])){
                            $H++;                              
                          }  
                        }
                      endforeach;

                      if($V == 1){  
                        $total++;                                               
                          echo '<td style="width:10px; '.$Holiday[$x].'">&#8730;</td>'; 
                      }else{
                        echo '<td style="width:10px; '.$Holiday[$x].'"></td>';                           
                      }                   
                    }
                    ?>
              <!-- <td class="text-center"><?= $total?></td> -->
              <td class="text-center"><?= $total - $H?></td>
              <td class="text-center"><?= $H?></td>
              <td class="text-center">-</td>
              <td style="text-align: right;">
                <?php 
                  $total = ($total * $model_ven_user->price);
                  echo number_format( $total , 2 );
                  $totals = $total + $totals; ?>
              </td>
              <td class="text-center"></td>
              <td class="text-center"></td>
              <td class="text-center"></td>
            </tr>                
               
            <?php  endforeach; ?>	
          </tbody>
          <footer>
            <tr>
              <td colspan="37" style="text-align: right;">รวมเป็นเงินทั้งสิ้น </td>
              <td style="background-color: #dddddd;"><?= number_format( $totals , 2 );?></td>
              <td colspan="3" style="font-size: 12px;">(<?=Convert($totals)?>)</td>
            </tr>
            <tr>
              <!-- <td ></td>
              <td>รวมเป็นเงินทั้งสิ้น (ตัวอักษร) (<?=Convert($totals)?>)</td> -->
            </tr>
          </footer>
        </table>
<br><br>
<div>
  <table>
    <tr >
      <td style="border: 1px">(ลงชื่อ)....................................................ผู้จ่ายเงิน</td>
      <td style="border: 1px">(ลงชื่อ)....................................................ผู้อนุมัติ</td>
    </tr>
    <tr >
      <!-- <td style="border: 1px">(นางสาวจุฑามาศ ขาวทอง)</td> -->
      <!-- <td style="border: 1px">(นางสุรารักษ์ กิจโชค)</td> -->
      <td style="border: 1px"></td>
      <td style="border: 1px"></td>
    </tr>
    <tr >
      <!-- <td style="border: 1px">นักวิชาการเงินและบัญชีปฏิบัติการ</td> -->
      <!-- <td style="border: 1px">ผู้อำนายการสำนักงานประจำศาลเยาวชนและครอบครัวจังหวัดประจวบคีรีขันธ์</td> -->
      <td style="border: 1px"></td>
      <td style="border: 1px"></td>
    </tr>
  </table>
</div>

        
      <!-- </div>
    </div>
  </div>
</div> -->

<?php
function Convert($amount_number)
{
    $amount_number = number_format($amount_number, 2, ".","");
    $pt = strpos($amount_number , ".");
    $number = $fraction = "";
    if ($pt === false) 
        $number = $amount_number;
    else
    {
        $number = substr($amount_number, 0, $pt);
        $fraction = substr($amount_number, $pt + 1);
    }
    
    $ret = "";
    $baht = ReadNumber ($number);
    if ($baht != "")
        $ret .= $baht . "บาท";
    
    $satang = ReadNumber($fraction);
    if ($satang != "")
        $ret .=  $satang . "สตางค์";
    else 
        $ret .= "ถ้วน";
    return $ret;
}

function ReadNumber($number)
{
    $position_call = array("แสน", "หมื่น", "พัน", "ร้อย", "สิบ", "");
    $number_call = array("", "หนึ่ง", "สอง", "สาม", "สี่", "ห้า", "หก", "เจ็ด", "แปด", "เก้า");
    $number = $number + 0;
    $ret = "";
    if ($number == 0) return $ret;
    if ($number > 1000000)
    {
        $ret .= ReadNumber(intval($number / 1000000)) . "ล้าน";
        $number = intval(fmod($number, 1000000));
    }
    
    $divider = 100000;
    $pos = 0;
    while($number > 0)
    {
        $d = intval($number / $divider);
        $ret .= (($divider == 10) && ($d == 2)) ? "ยี่" : 
            ((($divider == 10) && ($d == 1)) ? "" :
            ((($divider == 1) && ($d == 1) && ($ret != "")) ? "เอ็ด" : $number_call[$d]));
        $ret .= ($d ? $position_call[$pos] : "");
        $number = $number % $divider;
        $divider = $divider / 10;
        $pos++;
    }
    return $ret;
}
## วิธีใช้งาน
$num1 = '3500.01'; 
$num2 = '120000.50'; 
// echo  $num1  . "&nbsp;=&nbsp;" .Convert($num1),"<br>"; 
// echo  $num2  . "&nbsp;=&nbsp;" .Convert($num2),"<br>"; 
?>
