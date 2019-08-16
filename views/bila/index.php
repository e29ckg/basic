<?php

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
function DateThai_full($strDate)
	{
		$strYear = date("Y",strtotime($strDate))+543;
		$strMonth= date("n",strtotime($strDate));
		$strDay= date("j",strtotime($strDate));
		$strHour= date("H",strtotime($strDate));
		$strMinute= date("i",strtotime($strDate));
		$strSeconds= date("s",strtotime($strDate));
		$strMonthCut = Array("","มกราคม","กุมภาพันธ์","มีนาคม","เมษายน","พฤษภาคม","มิถุนายน","กรกฎาคม",
                            "สิงหาคม","กันยายน","ตุลาคม","พฤศจิกายน","ธันวาคม");
		$strMonthThai=$strMonthCut[$strMonth];
		return "$strDay $strMonthThai $strYear";
    }

$this->title = 'โปรแกรมใบลา';
$this->params['breadcrumbs'][] = $this->title;
?>
 <!-- Default box -->
<div class="box box-primary">
	<div class="box-header with-border">
		<h3 class="box-title"><?= Html::encode($this->title) ?></h3>
		<div class="box-tools pull-right">
				<button id="act-create-a" class="btn btn-danger btn-md" alt="act-create-a"><i class="fa fa-pencil-square-o "></i> เขียนใบลาป่วย </button>  
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
					<td class="text-center" alt="<?=$model->id?>">
						<?=$model->cat?>
						<br><?=$model->id?>
											
					</td>										
					<td><?=DateThai_full($model->date_begin)?>
					ถึง <?=DateThai_full($model->date_end)?>
					ลาครั้งนี้ <?=$model->date_total?> วัน</td>
					<td>
					<?= !empty($model->file) ? 
						Html::a('<i class="fa fa-file-o"></i> ไฟล์เอกสาร ', ['bila/file_view','id' => $model->id], [
							// 'class' => 'btn btn-xs',
							'data-id' => $model->id,
							'target' => '_blank'
						])
						:
						 Html::a('<i class="fa fa-print"></i> Print ', ['bila/print1','id' => $model->id], [
							'class' => 'btn btn-primary btn-xs',
							'data-id' => $model->id,
							'target' => '_blank'
						]). ' '.
						Html::a('<i class="fa fa-wrench"></i> แก้ไข ', '#', [
							'class' => 'act-update btn btn-warning btn-xs',
							'data-id' => $model->id,
						]). ' '.
						 Html::a('<i class="fa fa-remove"></i> ลบ ', ['bila/delete', 'id' => $model->id], [
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

		var url_update = "bila/update";
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
		var url_create_a = "bila/create_a";
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
		var url_create_b = "bila/create_b";
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

