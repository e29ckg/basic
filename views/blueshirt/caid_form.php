<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use app\models\Bila;
use kartik\select2\Select2;
use kartik\date\DatePicker;
use yii\helpers\ArrayHelper;


?>

<div class="box box-danger">
    <div class="box-header with-border">
        <h3 class="box-title"><?=$this->title?></h3>
    </div>
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
            
            <div class="col-md-8">
                <div class="form-group">
                    <?= $form->field($model, 'name', [
                        'inputOptions' => [
                            'placeholder' => $model->getAttributeLabel('name'),
                                'class'=>'form-control'
                            ],
                            // 'template' => '<div class="form-group"><label>{label}</label> {input}<b class="tooltip tooltip-top-right">'.$model->getAttributeLabel('date_total').'</b><em for="name" class="invalid">{error}{hint}</em></div>'
                        ]);
                    ?>
                </div>
            </div>
       
            <div class="col-md-4">
                <div class="form-group">
                    <?php 
                        echo $form->field($model, 'status')->widget(Select2::classname(), [
                            'data' => ['1' => 'ใช้งาน','4' => 'ยกเลิก'],
                            'language' => 'th',
                            'options' => [
                                'placeholder' => 'สถานะ',
                                'class' => 'form-control',
                            ],
                            'pluginOptions' => [
                                'allowClear' => true
                            ],
                        ]);
                    ?> 
                </div> 
            </div>
            
        </div>
        
        <hr> 
        
        <div class="text-right"> 
            <?= Html::submitButton('Save', ['class' => 'btn btn-block btn-success btn-lg']) ?>
        </div>
        
    </div>
    <?php ActiveForm::end(); ?>
</div>