<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\WebLink */

$this->title = 'แก้ไข : ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'รายชื่อผู้ลงนาม', 'url' => ['sbn_index']];
$this->params['breadcrumbs'][] = $model->name;
?>
<div class="web-link-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('sbn_form', [
        'model' => $model,
    ]) ?>

</div>
