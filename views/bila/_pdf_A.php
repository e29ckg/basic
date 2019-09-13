<?php
use yii\helpers\Html;
// use app\models\User;
// use app\models\Bila;
use app\models\SignBossName;
use yii\helpers\Url;
?>
<!-- <link rel="stylesheet" href="<?=Url::to(['/fonts/thsarabunnew.css'])?>" /> -->
<div style="A_CSS_ATTRIBUTE:all;position: absolute;bottom: 20px; right: 45px;left: 45px; top: 35px;  ">
<!-- <div class="text-center"><H3> </H3></div> -->

<table class="table_bordered " width="100%" border="1" cellpadding="1" cellspacing="0">
    <thead>
		<tr>
            <th  width="90%"><H2>แบบใบลาป่วย , ลากิจส่วนตัว , ลาคลอดบุตร</H2> </th>	
            <th  width="10%">                  
                <img src="<?= $model->getQr($model->id,$model->user_id);?>" height="60" width="60" >
                <div style="font-size: 11px; "><?=isset($model->running) ? $model->running : $model->id?></div>
            </th>		
		</tr>
	</thead>    
</table>
<table class="bl_detail" width="100%" border="0" cellpadding="0" cellspacing="0">
    <tr>
        <td width="7%"></td>
        <td width="4%"></td>
        <td width="7%"></td>
        <td width="3%"></td>
        <td width="6%"></td>
        <td width="9%"></td>
        <td width="9%"></td>
        <td width="8%"></td>
        <td width="10%"></td>
        <td width="10%"></td>
        <td width="10%"></td>
        <td width="10%"></td>
    </tr>
    <tr>
        <!-- <td colspan="1"></td> -->
        <td colspan="15" style="text-align:right">สำนักงานประจำศาลเยาวชนและครอบครัวจังหวัดประจวบคีรีขันธ์</td>
    </tr>
    <?php
        $date_create=date_create($model->date_create); 
        //echo date_format($date_create,"Y/m/d H:i:s");
    ?>
    <tr>        
        <td colspan="8" style="text-align:right">วันที่</td>
        <td colspan="1" class="TableLine" style="text-align:center"><?=(int)date_format($date_create,"d");?></td>
        <td colspan="1" style="text-align:center">เดือน</td>
        <td colspan="1" class="TableLine" style="text-align:center"><?=$model->DateThai_month_full($model->date_create);?></td>
        <td colspan="1" style="text-align:center">พ.ศ.</td>
        <td colspan="1" class="TableLine" style="text-align:center"><?=(int)date_format($date_create,"Y")+543;?> </td>
    </tr>
    <tr>
        <td colspan="1" >เรื่อง</td>
        <td colspan="13">ขออนุญาต<?= $model->cat; ?></td>
    </tr>
    <tr>
        <td colspan="1">เรียน</td>
        <td colspan="13">ผู้อำนวยการสำนักงานประจำศาลเยาวชนและครอบครัวจังหวัดประจวบคีรีขันธ์</td>
    </tr>
    <tr>
        <td colspan="2" ></td>
        <td colspan="1" >ข้าพเจ้า</td>
        <td colspan="4" class="TableLine" style="text-align:center"><?= $model->profile->fname.$model->profile->name.' '.$model->profile->sname;?></td>
        <td colspan="1" style="text-align:center">ตำแหน่ง</td>
        <td colspan="6" class="TableLine" style="text-align:center"><?= $model->profile->dep;?></td>
    </tr>
    <tr>
        <td colspan="1" >สังกัด</td>
        <td colspan="14" class="TableLine">สำนักงานประจำศาลเยาวชนและครอบครัวจังหวัดประจวบคีรีขันธ์</td>
    </tr>
    <tr>
        <td colspan="3" ></td>
        <td colspan="12" >[ <?= $model->cat == 'ลาป่วย' ? 'X' : '&nbsp;'?> ] ลาป่วย</td>
    </tr>
    <tr>
        <td colspan="3" ></td>
        <td colspan="3" >[ <?= $model->cat == 'ลากิจส่วนตัว' ? 'X' : '&nbsp;'?> ] ลากิจส่วนตัว</td>
        <td colspan="1" >เนื่องจาก</td>
        <td colspan="5" class="TableLine" style="text-align:center"><?=$model->due;?> </td>
    </tr>
    <tr>
        <td colspan="3" ></td>
        <td colspan="10" >[ <?= $model->cat == 'ลาคลอดบุตร' ? 'X' : '&nbsp;'?> ] ลาคลอดบุตร</td>
    </tr>
    <tr>
        <td colspan="2" >ตั้งแต่วันที่ </td>        
        <td colspan="4" class="TableLine" style="text-align:center"><?=$model->DateThai_full($model->date_begin);?> </td>
        <td colspan="1" style="text-align:center">ถึงวันที่</td>
        <td colspan="3" class="TableLine" style="text-align:center"><?=$model->DateThai_full($model->date_end);?></td>
        <td colspan="1" style="text-align:center">มีกำหนด</td>
        <td colspan="1" class="TableLine" style="text-align:center"><?=$model->date_total;?></td>
        <td colspan="1" style="text-align:center">วัน</td>
    </tr>
    <tr>
        <td colspan="2" >ข้าพเจ้าได้ลา </td>
        <td colspan="3" >[ <?= $model->cat == 'ลาป่วย' ? 'X' : '&nbsp;'?> ] ลาป่วย</td>
        <td colspan="2" >[ <?= $model->cat == 'ลากิจส่วนตัว' ? 'X' : '&nbsp;'?> ] ลากิจส่วนตัว</td>
        <td colspan="2" >[ <?= $model->cat == 'ลาคลอดบุตร' ? 'X' : '&nbsp;'?> ] ลาคลอดบุตร</td>
        <td colspan="4" >ครั้งสุดท้ายตั้งแต่</td>
    </tr>
    <tr>
        <td colspan="2" >ตั้งแต่วันที่ </td>
        <td colspan="4" class="TableLine" style="text-align:center"><?=$model->DateThai_full($model->dateO_begin);?></td>
        <td colspan="1" style="text-align:center">ถึงวันที่</td>
        <td colspan="3" class="TableLine" style="text-align:center"><?=$model->DateThai_full($model->dateO_end);?></td>
        <td colspan="1" style="text-align:center">มีกำหนด</td>
        <td colspan="1" class="TableLine" style="text-align:center"><?=$model->dateO_total ? $model->dateO_total : '-';?></td>
        <td colspan="1" style="text-align:center">วัน</td>
    </tr>
    <tr>
        <td colspan="5" >ระหว่างนี้ติดต่อข้าพเจ้าได้ที่</td>
        <td colspan="8" class="TableLine"><?=$model->address;?> </td>
    </tr>
    <tr>
        <td colspan="12" class="TableLine" style="text-align:center"><?= $model->getProfilePhone()?>.</td>
    </tr>
    <tr>
        <td colspan="12" ><?= $model->comment ? '( หมายเหตุ ' .$model->comment. ' )' : '' ;?></td>
    </tr>
    <tr>
    </tr>
  
    <tr>
        <td colspan="7" style="text-align:center">
        - ทราบ<br><br><br>
            (ลงชื่อ)................................................................
            <?php 
                    if(!empty($model->bigboss)){
                        $model_s_bigboss = SignBossName::find()->where(['id' => $model->bigboss])->one();
                        echo $model_s_bigboss ? '<br>('.$model_s_bigboss->name.')<br>'.$model_s_bigboss->dep1.'<br>'.$model_s_bigboss->dep2.'<br>'.$model_s_bigboss->dep3 : '<br><br><br><br><br><br><br>'; 
                    }else{
                        echo '';
                    }
                    ?>
        </td> 
        <td></td>       
        <td colspan="6" style="text-align:center">
            ขอแสดงความนับถือ<br><br><br>
            (ลงชื่อ)................................................................<br>
            ( <?= $model->getProfileName();?> )<br>
        </td>
    </tr>
    
    <tr>
        <td colspan="7">             
            <table class="table_bordered" width="100%" border="0" cellpadding="2" cellspacing="0">
                <tr>
                    <td colspan="8" style="text-align:center">สถิติการลาในปีงบประมาณนี้</td>
                </tr>
                <tr>
                    <td colspan="2" style="text-align:center">ประเภทการลา</td>
                    <td colspan="2" style="text-align:center">ลามาแล้ว<br>(วันทำการ)</td>
                    <td colspan="2" style="text-align:center">ลาครั้งนี้<br>(วันทำการ)</td>
                    <td colspan="2" style="text-align:center">รวมเป็น<br>(วันทำการ)</td>
                </tr>
                <tr>
                    <td colspan="2">ลาป่วย</td>
                    <td colspan="2" style="text-align:center"><?= $model->cat == 'ลาป่วย' ? $model->t1 : '&nbsp;'?></td>
                    <td colspan="2" style="text-align:center"><?= $model->cat == 'ลาป่วย' ? $model->t2 : '&nbsp;'?></td>
                    <td colspan="2" style="text-align:center"><?= $model->cat == 'ลาป่วย' ? $model->t3 : '&nbsp;'?></td>
                </tr>
                <tr>
                    <td colspan="2">ลากิจ</td>
                    <td colspan="2" style="text-align:center"><?= $model->cat == 'ลากิจส่วนตัว' ? $model->t1 : '&nbsp;'?> </td>
                    <td colspan="2" style="text-align:center"><?= $model->cat == 'ลากิจส่วนตัว' ? $model->t2 : '&nbsp;'?> </td>
                    <td colspan="2" style="text-align:center"><?= $model->cat == 'ลากิจส่วนตัว' ? $model->t3 : '&nbsp;'?> </td>
                </tr>
                <tr>
                    <td colspan="2">ลาคลอดบุตร</td>
                    <td colspan="2" style="text-align:center"><?= $model->cat == 'ลาคลอดบุตร' ? $model->t1 : '&nbsp;'?> </td>
                    <td colspan="2" style="text-align:center"><?= $model->cat == 'ลาคลอดบุตร' ? $model->t2 : '&nbsp;'?> </td>
                    <td colspan="2" style="text-align:center"><?= $model->cat == 'ลาคลอดบุตร' ? $model->t3 : '&nbsp;'?> </td>
                </tr>
            </table>
            <table class="bl_detail" width="100%" cellpadding="2" cellspacing="0">
                <tr>
                    <td colspan="8">
                    (ลงชื่อ)................................................ผู้ตรวจสอบ<br><br>
                    ตำแหน่ง................................................<br><br>
                    วันที่...................................................<br><br>
                    </td>
                </tr> 
            </table>
            <table class="bl_detail" width="100%" border="0" cellpadding="2" cellspacing="0"> 
                <tr>
                    <td colspan="8">
                    ประธานเสนอ ผู้พิพากษาหัวหน้าศาลฯ<br>
                    - เพื่อโปรดทราบ<br><br>
                    </td>
                </tr>
                <tr>
                    <td colspan="8" style="text-align:center">
                    <br> 
                    <?php 
                        if(!empty($model->po)){
                            $model_s_po = SignBossName::find()->where(['id' => $model->po])->one();
                            if($model_s_po){
                                echo '('.$model_s_po->name.')<br>'.$model_s_po->dep1.'<br>'.$model_s_po->dep2.'<br>'.$model_s_po->dep3; 
                            }
                        }else{
                            echo '';
                        }
                    ?>
                    </td>
                </tr>
            </table>
        </td>
        <td></td> 
        <td colspan="6">
            <table class="table_bordered" width="100%" border="0" cellpadding="2" cellspacing="0">
                <tr>
                    <td colspan="8" style="text-align:center">ความเห็นผู้บังคับบัญชา</td>
                </tr>
                <tr>
                    <td colspan="8">
                    <br>.................................................................................<br><br>
                        .................................................................................<br><br>
                        (ลงชื่อ).......................................................................<br><br>
                        ตำแหน่ง....................................................................<br><br>
                        วันที่.........................................................................<br>
                    </td>
                </tr>                
            </table>
            <table class="table_bordered" width="100%" border="0" cellpadding="2" cellspacing="0">               
                <tr>
                    <td colspan="8"><br>
                        คำสั่ง   &nbsp;&nbsp;&nbsp;[ &nbsp; ] อนุญาต  &nbsp;&nbsp;&nbsp;[ &nbsp; ] ไม่อนุญาต<br><br>
                        ............................................................................<br><br>
                        ............................................................................<br><br>
                        (ลงชื่อ)......................................................................<br><br>
                        ตำแหน่ง...................................................................<br><br>
                        วันที่.........................................................................<br>
                    </td>
                </tr>                
            </table>
        </td>
    </tr>    
</table>
</div>