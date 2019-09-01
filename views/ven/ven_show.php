<?php
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
                        ผู้อยู่เวร
                    </td>
                    <td>
                        <?= $model->getProfileName() ?>
                    </td>
                </tr>
                <tr>
                    <td class="text-right">
                        สถานะ
                    </td>
                    <td>
                        <?= VenChange::getStatusList()[$model->status] ;?>
                    </td>
                </tr>
                <tr>
                    <td class="text-right">
                        <?= $model->ven_month;?>
                    </td>
                    <td>
                        <?= $model->ven_time;?>
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
                <tr>
                    <td class="text-right">
                        
                    </td>
                    <td>
                        <?= $model->getVenForChangeCount($model->id) > 0 && $model->getCheck($model->id) ?
                            '<a class="btn btn-primary btn-xs act-ven-change" data-id="'.$model->id.'">ขอเปลี่ยน</a>'
                            : '';?>                        
                        <?= Yii::$app->user->identity->id == $model->user_id && $model->ven_date  >  date("Y-m-d") && $model->status <> 2 ? 
                            '<a class="btn btn-primary btn-xs" target="_blank" data-id='.$model->id.'>ยกให้</a>'
                            :'';?>
                    </td>
                </tr>       
            </table> 
            <hr>
            <table id="example1" class="table table-bordered table-striped dataTable" role="grid" >
                				
                <tbody>  
                                                
                    <?php foreach ($modelDs as $modeld): ?>
                    <tr>						                
                        <td><?=$modeld->id?></td>	
                        <td><?=$modeld->DateThai_full($modeld->ven_date)?></</td>									
                        <td> <?=$modeld->getProfileName()?> <?= VenCom::getVen_time()[$modeld->ven_time];?></td>
                        <td class = "text-center">
                            <?=$modeld->status == 2 || $modeld->status == 4 ?
                                '<a href="'.Url::to(['change_index']).'" class="label label-danger">'.VenChange::getStatusList()[$modeld->status].'</a>' 
                                :
                                '<a href="'.Url::to(['change_index']).'" class="label label-primary" >'.VenChange::getStatusList()[$modeld->status].'</a>' ;?>				        
                        </td>
                        
                    </tr>
                    <?php  endforeach; ?>								
                </tbody>
            </table>       
		</div>
	</div>
</div>

<br>เปลี่ยน :  <?=$model->getCheck($model->id) ? 'ได้' : 'ไม่ได้';?> , จำนวนเวรที่เหลือ : <?= $model->getVenForChangeCount($model->id) >= 1 ? $model->getVenForChangeCount($model->id) : '';?>

<?php

$script = <<< JS
    
$(document).ready(function() {	   

        var url_change = "ven_change";		
    	$(".act-ven-change").click(function(e) {			
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