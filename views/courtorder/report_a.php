<?php
use yii\helpers\Url;
$data = json_decode($data);


$this->title = 'คำสั่งที่ ';
?>
<style>


</style>

<div style="font-size: 12px;text-align: center;">
    
</div>
<div style="font-size: 18px;text-align: center;" > 
    <b>คำสั่งศาลฯ ปี <?=$data['0']->year;?></b>
</div>
<div style="font-size: 12px;text-align: center;"> 
    
</div>
<table>
    <thead >
        <tr >
            <th  style="width:100px; text-align: center; height : 150px; font-size: 14px;"> เลขที่คำสั่ง </th>
            <th  style=" width:500px;font-size: 17px;">เรื่อง</th>
            <th  style="width:100px; font-size: 15px;">วันที่</th>
            <th  style="width:200px; text-align: center;">กลุ่มงาน/ผู้บันทึก</th>
        </tr>
        
    </thead>
    <tbody>
        <?php foreach ($data as $d): ?>
            <tr>
                <td style="text-align: right;"><?=$d->num.'/'.$d->year?></td>
                <td style="text-align: left;"><?=$d->name?></td>
                <td style="font-size: 14px;"><?=$d->date_write?></td>
                <td style="font-size: 14px;"><?=$d->owner?></td>
            </tr>  
        <?php endforeach;?>
    </tbody>
    <footer>
              
    </footer>
</table>
<br>





