<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Login';
?>

<div class="login-box">
  <div class="login-logo">
    <a href="#"><b>Pkkjc </b>WebApp</a>
  </div>
  <!-- /.login-logo -->
  <div class="login-box-body">
    <p class="login-box-msg">Sign in to start your session</p>

      <?php $form = ActiveForm::begin([
          'id' => 'login-form',
          // 'layout' => 'horizontal',
          'fieldConfig' => [
              // 'template' => "{label}\n<div class=\"col-lg-3\">{input}</div>\n<div class=\"col-lg-8\">{error}</div>",
              // 'labelOptions' => ['class' => 'col-lg-1 control-label'],
          ],
      ]); ?>

    <div class="form-group has-feedback">
      <?= $form->field($model, 'username')->textInput(['autofocus' => true])->label(false) ?>
      <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
    </div>
        
    <div class="form-group has-feedback">
      <?= $form->field($model, 'password')->passwordInput()->label(false) ?>
      <span class="glyphicon glyphicon-lock form-control-feedback"></span>
    </div>
        

    <div class="row">
      <div class="col-xs-8">
        <div class="checkbox icheck">
          
          <?php $form->field($model, 'rememberMe')->checkbox([
            'class' => 'icheckbox_square-blue',
          // 'template' => "<div class="icheckbox_square-blue" aria-checked="false" aria-disabled="false" style="position: relative;">{input} {label}</div>\n<div class=\"col-lg-8\">{error}</div>",
          ]) ?>
          
        </div>
      </div>
      <!-- /.col -->
      <div class="col-xs-4">
        <?= Html::submitButton('Login', ['class' => 'btn btn-primary btn-block btn-flat' , 'name' => 'login-button']) ?>
      </div>
      <!-- /.col -->
    </div>
    

    <?php ActiveForm::end(); ?>

    <div class="social-auth-links text-center">
      <!-- <p>- OR -</p>
      <a href="#" class="btn btn-block btn-social btn-facebook btn-flat"><i class="fa fa-facebook"></i> Sign in using
        Facebook</a>
      <a href="#" class="btn btn-block btn-social btn-google btn-flat"><i class="fa fa-google-plus"></i> Sign in using
        Google+</a> -->
    </div>
    <!-- /.social-auth-links -->

    <!-- <a href="#">I forgot my password</a><br> -->
    <!-- <a href="register.html" class="text-center">Register a new membership</a> -->

  </div>
  <!-- /.login-box-body -->
</div>