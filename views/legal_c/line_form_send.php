<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;

?>

<?php $form = ActiveForm::begin()?>
<?=$form->field($model, 'name')?>

<?=Html::submitButton('ส่งข้อความ', ['class' => 'btn btn-warning'])?>
<?php ActiveForm::end()?>

<?php var_dump($json)?>