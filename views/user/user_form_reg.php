<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use app\models\User;
use kartik\select2\Select2;
use kartik\date\DatePicker;
use yii\helpers\ArrayHelper;
use kartik\file\FileInput;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'เพิ่มสมาชิก';
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
		'enableAjaxValidation' => true,
	]);  
?>

<div class="row">
	<div class ="col-md-12">		
 <!-- Default box -->
		<div class="box box-primary">
			
			<div class="box-body">
				<div class="row">	
					<!-- <div class ="col-md-1">
					
					</div>				 -->
					<div class ="col-md-4">
					<?= $form->field($model, 'username',[
						'inputOptions' => [
							'class' => 'form-control',
							'placeholder' => $model->getAttributeLabel('username')
						],
						// 'template' => '<section class=""><label class="label">{label}</label> <label class="input"> <i class="icon-append fa fa-user"></i>{input}<b class="tooltip tooltip-top-right">'.$model->getAttributeLabel('username').'</b></label><em for="name" class="invalid">{error}{hint}</em></section>'
						])->textInput(['maxlength' => true]) ?>
					</div>
					<!-- <div class ="col-md-1">
					
					</div> -->
					<div class ="col-md-4">
						<div class="form-group">			
							<?= $form->field($model, 'pwd1', [
								'inputOptions' => [
									'placeholder' => $model->getAttributeLabel('pwd1'),
									'class' => 'form-control',
									'type' => 'password'
								],
								// 'template' => '{label}{input}{error}{hint}'
							]);
							?>					
						</div>
					</div>
					<div class ="col-md-4">
						<div class="form-group">			
							<?= $form->field($model, 'pwd2', [
								'inputOptions' => [
									'placeholder' => $model->getAttributeLabel('pwd2'),
									'class' => 'form-control',
									'type' => 'password'
								],
								// 'template' => '{label}{input}{error}{hint}'
							]);
							?>					
						</div>
					</div>
													
				</div>
				<hr>
				<div class="row">
					<div class ="col-md-1">
					</div>
					<div class ="col-md-3">
						<div class="form-group">			
							
							<?php 
								echo $form->field($model, 'fname')->widget(Select2::classname(), [
									'data' => User::getFnameList(),
									'language' => 'th',
									'options' => [
										'class' => 'form-control',
										'placeholder' => ' เลือก คำนำหน้าชื่อ'
									],
									'pluginOptions' => [
										'allowClear' => true
									],
								]);
                    		?>				
						</div>
					</div>
					<div class ="col-md-4">
						<div class="form-group">			
							<?= $form->field($model, 'name', [
								'inputOptions' => [
									'placeholder' => $model->getAttributeLabel('name'),
									'class' => 'form-control',
									// 'type' => 'password'
								],
								// 'template' => '{label}{input}{error}{hint}'
							]);
							?>					
						</div>
					</div>
					<div class ="col-md-4">
						<div class="form-group">			
							<?= $form->field($model, 'sname', [
								'inputOptions' => [
									'placeholder' => $model->getAttributeLabel('sname'),
									'class' => 'form-control',
									// 'type' => 'password'
								],
								// 'template' => '{label}{input}{error}{hint}'
							]);
							?>					
						</div>
					</div>
				</div>
				<div class="row">					
					<div class ="col-md-4">
						<div class="form-group">			
							<?= $form->field($model, 'id_card', [
								'inputOptions' => [
									'placeholder' => $model->getAttributeLabel('id_card'),
									'class' => 'form-control',
									// 'type' => 'password'
								],
								// 'template' => '{label}{input}{error}{hint}'
							]);
							?>					
						</div>
					</div>
					<div class ="col-md-4">
						<div class="form-group">			
							<?php
							// echo $form->field($model, 'dep', [
							// 	'inputOptions' => [
							// 		'placeholder' => $model->getAttributeLabel('dep'),
							// 		'class' => 'form-control',
							// 		// 'type' => 'password'
							// 	],
							// 	// 'template' => '{label}{input}{error}{hint}'
							// ]);
							?>	
							<?php 
								echo $form->field($model, 'dep')->widget(Select2::classname(), [
									'data' => User::getDepList(),
									'language' => 'th',
									'options' => [
										'class' => 'form-control',
										'placeholder' => ' เลือก ตำแหน่ง'
									],
									'pluginOptions' => [
										'allowClear' => true
									],
								]);
                    		?>				
						</div>
					</div>
					<div class ="col-md-4">
						<div class="form-group">			
							<?= $form->field($model, 'birthday')->widget(DatePicker::classname(), [
								'options' => [
									'placeholder' => $model->getAttributeLabel('birthday'),
									'class' => 'form-control',
								],
								'pluginOptions' => [
									'autoclose'=>true,
									'format' => 'yyyy-mm-dd'
								]]);
							?>				
						</div>
					</div>
					<div class ="col-md-4">
						<div class="form-group">			
							<?= $form->field($model, 'address', [
								'inputOptions' => [
									'placeholder' => $model->getAttributeLabel('address'),
									'class' => 'form-control',
									// 'type' => 'password'
								],
								// 'template' => '{label}{input}{error}{hint}'
							]);
							?>					
						</div>						
					</div>
					<div class ="col-md-4">
						<div class="form-group">			
							<?= $form->field($model, 'phone', [
								'inputOptions' => [
									'placeholder' => $model->getAttributeLabel('phone'),
									'class' => 'form-control',
									// 'type' => 'password'
								],
								// 'template' => '{label}{input}{error}{hint}'
							]);
							?>					
						</div>
					</div>
					<div class ="col-md-4">
						<div class="form-group">			
							<?= $form->field($model, 'email', [
								'inputOptions' => [
									'placeholder' => $model->getAttributeLabel('email'),
									'class' => 'form-control',
									// 'type' => 'password'
								],
								// 'template' => '{label}{input}{error}{hint}'
							]);
							?>					
						</div>
					</div>

				</div>
				<div class="row">
					<div class ="col-md-12">
						<?php
							echo $form->field($model, 'img')->widget(FileInput::classname(), [
								'options' => ['accept' => 'image/*','class' => 'form-control',],
								// 'pluginOptions' => ['previewFileType' => 'any', 'uploadUrl' => Url::to(['/site/file-upload']),]
							]);
						?>
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

