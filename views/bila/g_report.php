<?php

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'G_report';
$this->params['breadcrumbs'][] = $this->title;
?>
 <!-- Default box -->
<div class="box box-primary">
	<div class="box-header with-border">
		<h3 class="box-title"><?= Html::encode($this->title)?></h3>
		<div class="box-tools pull-right">
			<button id="act-create-a" class="btn btn-danger btn-md" alt="act-create-a"><i class="fa fa-pencil-square-o "></i> เขียนใบลาป่วย ลากิจ ลาคลอด</button>  
			<button id="act-create-b" class="btn btn-primary btn-md" alt="act-create"><i class="fa fa-pencil-square-o "></i> เขียนใบลาพักผ่อน</button>
		</div>			
	</div>		
	<div class="box-body">
		<div id="wra_example1" class="dataTables_wrapper form-inline dt-bootstrap">
				
			<table id="example1" class="table table-bordered table-striped dataTable" role="grid" aria-describedby="example1_info">
			<thead>
				<tr role="row">
					<th class = "text-center" style="width: 100px;">ประเภทการลา</th>
					<th class = "text-center" style="width: 500px;">รายละเอียด</th>
					<th style="width: 100px;"></th>
				</tr>
			</thead>
			<tbody>  
				<?php $i = 1?>                              
				<?php foreach ($models as $model): ?>
				<tr>						                
										<td>
											<?=$model->getProfileName();?> 
											<?=$model->cat == 'ไปราชการ' ? '<span class="label label-info">'.$model->cat.'</span>' : '';?>
											<?=$model->cat == 'ลาป่วย' || $model->cat == 'ลากิจส่วนตัว' || $model->cat == 'ลาคลอดบุตร' ? '<span class="label label-danger">'.$model->cat.'</span>' : '' ; ?>
											<?=$model->cat == 'ลาพักผ่อน' ? '<span class="label label-primary">'.$model->cat.'</span>' : '' ;?>											
											<br><?= isset($model->running) ? $model->running : $model->id;?>
										</td>										
                                        <td><?=$model->DateThai_full($model->date_begin)?>
											ถึง <?=$model->DateThai_full($model->date_end)?>
											<br> ลาครั้งนี้ <?=$model->date_total?> วัน
											<?= $model->status == 4 ? '<span class="label label-danger">ยกเลิกการลา</span>' : '' ;?>
										</td>
										<td class = "text-center">
											<?= !empty($model->file) ? 
											Html::a('ไฟล์เอกสาร', ['bila/file_view','id' => $model->id], [
												
												'data-id' => $model->id,
												'target' => '_blank'
											]).' '	
											.Html::a('<i class="fa fa-remove"></i> ลบไฟล์ ', ['bila/file_del','id' => $model->id], [
												'class' => 'btn btn-danger btn-block btn-xs',
												'data-id' => $model->id,
												'data-confirm' => 'Are you sure to delete this item?',
                                    			'data-method' => 'post',
											]) 																			
											:
											Html::a('<i class="fa fa-print"></i> แนบไฟล์ ', '#', [
												'class' => 'act-file-up btn btn-success btn-xs',
												'data-id' => $model->id,
												// 'target' => '_blank'
											]).' '
											
											. ' <a href= "#" class="btn btn-warning btn-xs act-update" data-id='.$model->id.'><i class="fa fa-wrench"></i> แก้ไข</a>';
							
											;?>
											<!-- <a href="#" class="act-file-up btn btn-danger btn-xs" data-id=<?=$model->id?>>แนบไฟล์</a>  -->
																				
											<?=
											!($model->cat == 'ไปราชการ') ? 
											Html::a('<i class="fa fa-print"></i> Print ', ['bila/print1','id' => $model->id], [
												'class' => 'btn btn-primary btn-xs',
												'data-id' => $model->id,
												'target' => '_blank'
											]) :'';
											?>
											<?=
											' '.
											 	Html::a('<i class="fa fa-remove"></i> ลบ',['bila/delete_admin','id' => $model->id],
													[
														'class' => 'btn btn-danger btn-xs',
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
//var_dump($models );
$script = <<< JS
    
$(document).ready(function() {	
/* BASIC ;*/	


	function init_click_handlers(){    

		var url_show = "show";				
			$( ".act-show" ).click(function() {
				var fID = $(this).data("id");
        	$.get(url_show,{id: fID},function (data){
                $("#activity-modal").find(".modal-body").html(data);
                $(".modal-body").html(data);
                $(".modal-title").html("show");
            	// $(".modal-footer").html(footer);
                $("#activity-modal").modal("show");
                //   $("#myModal").modal('toggle');
        	});     
		});

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
		var url_create_a = "create_a";
    	$( "#act-create-a" ).click(function() {
        	$.get(url_create_a,function (data){
                $("#activity-modal").find(".modal-body").html(data);
                $(".modal-body").html(data);
                $(".modal-title").html("เพิ่มข้อมูล");
            	// $(".modal-footer").html(footer);
                $("#activity-modal").modal("show");
                //   $("#myModal").modal('toggle');
        	});     
		}); 
		var url_create_b = "create_b";
    	$( "#act-create-b" ).click(function() {
        	$.get(url_create_b,function (data){
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
	$('#example2').DataTable({
		'paging'      : true,
		'lengthChange': false,
		'searching'   : true,
		'ordering'    : true,
		'info'        : true,
		'autoWidth'   : false
		})
		
});
JS;
$this->registerJs($script);
?>

