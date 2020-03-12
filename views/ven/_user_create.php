<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
// use yii\helpers\Url;
use kartik\select2\Select2;
use kartik\date\DatePicker;
use yii\helpers\ArrayHelper;
use kartik\file\FileInput;

$this->title = 'คำสั่ง';
$this->params['breadcrumbs'][] = ['label' => 'เวร', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
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
            <div class="col-md-2">
                <div class="form-group">
                    <?php 
                        echo $form->field($model, 'order', [
                            'inputOptions' => [
                                'placeholder' => $model->getAttributeLabel('order'),
                                    'class'=>'form-control'
                                ],
                            ]);
                    ?> 
                </div> 
            </div>
            <div class="col-md-4">
                <div class="form-group">
                <?= $form->field($model, 'user_id')->widget(Select2::classname(), [
                            'data' => $model::getUserList(),
                            'language' => 'th',
                            'options' => ['class'=>'form-control','placeholder' => ' เลือก..U'],
                            'pluginOptions' => [
                                'allowClear' => true
                            ],
                        ]);
                    ?>
                </div> 
            </div>
            <div class="col-md-4">
                <div class="form-group">
                <?= $form->field($model, 'DN')->widget(Select2::classname(), [
                            'data' => $model::getVen_DN(),
                            'language' => 'th',
                            'options' => ['class'=>'form-control','placeholder' => ' เลือก.'],
                            'pluginOptions' => [
                                'allowClear' => true
                            ],
                        ]);
                    ?> 
                    
                </div> 
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <?php 
                        echo $form->field($model, 'price', [
                            'inputOptions' => [
                                'placeholder' => $model->getAttributeLabel('price'),
                                    'class'=>'form-control'
                                ],
                            ]);
                    ?> 
                </div> 
            </div>
            
            
                        
        </div>           

        
        <div class="text-right">             
            <?= Html::submitButton('Save', ['class' => 'btn btn-primary btn-lg']) ?>
        </div>

        
    </div>
    <?php ActiveForm::end(); ?>
</div>