<?php

use yii\helpers\Html;
use yii\helpers\Url;
use app\models\CLetter;

$data = json_decode($data);
// arsort($data);
// krsort($data,3);
// var_dump($data);
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'หนังสือเวียนทราบ';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="box box-primary">
	<div class="box-header with-border">
		<h3 class="box-title"><?= Html::encode($this->title) ?></h3>
	</div>	
	<div class="box-body">
		<div id="wra_example1" class="dataTables_wrapper form-inline dt-bootstrap">
			<table id="example1" class="table table-striped table-bordered" width="100%">
				<thead>
					<tr>
						<th class="text-center">Id</th>
						<th class="text-center">เรื่อง</th>
						<th class="text-center"style="width:150px">ประเภท</th>			
					</tr>
				</thead>
				<tbody>
					<?php foreach ($data as $d): ?>
					<tr>
						<td><i class="fa fa-bullhorn" alt="<?=$d->id?>"></i></td>
						<td><?= $d->file ? 
							// '<a href="#" class="act-show" data-id="'.$model->id.'">'.$model->name.'</a>'
							'<a href="'.Url::to(['show','id'=>$d->id,'ca_name'=>$d->ca_name]).'" target="_blank" data-id="'.$d->id.'">'.$d->name.'</a>'
							: $d->name;
							?>
						</td>
						<td><?=$d->ca_name?><br><?=Cletter::DateThai_full($d->created_at);?></td>
					</tr>
					<?php  endforeach; ?>
				</tbody>	
			</table>
		</div>
	</div>
</div>
 <!-- Default box -->
<!--  -->



<?php

$script = <<< JS
     
$(document).ready(function() {	
/* BASIC ;*/
	
		$('#example1').DataTable({
			"pageLength": 100,
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

	var url_show = "show";
	$( ".act-show" ).click(function() {
		var fID = $(this).data("id");
			// alert(fID);
        	$.get(url_show,{id: fID},function (data){
			$("#activity-modal").find(".modal-body").html(data);
			$(".modal-body").html(data);
			$(".modal-title").html("show");
			// $(".modal-footer").html(footer);
			$("#activity-modal").modal("show");
			//   $("#myModal").modal('toggle');
		});     
	}); 
	
		
});
JS;
$this->registerJs($script);
?>