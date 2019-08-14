<?php

use yii\helpers\Html;
use app\models\SignBossName;


/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'รายชื่อผู้ลงนาม';
$this->params['breadcrumbs'][] = $this->title;
?>
 <!-- Default box -->
<div class="box box-primary">
	<div class="box-header with-border">
		<h3 class="box-title"><?= Html::encode($this->title) ?></h3>
		<div class="box-tools pull-right">
			<button id="act-create" class="btn btn-danger btn-md" alt="act-create"><i class="fa fa-plus "></i> เพิ่ม </button>  
		</div>			
	</div>		
	<div class="box-body">
		<div id="wra_example1" class="dataTables_wrapper form-inline dt-bootstrap">
			<table id="example1" class="table table-striped table-bordered" width="100%">
				<thead>
					<tr>
						<th data-class="expand"> # </th>
						<th >ชื่อ-สกุล</th>
						<th data-hide="phone,tablet">ตำแหน่ง</th>
						<th data-hide="phone,tablet">ตำแหน่ง(บรรทัด2)</th>
						<th data-hide="phone,tablet">ตำแหน่ง(บรรทัด3)</th>
						<th data-hide="phone,tablet">สถานะ</th>
						<th style="width:120px"></th>
						
					</tr>
				</thead>
				<tbody>  
					<?php $i = 1?>                              
					<?php foreach ($models as $model): ?>
					<tr>
						<td><?= $i++?></td>
						
						<td><?=$model->name?></td>
						<td><?=$model->dep1?></td>		
						<td><?=$model->dep2?></td>	
						<td><?=$model->dep3?></td>	
						<td><?=SignBossName::getStName($model->status);?></td>
						<td>
							<a href="#" class="act-update btn btn-info btn-xs" data-id=<?=$model['id']?>>แก้ไข</a> 
							<?= Html::a('<i class="fa fa-remove"></i> ลบ',['bila/sbn_delete','id' => $model->id],
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

		var url_update = "sbn_update";
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
			
	$('#activity-modal').on('hidden.bs.modal', function () {
 		location.reload();
	})

				

/* END COLUMN FILTER */  

		var url_create = "sbn_create";
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
		responsive: true
	})
		
});
JS;
$this->registerJs($script);
?>
