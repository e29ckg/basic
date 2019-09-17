
<?php

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'ใบเปลี่ยน/ยก';
$this->params['breadcrumbs'][] = ['label' => 'เวร', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row">
    <div class="col-xs-12">
       	<div class="box">
           	<div class="box-header">
           		<h3 class="box-title"><?=$this->title?></h3>
				   <div class="box-tools pull-right">
				<!-- <button id="act-create" class="btn btn-danger btn-md" alt="act-create"><i class="fa fa-pencil-square-o "></i> เพิ่ม</button>   -->
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
										<th class="text-center" >เลขที่ใบเปลี่ยน</th>
										<th class="text-center" >เวร</th>
										<th class="text-center" >เวร</th>
										<th class="text-center" >รายละเอียด</th>
										<th></th>
									</tr>
                				</thead>
                				<tbody>  
									                          
									<?php foreach ($models as $model): ?>
						            <tr>						                
										<td><?=$model->id;?></td>	
										<td><?= isset($model->ven_id1) ? 
											$model->DateThai_full($model->ven1['ven_date'])
											.'<br>'.$model->ven1->getProfileName()
											.'<br>('.$model->ven_id1.')'
											.'<br>'.$model->getStatusList()[$model->status]
											: '-';?>
										</td>									
										<td>
											<?= isset($model->ven_id2) ?
												$model->DateThai_full($model->ven2->ven_date)
												.'<br>'.$model->ven2->getProfileName()
												.'<br>('.$model->ven_id2.')'
												.'<br>'.$model->getStatusList()[$model->status]
												: '-' ;
											?></td>
										<td>
											เนื่องจาก : <?=$model->comment?>
											<br>ผอ. : <?= $model->s_po ? $model->getS_SS($model->s_po)->name : '';?>
											<br>หัวหน้า : <?= $model->s_bb ? $model->getS_SS($model->s_bb)->name : '';?>
											<br>สร้างโดย <?=$model->getProfileName()?>
											<br>
											<?= empty($model->file) ? 
											'<br><button class="btn btn-warning btn-xs btn-block act-update " alt="act-update" data-id="'.$model->id.'">แก้ไข</button>'
											:'';?>
										</td>
										<td class = "text-center">
											
											<?= !empty($model->file) ? 
												Html::a('ไฟล์เอกสาร', ['ven/change_file_view','id' => $model->id], [													
													'data-id' => $model->id,
													'target' => '_blank'
												]).' '	
												.Html::a('<i class="fa fa-remove"></i> ลบไฟล์ ', ['ven/change_del_file','id' => $model->id], [
													'class' => 'btn btn-danger btn-block btn-xs',
													'data-id' => $model->id,
													'data-confirm' => 'Are you sure to delete this item?',
													'data-method' => 'post',
												]) 																			
												:
												Html::a('<i class="fa fa-print"></i> Print ', ['ven/print','id' => $model->id], [
													'class' => 'btn btn-primary btn-xs',
													'data-id' => $model->id,
													'target' => '_blank'
												])
												.' '.Html::a('<i class="fa fa-print"></i> แนบไฟล์ ', '#', [
													'class' => 'act-change-upfile btn btn-success btn-xs',
													'data-id' => $model->id,
													// 'target' => '_blank'
												]).' '
												 
												.' ' .Html::a('<i class="fa fa-remove"></i> ยกเลิก ', ['ven/change_del_user', 'id' => $model->id], [
													'class' => 'btn btn-danger btn-xs',
													'data-confirm' => 'Are you sure?',
													'data-method' => 'post',]) 
													
													; ?>
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

	var url_update = "ven_change_update";
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

		
    	  
		
		// }
		
		var url_change_upfile = "change_upfile";
    	$( ".act-change-upfile" ).click(function() {
			var fID = $(this).data("id");
        	$.get(url_change_upfile,{id: fID},function (data){
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