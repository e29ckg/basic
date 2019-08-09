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
			
	</div>
	<div class="box-body">
		<div id="example" class="dataTables_wrapper form-inline dt-bootstrap">

			<table id="example1" class="table table-striped table-bordered" width="100%">
				<thead>
					<tr>
						<th data-class="expand" width="10%" align="center"> # </th>
						<th width="10%">img</th>
						<th data-hide="phone" width="80%">Link</th>		
					</tr>
				</thead>
				<tbody>  
					<?php $i = 1?>                              
					<?php foreach ($models as $model): ?>
					<tr >
						<td align="center"><?= $i++?></td>
						<td class="img-weblink" >
							<a href="#"><img src="<?= Url::to('@web'.WebLink::getImg($model->id)) ?>" alt="Smiley face" data-id= "<?=$model->id?>" class = "act-show img"></a>
							<br>
							<?php // echo '<a href="'.$model->link.'" target="_blank">'.$model->name.'</a>'?>
						</td>
						
						<td>
						<?php  echo '<a href="'.$model->link.'" target="_blank">'.$model->name.'</a>'?>
						
						<?php 
								$modelFiles = $model->getWebLinkFile()->orderBy(['sort'=>SORT_ASC,'id' => SORT_ASC])->all();
								// $modelFiles = WebLinkFile::find()->where(['web_link_id'=>$model->id])->orderBy(['sort'=>SORT_ASC,'id' => SORT_ASC])->all(); 
								echo '<ul>';
								
								foreach ($modelFiles as $modelFile):
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
	$('#example1').DataTable({
    	"order": [[ 0, 'desc' ]]
	})

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
			
	// $('#activity-modal').on('hidden.bs.modal', function () {
 	// 	location.reload();
	// })

				var responsiveHelper_dt_basic = undefined;
				var responsiveHelper_datatable_fixed_column = undefined;
				var responsiveHelper_datatable_col_reorder = undefined;
				var responsiveHelper_datatable_tabletools = undefined;
				
				var breakpointDefinition = {
					tablet : 1024,
					phone : 480
				};	
				
			/* COLUMN FILTER  */
		    var otable = $('#datatable_fixed_column').DataTable({
		    	//"bFilter": false,
		    	//"bInfo": false,
		    	//"bLengthChange": false
		    	//"bAutoWidth": false,
		    	//"bPaginate": false,
		    	//"bStateSave": true // saves sort state using localStorage
				"sDom": "<'dt-toolbar'<'col-xs-12 col-sm-6 'f><'col-sm-6 col-xs-12 '<'toolbar'>>r>"+
						"t"+
						"<'dt-toolbar-footer'<'col-sm-6 col-xs-12 hidden-xs'i><'col-xs-12 col-sm-6'p>>",
				"autoWidth" : true,
				"preDrawCallback" : function() {
					// Initialize the responsive datatables helper once.
					if (!responsiveHelper_datatable_fixed_column) {
						responsiveHelper_datatable_fixed_column = new ResponsiveDatatablesHelper($('#datatable_fixed_column'), breakpointDefinition);
					}
				},
				"paging":   false,
				"rowCallback" : function(nRow) {
					responsiveHelper_datatable_fixed_column.createExpandIcon(nRow);
				},
				"drawCallback" : function(oSettings) {
					// responsiveHelper_datatable_fixed_column.respond();
				}		
			
		    });
		    
		    // custom toolbar
												
		    // $("div.toolbar").html('<div class="text-right"><button id="act-create" class="btn btn-success btn-md" alt="act-create"><i class="fa fa-plus "></i> act-create</button></div>');
			   
		    // Apply the filter
		    $("#datatable_fixed_column thead th input[type=text]").on( 'keyup change', function () {
		    	
		        otable
		            .column( $(this).parent().index()+':visible' )
		            .search( this.value )
		            .draw();
		            
		    } );

			otable.order( [[ 0, 'asc' ], [ 2, 'asc' ]] ).draw();

/* END COLUMN FILTER */  

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
		
});
JS;
$this->registerJs($script);
?>