
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
							<table id="example1" class="table table-bordered table-striped dataTable" role="grid" aria-describedby="example1_info">
                				<thead>
                					<tr role="row">
										<th class="sorting_asc" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-sort="ascending" aria-label="" style="width: 50px;">#</th>
										<th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="" style="width: 224px;">ชื่อ</th>
										<th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="" style="width: 100px;">ประเภทการลา</th>
										<th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="" style="width: 224px;">ลาตั้งแต่</th>
										<th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="" style="width: 199px;">ถังวันที่</th>
										<th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="" style="width: 120px;">รวมการลา(วัน)</th>
										<th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="" style="width: 200px;">ไฟล์</th>
										<th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="" style="width: 200px;"></th>
									</tr>
                				</thead>
                				<tbody>  
									<?php $i = 1?>                              
									<?php foreach ($models as $model): ?>
						            <tr>
						                <td><?= $i++ . $model->id?></td>
										<td><?=$model->getProfileName();?></td>
										<td class="img-weblink" ><?=$model->cat?></td>										
                                        <td><?=DateThai_full($model->date_begin)?></td>										
                                        <td><?=DateThai_full($model->date_end)?></td>	
										<td><?=$model->date_total?></td>
										<td>
											<?= !empty($model->file) ? 
											Html::a('<i class="fa fa-file-o"></i> ไฟล์เอกสาร ', ['bila/file_view','id' => $model->id], [
												'class' => 'btn btn-xs',
												'data-id' => $model->id,
												'target' => '_blank'
											])										
											:
											Html::a('<i class="fa fa-print"></i> แนบไฟล์ ', '#', [
												'class' => 'act-file-up btn btn-danger btn-xs',
												'data-id' => $model->id,
												// 'target' => '_blank'
											])
											;?>
											<!-- <a href="#" class="act-file-up btn btn-danger btn-xs" data-id=<?=$model->id?>>แนบไฟล์</a>  -->
										</td>					
										<td>
										<?= !empty($model->file) ? 
											Html::a('<i class="fa fa-remove"></i> ลบไฟล์ ', ['bila/file_del','id' => $model->id], [
												'class' => 'btn btn-danger btn-xs',
												'data-id' => $model->id,
												'data-confirm' => 'Are you sure to delete this item?',
                                    			'data-method' => 'post',
											]) 
											 :
											 Html::a('<i class="fa fa-print"></i> Print ', ['bila/print1','id' => $model->id], [
												'class' => 'btn btn-primary btn-xs',
												'data-id' => $model->id,
												'target' => '_blank'
											]) . ' ' . 
											Html::a('<i class="fa fa-wrench"></i> แก้ไข','#',
											[
												'class' => 'btn btn-warning btn-xs act-update',
												'data-id' => $model->id,
											]) 
											?>
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
	
	var table = $('#example1').DataTable({
		rowReorder: {
			selector: 'td:nth-child(2)'
		},
		responsive: true
})
	$('#example2').DataTable({
      'paging'      : true,
      'lengthChange': false,
      'searching'   : true,
      'ordering'    : true,
      'info'        : true,
      'autoWidth'   : false
    })

/* BASIC ;*/	
		        
	function init_click_handlers(){    

		var url_show = "show";				
			$( ".act-show" ).click(function() {
				var fID = $(this).data("id");
        	$.get(url_show,{id: fID},function (data){
                $("#activity-modal").find(".modal-body").html(data);
                $(".modal-body").html(data);
                $(".modal-title").html("show");
            	// $(".modal-footer").html(footer);
                $("#activity-modal").modal("show");
                //   $("#myModal").modal('toggle');
        	});     
		});

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
    
	}

    init_click_handlers(); //first run
			
	$('#activity-modal').on('hidden.bs.modal', function () {
 		location.reload();
	})


		// var url_create = "index.php?r=bila/create";
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

		var url_create = "createb";
    	$( "#act-create-b" ).click(function() {
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