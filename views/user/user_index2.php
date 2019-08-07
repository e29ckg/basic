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

<div class='row'>
	<div class="col-sm-3">
		<div class="search_box pull-right">
			<!-- <input type="text" placeholder="Search"/> -->
			<input id = "search" type="text" name="table_search" class="form-control pull-right" placeholder="Search" data-cip-id="cIPJQ342845640" autofocus>
		</div>
	</div>
</div>

<div class="row" id="features_items">
	<?php foreach ($models as $model): ?>
	
	<div class="col-md-4">
          <!-- Widget: user widget style 1 -->
          <div class="box box-widget widget-user">
            <!-- Add the bg color to the header using any of the bg-* classes -->
            <div class="widget-user-header <?= $model->status == 0 ? 'bg-yellow-active':'bg-aqua-active'?> ">
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
</div>

<?php
$script = <<< JS
     
$(document).ready(function() {

	$('#example1').DataTable()
	$('#example2').DataTable({
      'paging'      : true,
      'lengthChange': false,
      'searching'   : true,
      'ordering'    : true,
      'info'        : true,
      'autoWidth'   : false
    })

	$("#search").keyup(function () {
		//        var that = this,
			value = $(this).val();
			if(value == ""){
	  			location.reload();  
			}
			$.get("search",{id:value},
				function (data)
					{
						$("#features_items").html(data);
					}
				);

			});	

	function init_click_handlers(){        	
		$('#activity-modal').on('hidden.bs.modal', function () {
 		location.reload();
	});

		var url_update = "update_profile";
    	$(".act-update-profile").click(function(e) {            
			var fID = $(this).data("id");
			// alert(fID);
        	$.get(url_update,{id: fID},function (data){
            	$("#activity-modal").find(".modal-body").html(data);
            	$(".modal-body").html(data);
            	$(".modal-title").html("แก้ไขข้อมูล ");
            	$("#activity-modal").modal("show");
        	});
    	});

		var url_show_profile = "show_profile";
    	$(".act-show").click(function(e) {            
			var fID = $(this).data("id");
			// alert(fID);
        	$.get(url_show_profile,{id: fID},function (data){
            	$("#activity-modal").find(".modal-body").html(data);
            	$(".modal-body").html(data);
            	$(".modal-title").html("แก้ไขข้อมูลสมาชิก");
            	$("#activity-modal").modal("show");
        	});
    	});

		var url_line = "line_alert";		
    	$(".act-line").click(function(e) {			
                var fID = $(this).data("id");				
                $.get(url_line,{id: fID},function (data){
                        // $("#activity-modal").find(".modal-body").html(data);
                        // $(".modal-body").html(data);
                        // $(".modal-title").html("ข้อมูล");
                        // $("#activity-modal").modal("show");
                    }
                );
            }); 
			var url_rePass = "reset_pass";
			$(".act-resetPass").click(function(e) {			
                var fID = $(this).data("id");				
                $.get(url_rePass,{id: fID},function (data){
                        // $("#activity-modal").find(".modal-body").html(data);
                        // $(".modal-body").html(data);
                        // $(".modal-title").html("ข้อมูล");
                        // $("#activity-modal").modal("show");
                    }
                );
            }); 

    	var url_up_un = "update_username";		
    	$(".act-update-username").click(function(e) {			
                var fID = $(this).data("id");
				
                $.get(url_up_un,{id: fID},function (data){
                        $("#activity-modal").find(".modal-body").html(data);
                        $(".modal-body").html(data);
                        $(".modal-title").html("ข้อมูล");
                        $("#activity-modal").modal("show");
                    }
                );
            });  

		var url_chang_email = "chang_email";		
			$( ".act-chang-email" ).click(function() {
				var fID = $(this).data("id");	
        	$.get(url_chang_email,{id: fID},function (data){
                $("#activity-modal").find(".modal-body").html(data);
                $(".modal-body").html(data);
                $(".modal-title").html("แก้ไข");
            	// $(".modal-footer").html(footer);
                $("#activity-modal").modal("show");
        	});     
		}); 
    
	}

    init_click_handlers(); //first run
    
	var url_create = "reg";
	$( "#act-create" ).click(function() {
		$.get(url_create,function (data){
			$("#activity-modal").find(".modal-body").html(data);
			$(".modal-body").html(data);
			$(".modal-title").html("เพิ่มข้อมูล");
			// $(".modal-footer").html(footer);
			$("#activity-modal").modal("show");
			//   $("#myModal").modal('toggle');
		});     
	}); 
	
});
JS;
$this->registerJs($script);
?>