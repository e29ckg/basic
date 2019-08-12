<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Line';
$this->params['breadcrumbs'][] = $this->title;
?>
<?=Html::a('ลงทะเบียน', $result, ['class' => 'btn btn-success'])?>
<?=Html::a('https://notify-bot.line.me/th/', 'https://notify-bot.line.me/th/', ['class' => 'btn btn-success'])?>

 <!-- Default box -->
<div class="box box-primary">
	<div class="box-header with-border">
		<h3 class="box-title"><?= Html::encode($this->title) ?></h3>
		<div class="box-tools pull-right">
			<button id="act-create" class="btn btn-danger btn-md" alt="act-create-a"><i class="fa fa-pencil-square-o "></i> เพิ่ม</button>  
		</div>	
	</div>	
	<div class="box-body">
		<div id="wra_example1" class="dataTables_wrapper form-inline dt-bootstrap">
		<table id="example1" class="table table-striped table-bordered" width="100%">
			<thead>
				<tr>
					<th data-class="expand">Id</th>
					<th >name</th>
					<th>Token</th>	
					<th></th>					
				</tr>
			</thead>
					<tbody>
						<?php foreach ($models as $model): ?>
						<tr>
							<td><?=$model->id?></td>
							<td><?= $model->name ?></td>
							<td><?= $model->token?></td>
							<td>
								<a href= "#" class="btn btn-warning act-update" data-id=<?=$model->id?>><i class="fa fa-pencil-square-o"></i> แก้ไข</a>
								<?= Html::a('<i class="fa fa-remove"></i> ลบ',['line/line_del','id' => $model->id],
										[
											'class' => 'btn btn-danger act-update',
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

<?php

$script = <<< JS
     
$(document).ready(function() {	
/* BASIC ;*/

	$('#example1').DataTable()

	function init_click_handlers(){        	
		
		var url_update = "line_update";
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
			
	$('#activity-modal').on('hidden.bs.modal', function () {
 		location.reload();
	})

		var url_create = "line_create";
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


//load_data();

		
});
JS;
$this->registerJs($script);
?>