<?php

use yii\helpers\Html;

$this->title = 'แจ้งผ่าน Line Notify';
$this->params['breadcrumbs'][] = $this->title;
?>

<?=  !empty($model->token) ? $model->token 
              . ' ' 
              . Html::a('ทดสอบการส่ง', ['user_line_send'],['class' => 'btn btn-success'])
              .' '
              . Html::a('ลบ', ['line_delete','id' => $model->id],['class' => 'btn btn-warning'])
              : Html::a('ลงทะเบียน', $result, ['class' => 'btn btn-success']) ;?>