<?php
use yii\helpers\Html;
use app\models\Profile;
use yii\helpers\Url;
?>

<table width="100%">
    <tr>
        <td width="100%" style="text-align: right">
            <h4>รหัสใบเบิก </h4>            
        </td>
        
    </tr>
    <tr>
    <td width="100%" style="text-align: right">
            <h5></h5>            
        </td>
        </tr>
</table>
<table width="100%">
    <tr>
        <td colspan="2" width="80%">
            <h3>ผู้เบิก : </h3>
        </td>
        <td colspan="2" width="80%">
            
        </td>
    </tr>
</table>
<br>
<table class="table_bordered" width="100%" border="0" cellpadding="2" cellspacing="0">
    <thead>
		<tr class="cart_menu">
	    	<th class="image">ลำดับ<br>no.</th>
			<th class="description">รายการ</th>
			<th class="price">ราคาต่อหน่วย</th>
			<th class="quantity">จำนวน</th>
			<th class="total">ราคารวม<br>Total</th>
		</tr>
	</thead>
    <tbody>
        <?php $i = 1;
                $total = 0;
                $totalSum = 0;
                ?>
        
            <tr>
                <td><?=$i?></td>
                <td>
                    
                </td>
                <td></td>
                <td></td>
                <td>
                
                </td>
            </tr>
            <?php $totalSum = $totalSum + $total;
            $i++;
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
            </tr>
        <?php  } ?>    
    </tbody>
</table>
<table cellspacing="0" cellpadding="2" border="0" width="100%">
    <tr>
        <td width="50%"></td>
        <td width="50%">
            <table cellspacing="0" cellpadding="0" class="table_bordered" width="100%">
                <tr>
                    <td colspan="2" width="50%">
                        <u>ราคารวม :</u><br />
                        Total
                    </td>
                    <td colspan="2" width="50%">
                        <h3><?=$totalSum?></h3>
                    </td>
                </tr>                
            </table>
        </td>        
    </tr>
</table>

<table cellspacing="0" cellpadding="2" border="0" width="100%" style= "margin-top: 50px">
    <tr>
        <td width="">ผู้เบิก...............................................................</td>
        <td width="50%">ผู้อนุมัติ...........................................................</td>        
    </tr>
</table>
    <table cellspacing="0" cellpadding="2" border="0" width="100%" style= "margin-top: 75px">
    <tr>
        <td width="50%">ผู้จ่าย............................................................</td>
        <td width="50%">ผู้รับของ..........................................................</td>        
    </tr>
</table>
