<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ActiveForm;
use yii\widgets\LinkPager;
use yii\bootstrap\Modal;
use yii2mod\alert\Alert;
use yii\helpers\Url;
use app\models\User;
use app\models\Profile;
/* @var $this yii\web\View */
/* @var $searchModel backend\models\ProfileSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'จัดการสมาชิก';
$this->params['breadcrumbs'][] = $this->title;
?>


<!-- MAIN CONTENT -->

	<?php foreach ($models as $model): ?>
	
	<div class="col-md-4">
          <!-- Widget: user widget style 1 -->
          <div class="box box-widget widget-user">
            <!-- Add the bg color to the header using any of the bg-* classes -->
            <div class="widget-user-header <?= isset($model->status) == 0 ? 'bg-yellow-active':'bg-aqua-active'?> ">
              <h3 class="widget-user-username"><?= $model->username ?><?= $model->status == 0 ? '<span class="badge bg-red">ระงับการใช้งาน</span>':''?></h3>
              <h5 class="widget-user-desc">ID : <?= $model->id?></h5>
            </div>
            <div class="widget-user-image">
              <!-- <img class="img-circle" src="../dist/img/user1-128x128.jpg" alt="User Avatar"> -->
			  <?=Html::img($model->getProfileImg(), ['class'=>'img-circle','alt' => 'userPic','height'=>'42'])?>
			
            </div>
            <div class="box-footer">
              <div class="row">                
               
				<div class="col-sm-12 border-right">
                  <div class="description-block">
                    <h5 class="description-header"><?= $model->getProfileName() ?></h5>
                    <span class="description-text"><?= $model->getProfileDep()?></span>
                  </div>
                  <!-- /.description-block -->
                </div>
                <!-- /.col -->
                
              </div>
              <!-- /.row -->
			  	<div class="row">
                	<div class="col-sm-6">
						<?= Html::label('แก้ไข', 'update-profile', [
						'class' => 'btn btn-block btn-info act-update-profile',
						'data-id' => $model->id]) ?>
					</div>
				
                	<div class="col-sm-6">
					<?php if($model->status == 0){
						echo Html::a('SetActive', ['user/active', 'id' => $model->id], [
							'class' => 'btn btn-block btn-primary',
							'data-confirm'=>'Are you sure this item?']);
						}else{
							echo Html::a('ระงับ', ['user/del', 'id' => $model->id], [
								'class' => 'btn btn-block btn-danger',
								'data-confirm'=>'Are you sure ?']);
							}	?>
					</div>
				</div>
            </div>
          </div>
          <!-- /.widget-user -->
        </div>
	<?php  endforeach; ?>


