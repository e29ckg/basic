<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'หนังสือเวียนทราบ';
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
						<th data-class="expand">Id</th>
						<th >เรื่อง</th>
						<th data-hide="phone,tablet">ประเภท</th>
						<th data-hide="phone,tablet">วันที่บันทึก</th>
						<th  style="width:300px">เครื่องมือ</th>							
					</tr>
				</thead>
				<tbody>
					<?php foreach ($models as $model): ?>
					<tr>
						<td><?=$model->id?></td>
						<td><?= $model->file ? Html::a($model->name,['cletter/show','file' => $model->file],['target' => '_blank']) : $model->name;?></td>
						<td><?=$model->ca_name?></td>
						<td><?=$model->created_at?></td>
						
						<td>
							<?= Html::a('<i class="fa fa-paper-plane-o"></i> Line',['cletter/line_alert','id' => $model->id],
									[
										'class' => 'btn btn-danger act-update',
										'data-confirm' => 'Are you sure to Line this item?'
									]);
							?>
							<a href= "#" class="btn btn-warning act-update" data-id=<?=$model->id?>><i class="fa fa-pencil-square-o"></i> แก้ไข</a>
							<!-- <a href= "#" class="btn btn-info act-view" data-id=<?=$model->id?>><i class="fa fa-eye"></i> ดู</a> -->
							
								<?= Html::a('<i class="fa fa-remove"></i> ลบ',['cletter/delete','id' => $model->id],
									[
										'name'=>'Yii::$app->request->csrfParam',
										'value'=>'Yii::$app->request->csrfToken',
										'class' => 'btn btn-danger act-update',
										'data-confirm' => 'Are you sure to delete this item?',
										'data-method' => 'post',
									]);
							?>
							
						</td>
													
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