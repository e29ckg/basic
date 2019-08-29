<?php
use yii\helpers\Url;
use app\models\VenChange;
?>

<div class="box box-primary">			
	<div class="box-body">
		<div id="wra_example1" class="dataTables_wrapper form-inline dt-bootstrap">                
            <h2 class="alert alert-success fade in">
                <?= $model->getProfileName();?> 
                    <sup class="badge bg-color-orange bounceIn animated"><?= $model->id ?></sup>                    
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
                        <?=$model->venCom->ven_com_num;?>
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
                        <?= $countVen > 0 && !(Yii::$app->user->identity->id == $model->user_id) && $model->status == 1 && $model->ven_date  >  date("Y-m-d") && $model->getCountVen($model->ven_com_id) > 0 ?
                            '<a class="btn btn-primary btn-xs act-ven-change" data-id="'.$model->id.'">ขอเปลี่ยน</a>'
                            : '';?>                        
                        <?= Yii::$app->user->identity->id == $model->user_id && $model->status == 1 && $model->ven_date  >  date("Y-m-d")  ? 
                            '<a class="btn btn-primary btn-xs" target="_blank" data-id='.$model->id.'>ยกให้</a>'
                            :'';?>
                    </td>
                </tr>       
            </table>        
		</div>
	</div>
</div>
<?php
echo var_dump($model->getCheck_user($model->id));
echo '<br>'.$model->ven_date ;
echo '<br>'.date('Y-m-d', strtotime('-1 day', strtotime($model->ven_date)));
echo '<br><br>'.var_dump($model->getVenForChange($model->ven_com_id)); 
    // foreach (model->getCheck_user($model->id) as $countV):
    //     echo $countV->id.' '. $countV->ven_date.'<br>';
    // endforeach;
    ?>
<?= '<br>'.'จำนวนเวรที่สามารถเปลี่ยนได้ '.$model->getCountVen($model->ven_com_id).' : '.date("Y-m-d").' status : '. $model->status .' '?>
<?=$model->ven_date . ' > '. date("Y-m-d")?>
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