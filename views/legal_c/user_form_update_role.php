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

$this->title = 'กำหนดสิทธิ์';
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
			<div class="box-header with-border">
				<h3 class="box-title"><?= Html::encode($this->title). ' : '.$model->username ?></h3>
				<div class="box-tools pull-right">
						<!-- <button id="act-create-a" class="btn btn-danger btn-md" alt="act-create-a"><i class="fa fa-plus "></i> เพิ่มใบลาป่วย </button>  
						<button id="act-create-b" class="btn btn-primary btn-md" alt="act-create"><i class="fa fa-plus "></i> เพิ่มใบลาพักผ่อน</button> -->
				</div>			
			</div>
			<div class="box-body">
				<div class="row">	
					<div class="col-md-3"></div>
					<div class ="col-md-6">
						<div class="form-group">			
							
							<?php 
								echo $form->field($model, 'role')->widget(Select2::classname(), [
									'data' => User::getRoleList(),
									'language' => 'th',
									'options' => [
										'class' => 'form-control',
										'placeholder' => ' เลือก สิทธ์'
									],
									'pluginOptions' => [
										'allowClear' => true
									],
								]);
                    		?>				
						</div>
					</div>
				</div>
					
				<div class="row">	
					<div class="col-md-3"></div>
					<div class ="col-md-6">
						<div class="form-group text-center">
        					<?= Html::submitButton('บันทึก', ['class' => 'btn btn-block btn-success btn-lg']) ?>
						</div>
					</div>
				</div>
			</div>
			<!-- box-boby -->
			
		</div>
	</div>
</div>
<?php ActiveForm::end(); ?>