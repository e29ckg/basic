<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\WebLink */

$this->title = 'ใบลาป่วย';
$this->params['breadcrumbs'][] = ['label' => 'Web Links', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="web-link-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form_a', [
        'model' => $model,
    ]) ?>

</div>
