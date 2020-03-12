<?php
use yii\helpers\Html;
// use app\models\User;
// use app\models\Bila;
use app\models\SignBossName;

// function DateThai_full($strDate)
// 	{
// 		$strYear = date("Y",strtotime($strDate))+543;
// 		$strMonth= date("n",strtotime($strDate));
// 		$strDay= date("j",strtotime($strDate));
// 		$strHour= date("H",strtotime($strDate));
// 		$strMinute= date("i",strtotime($strDate));
// 		$strSeconds= date("s",strtotime($strDate));
// 		$strMonthCut = Array("","มกราคม","กุมภาพันธ์","มีนาคม","เมษายน","พฤษภาคม","มิถุนายน","กรกฎาคม",
//                             "สิงหาคม","กันยายน","ตุลาคม","พฤศจิกายน","ธันวาคม");
// 		$strMonthThai=$strMonthCut[$strMonth];
// 		return "$strDay $strMonthThai $strYear";
//     }
// function DateThai_month_full($strDate)
// 	{
// 		$strYear = date("Y",strtotime($strDate))+543;
// 		$strMonth= date("n",strtotime($strDate));
// 		$strDay= date("j",strtotime($strDate));
// 		$strHour= date("H",strtotime($strDate));
// 		$strMinute= date("i",strtotime($strDate));
// 		$strSeconds= date("s",strtotime($strDate));
// 		$strMonthCut = Array("","มกราคม","กุมภาพันธ์","มีนาคม","เมษายน","พฤษภาคม","มิถุนายน","กรกฎาคม",
//                             "สิงหาคม","กันยายน","ตุลาคม","พฤศจิกายน","ธันวาคม");
// 		$strMonthThai=$strMonthCut[$strMonth];
// 		return "$strMonthThai";
// 	}

?>

<!-- <div class="text-center"><H3> </H3></div> -->
<div style="A_CSS_ATTRIBUTE:all;position: absolute;bottom: 20px; right: 45px;left: 45px; top: 35px;  ">
<table class="table_bordered" width="100%" border="0" cellpadding="2" cellspacing="0">
    <thead>
		<tr>
            <th  width="90%" class=""><H2>ใบลาพักผ่อน</H2> </th>	
            <th  width="10%" class="">
                <img src="<?= $model->getQr($model->id,$model->user_id);?>" height="60" width="60" >
                <div style="font-size: 12px; "><?=isset($model->running) ? $model->running : $model->id?></div>
            </th>				
		</tr>
	</thead>    
</table>

<table class="bl_detail" width="100%" border="0" cellpadding="2" cellspacing="0">
    <!-- <tr>
        <td width="50px"></td>
        <td width="50px"></td>
        <td width="50px"></td>
        <td width="50px"></td>
        <td width="50px"></td>
        <td width="50px"></td>
        <td width="50px"></td>
        <td width="50px"></td>
        <td width="50px"></td>
        <td width="75px"></td>
        <td width="50px"></td>
        <td width="50px"></td>
    </tr> -->
    <tr>
        <td colspan="1"></td>
        <td colspan="15" style="text-align:right">สำนักงานประจำศาลเยาวชนและครอบครัวจังหวัดประจวบคีรีขันธ์</td>
    </tr>
    <?php
        $date_create=date_create($model->date_create); 
        //echo date_format($date_create,"Y/m/d H:i:s");
    ?>
    <tr>
        <td colspan="8" style="text-align:right">วันที่</td>
        <td colspan="1" class="TableLine" style="text-align:center"><?=(int)date_format($date_create,"j");?></td>
        <td colspan="1" style="text-align:center">เดือน</td>
        <td colspan="2" class="TableLine" style="text-align:center"><?=$model->DateThai_month_full($model->date_create);?></td>
        <td colspan="1" style="text-align:center">พ.ศ.</td>
        <td colspan="1" class="TableLine" style="text-align:center"><?=(int)date_format($date_create,"Y")+543;?></td>
    </tr>
    <tr>
        <td colspan="1" >เรื่อง</td>
        <td colspan="11">ขออนุญาตลาพักผ่อน</td>
    </tr>
    <tr>
        <td colspan="1">เรียน</td>
        <td colspan="11">
        <?=$model->getProfileDep() == 'ผู้อำนวยการฯ' ? 'ผู้พิพากษาหัวหน้าศาลเยาวชนและครอบครัวจังหวัดประจวบคีรีขันธ์' : 'ผู้อำนวยการสำนักงานประจำศาลเยาวชนและครอบครัวจังหวัดประจวบคีรีขันธ์';?>
        <!-- ผู้อำนวยการสำนักงานประจำศาลเยาวชนและครอบครัวจังหวัดประจวบคีรีขันธ์ -->
        </td>
    </tr>
    <tr>
        <td colspan="2" ></td>
        <td colspan="1" style="text-align:center">ข้าพเจ้า</td>
        <td colspan="5" class="TableLine" style="text-align:center"><?= $model->getProfileName(); ?></td>
        <td colspan="1" style="text-align:center">ตำแหน่ง</td>
        <td colspan="5" class="TableLine" style="text-align:center"><?= $model->getProfileDep(); ?></td>
    </tr>
    <tr>
        <td colspan="1" >สังกัด</td>
        <td colspan="13" class="TableLine">สำนักงานประจำศาลเยาวชนและครอบครัวจังหวัดประจวบคีรีขันธ์</td>
    </tr>
    <tr>
        <td colspan="3" >มีวันลาพักผ่อนสะสม</td>
        <td colspan="1" class="TableLine" style="text-align:center"><?=$model->p1;?></td>
        <td colspan="8" style="text-align:center">วัน มีสิทธิลาพักผ่อนประจำปีนี้อีก 10 วันทำการ รวมเป็น</td>
        <td colspan="1" class="TableLine" style="text-align:center"><?=$model->p2;?></td>
        <td colspan="1" style="text-align:right">วัน</td>
    </tr>
    <tr>
        <td colspan="3" >ขอลาพักผ่อนตั้งแต่วันที่ </td>
        <td colspan="4" class="TableLine" style="text-align:center"><?=$model->DateThai_full($model->date_begin);?></td>
        <td colspan="1" style="text-align:center">ถึงวันที่</td>
        <td colspan="3" class="TableLine" style="text-align:center"><?=$model->DateThai_full($model->date_end);?></td>
        <td colspan="1" style="text-align:center">มีกำหนด</td>
        <td colspan="1" class="TableLine" style="text-align:center"><?=$model->date_total;?></td>
        <td colspan="1" style="text-align:right">วัน</td>
    </tr>    
    
    <tr>
        <td colspan="4" >ระหว่างลาจะติดต่อข้าพเจ้าได้ที่</td>
        <td colspan="10" class="TableLine" ><?= $model->address;?></td>
    </tr>
    <tr>
        <td colspan="14" class="TableLine" style="text-align:center"><?= $model->getProfilePhone()?>.</td>
    </tr>
    <tr>
        <td colspan="14" ><?= $model->comment ? '( หมายเหตุ ' .$model->comment. ' )' : '' ;?></td>
    </tr>
    <tr>
    <td colspan="8" style="text-align:center">
        - ทราบ<br><br><br>
        (ลงชื่อ)................................................................
        <?php 
            if(!empty($model->bigboss)){
                $model_s_bigboss = SignBossName::find()->where(['id' => $model->bigboss])->one();
                
                    echo $model_s_bigboss ? '<br><br>('.$model_s_bigboss->name.')<br>'.$model_s_bigboss->dep1.'<br>'.$model_s_bigboss->dep2.'<br>'.$model_s_bigboss->dep3 : '<br><br><br><br><br><br><br>'; 
                
            }else{
                echo '<br><br><br><br><br><br><br>';
            }
        ?>
    </td>       
    <td colspan="10" style="text-align:center;">
        ขอแสดงความนับถือ<br><br><br>
        (ลงชื่อ)................................................................<br><br>
        ( <?= $model->getProfileName();?> )
        </td>        
    </tr>
    <tr>
        <td colspan="8"> 
            <table class="table_bordered" width="100%" border="0" cellpadding="2" cellspacing="0">
                <tr>
                    <td colspan="6" style="text-align:center">สถิติการลาในปีงบประมาณนี้</td>
                </tr>
                <tr>
                    <td colspan="2" style="text-align:center">ลามาแล้ว<br>(วันทำการ)</td>
                    <td colspan="2" style="text-align:center">ลาครั้งนี้<br>(วันทำการ)</td>
                    <td colspan="2" style="text-align:center">รวมเป็น<br>(วันทำการ)</td>
                </tr>
                <tr>
                    <td colspan="2" style="text-align:center"><?=$model->t1;?></td>
                    <td colspan="2" style="text-align:center"><?=$model->t2;?></td>
                    <td colspan="2" style="text-align:center"><?=$model->t3;?></td>
                </tr>
                 

            </table>
            <table class="bl_detail" width="100%" cellpadding="2" cellspacing="0">
                <tr>
                    <td colspan="8"><br>
                    (ลงชื่อ)................................................ผู้ตรวจสอบ<br><br>
                    ตำแหน่ง................................................<br><br>
                    วันที่...................................................<br><br>
                    </td>
                </tr> 
            </table>
            <table class="bl_detail" width="100%" border="0" cellpadding="2" cellspacing="0"> 
                <tr>
                    <td colspan="8"><br>
                    <?=$model->getProfileDep() == 'ผู้อำนวยการฯ' ?
                     '' : 
                    'ประธานเสนอ ผู้พิพากษาหัวหน้าศาลฯ<br>
                    - เพื่อโปรดทราบ<br><br>';?>
                    <!-- ประธานเสนอ ผู้พิพากษาหัวหน้าศาลฯ<br>
                    - เพื่อโปรดทราบ<br><br><br> -->
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
                <tr>
                    <td colspan="8"><br><br>                      
                    - รับทราบ<br><br>
                    (ลงชื่อ)...............................................ผู้ปฏิบัติงานแทน
                    </td>
                </tr>
            </table>
        </td>
        <td colspan="7">
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
