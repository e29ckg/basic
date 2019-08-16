
<?php

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
function DateThai_full($strDate)
	{
		$strYear = date("Y",strtotime($strDate))+543;
		$strMonth= date("n",strtotime($strDate));
		$strDay= date("j",strtotime($strDate));
		$strHour= date("H",strtotime($strDate));
		$strMinute= date("i",strtotime($strDate));
		$strSeconds= date("s",strtotime($strDate));
		$strMonthCut = Array("","มกราคม","กุมภาพันธ์","มีนาคม","เมษายน","พฤษภาคม","มิถุนายน","กรกฎาคม",
                            "สิงหาคม","กันยายน","ตุลาคม","พฤศจิกายน","ธันวาคม");
		$strMonthThai=$strMonthCut[$strMonth];
		return "$strDay $strMonthThai $strYear";
    }

$this->title = 'ใบลาทั้งหมด';
$this->params['breadcrumbs'][] = ['label' => 'โปรแกรมใบลา', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row">
    <div class="col-xs-12">
       	<div class="box">
           	<div class="box-header">
           		<h3 class="box-title"><?=$this->title?></h3>
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
										<td><?=$model->getProfileName();?> 
											<?=$model->cat == 'ลาป่วย' ? 
												'<span class="label label-danger">'.$model->cat.'</span>'
											: 
												'<span class="label label-primary">'.$model->cat.'</span>'
											?>
											
											<br><?= $model->id?>
										</td>										
                                        <td><?=DateThai_full($model->date_begin)?>
											ถึง <?=DateThai_full($model->date_end)?>
											<br> ลาครั้งนี้ <?=$model->date_total?> วัน
										</td>
										<td>
											<?= !empty($model->file) ? 
											Html::a('ไฟล์เอกสาร', ['bila/file_view','id' => $model->id], [
												// 'class' => 'btn btn-danger btn-xs',
												'data-id' => $model->id,
												'target' => '_blank'
											]).' '	
											.Html::a('<i class="fa fa-remove"></i> ลบไฟล์ ', ['bila/file_del','id' => $model->id], [
												'class' => 'btn btn-danger btn-xs',
												'data-id' => $model->id,
												'data-confirm' => 'Are you sure to delete this item?',
                                    			'data-method' => 'post',
											]) 																			
											:
											Html::a('<i class="fa fa-print"></i> แนบไฟล์ ', '#', [
												'class' => 'act-file-up btn btn-danger btn-xs',
												'data-id' => $model->id,
												// 'target' => '_blank'
											]).' '
											.Html::a('<i class="fa fa-print"></i> Print ', ['bila/print1','id' => $model->id], [
												'class' => 'btn btn-primary btn-xs',
												'data-id' => $model->id,
												'target' => '_blank'
											]) 
											. ' <a href= "#" class="btn btn-warning btn-xs act-update" data-id='.$model->id.'><i class="fa fa-wrench"></i> แก้ไข</a>';
							
											;?>
											<!-- <a href="#" class="act-file-up btn btn-danger btn-xs" data-id=<?=$model->id?>>แนบไฟล์</a>  -->
																				
											<?php
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
			
		$('#example1').DataTable({
		// 'order' 	: false,
		'paging'      : true,
		'lengthChange': false,
		'searching'   : true,
		'ordering'    : false,
		'info'        : true,
		'autoWidth'   : false
		})
		
});
JS;
$this->registerJs($script);
?>