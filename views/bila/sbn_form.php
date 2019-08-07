<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model app\models\WebLink */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="box box-danger">
    
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
        ]);  ?>
      
    
    <div class="box-body">
        <div class="row">
            <div class ="col-md-12">
                <div class="form-group">	
                    <?= $form->field($model, 'name', [
                        'inputOptions' => [
                            'class'=>'form-control',
                            'placeholder' => $model->getAttributeLabel('name')
                        ],
                        // 'template' => '<section class=""><label class="label">{label}</label> <label class="input"> <i class="icon-append fa fa-user"></i>{input}<b class="tooltip tooltip-top-right">'.$model->getAttributeLabel('name').'</b></label><em for="name" class="invalid">{error}{hint}</em></section>'
                        ])->label(false);
                    ?>		
                </div>
            </div>
        </div>
        <div class="row">
            <div class ="col-md-12">
                <div class="form-group">
                    <?= $form->field($model, 'dep1', [
                        'inputOptions' => [
                            'class'=>'form-control',
                            'placeholder' => $model->getAttributeLabel('dep1')
                        ],
                        // 'template' => '<section class=""><label class="label">{label}</label> <label class="input"> <i class="icon-append fa fa-user"></i>{input}<b class="tooltip tooltip-top-right">'.$model->getAttributeLabel('dep1').'</b></label><em for="name" class="invalid">{error}{hint}</em></section>'
                        ])->label(false);
                    ?>
                </div>
            </div>
        </div>
        <div class="row">
            <div class ="col-md-12">
                <div class="form-group">
                    <?= $form->field($model, 'dep2', [
                        'inputOptions' => [
                            'class'=>'form-control',
                            'placeholder' => $model->getAttributeLabel('dep2')
                        ],
                        // 'template' => '<section class=""><label class="label">{label}</label> <label class="input"> <i class="icon-append fa fa-user"></i>{input}<b class="tooltip tooltip-top-right">'.$model->getAttributeLabel('dep2').'</b></label><em for="name" class="invalid">{error}{hint}</em></section>'
                        ])->label(false);
                    ?>
                </div> 
            </div> 
        </div> 
        <div class="row">
            <div class ="col-md-12">
                <div class="form-group">
                    <?= $form->field($model, 'dep3', [
                        'inputOptions' => [
                            'class'=>'form-control',
                            'placeholder' => $model->getAttributeLabel('dep2')
                        ],
                        // 'template' => '<section class=""><label class="label">{label}</label> <label class="input"> <i class="icon-append fa fa-user"></i>{input}<b class="tooltip tooltip-top-right">'.$model->getAttributeLabel('dep2').'</b></label><em for="name" class="invalid">{error}{hint}</em></section>'
                        ])->label(false);
                    ?>
                </div> 
            </div> 
        </div>
        <div class="row">
            <div class ="col-md-12">
                <div class="form-group">
                    <?php 
                        echo $form->field($model, 'status')->widget(Select2::classname(), [
                            'data' => [1 => 'ใช้งาน',4 => 'ยกเลิก'],
                            'language' => 'th',
                            'options' => ['class'=>'form-control','placeholder' => ' เลือกสถานะ'],
                            'pluginOptions' => [
                                'allowClear' => true
                                ],
                            ])->label(false);   

                    ?>
                </div>
            </div> 
        </div>
    </div>

    <fieldset class="text-right"> 
        <?= Html::submitButton('Save', ['class' => 'btn btn-primary btn-lg']) ?>
    </fieldset>

    <?php ActiveForm::end(); ?>

</div>
