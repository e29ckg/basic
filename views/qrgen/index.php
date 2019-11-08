<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use app\models\Bila;
use kartik\select2\Select2;
use kartik\date\DatePicker;
use yii\helpers\ArrayHelper;

$this->title = 'สร้าง QrCode';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="col-md-2">
</div>
<div class="col-md-8">
    <div class="box box-danger">    
        <?php 
            $form = ActiveForm::begin([
                'id' => 'Qrgen-form',
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
                    <?php
                        echo $form->field($model, 'url', [
                            'inputOptions' => [
                                'class' => 'form-control ',
                                'placeholder' => $model->getAttributeLabel('url'),
                            ],
                            // 'template' => '<label class="input">{label}</label> <label class="input">{input}<b class="tooltip tooltip-top-right">'.$model->getAttributeLabel('url').'</b></label><em for="name" class="invalid">{error}{hint}</em>'
                        ])->label(false);
                    ?>
                </div>
                <div class="text-right"> 
                    <?= Html::submitButton('สร้าง QrCode', ['class' => 'btn btn-primary  btn-md']) ?>
                    <a href= "<?=Url::to(['index'])?>" class="btn btn-warning btn-md"> ล้าง </a>
                </div>
            </div> 
        </div>
        <br>
        <div class="row">        
        <div class ="col-md-12">
            <div class="form-group text-center">
            <?= !($Qrgen == null) ? '
                <img src="'.Url::to('@web/uploads/Qrgen/Qrgen.png').'">
                <br>'.$model->url
                .'<br><br>'.'<a href= "'. Url::to(['download']).'" class="btn btn-success btn-lg"> ดาวน์โหลด</a>'
                : '';?>
            </div>
        </div>         
    </div>
    <?php ActiveForm::end(); ?>
</div>
