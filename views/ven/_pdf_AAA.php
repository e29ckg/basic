<?php
use yii\helpers\Html;
// use app\models\User;
// use app\models\Bila;
// use app\models\SignBossName;
use yii\helpers\Url;
?>
<link rel="stylesheet" href="<?=Url::to(['/fonts/thsarabunnew.css'])?>" />
<div style="A_CSS_ATTRIBUTE:all;position: absolute;bottom: 20px; right: 45px;left: 45px; top: 50px;  ">
<!-- <div class="text-center"><H3> </H3></div> -->

<table class=" thsarabunnew" width="100%" border="0" cellpadding="1" cellspacing="0">
    <thead>
		<tr>
            <th  width="10%">                  
                <!-- <div style="font-size: 9px; "></div> -->
            </th>
            <th ><H2>ใบขอเปลี่ยนเวร</H2> </th>	
            <th  width="10%">    
                <img src="<?= $model->getQr($model->id,$model->user_id1);?>" height="60" width="60" >
                <div style="font-size: 9px; "><?=$model->id ? $model->id : $model->id?></div>
            </th>		
        </tr>
        
	</thead>    
</table>
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
            <td colspan="4"></td>	
            <td colspan="1" style="text-align:right">วันที่</td>	
            <td colspan="1" class="TableLine" style="text-align:center"><?=date("j",strtotime($model->create_at));?></td>	
            <td colspan="1" style="text-align:center">เดือน</td>
            <td colspan="1" class="TableLine" style="text-align:center"><?=$model->DateThai_month_full($model->create_at)?></td>
            <td colspan="1" style="text-align:center">พ.ศ.</td>
            <td colspan="1" class="TableLine" style="text-align:center"><?=date("Y",strtotime($model->create_at))+543;?></td>
		</tr>
        <tr>
            <td></td>	
            
        </tr>
        <tr>
            <td colspan="1">เรื่อง000</td>	
            <td colspan="9">ขอเปลี่ยนเวร</td>
		</tr>
        <tr>
            <td >เรียน</td>	
            <td colspan="9">ผู้อำนวยการสำนักงานประจำศาลเยาวชนและครอบครัวจังหวัดประจวบคีรีขันธ์</td>
		</tr>
  
        <tr>
            <td colspan="2"></td>	
            <td colspan="5">ตามคำสั่งศาลเยาวชนและครอบครัวจังหวัดประจวบคีรีขันธ์ ที่ </td>	
            <td colspan="3" class="TableLine" style="text-align:center"><?=$model->ven1_old->getVenComNum()?></td>
		</tr>
        <tr>
            <td>ลงวันที่</td>	
            <!-- <td class="TableLine"></td> -->
            <td colspan="9" class="TableLine" style="text-align:center">
                <?=$model->DateThai_full($model->ven1_old->getVenComDate());?>               
                <?= isset($model_old) ? $model_old : '' ;?>
            </td>
        </tr>
        <tr>
            <td >ให้</td>	
            <td colspan="8" class="TableLine" style="text-align:center"><?=$model->getProfileName()?></td>
            <td class="TableLine"></td>
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
            <td class="TableLine"></td>
            <td colspan="8" class="TableLine"><?=$model->comment?></td>
		</tr>
        <tr>
            <td colspan="2">จึงขอเปลี่ยนเวรกับ </td>
            <td colspan="5" class="TableLine" style="text-align:center"><?=$model->getProfileName2()?></td>
            <td colspan="5" class="TableLine"></td>
		</tr>
        <tr>
            <td colspan="6" >เป็นผู้ปฏิบัติหน้าที่แทน และข้าพเจ้าจะมาปฏิบัติหน้าที่แทนในวันที่ </td>	
            <td colspan="4" class="TableLine" style="text-align:center">
                <?=$model->ven_id2_old ? $model->DateThai_full($model->ven2_old->ven_date) :'-';?></td>            
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
            <td colspan="1"></td>
            <td colspan="3" style="text-align:center">ทราบ</td>
            <td colspan="2"></td>            
            <td colspan="3" style="text-align:center">[ / ] เห็นควรอนุญาต </td>
            <td colspan="1"></td>
		</tr>
        <tr>
            <td colspan="1"></td>
            <td colspan="3" style="text-align:center"></td>
            <td colspan="2"></td>            
            <td colspan="3" style="text-align:center"></td>
            <td colspan="1"></td>
		</tr>
        <tr>
            <td colspan="1"></td>
            <td colspan="3" class="TableLine" style="text-align:center"></td>
            <td colspan="2"></td>            
            <td colspan="3" class="TableLine" style="text-align:center"></td>
            <td colspan="1"></td>
		</tr>
        
        <tr>
            <td colspan="1"></td>
            <td colspan="3" style="text-align:center"><?= $model->s_bb ? '('.$model->getS_SS($model->s_bb)->name.')' : '';?></td>
            <td colspan="2"></td>            
            <td colspan="3" style="text-align:center"><?= $model->s_po ? '('.$model->getS_SS($model->s_po)->name.')' : '';?></td>
            <td colspan="1"></td>
		</tr>
        <tr>
            <td colspan="5" style="text-align:center"><?= $model->s_bb ? $model->getS_SS($model->s_bb)->dep1 : '';?></td>	
            <td colspan="5" style="text-align:center"><?= $model->s_po ? $model->getS_SS($model->s_po)->dep1 : '';?></td>	
		</tr>
        <tr>
            <td colspan="5" style="text-align:center"><?= $model->s_bb ? $model->getS_SS($model->s_bb)->dep2 : '';?></td>	
            <td colspan="5" style="text-align:center"><?= $model->s_po ? $model->getS_SS($model->s_po)->dep2 : '';?></td>	
		</tr><tr>
            <td colspan="5" style="text-align:center"><?= $model->s_bb ? $model->getS_SS($model->s_bb)->dep3 : '';?></td>	
            <td colspan="5" style="text-align:center"><?= $model->s_po ? $model->getS_SS($model->s_po)->dep3 : '';?></td>	
		</tr>
    </tbody>
	  
</table>
</div>
<div>
</div>