<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\models\PpssSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="ppss-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'black') ?>

    <?= $form->field($model, 'red') ?>

    <?= $form->field($model, 'persecutor') ?>

    <?= $form->field($model, 'accused') ?>

    <?php // echo $form->field($model, 'page') ?>

    <?php // echo $form->field($model, 'note') ?>

    <?php // echo $form->field($model, 'file') ?>

    <?php // echo $form->field($model, 'create_at') ?>

    <?php // echo $form->field($model, 'create_own') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
