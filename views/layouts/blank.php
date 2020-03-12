
<?php
use yii\helpers\Url;
use yii\helpers\Html;
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title><?= Html::encode($this->title) ?></title>
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.7 -->
  <link rel="stylesheet" href="<?=Url::to(['css/style.css?v=1'])?>" />
  <!-- <link rel="stylesheet" href="<?=Url::to(['adminlte2/bower_components/bootstrap/dist/css/bootstrap.min.css'])?>"> -->
  <!-- Font Awesome -->
  <!-- <link rel="stylesheet" href="<?=Url::to(['adminlte2/bower_components/font-awesome/css/font-awesome.min.css'])?>"> -->
  <!-- Ionicons -->
  <!-- <link rel="stylesheet" href="<?=Url::to(['adminlte2/bower_components/Ionicons/css/ionicons.min.css'])?>"> -->
  <!-- Theme style -->
  <!-- <link rel="stylesheet" href="<?=Url::to(['adminlte2/dist/css/AdminLTE.min.css'])?>"> -->
 
</head>
<body class="hold-transition login-page">

<?= $content ?>


  </body>
</html>
