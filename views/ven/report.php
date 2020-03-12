<?php
use yii\helpers\Url;

$Holiday = [];
for ($x = 1; $x <= 31; $x++) {
    foreach ($modelHoliday as $modelHoli):
        $dateH = date('j', strtotime($modelHoli->ven_date));
        if ($dateH == $x) {
            $C = $dateH;
        }
    endforeach;

    if (!empty($C) && $C == $x) {
        $Holiday[$x] = 'background-color: #dddddd;';
    } else {
        $Holiday[$x] = null;
    }
}

$this->title = 'คำสั่งที่ ' . $ven_com_num;
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
        <div style="font-size: 16px;text-align: center;" > ส่วนราชการ ศาลเยาวชนและครอบครัวจังหวัดประจวบคีรีขันธ์ ประจำเดือน <?=$modelVenMonth;?></div>
        <div style="font-size: 16x;text-align: center;"> คำสั่งศาลเยาวชนและครอบครัวจังหวัดประจวบคีรีขันธ์ ที่ <?=$ven_com_num?><?=$modelVenMonthCount <= 2 ?
' (เวลา 08:30 - 16:30 น., เวลา 16:30 - 08:30 น.)'
:
' (ฟื้นฟู/แขวง/ตรวจสอบการจับ เวลา 08:30 - 16:30 น.)'
?></div>
<table>

<thead>
<tr>
 <th colspan="1" rowspan="2" style="text-align: center; width:10px; font-size: 11px;"> ลำดับ</th>
 <th colspan="1" rowspan="2" style=" width:200px;">ชื่อ</th>
 <th colspan="1" rowspan="2" style="width:10px; font-size: 10px;">อัตราเงินค่าตอบแทน</th>
 <th colspan="31" style="text-align: center;">วันที่ที่ปฏิบัติงานนอกเวลาราชการ</th>
 <th colspan="3" style="width:200px; font-size: 12px;">รวมระยะเวลาปฏิบัติงาน</th>
 <th colspan="1" rowspan="2" style="width:100px; font-size: 12px;">จำนวนเงิน</th>
 <th colspan="1" rowspan="2" style="width:100px; font-size: 12px;">วัน เดือน ปี ที่รับเงิน</th>
 <th colspan="1" rowspan="2" style="width:100px; font-size: 12px;">ลายมือชื่อผู้รับเงิน</th>
 <th colspan="1" rowspan="2" style="width:100px; font-size: 12px;" >หมายเหตุ</th>
</tr>
<tr>

<?php 

for ($y = 1; $y <= 31; $y++) {
    $E = 0;
    for ($x = 1; $x <= $model_ven_user_count; $x++) {
        if ($datas[$x][$y] == 1){ $E = $E+1;}
    }
    echo '<td style="width:20px; ' . $Holiday[$y] . '">'; 
    echo $y;    
    // echo  !($E == 2 || $E == 4) ? 'X' : '';
    echo '</td>';
}?>
<th style="width:16px; font-size: 12px;">วันปกติ</th>
              <th style="width:16px; font-size: 12px;">วันหยุด</th>
              <th style="width:16px; font-size: 12px;">ชั่วโมง</th>
</tr>
</thead><tbody>
<?php
$totals = 0;
for ($x = 1; $x <= $model_ven_user_count; $x++) {
    echo '<tr>';
    echo '<td style="">' . $x . '</td>';
    echo '<td style="text-align: left">' . $datas[$x]['name'] . '</td>';
    echo '<td style="width:20px;">' . $datas[$x]['price'] . '</td>';
    $total = 0;
    $total_d = 0;
    $total_d_h = 0;
    for ($y = 1; $y <= 31; $y++) {
        if ($datas[$x][$y] == 1) {$total_d++;}
        echo '<td style="width:20px; ' . $Holiday[$y] . '">';
        if ($datas[$x][$y] == 1) {
            echo '&#8730;';
            $Holiday[$y] != null ? $total_d_h++ : '';
        }
        echo '</td>';
    }
    echo '<td class="text-center">' . ($total_d - $total_d_h) . '</td>';
    echo '<td class="text-center">' . $total_d_h . '</td>';
    echo '<td class="text-center">-</td>';
    $total = $datas[$x]['price'] * $total_d;
    echo '<td style="text-align: right;">' . number_format(($total), 2) . '</td>';
    echo '<td class="text-center"></td>
    <td class="text-center"></td>
    <td class="text-center"></td>';
    echo '</tr>';
    $totals = $totals + $total;
}
?>
</tbody>
<footer>
            <tr>
              <td colspan="37" style="text-align: right;">รวมเป็นเงินทั้งสิ้น </td>
              <td style="background-color: #dddddd;"><?=number_format($totals, 2);?></td>
              <td colspan="3" style="font-size: 12px;">(<?=Convert($totals)?>)</td>
            </tr>
            <tr>
              <!-- <td ></td>
              <td>รวมเป็นเงินทั้งสิ้น (ตัวอักษร) (<?=Convert($totals)?>)</td> -->
            </tr>
          </footer>
</table>

<br><br>
<div><table  >
<tr >
<td style="border: 1px">(ลงชื่อ)....................................................ผู้จ่ายเงิน</td>
<td style="border: 1px">(ลงชื่อ)....................................................ผู้อนุมัติ</td>
</tr>
<tr >
<td style="border: 1px">(นางสาวจุฑามาศ ขาวทอง)</td>
<td style="border: 1px">(นางสุรารักษ์ กิจโชค)</td>
</tr>
<tr >
<td style="border: 1px">นักวิชาการเงินและบัญชีปฏิบัติการ</td>
<td style="border: 1px">ผู้อำนายการสำนักงานประจำศาลเยาวชนและครอบครัวจังหวัดประจวบคีรีขันธ์</td>
</tr></table></div>





<?php
function Convert($amount_number)
{
    $amount_number = number_format($amount_number, 2, ".", "");
    $pt = strpos($amount_number, ".");
    $number = $fraction = "";
    if ($pt === false) {
        $number = $amount_number;
    } else {
        $number = substr($amount_number, 0, $pt);
        $fraction = substr($amount_number, $pt + 1);
    }

    $ret = "";
    $baht = ReadNumber($number);
    if ($baht != "") {
        $ret .= $baht . "บาท";
    }

    $satang = ReadNumber($fraction);
    if ($satang != "") {
        $ret .= $satang . "สตางค์";
    } else {
        $ret .= "ถ้วน";
    }

    return $ret;
}

function ReadNumber($number)
{
    $position_call = array("แสน", "หมื่น", "พัน", "ร้อย", "สิบ", "");
    $number_call = array("", "หนึ่ง", "สอง", "สาม", "สี่", "ห้า", "หก", "เจ็ด", "แปด", "เก้า");
    $number = $number + 0;
    $ret = "";
    if ($number == 0) {
        return $ret;
    }

    if ($number > 1000000) {
        $ret .= ReadNumber(intval($number / 1000000)) . "ล้าน";
        $number = intval(fmod($number, 1000000));
    }

    $divider = 100000;
    $pos = 0;
    while ($number > 0) {
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
// echo $model_ven_user_count;
// var_dump($datas);
?>
