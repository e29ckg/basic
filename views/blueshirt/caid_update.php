<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\CLetter */
$this->title = 'แก้ไขประเภทหนังสือ';
$this->params['breadcrumbs'][] = ['label' => 'ประเภทหนังสือ', 'url' => ['caid_index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cletter-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('caid_form', [
        'model' => $model,
    ]) ?>

</div>
