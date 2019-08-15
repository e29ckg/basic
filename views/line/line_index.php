<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;



/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Line';
$this->params['breadcrumbs'][] = $this->title;
?>


<section class="content">
	<div class="row">
		<div class="col-md-5">
			<div class="row">
				<div class="col-md-12">
					<div class="box box-primary">
						<div class="box-header with-border">
							<h3 class="box-title">Notify-Bot ?></h3>
							<div class="box-tools pull-right">
							<?=Html::a('เว็บไซต์ https://notify-bot.line.me/th/', 'https://notify-bot.line.me/th/', ['class' => 'btn btn-success','target' => '_blank'])?>
							</div>	
						</div>	
						<div class="box-body">
							<?php  $form = ActiveForm::begin();  ?>
							<?=$form->field($LineHome, 'name_ser')?>
							<?=$form->field($LineHome, 'client_id')?>
							<?=$form->field($LineHome, 'client_secret')?>							
							<?=$form->field($LineHome, 'api_url')?>
							<?=$form->field($LineHome, 'callback_url')?>
							<?=Html::submitButton('บันทึก', ['class' => 'btn btn-success'])?>
							<?php ActiveForm::end()?>
						</div>			
					</div>
				</div>
				<div class="col-md-12">	
					<div class="box box-primary">
						<div class="box-header with-border">
							<h3 class="box-title">lineHomeAll</h3>
							<div class="box-tools pull-right">
								<button id="act-create-linehome" class="btn btn-danger btn-md" alt="act-create-a"><i class="fa fa-pencil-square-o "></i> เพิ่ม</button>  
							</div>	
						</div>	
						<div class="box-body">
							<div id="wra_example1" class="dataTables_wrapper form-inline dt-bootstrap">
							<table id="example2" class="table table-striped table-bordered" width="100%">
								<thead>
									<tr>
										<th >id</th>
										<th>Nmae_ser</th>	
										<th></th>					
									</tr>
								</thead>
								<tbody>
									<?php foreach ($LineHomeAll as $model): ?>
									<tr>
										<td><?= $model->id ?></td>
										<td><?= $model->name_ser?></td>
										<td>
											<a href= "#" class="btn btn-warning btn-xs act-update-linehome" data-id=<?=$model->id?>><i class="fa fa-pencil-square-o"></i> แก้ไข</a>
											<?= Html::a('<i class="fa fa-remove"></i> ลบ',['line/linehome_delete','id' => $model->id],
													[
														'class' => 'btn btn-danger btn-xs act-update',
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

		<div class="col-md-7">
			<div class="row">
				<div class="col-md-12">
					<div class="box box-primary">
						<div class="box-header with-border">
							<h3 class="box-title">ไลน์กลุ่ม </h3>
							<div class="box-tools pull-right">
							
							</div>	
						</div>	
						<div class="box-body">
						Token : 
						<?=  !empty($LineGroup->token) ? $LineGroup->token 
									. ' ' 
									. Html::a('ลบ', ['line_delete','id' => $LineGroup->id],['class' => 'btn btn-danger btn-xs'])									
									.' '
									.'<button data-id ="'.$LineGroup->token.'" class="act-line-send btn btn-primary  btn-xs" alt="act-line-send"><i class="fa fa-pencil-square-o "></i> ทดสอบการส่ง</button>'
									: Html::a('ลงทะเบียนไลน์กลุ่ม', $result, ['class' => 'btn btn-success']) ;
								?>
						</div>					
					</div>
				</div>
				<div class="col-md-12">	
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
										<th >name</th>
										<th>Token</th>	
										<th></th>					
									</tr>
								</thead>
								<tbody>
									<?php foreach ($models as $model): ?>
									<tr>
										<td><?= $model->name ?></td>
										<td><?= $model->token?></td>
										<td>
											<a href= "#" class="btn btn-warning btn-xs act-update" data-id=<?=$model->id?>><i class="fa fa-pencil-square-o"></i> แก้ไข</a>
											<?= Html::a('<i class="fa fa-remove"></i> ลบ',['line/line_delete','id' => $model->id],
													[
														'class' => 'btn btn-danger btn-xs act-update',
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

		
</section>
<!-- Default box -->

<?php

$script = <<< JS
     
$(document).ready(function() {	
/* BASIC ;*/

	

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

		var url_linehome_update = "linehome_update";
    	$(".act-update-linehome").click(function(e) {            
			var fID = $(this).data("id");
			// alert(fID);
        	$.get(url_linehome_update,{id: fID},function (data){
            	$("#activity-modal").find(".modal-body").html(data);
            	$(".modal-body").html(data);
            	$(".modal-title").html("แก้ไขข้อมูลสมาชิก");
            	$("#activity-modal").modal("show");
        	});
    	});

		var url_line_send = "line_send";
    	$(".act-line-send").click(function(e) {            
			var fID = $(this).data("id");
			// alert(fID);
        	$.get(url_line_send,{token: fID},function (data){
            	$("#activity-modal").find(".modal-body").html(data);
            	$(".modal-body").html(data);
            	$(".modal-title").html("LineSend");
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
		var url_linehome_create = "linehome_create";
    	$( "#act-create-linehome" ).click(function() {
        	$.get(url_linehome_create,function (data){
                $("#activity-modal").find(".modal-body").html(data);
                $(".modal-body").html(data);
                $(".modal-title").html("เพิ่มข้อมูล");
            	// $(".modal-footer").html(footer);
                $("#activity-modal").modal("show");
                //   $("#myModal").modal('toggle');
        	});     
		}); 


//load_data();
	$('#example1').DataTable()
	$('#example2').DataTable()
		
});
JS;
$this->registerJs($script);
?>