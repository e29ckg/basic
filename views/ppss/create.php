<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model frontend\models\Ppss */

$this->title = 'เพิ่มสำเนาคำพิพากษาส่งภาค ๗';
$this->params['breadcrumbs'][] = ['label' => 'สำเนาคำพิพากษาส่งภาค ๗', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ppss-create">    

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
