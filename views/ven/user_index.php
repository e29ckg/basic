
<?php

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'ผู้อยู่เวร';
$this->params['breadcrumbs'][] = ['label' => 'เวร', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row">
    <div class="col-xs-12">
       	<div class="box">
           	<div class="box-header">
           		<h3 class="box-title"><?=$this->title?></h3>
				   <div class="box-tools pull-right">
				<button id="act-create" class="btn btn-danger btn-md" alt="act-create"><i class="fa fa-pencil-square-o "></i> เพิ่ม</button>  
				</div>	
			</div>
			
            <!-- /.box-header -->
            <div class="box-body">
              	<div id="example1_wrapper" class="dataTables_wrapper form-inline dt-bootstrap">				  
					<div class="row">
						<div class="col-sm-6">
							<table id="example1" class="table table-bordered table-striped dataTable" role="grid" >
                				<thead>
                					<tr role="row">										
										<th class="text-center" >ลำดับ</th>
										<th class="text-center" >กลางวัน</th>
										<th class="text-center" ></th>
										<th></th>
									</tr>
                				</thead>
                				<tbody>  									                          
									<?php foreach ($models_D as $model): ?>
						            <tr>						                
										<td><?=$model->order?></td>	
										<td><?=$model->getProfileName();?></td>									
                                        <td><?=$model->getVen_DN()[$model->DN];?> | <?=$model->price;?></td>										
										<td>
											<button class="btn btn-warning btn-xs act-update" alt="act-update" data-id="<?=$model->id?>">
												<i class="fa fa-pencil-square-o "></i> แก้ไข</button>  
												<?=Html::a('<i class="fa fa-remove"></i> ลบ ', ['ven/user_del', 'id' => $model->id], [
													'class' => 'btn btn-danger btn-xs',
													'data-confirm' => 'Are you sure?',
													'data-method' => 'post',
												]) ?>
										</td>
									</tr>
									<?php  endforeach; ?>								
								</tbody>
							</table>
						</div>

						<div class="col-sm-6">
							<table id="example1" class="table table-bordered table-striped dataTable" role="grid" >
                				<thead>
                					<tr role="row">										
										<th class="text-center" >ลำดับ</th>
										<th class="text-center" >เวรกลางคืน</th>
										<th class="text-center" ></th>
										<th></th>
									</tr>
                				</thead>
                				<tbody>  									                          
									<?php foreach ($models_N as $model): ?>
						            <tr>						                
										<td><?=$model->order?></td>	
										<td><?=$model->getProfileName();?></td>									
                                        <td><?=$model->getVen_DN()[$model->DN];?> | <?=$model->price;?></td>
										
										<td>
											<button class="btn btn-warning btn-xs act-update" alt="act-update" data-id="<?=$model->id?>">
												<i class="fa fa-pencil-square-o "></i> แก้ไข</button>  
												<?=Html::a('<i class="fa fa-remove"></i> ลบ ', ['ven/user_del', 'id' => $model->id], [
													'class' => 'btn btn-danger btn-xs',
													'data-confirm' => 'Are you sure?',
													'data-method' => 'post',
												]) ?>
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
<div id=content1></div>

<?php

$script = <<< JS
    
$(document).ready(function() {	
	
	  
			
	$('#activity-modal').on('hidden.bs.modal', function () {
 		location.reload();
	})

	var url_update = "user_update";
    	$(".act-update").click(function(e) {            
			var fID = $(this).data("id");
			// alert(fID);
        	$.get(url_update,{id: fID},function (data){
            	$("#activity-modal").find(".modal-body").html(data);
            	$(".modal-body").html(data);
            	$(".modal-title").html("แก้ไขข้อมูล");
            	$("#activity-modal").modal("show");
        	});
		}); 
		
		var url_create = "user_create";
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
			
		// $('#example1').DataTable({
		// 	"pageLength": 50,
		// 	'ordering'    : false,
		// 	'lengthChange': true,
		// 	'paging'      : true,
    	// 	// "order": [[ 0, 'desc' ]]
			
		// })
});
JS;
$this->registerJs($script);
?>