<?php

use yii\helpers\Html;
use yii\bootstrap\Modal;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\web\UploadedFile;
use yii\helpers\BaseFileHelper;
use yii\helpers\Json;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ProfileSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'คำพากษาส่งภาค 7');
$this->params['breadcrumbs'][] = $this->title;
// echo var_dump($countM1);
?>
<div class="box box-primary">
	<div class="box-header with-border">
		<h3 class="box-title"><?= Html::encode($this->title) ?></h3>
		<div class="box-tools pull-right">
			<button id="act-create" class="btn btn-danger btn-md" alt="act-create"><i class="fa fa-pencil-square-o "></i> เพิ่ม</button>  
		</div>	
	</div>
	<div class="box-body">
		<div id="wra_example1" class="dataTables_wrapper form-inline dt-bootstrap">
			<table id="datatable_fixed_column" class="table table-striped table-bordered" width="100%">
				<thead>
					<tr>
						<th data-class="expand">Id</th>
						<th >เลขดำ</th>
						<th >คำพิพากษา</th>
						<th >ร่าง</th>
						<th>ผู้บันทึก</th>
						<th >Date</th>
						<th ></th>
						
					</tr>
				</thead>
				<tbody>
					<?php foreach ($models as $model): ?>
					<tr>
						<td><?=$model->id?></td>	
						<td><?=$model->name?></td>					                								        
						<td><?= Html::a($model->file ? 'คำพากษา<i class="fa fa-file-pdf-o"></i>':'',['ppss/show','id' => $model->id],['target' => '_blank']);?></td>
						<td><?= Html::a($model->file2 ? 'ร่าง<i class="fa fa-file-pdf-o"></i>':'',['ppss/show2','id' => $model->id],['target' => '_blank']);?></td>
						<td><?=$model->create_own?></td>
						<td><?=$model->create_at?></td>
						<td>
							<a herf= "#" class="btn btn-warning act-update" data-id=<?=$model->id?>><i class="fa fa-pencil-square-o"></i> แก้ไข</a>
							<a herf= "#" class="btn btn-warning act-view" data-id=<?=$model->id?>><i class="fa fa-pencil-square-o"></i> ดู</a>
							<?php if(Yii::$app->user->identity->role == 9){
								echo '<a herf= "#" class="btn btn-warning act-update-admin" data-id='.$model->id.'><i class="fa fa-pencil-square-o"></i> ดู </a> ';
								echo Html::a('<i class="fa fa-remove"></i> ลบ',['ppss/delete','id' => $model->id],
								[
									'class' => 'btn btn-danger',
									'data-confirm' => 'Are you sure to delete this item?',
									'data-method' => 'post',
								]);
							}  ?>
							
						</td>
					</tr>
					<?php  endforeach; ?>
				</tbody>	
			</table>
		</div>
	</div>

</div>

   


<?php

$script = <<< JS

$('#eg8').click(function() {
		
        $.smallBox({
			title : "James Simmons liked your comment",
			content : "<i class='fa fa-clock-o'></i> <i>2 seconds ago...</i>",
            color : "#296191",
            iconSmall : "fa fa-thumbs-up bounce animated",
            timeout : 4000
        });

    });

     
$(document).ready(function() {	
/* BASIC ;*/	
		$("#viewM1").click(function(e) {
            var data = $(this).attr("data");
                $.get("viewm1",{year:data},
                    function (data)
                    {
                        $("#activity-modal").find(".modal-body").html(data);
                        $(".modal-body").html(data);
                        $(".modal-title").html("เพิ่มข้อมูล");
                        $("#activity-modal").modal("show");
                    }
                );
            });
        
        $("#viewM2").click(function(e) {
            var data = $(this).attr("data");
                $.get("viewm2",{year:data},
                    function (data)
                    {
                        $("#activity-modal").find(".modal-body").html(data);
                        $(".modal-body").html(data);
                        $(".modal-title").html("เพิ่มข้อมูล");
                        $("#activity-modal").modal("show");
                    }
                );
            });	
       
	function init_click_handlers(){        	
		
		var url_update = "update";
    	$(".act-update").click(function(e) {            
			var fID = $(this).data("id");
			// alert(fID);
        	$.get(url_update,{id: fID},function (data){
            	$("#activity-modal").find(".modal-body").html(data);
            	$(".modal-body").html(data);
            	$(".modal-title").html("แก้ไขข้อมูลสมาชิก");
            	$("#activity-modal").modal("show");
        	});
		});
		
		var url_update_admin = "update_admin";
    	$(".act-update-admin").click(function(e) {            
			var fID = $(this).data("id");
			// alert(fID);
        	$.get(url_update_admin,{id: fID},function (data){
            	$("#activity-modal").find(".modal-body").html(data);
            	$(".modal-body").html(data);
            	$(".modal-title").html("แก้ไขข้อมูลสมาชิก");
            	$("#activity-modal").modal("show");
        	});
    	});

    	var url_view = "view";		
    	$(".act-view").click(function(e) {			
                var fID = $(this).data("id");
                $.get(url_view,{id: fID},function (data){
                        $("#activity-modal").find(".modal-body").html(data);
                        $(".modal-body").html(data);
                        $(".modal-title").html("ข้อมูล");
                        $("#activity-modal").modal("show");
                    }
                );
            });   
    
	}

    init_click_handlers(); //first run
			
	$('#activity-modal').on('hidden.bs.modal', function () {
 		location.reload();
	})

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

		
});
JS;
$this->registerJs($script);
?>