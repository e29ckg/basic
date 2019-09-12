<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use app\models\Bila;
use kartik\select2\Select2;
use kartik\date\DatePicker;
use yii\helpers\ArrayHelper;

$this->title = 'Running Number';
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
                    
                        <?= $form->field($model, 'name', [
                            'inputOptions' => [
                                'placeholder' => $model->getAttributeLabel('name'),
                                'class' => 'form-control',
                                // 'value'=> $t3,
                            ],
                            // 'template' => '<label class="input">{label}</label> <label class="input">{input}<b class="tooltip tooltip-top-right">'.$model->getAttributeLabel('t1').'</b></label><em for="name" class="invalid">{error}{hint}</em>'
                        ]);
                        ?>   
                </div> 
            </div>
        </div>
        <hr>
        <div class="row">
            <div class ="col-md-5">
                <div class="form-group">			
                <?= $form->field($model, 'y')->widget(Select2::classname(), [
                            'data' => [
                                date('Y') + 542 => date('Y') + 542,
                                date('Y') + 543 => date('Y') + 543,
                                date('Y') + 544 => date('Y') + 544],
                            'language' => 'th',
                            'options' => ['class'=>'form-control','placeholder' => ' เลือก..U'],
                            'pluginOptions' => [
                                'allowClear' => true
                            ],
                        ]);
                    ?>
                </div>
            </div>
           
            <div class="col-md-2">
                <div class="form-group">
                    <?= $form->field($model, 'r', [
                        'inputOptions' => [
                            'placeholder' => $model->getAttributeLabel('r'),
                                'class'=>'form-control'
                            ],
                        ]);
                    ?>
                </div>
            </div>
        </div>
        
        <div class="text-right"> 
            <?= Html::submitButton('Save', ['class' => 'btn btn-primary btn-block']) ?>
        </div>

        
    </div>
    <?php ActiveForm::end(); ?>
</div>