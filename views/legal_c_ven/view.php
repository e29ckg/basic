<?php
use yii\helpers\Html;
use yii\helpers\Url;
?>

<div class="box box-primary">			
	<div class="box-body">
		<div id="wra_example1" class="dataTables_wrapper form-inline dt-bootstrap">                
            <h2 class="alert alert-primary fade in">
                <?= $model->getName();?> 
                    <sup class="badge bg-color-orange bounceIn animated"></sup>                    
            </h2>
            <table class="table table-bordered table-condensed">
                
                <tr>   
                    <td class="text-right">
                        ชื่อ
                    </td>
                    <td>
                        <?= $model->getName();?>
                    </td>
                </tr>
                <tr>   
                    <td class="text-right">
                        วันที่
                    </td>
                    <td>
                        <?= $model->dateThai_full($model->ven_date)?>
                    </td>
                </tr>
                <tr>
                    <td class="text-right">
                        โทรศัพท์
                    </td>
                    <td>
                    <a href="tel:<?= $model->getPhone() ?>"><?= $model->getPhone() ?></a>
                    </td>
                </tr>
                <tr>   
                    <td class="text-right">
                      หมายเหตุ
                    </td>
                    <td>
                        <?= $model->comment;?>
                        <?= $model->ven_date  >=  date("Y-m-d") ? Html::a('<i class="fa fa-paper-plane-o"></i> Line',['legal_c_ven/line_alert','id' => $model->id],
                            [
                                'class' => 'btn btn-success btn-xs act-update',
                                'data-confirm' => 'Are you sure to Line this item?'
                            ])
                            :'';
						?>
                    </td>
                </tr>
                       
            </table>  
            
            <div class="text-center">
                <label for="update" data-id=<?=$model->id?> class="btn btn-success act-update">แก้ไข</label>
                <?= Html::a('ลบ', ['del', 'id' => $model->id], ['class' => 'btn btn-danger btn-md','data-confirm'=>'Are you sure to ยกเลิก this item?']) ?>
            </div>
            
		</div>
	</div>
</div>

<?php

$script = <<< JS
    
$(document).ready(function() {	
    var url_update = "update";		
			$( ".act-update" ).click(function() {
				var fID = $(this).data("id");	
        	$.get(url_update,{id: fID},function (data){
                $("#activity-modal").find(".modal-body").html(data);
                $(".modal-body").html(data);
                $(".modal-title").html("แก้ไข");
            	// $(".modal-footer").html(footer);
                $("#activity-modal").modal("show");
        	});     
		});
        		
});
JS;
$this->registerJs($script);
?>