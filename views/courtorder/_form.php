<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use kartik\select2\Select2;
use kartik\date\DatePicker;
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
            ]);  
        ?>

        <div class="box-body">
            <div class="row">
                <div class ="col-md-12">
                    <div class="form-group">			
                        <?= $form->field($model, 'name', [
                                'inputOptions' => [
                                    'class'=>'form-control',
                                    'placeholder' => $model->getAttributeLabel('name')
                                ],
                            ]); // ->label(false);
                        ?>
                    </div>
                </div>
                <div class ="col-md-2">
                    <div class="form-group">			
                        <?= $form->field($model, 'num', [
                                'inputOptions' => [
                                    'class'=>'form-control',
                                    'placeholder' => $model->getAttributeLabel('num')
                                ],
                            ]); // ->label(false);
                        ?>
                    </div>
                </div>

                <div class ="col-md-5">
                    <div class="form-group">			
                        <?= $form->field($model, 'date_write')->widget(DatePicker::classname(), [
                            'options' => [
                                'class'=>'form-control',
                                'placeholder' => $model->getAttributeLabel('date_write')
                            ],
                            'pluginOptions' => [
                                'autoclose'=>true,
                                'format' => 'yyyy-mm-dd'
                            ]]);
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
