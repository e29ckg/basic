<?php
use yii\helpers\Url;
use app\models\VenCom;

$this->title = 'รายบุคคล ';
$data = json_decode($data);
// var_dump($data);
?> 

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
// echo count($models_ven_user);
// var_dump($models_ven_user[20]->user_id);

// echo count($datas);

?>


<?php

foreach ($data as $d):
?>
<!-- <div class="row">
<table>
    <tr>
        <th colspan="2" style="background-color: #dddddd; text-align: center; width:40px; font-size: 12px;">เดือน <?= VenCom::DateThai_full($VenMonth);?></th>
    </tr>
    <tr>        
        <th colspan="2"  style="text-align: left; width:250px; font-size: 17px;"><?=$d->name?></th>        
    </tr>
    <tr>
        <th style="width:200px; font-size: 12px;">(<?= $d->d_text;?> )</th>
        <th style="font-size: 12px;"><?=number_format($d->d_price ,0);?></th>
    </tr>  
    <tr>
        <th style="font-size: 12px;"> (<?= $d->n_text;?>)</th>
        <th style="font-size: 12px;"><?=number_format($d->n_price ,0);?></th>
    </tr>   
    <tr>
    <th colspan="2"  style="background-color: #dddddd; width:100px; font-size: 18px; text-align: right;"><?=number_format( $d->total , 0 )?></th>
    
   
      
</table>
</div>


<br> -->
<?php

endforeach; 

?>


<?php
$x =1;
$totals = 0;
$total_n = 0;
$total_d = 0;
foreach ($models_ven_user as $model_ven_user):
    $d = ($datas[$model_ven_user->user_id]['d'] * $datas[$model_ven_user->user_id]['d_price']);    
    $n = ($datas[$model_ven_user->user_id]['n'] * $datas[$model_ven_user->user_id]['n_price']);
    $total = $d + $n;
    $total_d = $total_d + $d;
    $total_n = $total_n + $n;
?>

<table>
    <tr>
        <th colspan="2" style="background-color: #dddddd; text-align: center; width:40px; font-size: 12px;">เดือน <?= VenCom::DateThai_full($VenMonth);?></th>
    </tr>
    <tr>
        <th colspan="1" rowspan="2" style="text-align: center; height : 50px; width:40px; font-size: 12px;"><?=$x?></th>
        <th colspan="1" rowspan="2" style="text-align: left; width:250px; font-size: 17px;"><?=$datas[$model_ven_user->user_id]['name']?></th>
        <th colspan="1" style="width:200px; font-size: 12px;">กลางวัน (<?= $datas[$model_ven_user->user_id]['d_price'];?> X <?=$datas[$model_ven_user->user_id]['d'] ?> )</th>
        <th colspan="1" style="font-size: 12px;"><?=number_format($d ,0);?></th>
        <th colspan="1" rowspan="2" style="background-color: #dddddd; width:100px; font-size: 18px; text-align: right;"><?=number_format( $total , 0 )?></th>
    </tr>
    <tr>
        <th style="font-size: 12px;">กลางคืน (<?= $datas[$model_ven_user->user_id]['n_price'];?> X <?=$datas[$model_ven_user->user_id]['n'] ?> )</th>
        <th style="font-size: 12px;"><?=number_format($n ,0);?></th>
    </tr>    
</table>
<br>
<?php
// echo ' '.$x.$datas[$model_ven_user->user_id]['name'];
// echo '-'.$datas[$model_ven_user->user_id]['d_price'];
// echo '-'.$datas[$model_ven_user->user_id]['d'];
// echo '-'.$datas[$model_ven_user->user_id]['n_price'];
// echo '-'.$datas[$model_ven_user->user_id]['n'];
$x++;
$totals = $totals + $total;
endforeach; 

echo '# กลางวัน '.number_format($total_d,0).' + กลางคืน '.number_format($total_n,0).' = '.number_format($totals,0);
?>

