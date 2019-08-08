<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use kartik\file\FileInput;

/* @var $this yii\web\View */
/* @var $model app\models\WebLink */
/* @var $form yii\widgets\ActiveForm */
?>
<div class="cletter-form">
    <div class="box box-danger">
        <div class="box-header with-border">
            <h3 class="box-title"><?= $this->title ?></h3>
        </div>

        <?php 
        $form = ActiveForm::begin([
            'id' => 'weblink-form',
            'options' => [
                'class' => 'smart-form',
                'novalidate'=>'novalidate',
                'enctype' => 'multipart/form-data'
            ],
            //'layout' => 'horiz\ontal',
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
                    <?= $form->field($modelFile, 'name', [
                        'inputOptions' => [
                            'class'=>'form-control',
                            'placeholder' => $modelFile->getAttributeLabel('name')
                        ],
                        // 'template' => '<section class=""><label class="label">{label}</label> <label class="input"> <i class="icon-append fa fa-user"></i>{input}<b class="tooltip tooltip-top-right">'.$modelFile->getAttributeLabel('name').'</b></label><em for="name" class="invalid">{error}{hint}</em></section>'
                        ]);
                    ?>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class ="col-md-12">
                    <div class="form-group">
                        <?php
                        echo $form->field($modelFile, 'file')->widget(FileInput::classname(), [
                            // 'options' => ['accept' => 'image/*'],
                            // 'pluginOptions' => ['previewFileType' => 'any', 'uploadUrl' => Url::to(['/site/file-upload']),]
                        ]);
                        ?>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class ="col-md-12">
                    <div class="form-group">
                        <fieldset class="text-right"> 
                            <?= Html::submitButton('Save', ['class' => 'btn btn-block btn-success btn-lg']) ?>
                        </fieldset>
                    </div>
                </div>
            </div>
        </div>
    <?php ActiveForm::end(); ?>
    </div>
</div>
