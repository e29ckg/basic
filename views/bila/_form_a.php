<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use app\models\Bila;
use kartik\select2\Select2;
use kartik\date\DatePicker;
use yii\helpers\ArrayHelper;

$this->title = 'เขียนใบลาป่วย';
$this->params['breadcrumbs'][] = ['label' => 'โปรแกรมใบลา', 'url' => ['index']];
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
      
    <?= $form->field($model, 'date_create')->hiddenInput(['readonly' => true, 'value' => date("Y-m-d")])->label(false) ?>
    <?= $form->field($model, 'user_id')->hiddenInput(['readonly' => true, 'value' => Yii::$app->user->identity->id])->label(false) ?>
    
    <div class="box-body">
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <?php 
                        echo $form->field($model, 'cat')->widget(Select2::classname(), [
                            'data' => Bila::getCat(),
                            'language' => 'th',
                            'options' => [
                                // 'placeholder' => ' ประเภทการลา',
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
        <div class="row">
            <div class ="col-md-5">
                <div class="form-group">			
                    <?= $form->field($model, 'date_begin')->widget(DatePicker::classname(), [
                        'options' => [
                            'class'=>'form-control',
                            'placeholder' => 'ลาตั้งแต่วันที่'
                        ],
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
                        'options' => [
                            'placeholder' => 'ถึงวันที่',
                            // 'value' => '2019-02-01'
                        ],
                        'pluginOptions' => [
                            'autoclose'=>true,
                            'format' => 'yyyy-mm-dd'
                        ]]);
                    ?>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <?= $form->field($model, 'date_total', [
                        'inputOptions' => [
                            'placeholder' => $model->getAttributeLabel('date_total'),
                                'class'=>'form-control'
                            ],
                            // 'template' => '<div class="form-group"><label>{label}</label> {input}<b class="tooltip tooltip-top-right">'.$model->getAttributeLabel('date_total').'</b><em for="name" class="invalid">{error}{hint}</em></div>'
                        ]);
                    ?>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <?= $form->field($model, 'due', [
                        'inputOptions' => [
                            'placeholder' => $model->getAttributeLabel('due'),
                            'class' => 'form-control'
                        ],
                        // 'template' => '{label}{input}{error}{hint}'
                    ]);
                    ?>	
                </div>										
            </div>
        </div>

        <hr> 

        <div class="row">
            <div class="col-md-5">
                <div class="form-group">
                    
                    <?= $form->field($model, 'dateO_begin')->widget(DatePicker::classname(), [
                        'options' => [
                            'placeholder' => 'ลาครั้งสุดท้าย ตั้งแต่วันที่',
                            'class' => 'form-control',
                            // 'value' => $date_begin,
                        ],
                        'pluginOptions' => [
                            'autoclose'=>true,
                            'format' => 'yyyy-mm-dd'
                        ]]);
                    ?>
                </div>
            </div>
            <div class="col-md-5">
                <div class="form-group">
                    
                    <?= $form->field($model, 'dateO_end')->widget(DatePicker::classname(), [
                        'options' => ['placeholder' => 'ถึงวันที่',
                        'class' => 'form-control',
                        // 'value' => $date_end,
                        ],                            
                        'pluginOptions' => [
                            'autoclose'=>true,
                            'format' => 'yyyy-mm-dd'
                        ]]);
                    ?>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    
                    <?= $form->field($model, 'dateO_total', [
                            'inputOptions' => [
                                'placeholder' => $model->getAttributeLabel('dateO_total'),
                                'class' => 'form-control',
                                // 'value' => $date_total,
                            ],
                            // 'template' => '<label class="input">{label}</label> <label class="input">{input}<b class="tooltip tooltip-top-right">'.$model->getAttributeLabel('dateO_total').'</b></label><em for="name" class="invalid">{error}{hint}</em>'
                        ]);
                    ?>   
                </div>             
            </div>
        </div>
                            
        <div class="row">
            <div class="col-md-2">
                <div class="form-group">
                    
                    <?= $form->field($model, 't1', [
                        'inputOptions' => [
                            'placeholder' => $model->getAttributeLabel('t1'),
                            'class' => 'form-control',
                            // 'value'=> $t3,
                        ],
                        // 'template' => '<label class="input">{label}</label> <label class="input">{input}<b class="tooltip tooltip-top-right">'.$model->getAttributeLabel('t1').'</b></label><em for="name" class="invalid">{error}{hint}</em>'
                    ]);
                    ?>  
                </div> 
            </div>  
            <div class="col-md-5">
                <div class="form-group">        
                    <?php 
                        echo $form->field($model, 'address', [
                                'inputOptions' => [
                                'placeholder' => $model->getAttributeLabel('address'),
                                'class' => 'form-control',
                            ],
                            // 'template' => '<label class="input">{label}</label> <label class="input">{input}<b class="tooltip tooltip-top-right">'.$model->getAttributeLabel('address').'</b></label><em for="name" class="invalid">{error}{hint}</em>'
                        ]);
                    ?>
                </div>
            </div>
            <div class="col-md-5">
                <div class="form-group">
                    <?php
                        echo $form->field($model, 'comment', [
                            'inputOptions' => [
                                'class' => 'form-control',
                                'placeholder' => $model->getAttributeLabel('comment'),
                            ],
                            // 'template' => '<label class="input">{label}</label> <label class="input">{input}<b class="tooltip tooltip-top-right">'.$model->getAttributeLabel('comment').'</b></label><em for="name" class="invalid">{error}{hint}</em>'
                        ]);
                    ?>
                </div>
            </div> 
        </div>    
        
        <hr> 
        
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <?php 
                        echo $form->field($model, 'po')->widget(Select2::classname(), [
                            'data' => Bila::getSignList(),
                            'language' => 'th',
                            'options' => [
                                'placeholder' => ' เลือก ผอ.',
                                'class' => 'form-control',
                            ],
                            'pluginOptions' => [
                            'allowClear' => true
                            ],
                        ]);
                    ?> 
                </div> 
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <?php 
                        echo $form->field($model, 'bigboss')->widget(Select2::classname(), [
                            'data' => Bila::getSignList(),
                            'language' => 'th',
                            'options' => [
                                'class' => 'form-control',
                                'placeholder' => ' เลือก หัวหน้าศาลฯ'
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
            <?php Html::resetButton('Reset', ['class' => 'btn btn-warning btn-lg']) ?> 
            <?= Html::submitButton('Save', ['class' => 'btn btn-primary btn-lg']) ?>
        </div>

        
    </div>
    <?php ActiveForm::end(); ?>
</div>