<?php

use yii\helpers\Html;
use yii\bootstrap\Modal;
use yii2mod\alert\Alert;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $searchModel backend\models\ProfileSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'รายชื่อที่ปรึกษากฎหมาย';
$this->params['breadcrumbs'][] = $this->title;
?>

<!-- MAIN CONTENT -->

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
											<?=Html::img($model->getImg($model->img), [
												'class' => 'profile-user-img img-responsive img-circle act-profile-show',
												'data-id' => $model->id,
												'alt' => 'userPic',
												'height'=>'42'])?>
											<?= $model->fname.$model->name.' '.$model->sname ?>
										</td>
																		
										<td >
											<?= $model->fname.$model->name.' '.$model->sname ?>
										</td>
										<td>
											<?= Html::label('แก้ไข', 'update', [
												'class' => 'btn btn-success btn-xs act-update',
												'data-id' => $model->id]) ?>																					
											
											<?= Html::a('ลบ', ['legal_c/delete', 'id' => $model->id], ['class' => 'btn btn-danger btn-xs','data-confirm'=>'Are you sure to ยกเลิก this item?']) ?>
												
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

		var url_update = "update";
    	$(".act-update").click(function(e) {            
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
    
	var url_create = "create";
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