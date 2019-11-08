
<?php

use yii\helpers\Html;
use yii\helpers\Url;


$this->title = 'จัดการกลุ่มงาน';
$this->params['breadcrumbs'][] = ['label' => 'จัดการสมาชิก', 'url' => ['user/user_index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row">
    <div class="col-xs-12">
       	<div class="box">
           	<div class="box-header">
				   <h3 class="box-title"><?= Html::encode($this->title) ?></h3>
				<div class="box-tools pull-right">
					<button id="act-create" class="btn btn-danger btn-md" alt="act-create"><i class="fa fa-plus "></i> เพิ่ม </button>  
				</div>
			</div>
			   
            <!-- /.box-header -->
            <div class="box-body">
              	<div id="example1_wrapper" class="dataTables_wrapper form-inline dt-bootstrap">				  
					<div class="row">
						<div class="col-sm-12">
							<table id="example1" class="table table-bordered table-striped dataTable" role="grid" aria-describedby="example1_info">
                				<thead>
                					<tr role="row">
										<th class="sorting_asc" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-sort="" aria-label="" style="width: 20px;">#</th>
										<th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="" style="width: 224px;">ชื่อตำแหน่ง</th>
										<th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="" style="width: 224px;"></th>
									</tr>
                				</thead>
                				<tbody>  
									<?php $i = 1?>                              
									<?php foreach ($models as $model): ?>
						            <tr>
						                <td><?= $i++?></td>
										<td><?=$model->name?></td>										
										<td>
											<a href="#" class="act-update btn btn-info btn-xs" data-id=<?=$model->id?>>แก้ไข</a> 
											<?= Html::a('<i class="fa fa-remove"></i> ลบ',['user/group_delete','id' => $model->id],
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

		var url_update = "group_update";
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
    
	}

    init_click_handlers(); //first run
			
	
		// var url_create = "index.php?r=bila/create";
		var url_create = "group_create";
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