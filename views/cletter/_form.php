<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use kartik\select2\Select2;
use kartik\file\FileInput;

/* @var $this yii\web\View */
/* @var $model app\models\CLetter */
/* @var $form yii\widgets\ActiveForm */

?>

<div class="cletter-form">
    <div class="box box-danger">
        <div class="box-header with-border">
            <h3 class="box-title"><?= $this->title ?></h3>
        </div>
        <?php 
        $form = ActiveForm::begin([
            'id' => 'cletter-form',
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

        <div class="box-body">
            <div class="row">
                <div class ="col-md-8">
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
                <div class ="col-md-4">
                    <div class="form-group">			
                    <?php 
                        echo $form->field($model, 'caid')->widget(Select2::classname(), [
                            'data' => $model->getCaidList(),
                            'language' => 'th',
                            'options' => ['class'=>'form-control','placeholder' => ' เลือกประเภทหนังสือ...'],
                            'pluginOptions' => [
                                'allowClear' => true
                            ],
                        ])->label(false);

                        ?>
                    </div>
                </div>
                <div class ="col-md-12">
                    <div class="form-group">			
                    <?php
                        echo $form->field($model, 'file')->widget(FileInput::classname(), [
                            'options' => [
                                'class'=>'form-control',
                                // 'accept' => 'image/*'
                            ],
                        ]);
                        ?>
                    </div>
                </div>
            </div>
            <div class='row'>
            <div class ="col-md-12">
                <div class="form-group">
                    <?= Html::submitButton('Save', ['class' => 'btn btn-block btn-success btn-lg']) ?>
                </div>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
    </div>    
</div>
