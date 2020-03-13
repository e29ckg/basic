<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
// use yii\helpers\Url;
use kartik\select2\Select2;
use kartik\date\DatePicker;
use yii\helpers\ArrayHelper;
use kartik\datetime\DateTimePicker;
use kartik\file\FileInput;

$this->title = 'เพิ่ม';
$this->params['breadcrumbs'][] = ['label' => 'conf', 'url' => ['index']];
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
            <div class="col-md-12">
                <div class="form-group">
                    <?= $form->field($model, 'cname', [
                        'inputOptions' => [
                            'placeholder' => $model->getAttributeLabel('cname'),
                                'class'=>'form-control'
                            ],
                        ]);
                    ?>
                </div>
            </div>
           
            <div class="col-md-12">
                <div class="form-group">
                    <?= $form->field($model, 'title', [
                        'inputOptions' => [
                            'placeholder' => $model->getAttributeLabel('title'),
                                'class'=>'form-control',
                                'value' => $model->title ? $model->title : 'ขอใช้ห้องประชุมทางจอภาพ Web Conference',
                            ],
                        ]);
                    ?>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group text-center">
                    <?= $form->field($model, 'start')->widget(DateTimePicker::classname(), [
                            'options' => [
                                'class'=>'form-control',
                                'placeholder' => $model->getAttributeLabel('start'),
                                // 'value' => date()
                            ],
                            'pluginOptions' => [
                                'autoclose'=>true,
                                'format' => 'yyyy-mm-dd HH:ii'
                            ]]);
                    ?>
                </div> 
            </div>
            
            <div class="col-md-6">
                <div class="form-group text-center">
                    <?= $form->field($model, 'end')->widget(DateTimePicker::classname(), [
                            'options' => [
                                'class'=>'form-control',
                                'placeholder' => $model->getAttributeLabel('end'),
                            ],
                            'pluginOptions' => [
                                'autoclose'=>true,
                                'format' => 'yyyy-mm-dd HH:ii'
                            ]]);
                    ?>
                </div> 
            </div>
            <hr>        
            <div class="col-md-12">
                <div class="form-group">
                    <?= $form->field($model, 'detail', [
                        'inputOptions' => [
                            'placeholder' => $model->getAttributeLabel('detail'),
                                'class'=>'form-control'
                            ],
                        ]);
                    ?>
                </div>
            </div>
            
            <div class="col-md-12">
                <div class="form-group">
                    <?= $form->field($model, 'fname', [
                        'inputOptions' => [
                            'placeholder' => $model->getAttributeLabel('fname'),
                                'class'=>'form-control'
                            ],
                        ]);
                    ?>
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    <?= $form->field($model, 'tel', [
                        'inputOptions' => [
                            'placeholder' => $model->getAttributeLabel('tel'),
                                'class'=>'form-control'
                            ],
                        ]);
                    ?>
                </div>
            </div>
            <div class ="col-md-12">
                <div class="form-group">
                    <?=  $form->field($model, 'file')->widget(FileInput::classname(), [
                        'options' => [
                        // 'accept' => 'image/*',
                        'class' => 'form-control'],
                        // 'pluginOptions' => ['previewFileType' => 'any', 'uploadUrl' => Url::to(['/site/file-upload']),]
                     ]);
                    ?>

                </div>
            </div>
        </div>       
        
        <div class="text-center">             
            <?= Html::submitButton('Save', ['class' => 'btn btn-primary btn-lg btn-block']) ?>
                            
        </div>
        
    </div>
    <?php ActiveForm::end(); ?>
</div>