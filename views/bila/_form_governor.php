<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use app\models\User;
use app\models\Bila;
use kartik\select2\Select2;
use kartik\date\DatePicker;
use yii\helpers\ArrayHelper;

$this->title = 'ไปราชการ';
$this->params['breadcrumbs'][] = ['label' => 'โปรแกรมใบลา', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="box box-danger">
    <div class="box-header with-border">
        <h3 class="box-title">ไปราชการ</h3>
    </div>

    <?php 
    $form = ActiveForm::begin([
		'id' => 'weblink-form',
		'options' => [
            'class' => 'smart-form',
            'novalidate'=>'novalidate',
            'enctype' => 'multipart/form-data'
        ],
        //'layout' => 'horizontal',
        'fieldConfig' => [
            //'template' => "{label}{input}{error}",
            'labelOptions' => ['class' => 'label'],
        ],
        'enableAjaxValidation' => true,
	]);  ?>
    
    <?= $form->field($model, 't1')->hiddenInput(['readonly' => true, 'value' => 99])->label(false) ?>
    
    
    <div class="box-body">
        <div class="row">
            <div class ="col-md-6">
                <div class="form-group">
                    <?php 
                        echo $form->field($model, 'user_id')->widget(Select2::classname(), [
                                'data' => Bila::getProfileList(),
                                'language' => 'th',
                                'options' => ['class'=>'form-control','placeholder' => ' เลือก ผู้ไปราชการ'],
                                'pluginOptions' => [
                                'allowClear' => true
                                ],
                            ]);
                    ?>
                </div>
            </div>
		</div>	
        <div class="row">
            <div class ="col-md-5">
                <div class="form-group">
                    <?= $form->field($model, 'date_begin')->widget(DatePicker::classname(), [
                        'options' => ['class'=>'form-control','placeholder' => 'ตั้งแต่วันที่'],
                        'pluginOptions' => [
                            'autoclose'=>true,
                            'format' => 'yyyy-mm-dd'
                        ]]);
                    ?>
                </div>
            </div>
			<div class ="col-md-5">
                <div class="form-group">		
				    <?= $form->field($model, 'date_end')->widget(DatePicker::classname(), [
                            'options' => ['class'=>'form-control','placeholder' => 'ถึงวันที่'],
                            'pluginOptions' => [
                                'autoclose'=>true,
                                'format' => 'yyyy-mm-dd'
                            ]]);
                    ?>
				</div>
            </div>
            <div class ="col-md-2">
                <div class="form-group">
                    <?= $form->field($model, 'date_total', [
                            'inputOptions' => [
                                'class'=>'form-control',
                                'placeholder' => $model->getAttributeLabel('date_total')
                            ],
                            // 'template' => '<section class="col col-2"><label class="input">{label}</label> <label class="input">{input}<b class="tooltip tooltip-top-right">'.$model->getAttributeLabel('date_total').'</b></label><em for="name" class="invalid">{error}{hint}</em></section>'
                        ]);
                    ?>
				</div>
            </div>
       
       
            <div class ="col-md-12">
                <div class="form-group">
                    <?php
                        echo $form->field($model, 'comment', [
                            'inputOptions' => [
                                'class'=>'form-control',
                                'placeholder' => $model->getAttributeLabel('comment'),
                            ],
                            // 'template' => '<section class="col col-6"><label class="input">{label}</label> <label class="input">{input}<b class="tooltip tooltip-top-right">'.$model->getAttributeLabel('comment').'</b></label><em for="name" class="invalid">{error}{hint}</em></section>'
                        ]);
                    ?>
                </div> 
            </div>
        </div>
        
        <div class="text-right"> 
            <?php Html::resetButton('Reset', ['class' => 'btn btn-warning btn-lg']) ?>
            <?= Html::submitButton('Save', ['class' => 'btn btn-primary btn-lg']) ?>
        </div>
    <?php ActiveForm::end(); ?>
    </div>
</div>
