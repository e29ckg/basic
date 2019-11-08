<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\CLetter */
$this->title = 'เพิ่มหนังสือเวียน';
$this->params['breadcrumbs'][] = ['label' => 'หนังสือเวียน', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cletter-create">
<!--     <h1><?= Html::encode($this->title) ?></h1> -->

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
