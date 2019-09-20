<?php
use yii\helpers\Html;
use yii\helpers\Url;
use app\models\VenCom;
use app\models\VenChange;
?>

<div class="box box-primary">	
    		
	<div class="box-body">
		<div id="wra_example1" class="dataTables_wrapper form-inline dt-bootstrap">                
           <h2 class="alert alert-success fade in">
                <?= $model->getProfileName();?> 
                    <sup class="badge bg-color-danger bounceIn animated"><?= VenChange::getStatusList()[$model->status]  ?></sup>                    
                   <br>
                    <span><?= $model->DateThai_full($model->ven_date);?></span>
            </h2> 

            <table class="table table-bordered table-condensed">
                <tr>
                    <td class="text-right">
                        รหัสใบเวร
                    </td>
                    <td>
                        <?= $model->id ?>
                    </td>
                </tr>
                <tr>   
                    <td class="text-right">
                        วันที่
                    </td>
                    <td>
                        <?= $model->DateThai_full($model->ven_date);?>
                    </td>
                </tr>
                <tr>
                    <td class="text-right">
                        คำสั่ง
                    </td>
                    <td>
                        <?=$model->venCom->ven_com_num.' ลงวันที่ '.$model->DateThai_full($model->venCom->ven_com_date)?>
                        <br><?= VenCom::getVen_time()[$model->ven_time];?>
                    </td>
                </tr>
                
                <tr>
                    <td class="text-right">
                        <?= $model->ven_month;?>
                    </td>
                    <td>
                        <?= $model->ven_time;?>
                        <?=Html::a('setActive',['ven/admin_set_active','id' => $model->id],
                        [
                            'class' => 'btn btn-danger btn-sx ',
                        ])?> 
                        <?=Html::a('setDis',['ven/admin_set_dis','id' => $model->id],
                        [
                            'class' => 'btn btn-info btn-sx ',
                        ])?> 
                    </td>
                </tr>
                
                <?= !empty($model->file) ? '
                <tr>
                    <td class="text-right">
                        ไฟล์
                    </td>
                    <td>
                        '.$model->file.'
                    </td>
                </tr>'
                : '';?>
                      
            </table> 
            <hr>
            <?=Html::a(' <i class="fa fa-remove"></i> ลบ',['ven/admin_del','id' => $model->id],
                        [
                            'class' => 'btn btn-danger btn-lg btn-block',
                            'data-confirm' => 'Are you sure to delete this item?',
                            'data-method' => 'post',
                        ])?>  
		</div>
	</div>
</div>

<br>เปลี่ยน :  <?=$model->getCheck($model->id) ? 'ได้' : 'ไม่ได้';?> , จำนวนเวรที่เหลือ : <?= $model->getVenForChangeCount($model->id) >= 1 ? $model->getVenForChangeCount($model->id) : '';?>

<?php

$script = <<< JS
    
$(document).ready(function() {	   

        var url_change = "admin_update";		
    	$(".act-update").click(function(e) {			
			var fID = $(this).data("id");
			$.get(url_change,{id: fID},function (data){
					$("#activity-modal").find(".modal-body").html(data);
					$(".modal-body").html(data);
					$(".modal-title").html("เปลี่ยนเวร");
					$("#activity-modal").modal("show");
				}
			);
		});       
          		
});
JS;
$this->registerJs($script);
?>