<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'เสื้อฟ้า';
$this->params['breadcrumbs'][] = $this->title;
?>

 <!-- Default box -->
<div class="box box-primary">
	<div class="box-header with-border">
		<h3 class="box-title"><?= Html::encode($this->title) ?></h3>
		<div class="box-tools pull-right">
			<button id="act-create" class="btn btn-danger btn-md" alt="act-create"><i class="fa fa-pencil-square-o "></i> เพิ่ม</button>  
		</div>			
	</div>	
	<div class="box-body">
		<div id="wra_example1" class="dataTables_wrapper form-inline dt-bootstrap">
			<table id="example1" class="table table-striped table-bordered" width="100%">
				<thead>
					<tr>
						<th class="text-center">วันที่</th>
						<th class="text-center">เวร</th>
						<th class="text-center">ผู้ตรวจ</th>	
						<th class="text-center"style="width:150px"></th>			
					</tr>
				</thead>
				<tbody>
					<?php foreach ($models as $model): ?>
					<tr>
						<td><?=$model->line_alert?></td>
						<td><?=$model->getProfileName()?></td>
						<td><?=$model->getProfileName2()?></td>
						<td>
							<?= Html::a('<i class="fa fa-paper-plane-o"></i> Line',['blueshirt/line_alert','id' => $model->id],
									[
										'class' => 'btn btn-success btn-xs act-update',
										'data-confirm' => 'Are you sure to Line this item?'
									]);
							?>
							<?= Html::a('<i class="fa fa-remove"></i> ลบ',['blueshirt/delete','id' => $model->id],
									[
										'name'=>'Yii::$app->request->csrfParam',
										'value'=>'Yii::$app->request->csrfToken',
										'class' => 'btn btn-danger btn-xs act-update',
										'data-confirm' => 'Are you sure to delete this item?',
										'data-method' => 'post',
									]);
							?></td>
					</tr>
					<?php  endforeach; ?>
				</tbody>	
			</table>
		</div>
	</div>
</div>



<?php

$script = <<< JS
     
	 $(document).ready(function() {	
/* BASIC ;*/

	

	function init_click_handlers(){        	
		
		var url_update = "update";
    	$(".act-update").click(function(e) {            
			var fID = $(this).data("id");
			// alert(fID);
        	$.get(url_update,{id: fID},function (data){
            	$("#activity-modal").find(".modal-body").html(data);
            	$(".modal-body").html(data);
            	$(".modal-title").html("แก้ไขข้อมูลสมาชิก");
            	$("#activity-modal").modal("show");
        	});
    	});

		var url_line = "line_alert";		
    	$(".act-line").click(function(e) {			
                var fID = $(this).data("id");				
                $.get(url_line,{id: fID},function (data){
                        // $("#activity-modal").find(".modal-body").html(data);
                        // $(".modal-body").html(data);
                        // $(".modal-title").html("ข้อมูล");
                        // $("#activity-modal").modal("show");
                    }
                );
            }); 

    	var url_view = "view";		
    	$(".act-view").click(function(e) {			
                var fID = $(this).data("id");
				
                $.get(url_view,{id: fID},function (data){
                        $("#activity-modal").find(".modal-body").html(data);
                        $(".modal-body").html(data);
                        $(".modal-title").html("ข้อมูล");
                        $("#activity-modal").modal("show");
                    }
                );
            });   
    
	}

    init_click_handlers(); //first run
			
	
		var url_create = "create";
    	$( "#act-create" ).click(function() {
        	$.get(url_create,function (data){
                $("#activity-modal").find(".modal-body").html(data);
                $(".modal-body").html(data);
                $(".modal-title").html("เพิ่มข้อมูล");
            	// $(".modal-footer").html(footer);
                $("#activity-modal").modal("show");
                //   $("#myModal").modal('toggle');
        	});     
		}); 
	
		$('#example1').DataTable({
    		"order": [[ 0, 'desc' ], [ 3, 'desc' ]]
		})	
});
JS;
$this->registerJs($script);
?>