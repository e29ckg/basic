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
        ]);  ?>

        <div class="box-body">
            <div class="row">
                <div class ="col-md-4">
                    <div class="form-group">			
                        <?= $form->field($model, 'line_alert')->widget(DatePicker::classname(), [
                            'options' => [
                                'class'=>'form-control',
                                'placeholder' => 'วันที่'
                            ],
                            'pluginOptions' => [
                                'autoclose'=>true,
                                'format' => 'yyyy-mm-dd'
                            ]])->label(false);
                        ?>
                    </div>
                </div>
                <div class ="col-md-4">
                    <div class="form-group">			
                    <?php 
                        echo $form->field($model, 'user_id')->widget(Select2::classname(), [
                            'data' => $model->getUserList(),
                            'language' => 'th',
                            'options' => ['class'=>'form-control','placeholder' => 'เวร'],
                            'pluginOptions' => [
                                'allowClear' => true
                            ],
                        ])->label(false);

                        ?>
                    </div>
                </div>
                <div class ="col-md-4">
                    <div class="form-group">			
                    <?php 
                        echo $form->field($model, 'user_id2')->widget(Select2::classname(), [
                            'data' => $model->getUserList(),
                            'language' => 'th',
                            'options' => ['class'=>'form-control','placeholder' => 'ผู้ตรวจ'],
                            'pluginOptions' => [
                                'allowClear' => true
                            ],
                        ])->label(false);

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
