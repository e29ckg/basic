<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\WebLink */

$this->title = 'ใบลาเลขที่ : ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Web Links', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="web-link-update">

    <h1><?= Html::encode($this->title) ?></h1>
<?php
if($model->cat == 'ลาป่วย'){
    $_form = '_form_a';
}else if($model->cat == 'ลาพักผ่อน'){
    $_form = '_form_b';
}
?>
    <?= $this->render($_form, [
        'model' => $model,
    ]) ?>

</div>
