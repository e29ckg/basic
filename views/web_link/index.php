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

<div class="box box-primary">
	<div class="box-header with-border">
		<h3 class="box-title"><?= Html::encode($this->title) ?></h3>
		<div class="box-tools  pull-right">
			<!-- <input type="text" placeholder="Search"/> -->
			<!-- <input id = "search" type="text" name="table_search" class="form-control pull-right" placeholder="Search" data-cip-id="cIPJQ342845640" autofocus> -->
		</div>
			
	</div>
	<div class="box-body">
		<div id="example" class="dataTables_wrapper form-inline dt-bootstrap">

			<table id="example1" class="table table-striped table-bordered" width="100%">
				<thead>
					<tr>
						<!-- <th data-class="expand" width="10%" align="center"> # </th> -->
						<th width="10%">img</th>
						<th data-hide="phone" width="80%">Link</th>		
					</tr>
				</thead>
				<tbody>  
					<?php $i = 1?>                              
					<?php foreach ($models as $model): ?>
					<tr >
						<!-- <td align="center"><?= $i++?></td> -->
						<td class="img-weblink" >
							<a href="#"><img src="<?= Url::to('@web'.WebLink::getImg($model->id)) ?>" alt="Smiley face" data-id= "<?=$model->id?>" class = "act-show img"></a>
							<br>
							<?php // echo '<a href="'.$model->link.'" target="_blank">'.$model->name.'</a>'?>
						</td>
						
						<td>
						<?php  echo '<a href="'.$model->link.'" target="_blank">'.$model->name.'</a>'?>
						
						<?php 
								// $modelFiles = $model->getWebLinkFile()->orderBy(['sort'=>SORT_ASC,'id' => SORT_ASC])->all();
								// $modelFiles = WebLinkFile::find()->where(['web_link_id'=>$model->id])->orderBy(['sort'=>SORT_ASC,'id' => SORT_ASC])->all(); 
								echo '<ul>';
								// echo var_dump($model->webLinkFile);
								foreach ($model->webLinkFile as $modelFile):
									// echo $modelFile->name;
									echo '<li>';
									if($modelFile->type =='url'){
										echo '<a href="'.Url::to($modelFile->url).'"  target="_blank">'.$modelFile->name.'</a> ';														
									}else{
									echo '<a href="'.Url::to('@web/uploads/weblink/'.$model->id.'/'.$modelFile->file).'"  target="_blank">'.$modelFile->name.'.'.$modelFile->type.'</a> ';
									}		
									echo '</li>';
								endforeach;
								echo '</ul>';
								// echo var_dump($modelFiles);
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
	

	function init_click_handlers(){  

		$("#search").keyup(function () {
		//        var that = this,
			value = $(this).val();
			if(value == ""){
	  			location.reload();  
			}
			$.get("search",{q:value},
				function (data)
					{
						$("#example").html(data);
					}
				);

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
            	$(".modal-title").html("แก้ไขข้อมูลสมาชิก");
            	$("#activity-modal").modal("show");
        	});
    	});
		
    	var url_view = "view";		
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

    init_click_handlers(); //first run
	

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

		$('#example1').DataTable({
			"pageLength": 100,
			'ordering'    : false,
			'lengthChange': true,
			'paging'      : true,
    		// "order": [[ 0, 'desc' ]]
		})

	
		
});
JS;
$this->registerJs($script);
?>