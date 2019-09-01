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
            <div class="col-md-4">
                <div class="form-group">
                    <?php 
                        echo $form->field($model, 'ven_com_num', [
                            'inputOptions' => [
                                'placeholder' => $model->getAttributeLabel('ven_com_num'),
                                    'class'=>'form-control'
                                ],
                            ]);
                    ?> 
                </div> 
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    
                    <?= $form->field($model, 'ven_com_date')->widget(DatePicker::classname(), [
                            'options' => [
                                'class'=>'form-control',
                                'placeholder' => 'วันที่',
                                'value' => $model->ven_com_date ? $model->ven_com_date : '',
                            ],
                            'pluginOptions' => [
                                'autoclose'=>true,
                                'format' => 'yyyy-mm-dd'
                            ]]);
                    ?>
                
                </div> 
            </div>
            <!-- $model->DateThai_full(date("Y-m", strtotime("+1"))); -->
            <!-- date('Y-m-d', strtotime('-1 day', strtotime($model->ven_date))) -->
            <div class="col-md-4">
                <div class="form-group">                   
                    <?= $form->field($model, 'ven_month')->widget(Select2::classname(), [
                            'data' => $model::getVen_month(),
                            'language' => 'th',
                            'options' => [
                                'value' => $model->ven_month ? $model->ven_month : date("Y-m"),
                                'class'=>'form-control',
                                'placeholder' => $model->getAttributeLabel('ven_month')
                            ],
                            'pluginOptions' => [
                                'allowClear' => true
                            ],
                        ]);
                    ?>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">                   
                    <?= $form->field($model, 'ven_time')->widget(Select2::classname(), [
                            'data' => $model::getVen_time(),
                            'language' => 'th',
                            'options' => [
                                'class'=>'form-control',
                                'placeholder' => $model->getAttributeLabel('ven_time')],
                            'pluginOptions' => [
                                'allowClear' => true
                            ],
                        ]);
                    ?>
                </div>
            </div>      
            <div class ="col-md-4">
                <div class="form-group">			
                <?= $form->field($model, 'ven_com_name')->widget(Select2::classname(), [
                            'data' => $model::getAven(),
                            'language' => 'th',
                            'options' => [
                                'class'=>'form-control',
                                'placeholder' => $model->getAttributeLabel('ven_com_name')],
                            'pluginOptions' => [
                                'allowClear' => true
                            ],
                        ]);
                    ?>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">                   
                    <?= $form->field($model, 'status')->widget(Select2::classname(), [
                            'data' => [
                                '1' => 'ใช้งาน',
                                '7' => 'ไม่ใช้งาน',
                            ],
                            'language' => 'th',
                            'options' => [
                                'class'=>'form-control',
                            ],
                            'pluginOptions' => [
                                'allowClear' => true
                            ],
                        ]);
                    ?>
                </div>
            </div> 
                               
            <div class="col-md-12">
                <div class="form-group">
                    <?= $form->field($model, 'file')->widget(FileInput::classname(), [
                            'options' => [
                                // 'accept' => 'image/*',
                                'class' => 'form-control',],
                            // 'pluginOptions' => ['previewFileType' => 'any', 'uploadUrl' => Url::to(['/site/file-upload']),]
                        ]);
                    ?>	
                </div>										
            </div>
        </div>
            

        
        <div class="text-right">             
            <?= Html::submitButton('Save', ['class' => 'btn btn-primary btn-lg']) ?>
            <?php Html::resetButton('Reset', ['class' => 'btn btn-warning btn-lg']) ?> 
        </div>

        
    </div>
    <?php ActiveForm::end(); ?>
</div>