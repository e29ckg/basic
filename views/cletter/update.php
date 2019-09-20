<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\CLetter */

$this->title = 'Update Cletter: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Cletters', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="cletter-update">

    <!-- <h1><?= Html::encode($this->title) ?></h1> -->

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
