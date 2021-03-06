
<?php

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'คำสั่ง';
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
						<div class="col-sm-12">
							<table id="example1" class="table table-bordered table-striped dataTable" role="grid" >
                				<thead>
                					<tr role="row">										
										<th class="text-center" >id</th>
										<th class="text-center" >เลขที่คำสั่ง</th>
										<th class="text-center" >ชื่อคำสั่ง</th>
										<th class="text-center" >status</th>
										<th></th>
									</tr>
                				</thead>
                				<tbody>  
									                          
									<?php foreach ($models as $model): ?>
						            <tr>						                
										<td><?=$model->id.' - '?>
											<?=Html::a('report1', ['ven/report', 'ven_com_num' => $model->ven_com_num], [
													'class' => 'btn btn-sucess btn-xs',
													'target' => '_blank',
												]) ?>
											<?=Html::a('report2', ['ven/report2', 'ven_com_num' => $model->ven_com_num], [
												'class' => 'btn btn-sucess btn-xs',
												'target' => '_blank',
											]) ?>
												</td>
													
										<td><?=$model->ven_com_num. ' '. $model->ven_com_date;?><br>เดือน <?=$model->ven_month;?></</td>									
                                        <td><?=$model->getVen_time()[$model->ven_time];?><br><?=$model->ven_com_name;?></td>
										<td class = "text-center">
											<a href="<?=Url::to(['ven/com_set_status','id'=>$model->id])?>">
													<?=$model->status == 1 ?
													'<span class="label label-primary ">ใช้งาน</span>' 
													:
													'<span class="label label-danger " >ไม่ใช้งาน</span>' ;?>				        
											</a>
										</td>
										<td>
											<button class="btn btn-warning btn-xs act-update" alt="act-update" data-id="<?=$model->id?>">
												<i class="fa fa-pencil-square-o "></i> แก้ไข</button>  
												<?=Html::a('<i class="fa fa-remove"></i> ลบ ', ['ven/com_del', 'id' => $model->id], [
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

	var url_update = "com_update";
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
		
		var url_create = "com_create";
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
});
JS;
$this->registerJs($script);
?>