<?php
use yii\helpers\Html;
// use app\models\User;
// use app\models\Bila;
use app\models\SignBossName;
use yii\helpers\Url;
?>
<link rel="stylesheet" href="<?=Url::to(['/fonts/thsarabunnew.css'])?>" />
<div style="A_CSS_ATTRIBUTE:all;position: absolute;bottom: 20px; right: 45px;left: 45px; top: 80px;  ">
<!-- <div class="text-center"><H3> </H3></div> -->

<table class=" thsarabunnew" width="100%" border="0" cellpadding="1" cellspacing="0">
    <thead>
		<tr>
            <th  width="10%">                  
                <!-- <div style="font-size: 9px; "></div> -->
            </th>
            <th ><H2>ใบขอเปลี่ยนเวร</H2> </th>	
            <th  width="10%">    
                <img src="<?=$model->id?>" height="60" width="60" >
                <div style="font-size: 9px; "><?=$model->id?></div>
            </th>		
        </tr>
        
	</thead>    
</table>
<br>
<table class="bl_detail thsarabunnew" width="100%" border="0" cellpadding="1" cellspacing="0">
    <tbody>
        <tr>
            <td width="10%"></td>
            <td width="10%"></td>
            <td width="10%"></td>
            <td width="10%"></td>
            <td width="10%"></td>
            <td width="10%"></td>
            <td width="10%"></td>
            <td width="10%"></td>
            <td width="10%"></td>
            <td width="10%"></td>
        </tr>
        <tr>
            <td colspan="4"></td>	
            <td colspan="6" style="text-align:right">(เขียนที่)สำนักงานประจำศาลเยาวชนและครอบครัวจังหวัดประจวบคีรีขันธ์</td>
		</tr>
            
        <tr>
            <td colspan="5"></td>	
            <td colspan="5">วันที่ <?=$model->DateThai_full($model->create_at)?></td>
		</tr>
  
        <tr>
            <td colspan="1">เรื่อง</td>	
            <td colspan="9">ขอเปลี่ยนเวร</td>
		</tr>
        <tr>
            <td >เรียน</td>	
            <td colspan="9">ผู้อำนวยการสำนักงานประจำศาลเยาวชนและครอบครัวจังหวัดประจวบคีรีขันธ์</td>
		</tr>
  
        <tr>
            <td></td>	
            <td></td>
            <td colspan="5">ตามคำสั่งศาลเยาวชนและครอบครัวจังหวัดประจวบคีรีขันธ์ ที่ </td>	
            <td colspan="3" class="TableLine" style="text-align:center"><?=$model->ven1_old->getVenComNum()?></td>
		</tr>
        <tr>
            <td>ลงวันที่</td>	
            <td colspan="3" class="TableLine" style="text-align:center"><?=$model->DateThai_full($model->ven1_old->getVenComDate());?></td>
            <td style="text-align:center">ให้</td>	
            <td colspan="5" class="TableLine" style="text-align:center"><?=$model->getProfileName()?></td>
		</tr>
        <tr>
            <td>อยู่เวร</td>	
            <td colspan="3" class="TableLine" style="text-align:center"><?=$model->ven1_old->getVenComName()?></td>
            <td style="text-align:center">วันที่</td>
            <td colspan="2" class="TableLine" style="text-align:center"><?=$model->DateThai_full($model->ven1_old->ven_date)?></td>	
            <td style="text-align:center">เวลา</td>
            <td colspan="2" class="TableLine" style="text-align:center"><?=$model->getVen_time()[$model->ven1_old->ven_time]?></td>	
		</tr>
        <tr>
            <td>เนื่องจาก</td>	
            <td colspan="9" class="TableLine"><?=$model->comment?></td>
		</tr>
        <tr>
            <td colspan="2">จึงขอเปลี่ยนเวรกับ </td>	
            <td colspan="8" class="TableLine" style="text-align:center"><?=$model->getProfileName2()?></td>
		</tr>
        <tr>
            <td colspan="6" >เป็นผู้ปฏิบัติหน้าที่แทน และข้าพเจ้าจะมาปฏิบัติหน้าที่แทนในวันที่ </td>	
            <td colspan="4" class="TableLine" style="text-align:center">
                <?=$model->ven_id2_old ? $model->DateThai_full($model->ven2_old->getVenComDate()) :'';?></td>            
		</tr>
        <tr>
            <td colspan="6" ></td>	
            <td colspan="4" style="text-align:center"></td>            
		</tr>
        <tr>
            <td colspan="2" ></td>	
            <td colspan="8" >จึงเรียนมาเพื่อโปรดพิจารณา</td>
		</tr>
        <tr>
            <td colspan="5" style="text-align:center"></td>
            <td colspan="5" style="text-align:center"></td>
        </tr>
      
        <tr>
            <td colspan="5" style="text-align:right">(ลงชื่อ)</td>
            <td colspan="3" class="TableLine" style="text-align:center"></td>	
            <td colspan="2" >ผู้ขอเปลี่ยนเวร</td>	
		</tr>
        <tr>
            <td colspan="5" style="text-align:center"></td>
            <td colspan="3" style="text-align:center">( <?=$model->getProfileName()?> )</td>
            <td colspan="2" style="text-align:center"></td>
        </tr>
        <tr>
            <td colspan="5" style="text-align:center"></td>
            <td colspan="5" style="text-align:center"></td>
        </tr>
        <tr>
            <td colspan="5" style="text-align:right">(ลงชื่อ)</td>
            <td colspan="3" class="TableLine" style="text-align:center"></td>	
            <td colspan="2"  >ผู้รับเปลี่ยนเวร</td>	
		</tr>
        <tr>
            <td colspan="5" style="text-align:center"></td>
            <td colspan="3" style="text-align:center">( <?=$model->user_id2 ? $model->getProfileName2() : '';?> )</td>
            <td colspan="2" style="text-align:center"></td>
		</tr>
        <tr>
            <td colspan="5" style="text-align:center"></td>
            <td colspan="5" style="text-align:center"></td>
        </tr>
        <tr>
            <td colspan="5" style="text-align:center"></td>
            <td colspan="5" style="text-align:center"></td>
		</tr>
    
        <tr>
            <td colspan="5" style="text-align:center">ทราบ</td>
            <td colspan="5" style="text-align:center">[ ] เห็นควรอนุญาต </td>
		</tr>
        <tr>
            <td colspan="5" class="TableLine" style="text-align:center"></td>	
            <td colspan="5" class="TableLine" style="text-align:center"></td>
		</tr>
        <tr>
            <td colspan="5" style="text-align:center">(ชื่อหัวหน้า)</td>	
            <td colspan="5" style="text-align:center">ชื่อ ผอ.</td>
		</tr>
        <tr>
            <td colspan="5" style="text-align:center">ตำแหน่ง</td>	
            <td colspan="5" style="text-align:center">ตำแหน่ง</td>	
		</tr>
        <tr>
            <td colspan="5" style="text-align:center">ตำแหน่ง1</td>	
            <td colspan="5" style="text-align:center">ตำแหน่ง1</td>	
		</tr><tr>
            <td colspan="5" style="text-align:center">ตำแหน่ง2</td>	
            <td colspan="5" style="text-align:center">ตำแหน่ง2</td>	
		</tr>
    </tbody>
	  
</table>
</div>
<div>

</div>