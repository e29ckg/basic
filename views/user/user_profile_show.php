
<?php

// use yii\helpers\Html;
// use yii\helpers\Url;

$this->title = 'ข้อมูลส่วนตัว';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row">
  <div class="col-md-5">
    <div class="row">  
      <div class="col-md-12">
          <div class="box box-primary">
            <div class="box-body box-profile">
              <img class="profile-user-img img-responsive img-circle" src="<?= $model->getProfileImg($model->img);  ?>" alt="profile picture">

              <h3 class="profile-username text-center"><?= $model->profile->fname.$model->profile->name.' '.$model->profile->sname;?> </h3>

              <p class="text-muted text-center"><?=$model->profile->dep?></p>

              <ul class="list-group list-group-unbordered">
                <li class="list-group-item">
                  <b>Id Card</b> <a class="pull-right"><?=$model->profile->id_card?></a>
                </li>
                <li class="list-group-item">
                  <b><i class="fa fa-phone"></i> Phone</b> <a href="tel:<?=$model->profile->phone?>"class="pull-right"><?=$model->profile->phone?></a>
                </li>
                <li class="list-group-item">
                  <b>วันเกิด</b> <a class="pull-right"><?=$model->profile->birthday?></a>
                </li>
              </ul>
              <a id="act-update-profile" data-id="<?=$model->profile->user_id?>" href="javascript:void(0);" class="btn btn-primary btn-block"><i class="fa fa-gear fa-spin fa-lg"></i> แก้ไขข้อมูล </a>
                        
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
      </div>
    </div>
    
    
  </div>
    
  <!-- Profile Image -->  
  <div class="col-md-7">    
    <div class="box box-primary">
      <div class="box-header with-border">
        <h3 class="box-title">About Me</h3>
      </div>
      <div class="box-body">
        <strong><i class="fa fa-book margin-r-5"></i> Line Token :: </strong>
           
        <p class="text-muted">  
            <?=  !empty($mdLine->token) ? $mdLine->token
              .'<button data-id ="'.$mdLine->token.'" class="act-line-send btn btn-primary btn-xs" alt="act-line-send"><i class="fa fa-pencil-square-o "></i> ทดสอบการส่ง</button>'
              : 'ยังไม่ได้ลงทะเบียน Line';?> 
        </p>

        <hr>
        <strong><i class="fa fa-map-marker margin-r-5"></i> Location</strong>
        <p class="text-muted"><?=$model->profile->address?></p>
        <hr>
        <strong><i class="fa fa-pencil margin-r-5"></i> Skills</strong>
        <p>
          <!-- <span class="label label-danger">UI Design</span>
          <span class="label label-success">Coding</span>
          <span class="label label-info">Javascript</span>
          <span class="label label-warning">PHP</span>
          <span class="label label-primary">Node.js</span> -->
        </p>
        <hr>
        <strong><i class="fa fa-file-text-o margin-r-5"></i> Notes</strong>
        <p> </p>
      </div>
    </div>
  </div>
</div>

<?php
$script = <<< JS

$(document).ready(function() {	
	$('#activity-modal').on('hidden.bs.modal', function () {
 		location.reload();
	}) 

		var url_update_profile = "update_profile";		
			$( "#act-update-profile" ).click(function() {
				var fID = $(this).data("id");	
        	$.get(url_update_profile,{id: fID},function (data){
                $("#activity-modal").find(".modal-body").html(data);
                $(".modal-body").html(data);
                $(".modal-title").html("แก้ไข");
            	// $(".modal-footer").html(footer);
                $("#activity-modal").modal("show");
        	});     
		});

    var url_line_send = "user_line_send";
    	$(".act-line-send").click(function(e) {            
			var fID = $(this).data("id");
			// alert(fID);
        	$.get(url_line_send,{token: fID},function (data){
            	$("#activity-modal").find(".modal-body").html(data);
            	$(".modal-body").html(data);
            	$(".modal-title").html("LineSend");
            	$("#activity-modal").modal("show");
        	});
    	});    

	});
JS;
$this->registerJs($script);
?>