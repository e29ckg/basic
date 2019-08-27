<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\WebLink */

$this->title = 'ID : ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Web Links', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="web-link-update">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php
        if($model->cat == 'ลาป่วย' || $model->cat == 'ลากิจส่วนตัว' || $model->cat == 'ลาคลอดบุตร'){
            $_form = '_form_a';
        }else if($model->cat == 'ลาพักผ่อน'){
            $_form = '_form_b';
        }else if($model->cat == 'ไปราชการ'){
            $_form = '_form_governor';
        }
    ?>  
    <?= $this->render($_form, [
        'model' => $model,
    ]) ?>

</div>
