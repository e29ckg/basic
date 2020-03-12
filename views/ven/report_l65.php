<?php
use yii\helpers\Url;
$data = json_decode($data);
// echo $totals;

$this->title = 'คำสั่งที่ ' . $ven_com_num;
?>
<style>


</style>

<div style="font-size: 12px;text-align: center;">
    <?=$modelVenMonthCount <= 2 ?
        'หลักฐานการจ่ายเงินค่าตอบแทนการปฏิบัติงานนอกเวลาราชการเพื่อการออกหมายจับ'
        :
        'หลักฐานการจ่ายเงินค่าตอบแทนการปฏิบัติงานนอกเวลาราชการกรณีเปิดทำการศาลในวันหยุด'
        ?>
</div>
<div style="font-size: 12px;text-align: center;" > 
    ส่วนราชการ ศาลเยาวชนและครอบครัวจังหวัดประจวบคีรีขันธ์ ประจำเดือน <b><?=$modelVenMonth;?></b>
</div>
<div style="font-size: 12px;text-align: center;"> 
    คำสั่งศาลเยาวชนและครอบครัวจังหวัดประจวบคีรีขันธ์ ที่ 
    <?=$ven_com_num?><?=$modelVenMonthCount <= 2 ?
        ' (เวลา 08:30 - 16:30 น., เวลา 16:30 - 08:30 น.)'
        :
        ' (ฟื้นฟู/แขวง/ตรวจสอบการจับ เวลา 08:30 - 16:30 น.)'
        ?>
</div>
<table>
    <thead>
        <tr>
            <th colspan="1" rowspan="2" style="text-align: center; height : 150px; width:50px; font-size: 12px;"> ลำดับ</th>
            <th colspan="1" rowspan="2" style=" width:240px;font-size: 17px;">ชื่อ</th>
            <th colspan="1" rowspan="2" style="width:65px; font-size: 10px;">อัตราเงิน<br>ค่า<br>ตอบแทน</th>
            <th colspan="31" style="text-align: center;">วันที่ที่ปฏิบัติงานนอกเวลาราชการ</th>
            <th colspan="3" style=" font-size: 12px;">รวมระยะเวลา <br> ปฏิบัติงาน</th>
            <th colspan="1" rowspan="2" style="width:100px; font-size: 12px;">จำนวนเงิน</th>
            <th colspan="1" rowspan="2" style="width:100px; font-size: 12px;">วัน เดือน ปี ที่รับเงิน</th>
            <th colspan="1" rowspan="2" style="width:100px; font-size: 12px;">ลายมือชื่อผู้รับเงิน</th>
            <th colspan="1" rowspan="2" style="width:100px; font-size: 12px;" >หมายเหตุ</th>
        </tr>
        <tr> 
            <?php 
                foreach ($data[0]->day as $da): 
                    echo '<td style="width:32px; ';
                    echo $da->h ? 'background-color: #dddddd;' : '';
                    echo '">';
                    echo $da->y;
                    echo '</td>';        
                endforeach;
            ?>
            <th style="width:16px; font-size: 12px;">วัน <br> ปกติ</th>
            <th style="width:16px; font-size: 12px;">วัน <br> หยุด</th>
            <th style="width:16px; font-size: 12px;">ชั่วโมง</th>
        </tr>
    </thead>
    <tbody>
        <?php
            foreach ($data as $d): 
                echo '<tr>';
                echo '<td style="">' . $d->id . '</td>';
                echo '<td style="text-align: left; ">' . $d->name . '</td>';
                echo '<td style="width:20px;">' . $d->price . '</td>';
                foreach ($d->day as $day): 
                    echo '<td style="width:20px; ';
                    echo $day->h ? 'background-color: #dddddd;' : '';
                    echo '">';
                    echo $day->st ? '&#8730;' : '';
                    echo '</td>';        
                endforeach;
                    echo '<td class="text-center">' . $d->day_na . '</td>';
                    echo '<td class="text-center">' . $d->day_off . '</td>';
                    echo '<td class="text-center"> - </td>';
                    echo '<td class="text-right" style="text-align: right;">' . number_format(($d->money ), 2). '</td>';
                    echo '<td class="text-center"></td>
                        <td class="text-center"></td>
                        <td class="text-center"></td>';
                echo '</tr>';
            endforeach;
        ?>
    </tbody>
    <footer>
        <tr>
            <td colspan="37" style="text-align: right;">รวมเป็นเงินทั้งสิ้น </td>
            <td style="background-color: #dddddd;font-size: 16px;"><?=number_format($totals, 2);?></td>
            <td colspan="3" style="font-size: 16px;">(<?=Convert($totals)?>)</td>
        </tr>        
    </footer>
</table>
<br>
<div>
    <table class="bl_detail">
        <tr class="text-right" >
            <td style=" font-size: 12px;">
                (ลงชื่อ)....................................................ผู้จ่ายเงิน
                <br>(นางสาวจุฑามาศ ขาวทอง)   
                <br>นักวิชาการเงินและบัญชีปฏิบัติการ 
            </td>
            <td style=" font-size: 12px;">
                (ลงชื่อ)....................................................ผู้อนุมัติ
                <br>(นางสุรารักษ์ กิจสโชค)
                <br>ผู้อำนายการสำนักงานประจำศาลเยาวชนและครอบครัวจังหวัดประจวบคีรีขันธ์
            </td>
        </tr>
    </table>
</div>



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
// $num1 = '3500.01';
// $num2 = '120000.50';
// echo  $num1  . "&nbsp;=&nbsp;" .Convert($num1),"<br>";
// echo  $num2  . "&nbsp;=&nbsp;" .Convert($num2),"<br>";
// echo $model_ven_user_count;
// var_dump($datas);
// var_dump($data);
?>
