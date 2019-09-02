<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
// use yii\helpers\Url;
use kartik\select2\Select2;
use kartik\date\DatePicker;
use yii\helpers\ArrayHelper;
// use kartik\file\FileInput;

$this->title = 'ใบเปลี่ยนเวร';
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
        <?php// var_dump($ven_id1)?>
            <div class="col-md-12">
                <div class="form-group">
                    
                    <?=  $form->field($model, 'ven_id1')->widget(Select2::classname(), [
                            'data' => $ven_id1,
                            'language' => 'th',
                            'options' => [
                                // 'placeholder' => ' ',
                                'class' => 'form-control',
                            ],
                            'pluginOptions' => [
                            'allowClear' => true
                            ],
                        ]);
                    ?> 
                </div>
            </div>
            <?php// var_dump($ven_id2)?>
            <div class="col-md-12">
                <div class="form-group">
                <?=  $form->field($model, 'user_id2')->widget(Select2::classname(), [
                            'data' =>  $model->getUserList(),
                            'language' => 'th',
                            'options' => [
                                'placeholder' => $model->getAttributeLabel('user_id2'),
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
                    <?=  $form->field($model, 's_po')->widget(Select2::classname(), [
                            'data' => $model->getSignList(),
                            'language' => 'th',
                            'options' => [
                                'placeholder' => $model->getAttributeLabel('s_po'),
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
                <?=  $form->field($model, 's_bb')->widget(Select2::classname(), [
                            'data' => $model->getSignList(),
                            'language' => 'th',
                            'options' => [
                                'placeholder' => $model->getAttributeLabel('s_bb'),
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
        
        
        <div class="text-center">             
            <?= Html::submitButton('Save', ['class' => 'btn btn-primary btn-lg']) ?>
            
        </div>
        <?php Yii::$app->requestedAction->id ?>
        
    </div>
    <?php ActiveForm::end(); ?>
</div>