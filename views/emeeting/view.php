<?php
use yii\helpers\Html;
use yii\helpers\Url;
?>

<div class="box box-primary">			
	<div class="box-body">
		<div id="wra_example1" class="dataTables_wrapper form-inline dt-bootstrap">                
            <h2 class="alert alert-primary fade in">
                <?= $model->cname;?> 
                    <sup class="badge bg-color-orange bounceIn animated"></sup>                    
            </h2>
            <table class="table table-bordered table-condensed">
                
                <tr>   
                    <td class="text-right">
                        ศาล
                    </td>
                    <td>
                        <?= $model->cname;?>
                    </td>
                </tr>
                <tr>   
                    <td class="text-right">
                        เรื่อง
                    </td>
                    <td>
                        <?= $model->title;?>
                    </td>
                </tr>
                <tr>   
                    <td class="text-right">
                        วันที่
                    </td>
                    <td>
                        <?= $model->start ?>
                    </td>
                </tr>
                <tr>   
                    <td class="text-right">
                        ถึงวันที่
                    </td>
                    <td>
                        <?= $model->end ?>
                    </td>
                </tr>
                <tr>
                    <td class="text-right">
                        ผู้ขอ
                    </td>
                    <td>
                        <?= $model->fname ?>
                    </td>
                </tr>
                <tr>
                    <td class="text-right">
                        โทรศัพท์
                    </td>
                    <td>
                    <a href="tel:<?= $model->tel ?>"><?= $model->tel ?></a>
                    </td>
                </tr>
                <tr>   
                    <td class="text-right">
                      รายละเอียด
                    </td>
                    <td>
                        <?= $model->detail;?>
                       
                    </td>
                </tr>
                       
            </table>  
            <?php  if( !empty($model->file)){?>
                    <embed src="<?= $completePath?>" frameborder="0" width="100%" height="800px">
            <?php }?>
            
            <?= !Yii::$app->user->isGuest ? 
                '<div class="text-center">
                    <label for="update" data-id='.$model->id.' class="btn btn-success act-update">แก้ไข</label> '
                    .Html::a('ลบ', ['del', 'id' => $model->id], ['class' => 'btn btn-danger btn-md','data-confirm'=>'Are you sure to ยกเลิก this item?'])
                    .'</div>'
                : '';?>

            <!-- <div class="text-center">
                <label for="update" data-id=<?=$model->id?> class="btn btn-success act-update">แก้ไข</label>
                <?= Html::a('ลบ', ['del', 'id' => $model->id], ['class' => 'btn btn-danger btn-md','data-confirm'=>'Are you sure to ยกเลิก this item?']) ?>
            </div> -->
            
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