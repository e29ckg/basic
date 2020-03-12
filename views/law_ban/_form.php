<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use kartik\select2\Select2;
use kartik\date\DatePicker;
use yii\helpers\ArrayHelper;
use kartik\file\FileInput;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'ทนายความ-ห้ามทำการ';
$this->params['breadcrumbs'][] = ['label' => 'จัดการที่ปรึกษากฎหมาย', 'url' => ['legal_c/index']];
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
					
					<div class ="col-md-12">
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
					<div class ="col-md-12">
						<div class="form-group">			
							<?= $form->field($model, 'license', [
								'inputOptions' => [
									'placeholder' => $model->getAttributeLabel('license'),
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
						<div class="form-group">			
							<?= $form->field($model, 'ban', [
								'inputOptions' => [
									'placeholder' => $model->getAttributeLabel('ban'),
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
							echo $form->field($model, 'file')->widget(FileInput::classname(), [
								'options' => [
									// 'accept' => 'image/*',
									'class' => 'form-control',
								],
								// 'pluginOptions' => ['previewFileType' => 'any', 'uploadUrl' => Url::to(['/site/file-upload']),]
							]);
						?>
					</div>
				</div>
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

		
		
});
JS;
$this->registerJs($script);
?>

