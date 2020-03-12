
<?php

use yii\helpers\Html;
// use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'ใบลาทั้งหมด ';
$this->params['breadcrumbs'][] = ['label' => 'โปรแกรมใบลา', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row">
    <div class="col-xs-12">
       	<div class="box">
           	<div class="box-header">
				   <h3 class="box-title"><?=$this->title?></h3>
				   <div class="box-tools pull-right">
				   		<button id="act-create-go" class="btn btn-info btn-md" alt="act-create-go"><i class="fa fa-pencil-square-o "></i> ไปราชการ </button>  
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
										<th class="text-center" style="width: 100px;">ชื่อ</th>
										<th class="text-center" style="width: 224px;">รายละเอียด</th>
										<th class="text-center" style="width: 100px;">ไฟล์</th>
									</tr>
                				</thead>
                				<tbody>  
									                          
									<?php foreach ($models as $model): ?>
						            <tr>						                
										<td>
											<?=$model->getProfileName();?> 
											<?=$model->cat == 'ไปราชการ' ? '<span class="label label-info">'.$model->cat.'</span>' : '';?>
											<?=$model->cat == 'ลาป่วย' || $model->cat == 'ลากิจส่วนตัว' || $model->cat == 'ลาคลอดบุตร' ? '<span class="label label-danger">'.$model->cat.'</span>' : '' ; ?>
											<?=$model->cat == 'ลาพักผ่อน' ? '<span class="label label-primary">'.$model->cat.'</span>' : '' ;?>											
											<br><?= isset($model->running) ? $model->running : $model->id;?>
										</td>										
                                        <td><?=$model->DateThai_full($model->date_begin)?>
											ถึง <?=$model->DateThai_full($model->date_end)?>
											<br> ลาครั้งนี้ <?=$model->date_total?> วัน
											<?= $model->status == 4 ? '<span class="label label-danger">ยกเลิกการลา</span>' : '' ;?>
										</td>
										<td class = "text-center">
										<?=$model->cat <> 'ไปราชการ' ? Html::a('<i class="fa fa-print"></i> Print ', ['bila/print1','id' => $model->id], [
												'class' => 'btn btn-primary btn-xs',
												'data-id' => $model->id,
												'target' => '_blank'
											]) : '';?>

											<?= !empty($model->file) ? 
											Html::a('ไฟล์เอกสาร', ['bila/file_view','id' => $model->id], [
												
												'data-id' => $model->id,
												'target' => '_blank'
											]).' '	
											.Html::a('<i class="fa fa-remove"></i> ลบไฟล์ ', ['bila/file_del','id' => $model->id], [
												'class' => 'btn btn-danger btn-block btn-xs',
												'data-id' => $model->id,
												'data-confirm' => 'Are you sure to delete this item?',
                                    			'data-method' => 'post',
											]) 																			
											:
											Html::a('<i class="fa fa-print"></i> แนบไฟล์ ', '#', [
												'class' => 'act-file-up btn btn-success btn-xs',
												'data-id' => $model->id,
												// 'target' => '_blank'
											]).' '
											 
											. ' <a href= "#" class="btn btn-warning btn-xs act-update" data-id='.$model->id.'><i class="fa fa-wrench"></i> แก้ไข</a>';
							
											;?>
											<!-- <a href="#" class="act-file-up btn btn-danger btn-xs" data-id=<?=$model->id?>>แนบไฟล์</a>  -->
																				
											<?=
											 	Html::a('<i class="fa fa-remove"></i> ลบ',['bila/delete','id' => $model->id],
													[
														'class' => 'btn btn-danger btn-xs',
														'data-confirm' => 'Are you sure to delete this item?',
                                    					'data-method' => 'post',
													]);
											?>						        
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
	
	  
			
	$('#activity-modal').on('hidden.bs.modal', function () {
 		// location.reload();
	})

	var url_update = "update";
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
		
	var url_file_up = "file_up";		
    	$(".act-file-up").click(function(e) {			
			var fID = $(this).data("id");
			$.get(url_file_up,{id: fID},function (data){
					$("#activity-modal").find(".modal-body").html(data);
					$(".modal-body").html(data);
					$(".modal-title").html("ข้อมูล");
					$("#activity-modal").modal("show");
				}
			);
		});

		var url_create_go = "governor_create";
    	$( "#act-create-go" ).click(function() {
        	$.get(url_create_go,function (data){
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