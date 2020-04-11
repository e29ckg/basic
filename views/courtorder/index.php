<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'คำสั่ง';
$this->params['breadcrumbs'][] = $this->title;
?>
<section class="content">
	<div class="row">
		<div class="col-md-6">
			<div class="row">
				<div class="col-md-12">
					<!-- Default box -->
					<div class="box box-danger">
						<div class="box-header with-border">
							<h3 class="box-title">คำสั่งศาลฯ</h3>
							<div class="box-tools pull-right">
								<button id="act-create" class="btn btn-danger btn-md" alt="act-create"><i class="fa fa-plus "></i> เพิ่ม คำสั่งศาลฯ</button>
							</div>
						</div>
						<div class="box-body">
							<div id="wra_example1" class="dataTables_wrapper form-inline dt-bootstrap">
								<table id="example1" class="table table-striped table-bordered" width="100%">
									<thead>
										<tr>
											<th class="text-center">เรื่อง</th>
											<th class="text-center"style="width:50px">ผู้บันทึก</th>
										</tr>
									</thead>
									<tbody>
										<?php foreach ($models as $model): ?>
										<tr>
											<td><?=isset($model->num) ? $model->num : '';?>/<?=isset($model->year) ? $model->year : '';?>
												<?=isset($model->date_write) ? ' ลว. ' . $model->DateThai($model->date_write) : '';?>
												<?=$model->file ? Html::a('เรื่อง ' . $model->name, ['courtorder/show', 'id' => $model->id], ['target' => '_blank']) : 'เรื่อง ' . $model->name;?></td>
											<td>
											<?=$model->getProfileName()?>
											<?php
if ($model->owner == Yii::$app->user->identity->id || Yii::$app->user->identity->role == 9) {
    echo Html::label('<i class="fa fa-wrench"></i> แก้ไข ','javascript:void(0)', [
        'class' => 'act-update btn btn-warning btn-xs',
        'data-id' => $model->id,
    ]) . ' ';}?>
	<?php if(Yii::$app->user->identity->role == 9){
		echo Html::a('<i class="fa fa-wrench"></i> ลบ',['courtorder/delete', 'id' => $model->id], [
			'class' => 'btn btn-danger btn-xs',
			'data-method' => 'post',
			'data-confirm' => 'Are you sure Delete ?'
		]);
	}?>
											</td>
										</tr>
										<?php endforeach;?>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-md-6">
			<div class="row">
				<div class="col-md-12">
					<div class="box box-primary">
						<div class="box-header with-border">
							<h3 class="box-title">คำสั่งสำนักงาน</h3>
							<div class="box-tools pull-right">
								<button id="act-create2" class="btn btn-info btn-md" alt="act-create"><i class="fa fa-plus"></i> เพิ่ม คำสั่งสำนักงาน</button>
							</div>
						</div>
						<div class="box-body">
							<div id="wra_example1" class="dataTables_wrapper form-inline dt-bootstrap">
								<table id="example2" class="table table-striped table-bordered" width="100%">
									<thead>
										<tr>
											<th class="text-center">เรื่อง</th>
											<th class="text-center"style="width:50px">ผู้บันทึก</th>
										</tr>
									</thead>
									<tbody>
										<?php foreach ($models2 as $model2): ?>
										<tr>
											<td><?=$model2->num?> / <?=$model2->year?>
												<?=isset($model2->date_write) ? ' ลว. ' .  $model2->DateThai($model2->date_write) : '';?>
												<?=$model2->file ? Html::a('เรื่อง ' . $model2->name, ['courtorder/show2', 'id' => $model2->id], ['target' => '_blank']) : 'เรื่อง ' . $model2->name;?></td>
											<td><?=$model2->getProfileName()?>
											<?php
if ($model2->owner == Yii::$app->user->identity->id || Yii::$app->user->identity->role == 9) {
    echo Html::a('<i class="fa fa-wrench"></i> แก้ไข ', '#', [
        'class' => 'act-updateb btn btn-warning btn-xs',
        'data-id' => $model2->id,
    ]) . ' ';}?>
	<?php if(Yii::$app->user->identity->role == 9){
		echo Html::a('<i class="fa fa-wrench"></i> ลบ',['courtorder/delete2', 'id' => $model2->id], [
			'class' => 'btn btn-danger btn-xs',
			'data-method' => 'post',
			'data-confirm' => 'Are you sure Delete ?'
		]);}?>
											</td>
										</tr>
										<?php endforeach;?>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>

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
			"pageLength": 100,
			'ordering'    : false,
			'lengthChange': true,
			'paging'      : true,
    		// "order": [[ 0, 'desc' ]]
		})

	var url_update = "updatebb";
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

	var url_update2 = "updateb";
    	$(".act-updateb").click(function(e) {
			var fID = $(this).data("id");
			// alert(fID);
        	$.get(url_update2,{id: fID},function (data){
            	$("#activity-modal").find(".modal-body").html(data);
            	$(".modal-body").html(data);
            	$(".modal-title").html("แก้ไขข้อมูล");
            	$("#activity-modal").modal("show");
        	});
		});

	var url_create = "createbb";
    	$( "#act-create" ).click(function() {
        	$.get(url_create,function (data){
                $("#activity-modal").find(".modal-body").html(data);
                $(".modal-body").html(data);
                $(".modal-title").html("เพิ่มข้อมูล");
            	// $(".modal-footer").html(footer);
                $("#activity-modal").modal("show");
                // $("#myModal").modal('toggle');
        	});
		});

	var url_create2 = "createb";
    	$( "#act-create2" ).click(function() {
        	$.get(url_create2,function (data){
                $("#activity-modal").find(".modal-body").html(data);
                $(".modal-body").html(data);
                $(".modal-title").html("เพิ่มข้อมูล");
            	// $(".modal-footer").html(footer);
                $("#activity-modal").modal("show");
                // $("#myModal").modal('toggle');
        	});
		});


});
JS;
$this->registerJs($script);
?>