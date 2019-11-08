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
                        รหัสใบลา
                    </td>
                    <td>
                        <?= $model->id ?>
                    </td>
                </tr>
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
                      หมายเหตุ
                    </td>
                    <td>
                        <?= $model->comment;?>
                    </td>
                </tr>
                       
            </table>  
            
            
            <?= Html::a('ลบ', ['del', 'id' => $model->id], ['class' => 'btn btn-danger btn-block','data-confirm'=>'Are you sure to ยกเลิก this item?']) ?>
                
            
		</div>
	</div>
</div>

<?php

$script = <<< JS
    
$(document).ready(function() {	

        		
});
JS;
$this->registerJs($script);
?>