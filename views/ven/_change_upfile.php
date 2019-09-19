<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
// use app\models\Bila;
use yii\helpers\ArrayHelper;
use kartik\file\FileInput;
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
            <div class ="col-md-12">
                <div class="form-group">
                    <?php
                        echo $form->field($model, 'file')->widget(FileInput::classname(), [
                            'options' => [
                                'accept' => ['image/*','pdf'],
                                'class' => 'form-control',],
                            // 'pluginOptions' => ['previewFileType' => 'any', 'uploadUrl' => Url::to(['/site/file-upload']),]
                        ]);
                    ?>

                </div>
            </div>
        </div>
        
        <hr> 
        
        <div class="text-right"> 
            <?= Html::submitButton('Save', ['class' => 'btn btn-primary btn-lg']) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>

