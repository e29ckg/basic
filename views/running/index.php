<?php

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Running Number';
$this->params['breadcrumbs'][] = $this->title;
?>
 <!-- Default box -->
<div class="box box-primary">
	<div class="box-header with-border">
		<h3 class="box-title"><?= Html::encode($this->title)?></h3>
		<div class="box-tools pull-right">
			<button id="act-create" class="btn btn-primary btn-md" alt="act-create"><i class="fa fa-pencil-square-o "></i> เพิ่ม</button>
		</div>			
	</div>		
	<div class="box-body">
		<div id="wra_example1" class="dataTables_wrapper form-inline dt-bootstrap">
				
			<table id="example1" class="table table-bordered table-striped dataTable" role="grid" aria-describedby="example1_info">
			<thead>
				<tr role="row">
					<th class = "text-center" >name</th>
					<th class = "text-center" >year</th>
					<th class = "text-center" >Run</th>
					<th style="width: 100px;"></th>
				</tr>
			</thead>
			<tbody>  
				<?php $i = 1?>                              
				<?php foreach ($models as $model): ?>
				<tr>
					<td class="text-center" alt="<?=$model->id?>">
						<?=$model->name?>											
					</td>										
					<td><?=$model->y?></td>
					<td><?=$model->r?></td>
					<td class="text-center"> 
					<?=
						Html::a('<i class="fa fa-wrench"></i> แก้ไข ', '#', [
							'class' => 'act-update btn btn-warning btn-xs',
							'data-id' => $model->id,
						]). ' '.
						 Html::a('<i class="fa fa-remove"></i> ลบ ', ['running/delete', 'id' => $model->id], [
							'class' => 'btn btn-danger btn-xs',
							'data-confirm' => 'Are you sure?',
							'data-method' => 'post',
						]) ?>			        
					</td>
				</tr>
				<?php  endforeach; ?>								
			</tbody>
		</table>
		</div>
	</div>
</div>

<?php
//var_dump($models );
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
            	$(".modal-title").html("แก้ไขข้อมูล");
            	$("#activity-modal").modal("show");
        	});
    	});   	
		
    
	}

    init_click_handlers(); //first run
			
	$('#activity-modal').on('hidden.bs.modal', function () {
 		location.reload();
	})				
		// var url_create = "index.php?r=bila/create";
		var url_create_a = "create";
    	$( "#act-create" ).click(function() {
        	$.get(url_create_a,function (data){
                $("#activity-modal").find(".modal-body").html(data);
                $(".modal-body").html(data);
                $(".modal-title").html("เพิ่มข้อมูล");
            	// $(".modal-footer").html(footer);
                $("#activity-modal").modal("show");
                //   $("#myModal").modal('toggle');
        	});     
		}); 
		
	$('#example1').DataTable({
		// 'order' 	: false,
		'paging'      : true,
		'lengthChange': false,
		'searching'   : true,
		'ordering'    : false,
		'info'        : true,
		'autoWidth'   : false
		})
		
});
JS;
$this->registerJs($script);
?>

