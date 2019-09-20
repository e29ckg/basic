<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'หนังสือเวียนทราบ';
$this->params['breadcrumbs'][] = $this->title;
?>

 <!-- Default box -->
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
					<?php foreach ($models as $model): ?>
					<tr>
						<td><?=$model->id?></td>
						<td><?= $model->file ? Html::a($model->name,['cletter/show','id' => $model->id],['target' => '_blank']) : $model->name;?></td>
						<td><?=$model->ca_name?><br><?=$model->DateThai_full($model->created_at);?></td>
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
	
	
		
});
JS;
$this->registerJs($script);
?>