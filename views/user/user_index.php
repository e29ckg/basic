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

<div class="row">
	<div class="col-lg-3 col-xs-6">
		<!-- small box -->
		<div class="small-box bg-aqua">
			<div class="inner">
				<h3><?=$userAll?></h3>

				<p>All User</p>
			</div>
			<div class="icon">
				<i class="fa fa-users"></i>
			</div>
			<a href="<?=Url::to(['user/user_index','id'=>'all']);?>" class="small-box-footer">
				More info <i class="fa fa-arrow-circle-right"></i>
			</a>
		</div>
	</div>
        <!-- ./col -->
	<div class="col-lg-3 col-xs-6">
		<!-- small box -->
		<div class="small-box bg-green">
			<div class="inner">
				<h3><?=$userActive?></h3>

				<p>Active User</p>
			</div>
			<div class="icon">
				<i class="fa fa-user-plus"></i>
			</div>
			<a href="<?=Url::to(['user/user_index','id' => 'active']);?>" class="small-box-footer">
				More info <i class="fa fa-arrow-circle-right"></i>
			</a>
		</div>
	</div>
        <!-- ./col -->
	<div class="col-lg-3 col-xs-6">
		<!-- small box -->
		<div class="small-box bg-yellow">
			<div class="inner">
				<h3><?=$userDis?></h3>

				<p>DisActiveUser</p>
			</div>
			<div class="icon">
				<i class="fa fa-user-times"></i>
			</div>
			<a href="<?=Url::to(['user/user_index','id' => 'dis']);?>" class="small-box-footer">
				More info <i class="fa fa-arrow-circle-right"></i>
			</a>
		</div>
	</div>
	   <!-- ./col -->
	<div class="col-lg-3 col-xs-6">
		<!-- small box -->
		<div class="small-box bg-red">
			<div class="inner">
				<h3>0</h3>

				<p>--</p>
			</div>
			<div class="icon">
				<i class="fa fa-user-secret"></i>
			</div>
			<a href="#" class="small-box-footer">
				More info <i class="fa fa-arrow-circle-right"></i>
			</a>
		</div>
	</div>
	<!-- ./col -->
</div>
	  

<div class="row">
    <div class="col-xs-12">
       	<div class="box">
           	<div class="box-header">
				<h3 class="box-title"><?=$this->title;?></h3>
				<div class="box-tools pull-right">
					<button id="act-create" class="btn btn-danger btn-md" alt="act-create"><i class="fa fa-plus "></i> เพิ่ม</button> 
				</div>
			</div>
			   
            <!-- /.box-header -->
            <div class="box-body">
              	<div id="example1_wrapper" class="dataTables_wrapper form-inline dt-bootstrap">				  
					<div class="row">
						<div class="col-sm-12">
							<table id="example1" class="table table-striped table-bordered" width="100%">                                        
								<thead>			                
									<tr>
										<!-- <th data-class="expand">ID</th>											 -->
										<th data-hide="phone"><i class="fa fa-fw fa-user text-muted hidden-md hidden-sm hidden-xs"></i> pic</th>
										
										<th >ชื่อ-สกุล</th>
										<th ></th>
									</tr>
								</thead>                                        
								<tbody>
									<?php foreach ($models as $model): ?>
									<tr>
										<!-- <td class="text-center" ><?=$model->id ?></td>		 -->
										<td class="text-center" >
											<?=Html::img($model->getProfileImg(), [
												'class' => 'profile-user-img img-responsive img-circle act-profile-show',
												'data-id' => $model->id,
												'alt' => 'userPic',
												'height'=>'42'])?>
											<?= $model->username ?>
										</td>
																		
										<td >
											<?= $model->getProfileName()?> 												
											<br><?=$model->getProfileDep()?>
											<br><?=$model->getProfileWorkgroup()?>
											<br>Tel : <a href="tel:<?=$model->profile->phone?>"><?=$model->profile->phone?></a>
											<br><span class="label label-info act-update-role" data-id=<?=$model->id?>><?= $model->getRoleName($model->role)?></span>
											<?= $model->status == 0 ? '<span class="label label-danger">ระงับ</span>':'<span class="label label-primary">อนุญาต</span>';?>
											</td>
										<td>
											<?= Html::label('แก้ไข', 'update-profile', [
												'class' => 'btn btn-success btn-xs act-update-profile',
												'data-id' => $model->id]) ?>
											<?= Html::label('กำหนดสิทธิ์', 'update-role', [
												'class' => 'btn btn-info btn-xs act-update-role',
												'data-id' => $model->id]) ?>
											<?= Html::a('<i class="fa fa-gear fa-lg"></i> รีเซตพาสเวิร์ด', ['user/reset_pass', 'id' => $model->id], [
												'class' => 'btn  btn-xs btn-warning',
												'data-confirm'=>'Are you sure ?']) 
											?>
											<?= $model->status == 0 ?
												Html::a('เปิดใช้งาน', ['user/active', 'id' => $model->id], [
													'class' => 'btn btn-xs btn-primary',
													'data-confirm'=>'Are you sure this item?'])
												:
												Html::a('ระงับการใช้งาน', ['user/del', 'id' => $model->id], [
														'class' => 'btn btn-danger btn-xs ',
														'data-confirm'=>'Are you sure ?'])
												;?>
											<!-- <?= Html::a('ลบ', ['user/del', 'id' => $model->id], ['class' => 'btn btn-danger btn-xs','data-confirm'=>'Are you sure to ยกเลิก this item?']) ?> -->
											<!-- <a href="index.php?r=user/profile&id=<?=$model->id?>" class="btn btn-info btn-xs">แก้ไข </a>  -->
											<!-- <a href="index.php?r=user/del&id=<?=$model->id?>" data-confirm="Are you sure to ยกเลิก this item?" class="btn btn-danger btn-xs"> ระงับ</a>  -->
											
										</td>
									</tr>
									<?php  endforeach; ?>
								</tbody>
							</table>
						</div>
					</div>
				</div>
		    </div>
		</div>
	</div>		
</div>

<?php
$script = <<< JS
     
$(document).ready(function() {
	
	function init_click_handlers(){  

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

		var url_profile_show = "profile_show";
    	$(".act-profile-show").click(function(e) {            
			var fID = $(this).data("id");
			// alert(fID);
        	$.get(url_profile_show,{id: fID},function (data){
            	$("#activity-modal").find(".modal-body").html(data);
            	$(".modal-body").html(data);
            	$(".modal-title").html("แก้ไขข้อมูล ");
            	$("#activity-modal").modal("show");
        	});
    	});

		var url_update_role = "update_role";
    	$(".act-update-role").click(function(e) {            
			var fID = $(this).data("id");
			// alert(fID);
        	$.get(url_update_role,{id: fID},function (data){
            	$("#activity-modal").find(".modal-body").html(data);
            	$(".modal-body").html(data);
            	$(".modal-title").html("แก้ไขสิทธิ์");
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
	
	$('#example1').DataTable({
			"pageLength": 50,
			'ordering'    : false,
			'lengthChange': true,
			'paging'      : true,
    		// "order": [[ 0, 'desc' ]]
		})
		
	$('#example2').DataTable({
      'paging'      : true,
      'lengthChange': false,
      'searching'   : true,
      'ordering'    : true,
      'info'        : true,
      'autoWidth'   : false
    })

	
});
JS;
$this->registerJs($script);
?>