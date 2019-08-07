<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use kartik\file\FileInput;

/* @var $this yii\web\View */
/* @var $model app\models\WebLink */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="web-link-form">

    <?php 
    $form = ActiveForm::begin([
		'id' => 'weblink-form',
		'options' => [
            'class' => 'smart-form',
            'novalidate'=>'novalidate',
            'enctype' => 'multipart/form-data'
        ],
        //'layout' => 'horiz\ontal',
        'fieldConfig' => [
            //'template' => "{label}{input}{error}",
            'labelOptions' => ['class' => 'label'],
        ],
        'enableAjaxValidation' => true,
	]);  ?>

<div>

<?= $form->field($modelFile, 'name', [
    'inputOptions' => [
        'placeholder' => $modelFile->getAttributeLabel('name')
    ],
    'template' => '<section class=""><label class="label">{label}</label> <label class="input"> <i class="icon-append fa fa-user"></i>{input}<b class="tooltip tooltip-top-right">'.$modelFile->getAttributeLabel('name').'</b></label><em for="name" class="invalid">{error}{hint}</em></section>'
    ])->label(false);
    ?>
</div> 
 

<div>
<?php
echo $form->field($modelFile, 'file')->widget(FileInput::classname(), [
    // 'options' => ['accept' => 'image/*'],
    // 'pluginOptions' => ['previewFileType' => 'any', 'uploadUrl' => Url::to(['/site/file-upload']),]
]);
?>
<?php
// echo $form->field($model, 'file',[
//    'inputOptions' => [
//         'placeholder' => $model->getAttributeLabel('file'),
//         'onchange'=>'this.parentNode.nextSibling.value = this.value'
//     ],
//     'template' => '<section><label class="label">{label}</label><div class="input input-file"><span class="button">{input}Browse</span><input type="text" placeholder="Include some files" readonly=""><div class="invalid">{error}{hint}</div></div></section>'
// ])->fileInput()->label(false) 
?>
</div>

 
<fieldset class="text-right"> 
 
<?= Html::resetButton('Reset', ['class' => 'btn btn-warning btn-lg']) ?> 
<?= Html::submitButton('Save', ['class' => 'btn btn-primary btn-lg']) ?>
</fieldset>

    <?php ActiveForm::end(); ?>

</div>
