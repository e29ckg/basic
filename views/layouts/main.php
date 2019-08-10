<?php

/* @var $this \yii\web\View */
/* @var $content string */

use app\widgets\Alert;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;
use yii\helpers\Url;
use app\models\Profile;
use yii\bootstrap\Modal;

AppAsset::register($this);

$profileActive = Profile::getProfileActive();
// echo $profileActive["img"];

?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
  <meta charset="<?= Yii::$app->charset ?>">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1"> <title><?= Html::encode($this->title) ?></title>
  <link rel="shortcut icon" type="image/x-icon" href="<?=Url::to(['/img/favicon.ico'])?>">
  <!-- Google Font -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
  <?php $this->head() ?>
</head>
<body class="hold-transition skin-blue sidebar-mini">
<?php $this->beginBody() ?>
<!-- Site wrapper -->
<div class="wrapper">

  <header class="main-header">
    <!-- Logo -->
    <a href="<?=Url::to(['site/index'])?>" class="logo">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini"><b>A</b>LT</span>
      <!-- logo for regular state and mobile devices -->
      <span class="logo-lg">  <b><?= Yii::$app->name ?></b></span>
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
      <!-- Sidebar toggle button-->
      <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </a>

      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
        
          <!-- User Account: style can be found in dropdown.less -->
          <li class="dropdown user user-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <img src="<?=  $profileActive["img"]; ?>" class="user-image" alt="User Image">
              
              <span class="hidden-xs"><?=  $profileActive["fullname"]; ?></span>
            </a>
            <ul class="dropdown-menu">
              <!-- User image -->
              <li class="user-header">
                <img src="<?=  $profileActive["img"]; ?>" class="img-circle" alt="User Image">

                <p>
                  <?=  $profileActive["fullname"]; ?>
                  <small><?= $profileActive["dep"]; ?></small>
                </p>
              </li>
              <!-- Menu Body -->
              <li class="user-body">
                <div class="row">
                  <div class="col-xs-4 text-center">
                    <a href="#">Followers</a>
                  </div>
                  <div class="col-xs-4 text-center">
                    <a href="#">Sales</a>
                  </div>
                  <div class="col-xs-4 text-center">
                    <a href="#">Friends</a>
                  </div>
                </div>
                <!-- /.row -->
              </li>
              <!-- Menu Footer-->
              <li class="user-footer">
                <div class="pull-left">
                  <?= Yii::$app->user->isGuest ? '' : Html::a('Profile', Url::to(['/user/profile'], true),['class'=>'btn btn-default btn-flat']) ?>
                </div>
                
                <div class="pull-right">
                <?= Yii::$app->user->isGuest ? Html::a('Sign In', Url::to(['/site/login'], true),['class' => 'btn btn-default btn-flat']) : 
                Html::beginForm(['/site/logout'], 'post')
                .Html::submitButton('Sign out',['class' => 'btn btn-default btn-flat','data-confirm'=> 'Are you sure ?'])
                .Html::endForm()?>

                <?php Html::beginForm(['/site/logout'], 'post')
                . Html::submitButton(
                    'Sign out',[
                      'class' => 'btn btn-default btn-flat',
                    'data-confirm'=> 'Are you sure ?'
                     ]
                )
                . Html::endForm()?>
                </div>
              </li>
            </ul>
          </li>
          <!-- Control Sidebar Toggle Button -->
          <!-- <li>
            <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
          </li> -->
        </ul>
      </div>
    </nav>
  </header>

  <!-- =============================================== -->
  
  <!-- Left side column. contains the sidebar -->
  <aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->    
    <section class="sidebar">
      <!-- Sidebar user panel -->
      <?= !Yii::$app->user->isGuest ? '
      <div class="user-panel">
        <div class="pull-left image">
          <img src="'.$profileActive["img"].'" class="img-circle" alt="User Image">
        </div>
        <div class="pull-left info">
          <p>'.$profileActive["fullname"].'</p>
          <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
        </div>
      </div>': '';?>

      <?= $this->render('left.php') ?>
           
    </section>    
    <!-- /.sidebar -->
  </aside>

  <!-- =============================================== -->

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
              
        <?php if (isset($this->blocks['content-header'])) { ?>
            <h1><?= $this->blocks['content-header'] ?></h1>
        <?php } else { ?>
            <h1>
                <?php
                if ($this->title !== null) {
                    echo \yii\helpers\Html::encode($this->title);
                } else {
                    echo \yii\helpers\Inflector::camel2words(
                        \yii\helpers\Inflector::id2camel($this->context->module->id)
                    );
                    echo ($this->context->module->id !== \Yii::$app->id) ? '<small>Module</small>' : '';
                } ?>
            </h1>
        <?php } ?>

        <?=
        Breadcrumbs::widget(
            [
                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
            ]
        ) ?>
    </section>
    </section>

    <!-- Main content -->
    <section class="content">
      
     
        <?= $content ?>
        

    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <footer class="main-footer">
    <div class="pull-right hidden-xs">
      <b>Version</b> 2.4.13
    </div>
      <small># <?= $csrf =yii::$app->request->csrfParam;?> # 
      <?= $token = yii::$app->request->csrfToken;?> # <?= Yii::$app->getRequest()->getUserIP();?></small>
    <!-- <strong>Copyright &copy; 2014-2019 <a href="https://adminlte.io">AdminLTE</a>.</strong> All rights -->
    <!-- reserved. -->
  </footer>

  <!-- Control Sidebar -->
  
  <!-- /.control-sidebar -->
  <!-- Add the sidebar's background. This div must be placed
       immediately after the control sidebar -->
  <!-- <div class="control-sidebar-bg"></div> -->
</div>
<!-- ./wrapper -->

<?php $this->endBody() ?>
<script>
  $(document).ready(function () {
    $('.sidebar-menu').tree()
  })
</script>
<?= Alert::widget() ?>
<?php
        Modal::begin([
            'id' => 'activity-modal',
            'header' => '<h4 class="modal-title"></h4>',
                'size' => 'modal-lg',
                'options' => ['tabindex' => ''],
            // 'footer' => '<a href="#" class="btn btn-primary" data-dismiss="modal">ปิด</a>',
        ]);
        echo "<div id='modalContent'></div>";
        Modal::end();
    ?>

</body>
</html>
<?php $this->endPage() ?>
