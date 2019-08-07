<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use app\models\User;
use app\models\Bila;
use kartik\select2\Select2;
use kartik\date\DatePicker;
use yii\helpers\ArrayHelper;

$this->title = 'เขียนใบลาพักผ่อน';
$this->params['breadcrumbs'][] = ['label' => 'โปรแกรมใบลา', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="box box-danger">
    <div class="box-header with-border">
        <h3 class="box-title">ใบลาพักผ่อน</h3>
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
    <?= $form->field($model, 'user_id')->hiddenInput(['readonly' => true, 'value' => Yii::$app->user->identity->id])->label(false) ?>
    <?= $form->field($model, 'date_create')->hiddenInput(['readonly' => true, 'value' => date("Y-m-d")])->label(false) ?>

    <div class="box-body">
        <div class="row">
            <div class ="col-md-3">
                <div class="form-group">
                    <?= $form->field($model, 'p1', [
                            'inputOptions' => [
                                'class'=>'form-control',
                                'placeholder' => $model->getAttributeLabel('p1'),
                                // 'value' => $p1
                            ],
                            // 'template' => '<section class="col col-3"><label class="input">{label}</label> <label class="input">{input}<b class="tooltip tooltip-top-right">'.$model->getAttributeLabel('p1').'</b></label><em for="name" class="invalid">{error}{hint}</em></section>'
                        ]);
                    ?>
                </div>
            </div>
		</div>	
        <div class="row">
            <div class ="col-md-5">
                <div class="form-group">
                    <?= $form->field($model, 'date_begin')->widget(DatePicker::classname(), [
                        'options' => ['class'=>'form-control','placeholder' => 'ลาตั้งแต่วันที่'],
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
        </div>
        <hr>
        <div class="row">
            <div class ="col-md-2">
                <div class="form-group">                    
                    <?= $form->field($model, 't1', [
                            'inputOptions' => [
                                'class'=>'form-control',
                                'placeholder' => $model->getAttributeLabel('t1'),
                                // 'value'=> $t3,
                            ],
                            // 'template' => '<section class="col col-3"><label class="input">{label}</label> <label class="input">{input}<b class="tooltip tooltip-top-right">'.$model->getAttributeLabel('t1').'</b></label><em for="name" class="invalid">{error}{hint}</em></section>'
                        ]);
                    ?>
				</div>                
            </div>
            <div class ="col-md-5">
                <div class="form-group">
                    <?php
                        echo $form->field($model, 'address', [
                            'inputOptions' => [
                                'class'=>'form-control',
                                'placeholder' => $model->getAttributeLabel('address'),
                                // 'value' => User::getProfileAddressById(Yii::$app->user->identity->id)
                            ],
                            // 'template' => '<section class="col col-6"><label class="input">{label}</label> <label class="input">{input}<b class="tooltip tooltip-top-right">'.$model->getAttributeLabel('address').'</b></label><em for="name" class="invalid">{error}{hint}</em></section>'
                        ]);
                    ?>
                </div>
            </div>
            <div class ="col-md-5">
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
        <hr>
        <div class="row">
            <div class ="col-md-6">
                <div class="form-group">
                    <?php 
                        echo $form->field($model, 'po')->widget(Select2::classname(), [
                                'data' => Bila::getSignList(),
                                'language' => 'th',
                                'options' => ['class'=>'form-control','placeholder' => ' เลือก ผอ.'],
                                'pluginOptions' => [
                                'allowClear' => true
                                ],
                            ]);
                    ?>
                </div>
            </div>
            <div class ="col-md-5">
                <div class="form-group">
                    <?php 
                        echo $form->field($model, 'bigboss')->widget(Select2::classname(), [
                            'data' => Bila::getSignList(),
                            'language' => 'th',
                            'options' => ['class'=>'form-control','placeholder' => ' เลือก หัวหน้าศาลฯ'],
                            'pluginOptions' => [
                                'allowClear' => true
                            ],
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
