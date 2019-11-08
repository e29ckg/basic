<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ActiveForm;
use yii\widgets\LinkPager;
use yii\bootstrap\Modal;
use yii2mod\alert\Alert;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\ProfileSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'สมาชิกถูกระงับ';
$this->params['breadcrumbs'][] = ['label' => 'จัดการสมาชิก', 'url' => ['user/index']];
$this->params['breadcrumbs'][] = $this->title;
?>


<!-- MAIN CONTENT -->
<?php
	// print_r($models);
	// echo Yii::$app->security->generatePasswordHash('admin').'<br>';
	// echo md5('admin').'<br>';
?>

<div class="row">
	<div class="col-lg-3 col-xs-6">
		<!-- small box -->
		<div class="small-box bg-aqua">
			<div class="inner">
				<h3><?=$userAll?></h3>

				<p>All User</p>
			</div>
			<div class="icon">
				<i class="fa fa-users"></i>
			</div>
			<a href="<?=Url::to(['user/index']);?>" class="small-box-footer">
				More info <i class="fa fa-arrow-circle-right"></i>
			</a>
		</div>
	</div>
        <!-- ./col -->
	<div class="col-lg-3 col-xs-6">
		<!-- small box -->
		<div class="small-box bg-green">
			<div class="inner">
				<h3><?=$userActive?></h3>

				<p>Active User</p>
			</div>
			<div class="icon">
				<i class="fa fa-user-plus"></i>
			</div>
			<a href="<?=Url::to(['user/index']);?>" class="small-box-footer">
				More info <i class="fa fa-arrow-circle-right"></i>
			</a>
		</div>
	</div>
        <!-- ./col -->
	<div class="col-lg-3 col-xs-6">
		<!-- small box -->
		<div class="small-box bg-yellow">
			<div class="inner">
				<h3><?=$userDis?></h3>
				<p>DisActiveUser</p>
			</div>
			<div class="icon">
				<i class="fa fa-user-times"></i>
			</div>
			<a href="<?=Url::to(['user/index_dis']);?>" class="small-box-footer">
				More info <i class="fa fa-arrow-circle-right"></i>
			</a>
		</div>
	</div>
	   <!-- ./col -->
	<div class="col-lg-3 col-xs-6">
		<!-- small box -->
		<div class="small-box bg-red">
			<div class="inner">
				<h3>0</h3>
				<p>--</p>
			</div>
			<div class="icon">
				<i class="fa fa-user-secret"></i>
			</div>
			<a href="#" class="small-box-footer">
				More info <i class="fa fa-arrow-circle-right"></i>
			</a>
		</div>
	</div>
	<!-- ./col -->
</div>


<div class="row">
    <div class="col-xs-12">
       	<div class="box">
           	<!-- <div class="box-header">
           		<h3 class="box-title">Data Table With Full Features</h3>
			</div> -->
			   
            <!-- /.box-header -->
            <div class="box-body">
              	<div id="example1_wrapper" class="dataTables_wrapper form-inline dt-bootstrap">				  
					<div class="row">
						<div class="col-sm-12">
								<table id="example1" class="table table-striped table-bordered" width="100%">
                                    <thead>			                
                                        <tr>
                                            <th data-class="expand">ID</th>
											<th data-hide="phone"> Username</th>
                                            <th><i class="fa fa-fw fa-user text-muted hidden-md hidden-sm hidden-xs"></i> Name</th>
                                            <th data-hide="phone"><i class="fa fa-fw fa-phone text-muted hidden-md hidden-sm hidden-xs"></i> Phone</th>
											<th ></th>
										</tr>
                                    </thead>                                        
                                    <tbody>
                                    	<?php foreach ($models as $model): ?>
										<tr>
                       						<td class="text-center" ><?=$model->id ?></td>
											<td class="text-center" ><a class="act-view"><?=$model->username ?></a></td>
                       						<td  ><?= $model->getProfileName() ?>  </td>
                       						<td><?= $model->getProfilePhone()?></td>
                                            <td>
												<a href="<?=Url::to(['user/active','id' => $model->id])?>" class = "btn btn-info btn-xs" data-confirm = "Are you sure ?" >SetActive</a> 
												<?php Html::a('<i class="fa fa-remove"></i> ลบ ', ['user/delete', 'id' => $model->id], [
													'class' => 'btn btn-danger btn-xs',
													'data-confirm' => 'Are you sure?',
													'data-method' => 'post',
												]) ?>
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
<!-- END MAIN CONTENT -->

<?php
$script = <<< JS
     
$(document).ready(function() {

	$('#example1').DataTable()
	$('#example2').DataTable({
      'paging'      : true,
      'lengthChange': false,
      'searching'   : true,
      'ordering'    : true,
      'info'        : true,
      'autoWidth'   : false
    })

	function init_click_handlers(){        	
		
		var url_update = "index.php?r=cletter/update";
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

		var url_line = "index.php?r=cletter/line_alert";		
    	$(".act-line").click(function(e) {			
                var fID = $(this).data("id");				
                $.get(url_line,{id: fID},function (data){
                        // $("#activity-modal").find(".modal-body").html(data);
                        // $(".modal-body").html(data);
                        // $(".modal-title").html("ข้อมูล");
                        // $("#activity-modal").modal("show");
                    }
                );
            }); 

    	var url_view = "index.php?r=user/create";		
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
			
	$('#activity-modal').on('hidden.bs.modal', function () {
 		location.reload();
	})    

    
    /* BASIC ;*/
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
				"sDom": "<'dt-toolbar'<'col-xs-12 col-sm-6 hidden-xs'f><'col-sm-6 col-xs-12 hidden-xs'<'toolbar'>>r>"+
						"t"+
						"<'dt-toolbar-footer'<'col-sm-6 col-xs-12 hidden-xs'i><'col-xs-12 col-sm-6'p>>",
				"autoWidth" : true,
				"preDrawCallback" : function() {
					// Initialize the responsive datatables helper once.
					if (!responsiveHelper_datatable_fixed_column) {
						responsiveHelper_datatable_fixed_column = new ResponsiveDatatablesHelper($('#datatable_fixed_column'), breakpointDefinition);
					}
				},
				"paging": true,
				"rowCallback" : function(nRow) {
					responsiveHelper_datatable_fixed_column.createExpandIcon(nRow);
				},
				"drawCallback" : function(oSettings) {
					responsiveHelper_datatable_fixed_column.respond();
				}		
			
		    });
		    
		    // custom toolbar
		    $("div.toolbar").html('<div class="text-right"><button id="act-create" class="btn btn-success btn-md" alt="act-create"><i class="fa fa-plus "></i> act-create</button></div>');
		    	   
		    // Apply the filter
		    $("#datatable_fixed_column thead th input[type=text]").on( 'keyup change', function () {
		    	
		        otable
		            .column( $(this).parent().index()+':visible' )
		            .search( this.value )
		            .draw();
		            
		    } );
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