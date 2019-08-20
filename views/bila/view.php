<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\models\User;
use app\models\Bila;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\WebLink */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Bila', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="box box-primary">
			
	<div class="box-body">
		<div id="wra_example1" class="dataTables_wrapper form-inline dt-bootstrap">
                
                <h2 class="alert <?= $model->cat == 'ลาป่วย' ? 'alert-danger': 'alert-success';?> fade in">
                    
                    <?= $model->getProfileName();?> 
                    <sup class="badge bg-color-orange bounceIn animated"><?= $model->cat ?></sup>                    
                </h2>
                <!-- <h2><?= $model->cat ?></h2> -->

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
                    <?= $model->getProfileName();?>
                </td>
            </tr>
            <tr>
                <td class="text-right">
                    ตำแหน่ง
                </td>
                <td>
                    <?=$model->getProfileDep();?>
                </td>
            </tr>
            <tr>
                <td class="text-right">
                    ประเภทการลา
                </td>
                <td>
                    <?= $model->cat ?>
                </td>
            </tr>
            <tr>
                <td class="text-right">
                    จำนวนวันที่ลา
                </td>
                <td>
                    <?= $model->date_total ;?> วัน
                </td>
            </tr>
            <tr>
                <td class="text-right">
                    ตั้งแต่วันที่
                </td>
                <td>
                    <?= Bila::DateThai_full($model->date_begin);?>
                </td>
            </tr>
            <tr>
                <td class="text-right">
                    ถึงวันที่
                </td>
                <td>
                    <?= Bila::DateThai_full($model->date_end);?>
                </td>
            </tr>
            <tr>
                <td class="text-right">
                    
                </td>
                <td>
                <?= $model->file ?
                '<a href="'.Url::to(['bila/file_view','id' => $model->id]).'"  target="_blank" data-id='.$model->id.'>ไฟล์เอกสาร</a>' 
                :
                ' <a href="'.Url::to(['bila/print1','id' => $model->id]).'" class="btn btn-primary btn-xs" target="_blank" data-id='.$model->id.'>print</a>' ?>
                <?php 
                    // if(!Yii::$app->user->isGuest){
                     if(!Yii::$app->user->isGuest && empty($model->file) && Yii::$app->user->identity->role == 9){
                        '<button class="btn btn-success btn-xs act-file-up" data-id='.$model->id.'>แนบไฟล์</button>' ;
                    //  } 
                    }?>
                </td>
            </tr>
        
        </table>
    
        </tbody>
		</table>
								</div>
	</div>
</div>

<?php

$script = <<< JS
    
$(document).ready(function() {	

var url_file_up = "../file_up";		
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
        		
});
JS;
$this->registerJs($script);
?>