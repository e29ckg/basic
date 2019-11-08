<?php
use yii\helpers\Html;
?>


<div class=""><H4> สารบัญคดี ส่งคำพิพากษา ที่ส่งสำนักงานอธิบดีผู้พิพากษาภาค 7 ประจำเดือน <?=$Mname?></H4></div>
<!-- <div class="text-center"><H3> <?=$Mname?></H3></div> -->
<table class="table_bordered" width="100%" border="0" cellpadding="2" cellspacing="0">
    <thead>
		<tr class="cart_menu">
	    	<th class="">ลำดับ</th>
			<th >เลขดำ</th>
			<th class="">เลขแดง</th>
			<th class="">โจทย์</th>
			<th class="">จำเลย</th>
            <th class="">จำนวนหน้า</th>
		</tr>
	</thead>
    <tbody>
        <?php $i = 1;?>
        <?php foreach ($models as $model): ?>
            <tr>
                <td><?=$i?></td>
                <td><?=$model->name?></td>
                <td><?=$model->red;?></td>
                <td class="textbox"><?=$model->persecutor?></td>
                <td><?=$model->accused?></td>
                <td><?=$model->page?></td>
            </tr>
            
        <?php
            $i++ ;
            endforeach; 
        ?>    
    </tbody>
    <tbody>
        <?php for($i;$i<=10;$i++){ ?>         
            <tr>
                <td><?=$i?></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
        <?php  } ?>    
    </tbody>
</table>