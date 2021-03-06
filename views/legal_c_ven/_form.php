<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
// use yii\helpers\Url;
use kartik\select2\Select2;
use kartik\date\DatePicker;
use yii\helpers\ArrayHelper;
// use kartik\file\FileInput;

$this->title = 'เวรที่ปรึกษา';
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
            <div class="col-md-12">
                <div class="form-group text-center">
                    วันที่ <?=$model->DateThai_full($date_id)?> 
                    <?= $form->field($model, 'ven_date')->hiddenInput(['class'=>'form-control','value'=>$date_id])->label(false)?>
                </div> 
            </div>
            <hr>        
            <div class ="col-md-12">
                <div class="form-group">			
                    <?= $form->field($model, 'legal_c_id')->widget(Select2::classname(), [
                            'data' => $model::getUserList(),
                            'language' => 'th',
                            'options' => ['class'=>'form-control','placeholder' => ' เลือก..'],
                            'pluginOptions' => [
                                'allowClear' => true
                            ],
                        ]);
                    ?>
                </div>
            </div>
            
            <div class="col-md-12">
                <div class="form-group">
                    <?= $form->field($model, 'comment', [
                        'inputOptions' => [
                            'placeholder' => $model->getAttributeLabel('comment'),
                                'class'=>'form-control'
                            ],
                        ]);
                    ?>
                </div>
            </div>
        </div>       
        
        <div class="text-center">             
            <?= Html::submitButton('Save', ['class' => 'btn btn-primary btn-lg btn-block']) ?>
                            
        </div>
        <?php Yii::$app->requestedAction->id?>
        
    </div>
    <?php ActiveForm::end(); ?>
</div>