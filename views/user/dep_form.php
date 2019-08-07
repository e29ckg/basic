<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'ตำแหน่ง';
$this->params['breadcrumbs'][] = ['label' => 'จัดการสมาชิก', 'url' => ['user/index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?php 
	$form = ActiveForm::begin([
		'id' => 'weblink-form',
		'options' => [
			// 'class' => 'smart-form',
			'novalidate'=>'novalidate',
			'enctype' => 'multipart/form-data'
		],
		//'layout' => 'horizontal',
		'fieldConfig' => [
			//'template' => "{label}{input}{error}",
			'labelOptions' => ['class' => 'label'],
		],
		// 'enableAjaxValidation' => true,
	]);  
?>

<div class="row">
	<div class ="col-md-12">		
 <!-- Default box -->
		<div class="box box-primary">
			<div class="box-header with-border">
				<h3 class="box-title"><?= Html::encode($this->title) ?></h3>
				<div class="box-tools pull-right">
					<!-- <button id="act-create-b" class="btn btn-primary btn-md" alt="act-create"><i class="fa fa-plus "></i> เพิ่ม<button> --> 
				</div>			
			</div>
			<div class="box-body">
				<div class="row">	
					<div class ="col-md-1">
					
					</div>				
					<div class ="col-md-4">
					<?= $form->field($model, 'name',[
						'inputOptions' => [
							'class' => 'form-control',
							'placeholder' => $model->getAttributeLabel('name')
						],
						// 'template' => '<section class=""><label class="label">{label}</label> <label class="input"> <i class="icon-append fa fa-user"></i>{input}<b class="tooltip tooltip-top-right">'.$model->getAttributeLabel('username').'</b></label><em for="name" class="invalid">{error}{hint}</em></section>'
						])->textInput(['maxlength' => true]) ?>
					</div>
													
				</div>
				<hr>
				<div class="row">	
					<div class ="col-md-12">
						<div class="form-group text-center">
        					<?= Html::submitButton('บันทึก', ['class' => 'btn btn-block btn-success btn-lg']) ?>
						</div>
					</div>
				</div>
			</div>
			<!-- box-boby -->
			
		</div>
	</div>
	<!-- <div class ="col-md-2">
		
		
	</div> -->
</div>
<?php ActiveForm::end(); ?>
<?php

$script = <<< JS
    
$(document).ready(function() {	
/* BASIC ;*/	
$('#example1').DataTable()
$('#example2').DataTable({
      'paging'      : true,
      'lengthChange': false,
      'searching'   : true,
      'ordering'    : true,
      'info'        : true,
      'autoWidth'   : false
    })

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
		var url_createb = "bila/createb";
    	$( "#act-create-b" ).click(function() {
        	$.get(url_createb,function (data){
                $("#activity-modal").find(".modal-body").html(data);
                $(".modal-body").html(data);
                $(".modal-title").html("เพิ่มข้อมูล");
            	// $(".modal-footer").html(footer);
                $("#activity-modal").modal("show");
                //   $("#myModal").modal('toggle');
        	});     
		}); 
		
});
JS;
$this->registerJs($script);
?>

