<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\CLetter */
$this->title = 'แก้ไข';
$this->params['breadcrumbs'][] = ['label' => 'Line', 'url' => ['line_index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cletter-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('line_form', [
        'model' => $model,
    ]) ?>

</div>
