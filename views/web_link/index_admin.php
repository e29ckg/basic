<?php

use yii\helpers\Html;
use app\models\WebLink;
use app\models\WebLinkFile;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Web Links';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="content">
<div class="row">
<div class="col-md-12">

<div class="box box-primary">
	<div class="box-header with-border">
		<h3 class="box-title"><?= Html::encode($this->title) ?></h3>
		<div class="box-tools pull-right">

			<button id="act-create" class="btn btn-danger btn-md" alt="act-create"><i class="fa fa-pencil-square-o "></i> เพิ่ม</button>  
		</div>	
		<div class="col-sm-3">
						<div class="search_box pull-right">
							<!-- <input type="text" placeholder="Search"/> -->
							<input id = "search" type="text" name="table_search" class="form-control pull-right" placeholder="Search" data-cip-id="cIPJQ342845640" autofocus>
						</div>
					</div>
	</div>	
	<div class="box-body">
		<div id="example" class="dataTables_wrapper form-inline dt-bootstrap">
			<table id="example1" class="table table-striped table-bordered" >
				<thead>
					<tr>
						
						<th >img</th>
						<th style="width: 85">ชื่อ</th>
						
						<th style="width: 10px"></th>	
					</tr>
				</thead>
					<tbody>  
						                             
						<?php foreach ($models as $model): ?>
						<tr>
						
							<td class="img-weblink" >
							<a href="#" ><img src="<?= Url::to('@web'.WebLink::getImg($model->id)) ?>" alt="Smiley face" data-id= "<?=$model->id?>" class = "act-show img"></a>
							</td>
							<td>
								<?= '<a href="'.$model->link.'" target="_blank">'.$model->name.'</a>'?>
							
								<?php 
									$modelFiles = WebLinkFile::find()->where(['web_link_id'=>$model->id])->orderBy(['sort'=>SORT_ASC,'id' => SORT_ASC])->all(); 
									echo '<ul>';
									
									foreach ($modelFiles as $modelFile):
										// echo $modelFile->name;
										echo '<li>';
										if($modelFile->type =='url'){
											echo '<a href="'.Url::to($modelFile->url).'"  target="_blank">'.$modelFile->name.'</a> ';
											echo '<button class="act-update-url btn btn-xs bg-color-white txt-color-red" alt="act-update-url" data-id="'.$modelFile->id.'"><i class="fa fa-gear fa-spin fa-lg"></i> แก้ไข</button>';
											
										}else{
										echo '<a href="'.Url::to('@web/uploads/weblink/'.$model->id.'/'.$modelFile->file).'"  target="_blank">'.$modelFile->name.'.'.$modelFile->type.'</a> ';
										echo '<button class="act-update-file btn btn-xs bg-color-white txt-color-red" alt="act-update-file" data-id="'.$modelFile->id.'"><i class="fa fa-gear fa-spin fa-lg"></i> แก้ไข</button>';
										
									}
										
										'</li>';
									endforeach;
									echo '</ul>';
									// echo var_dump($modelFiles);
								?>
									<button class= "act-create-file btn btn-success btn-xs" alt="act-create" data-id=<?=$model['id']?>><i class="fa fa-plus "></i> เพิ่มfile</button>
									<button class= "act-create-url btn btn-success btn-xs" alt="act-create-url" data-id=<?=$model['id']?>><i class="fa fa-plus "></i> เพิ่มurl</button>
								
								</td>		
								
								<td>
								<a href="#" class="act-update btn btn-info btn-xs" data-id=<?=$model['id']?>>แก้ไข</a> 
								<?= Html::a('<i class="fa fa-remove"></i> ลบ',['web_link/delete','id' => $model->id],
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

<?php

$script = <<< JS
    
$(document).ready(function() {	
/* BASIC ;*/	

	init_click_handlers(); //first run
			
	

	$('#activity-modal').on('hidden.bs.modal', function () {
 		location.reload();
	})
	
		        
	function init_click_handlers(){    

		var url_create_file = "createfile";
    	$( ".act-create-file" ).click(function() {
        	var fID = $(this).data("id");
			// alert(fID);
        	$.get(url_create_file,{id: fID},function (data){
                $("#activity-modal").find(".modal-body").html(data);
                $(".modal-body").html(data);
                $(".modal-title").html("เพิ่มข้อมูล");
            	// $(".modal-footer").html(footer);
                $("#activity-modal").modal("show");
                //   $("#myModal").modal('toggle');
        	});     
		}); 

		var url_create_url = "createurl";
    	$( ".act-create-url" ).click(function() {
        	var fID = $(this).data("id");
			// alert(fID);
        	$.get(url_create_url,{id: fID},function (data){
                $("#activity-modal").find(".modal-body").html(data);
                $(".modal-body").html(data);
                $(".modal-title").html("เพิ่มข้อมูล");
            	// $(".modal-footer").html(footer);
                $("#activity-modal").modal("show");
                //   $("#myModal").modal('toggle');
        	});     
		});

		
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
            	$(".modal-title").html("แก้ไข");
            	$("#activity-modal").modal("show");
        	});
    	});

		var url_update_file = "updatefile";
    	$(".act-update-file").click(function(e) {            
			var fID = $(this).data("id");
			// alert(fID);
        	$.get(url_update_file,{id: fID},function (data){
            	$("#activity-modal").find(".modal-body").html(data);
            	$(".modal-body").html(data);
            	$(".modal-title").html("แก้ไขข้อมูลสมาชิก");
            	$("#activity-modal").modal("show");
        	});
    	});    	
		
		var url_update_url = "updateurl";
    	$(".act-update-url").click(function(e) {            
			var fID = $(this).data("id");
			// alert(fID);
        	$.get(url_update_url,{id: fID},function (data){
            	$("#activity-modal").find(".modal-body").html(data);
            	$(".modal-body").html(data);
            	$(".modal-title").html("แก้ไขข้อมูลสมาชิก");
            	$("#activity-modal").modal("show");
        	});
    	});
    	var url_view = "index.php?r=ppss/view";		
    	$(".act-view").click(function(e) {			
                var fID = $(this).data("id");
                $.get(url_view,{id: fID},function (data){
                        $("#activity-modal").find(".modal-body").html(data);
                        $(".modal-body").html(data);
                        $(".modal-title").html("ข้อมูล");
                        $("#activity-modal").modal("show");
                    }
                );
            });   
    
	}

    
		// var url_create = "index.php?r=web_link/create";
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
		$('#example1').DataTable()

		$("#search").keyup(function () {
		//        var that = this,
			value = $(this).val();
			if(value == ""){
	  			location.reload();  
			}
			$.get("search",{q:value},
				function (data)
					{
						$("#features_items").html(data);
					}
				);

			});	
		
});
JS;
$this->registerJs($script);
?>