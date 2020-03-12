<?php

use yii\helpers\Html;
use yii\bootstrap\Modal;
use yii2mod\alert\Alert;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $searchModel backend\models\ProfileSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'ทนายโดนแบน';
$this->params['breadcrumbs'][] = $this->title;
?>

<!-- MAIN CONTENT -->

<div class="row">
    <div class="col-xs-12">
       	<div class="box">
           	<div class="box-header">
				<h3 class="box-title"><?=$this->title;?></h3>
				<div class="box-tools pull-right">
					<button id="act-create" class="btn btn-danger btn-md" alt="act-create"><i class="fa fa-plus "></i> เพิ่ม</button> 
				</div>
			</div>
			   
            <!-- /.box-header -->
            <div class="box-body">
              	<div id="example1_wrapper" class="dataTables_wrapper form-inline dt-bootstrap">				  
					<div class="row">
						<div class="col-sm-12">
							<table id="example1" class="table table-striped table-bordered" width="100%">                                        
								<thead>			                
									<tr>
										<!-- <th data-class="expand">ID</th>											 -->
										
										<th >ชื่อ-สกุล</th>
										<th >เลขที่ใบอนุญาต</th>
										<th >ระยะเวลาห้ามทำการเป็นทนายความ</th>
										<th ></th>
									</tr>
								</thead>                                        
								<tbody>
									<?php foreach ($models as $model): ?>
									<tr>
										<td ><?= $model->name ?></td>																		
										<td ><?= $model->license ?></td>										
										<td ><?= $model->ban ?>
											<?= $model->file ? 
												'<a href="#" class="act-show" data-id="'.$model->id.'"><i class="fa fa-file-o" aria-hidden="true"></i></a>'
												:
												'';?>
										</td>
										<td>
											<?= Html::label('แก้ไข', 'update', [
												'class' => 'btn btn-success btn-xs act-update',
												'data-id' => $model->id]) ?>																					
											
											<?= Html::a('ลบ', ['del', 'id' => $model->id], ['class' => 'btn btn-danger btn-xs','data-confirm'=>'Are you sure to ยกเลิก this item?']) ?>
												
										</td>
									</tr>
									<?php  endforeach; ?>
								</tbody>
							</table>
						</div>
					</div>
				</div>
		    </div>
		</div>
	</div>		
</div>

<?php
$script = <<< JS
     
$(document).ready(function() {
	
	function init_click_handlers(){  

		var url_update = "update";
    	$(".act-update").click(function(e) {            
			var fID = $(this).data("id");
			// alert(fID);
        	$.get(url_update,{id: fID},function (data){
            	$("#activity-modal").find(".modal-body").html(data);
            	$(".modal-body").html(data);
            	$(".modal-title").html("แก้ไขข้อมูล ");
            	$("#activity-modal").modal("show");
        	});
    	});	
	}

	var url_show = "show";
	$( ".act-show" ).click(function() {
		var fID = $(this).data("id");
			// alert(fID);
        	$.get(url_show,{id: fID},function (data){
			$("#activity-modal").find(".modal-body").html(data);
			$(".modal-body").html(data);
			$(".modal-title").html("show");
			// $(".modal-footer").html(footer);
			$("#activity-modal").modal("show");
			//   $("#myModal").modal('toggle');
		});     
	}); 

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
			"pageLength": 50,
			'ordering'    : false,
			'lengthChange': true,
			'paging'      : true,
    		// "order": [[ 0, 'desc' ]]
		})
	

	
});
JS;
$this->registerJs($script);
?>