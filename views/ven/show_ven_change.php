<?php
use yii\helpers\Url;
?>

<div class="box box-primary">			
	<div class="box-body">
		<div id="wra_example1" class="dataTables_wrapper form-inline dt-bootstrap">                
            <h2 class="alert alert-success fade in">
                ใบเปลี่ยนเวรเลขที่ <?= $model->id;?> 
                <sup class="badge bg-color-orange bounceIn animated"><?= $model->id ?></sup>                    
            </h2>

            <table class="table table-bordered table-condensed ">                             
                <tr>   
                    <td class="text-right">
                        ชื่อ
                    </td>
                    <td>
                        <?= $model->getProfileName();?>
                    </td>
                </tr>
                <tr>
                    <td class="text-right">
                        อยู่เวรวันที่
                    </td>
                    <td>
                        <?=$model->DateThai_full($model->ven1->ven_date);?> เวลา <?= date(" H : i ", strtotime($model->ven1->ven_time))?>
                    </td>
                </tr>
                <tr>
                    <td class="text-right">
                        คำสั่ง
                    </td>
                    <td>
                        <?=$model->ven1->getVenComNum() .' ลงวันที่ '. $model->DateThai_full($model->ven1->getVenComDate()).' --> '. $model->ven1->getVenComName();?>
                    </td>
                </tr>
                <tr>
                    <td class="text-right">
                        รหัสอ้างอิง
                    </td>
                    <td>
                       <!-- <label class = "act-ven-show" data-id = "<?= $model->ven_id1 ?>"><?= $model->ven_id1 ?></label> -->
                        <a href="#" class="act-ven-show" data-id = "<?= $model->ven_id1 ?>"><?= $model->ven_id1 ?></a> 
                    </td>
                </tr>                       
            </table> 
            <hr>
            <?= $model->ven_id2_old ? '
            <table class="table table-bordered table-condensed">                            
                <tr>   
                    <td class="text-right">
                        ชื่อ
                    </td>
                    <td>
                        '.$model->getProfileName2().'
                    </td>
                </tr>
                <tr>
                    <td class="text-right">
                        อยู่เวรวันที่
                    </td>
                    <td>
                        '. $model->DateThai_full($model->ven2->ven_date).' เวลา '. date(" H : i ", strtotime($model->ven2->ven_time)).'
                    </td>
                </tr>
                <tr>
                    <td class="text-right">
                        คำสั่ง
                    </td>
                    <td>
                        '.$model->ven1->getVenComNum() .' ลงวันที่ '. $model->DateThai_full($model->ven1->getVenComDate()).' --> '. $model->ven1->getVenComName().'
                    </td>
                </tr>
                <tr>
                    <td class="text-right">
                        รหัสอ้างอิง
                    </td>
                    <td>
                        <a href="#" class="act-ven-show" data-id = "'.$model->ven_id2 .'">'. $model->ven_id2 .'</a> 
                    </td>
                </tr> 
                
            </table>
        ' : '' ;?>      
            <table class="table ">
                <tr>
                    <td class="text-center">
                        <?= $model->file ?
                        '<a href="'.Url::to(['ven/ven_file_view','id' => $model->id]).'"  target="_blank" data-id='.$model->id.'>ไฟล์เอกสาร</a>' 
                        :
                        '<a href="'.Url::to(['ven/print','id' => $model->id]).'" class="btn btn-primary btn-xs" target="_blank" data-id='.$model->id.'>print</a>' ?>
                        <?php
                            
                        if(!Yii::$app->user->isGuest && empty($model->file) && Yii::$app->user->identity->role == 9){
                            echo '<button class="btn btn-success btn-xs act-file-up" data-id='.$model->id.'>แนบไฟล์</button>' ;                           
                        }?>
                    </td>
                </tr>       
            </table> 
		</div>
	</div>
</div>

<?php

$script = <<< JS
    
$(document).ready(function() {	

var url_file_up = "../change_upfile";		
    	$(".act-file-up").click(function(e) {			
			var fID = $(this).data("id");
			$.get(url_file_up,{id: fID},function (data){
					$("#activity-modal").find(".modal-body").html(data);
					$(".modal-body").html(data);
					$(".modal-title").html("ข้อมูล");
					$("#activity-modal").modal("show");
				}
			);
		});

        var url_show = "../ven_show";
        $(".act-ven-show").click(function(e) {        
            var fID = $(this).data("id");
            $.get(url_show,{id: fID},function (data){
                    $("#activity-modal").find(".modal-body").html(data);
                    $(".modal-body").html(data);
                    $(".modal-title").html("view");
                    $("#activity-modal").modal("show");
                });	
        });
});
JS;
$this->registerJs($script);
?>