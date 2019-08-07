<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

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
<?= $form->field($modelFile, 'url', [
    'inputOptions' => [
        'placeholder' => $modelFile->getAttributeLabel('url')
    ],
    'template' => '<section class=""><label class="label">{label}</label> <label class="input"> <i class="icon-append fa fa-user"></i>{input}<b class="tooltip tooltip-top-right">'.$modelFile->getAttributeLabel('url').'</b></label><em for="name" class="invalid">{error}{hint}</em></section>'
    ])->input('url')->label(false);
    ?>
</div> 

 
<fieldset class="text-right"> 
<?= Html::a('ลบ',['web_link/deleteurl','id' => $modelFile->id],
													[
														'class' => 'btn btn-danger btn-lg',
														'data-confirm' => 'Are you sure to delete this item?',
                                    					'data-method' => 'post',
													]); ?> 
<?= Html::resetButton('Reset', ['class' => 'btn btn-warning btn-lg']) ?> 
<?= Html::submitButton('Save', ['class' => 'btn btn-primary btn-lg']) ?>
</fieldset>

    <?php ActiveForm::end(); ?>

</div>
