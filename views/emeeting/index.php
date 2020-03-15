<?php
use yii\helpers\Url;
use yii\helpers\Html;
$this->title = 'ระบบจองใช้ห้องประชุมทางจอภาพ Web Conference ศาลเยาวชนฯ จ.ประจวบฯ';
// $this->params['breadcrumbs'][] = $this->title;
?>
<link href="<?= Url::to('@web/plugins/fullcalendar/packages/core/main.css')?>" rel='stylesheet' />
<link href="<?= Url::to('@web/plugins/fullcalendar/packages/daygrid/main.css')?>" rel='stylesheet' />

<div class="row">
    <div class="col-md-12">
    <center>
				<button id="act-create" class="btn btn-primary"">						
				+ บันทึกจองคิวนัดประชุมผ่านจอภาพ (Web Conference) +  
				</button>
				</center>
    </div>
   <hr>
    <div class="col-md-12">
      <div class="box box-primary">
        <div class="box-body">
          <div id="calendar"></div>
        </div>
      </div>
    </div>
    <hr>
    <div class="col-md-12">
      <div class="box box-primary">
        <div class="box-body">
          <table id="example1" class="table table-bordered table-striped dataTable" role="grid" aria-describedby="example1_info">
            <thead>
              <tr role="row">
                <th class = "text-center" ></th>
                <th class = "text-center" >รายละเอียด</th>
                <th style="width: 100px;"></th>
              </tr>
            </thead>
            <tbody>                              
              <?php foreach ($models as $model): ?>
                <tr>
                  <td class="text-center" alt="<?=$model->id?>">
                    <?=$model->cname?>
                    <br><?= $model->title;?>
                  </td>										
                  <td>
                    <?=$model->start?> ถึง <?=$model->end?>
                  </td>
                  <td class="text-center"> 
                            
                    <button class ="act-update1 btn btn-warning btn-xs" data-id = "<?=$model->id?>">
                      <i class="fa fa-wrench"></i> แก้ไข 
                    </button>

                    <?=						
                    Html::a('<i class="fa fa-remove"></i> ลบ ', ['emeeting/del', 'id' => $model->id], [
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
<script src="<?= Url::to('@web/plugins/fullcalendar/packages/core/main.js')?>"></script>
<script src="<?= Url::to('@web/plugins/fullcalendar/packages/daygrid/main.js')?>"></script>
<script>
     document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');

    var calendar = new FullCalendar.Calendar(calendarEl, {
		// monthNamesShort: ['Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic'],   
		lang: 'th',    
      plugins: [ 'dayGrid'],

      header: {
        left: 'prev,next today',
        center: 'title',
        right: 'dayGridMonth,listYear'
      },
      // firstDay : 1,
      locale: 'th',

      displayEventTime: false, // don't show the time column in list view       
      
      events: <?=$event?>,
      allDay : true,

      eventClick: function(arg) {
        // opens events in a popup window
        // alert(arg.event.title);
        // window.open(arg.event.url,'width=800,height=600');
        // arg.jsEvent.preventDefault() // don't navigate in main tab
        // var fID = $(this).data("id");
			// alert(fID);
      var url_update = "view";
        	$.get(url_update,{id: arg.event.id},function (data){
            	$("#activity-modal").find(".modal-body").html(data);
            	$(".modal-body").html(data);
            	$(".modal-title").html("view");
            	$("#activity-modal").modal("show");
        	});
      },
    });

    calendar.render();
  });

</script>
<?=date("Y-m-d")?>
<?php// var_dump($event);?>
<?php
$script = <<< JS
  var url_create_b = "create";
  $( "#act-create" ).click(function() {
      $.get(url_create_b,function (data){
            $("#activity-modal").find(".modal-body").html(data);
            $(".modal-body").html(data);
            $(".modal-title").html("เพิ่มข้อมูล");
          // $(".modal-footer").html(footer);
            $("#activity-modal").modal("show");
            //   $("#myModal").modal('toggle');
      });     
}); 

$( ".act-update1" ).click(function() {
  var url_update = "view";
  var fID = $(this).data("id");
    $.get(url_update,{id:fID},function (data){

        $("#activity-modal").find(".modal-body").html(data);
        $(".modal-body").html(data);
        $(".modal-title").html("view");
        $("#activity-modal").modal("show");
    });
});

$(document).on('click','.fc-day-top',function(){
  var url_create = "create";
  var fID = $(this).attr("data-date");
  // alert(fID);
  $.get(url_create,{date_id: fID},function (data){
            	$("#activity-modal").find(".modal-body").html(data);
            	$(".modal-body").html(data);
            	$(".modal-title").html("แก้ไขข้อมูล");
            	$("#activity-modal").modal("show");
        	});
});

JS;
$this->registerJs($script);
?>