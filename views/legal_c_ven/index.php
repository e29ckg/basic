<?php
use yii\helpers\Url;

$this->title = 'ตารางเวรที่ปรึกษากฎหมาย';
$this->params['breadcrumbs'][] = $this->title;
?>
<link href="<?= Url::to('@web/plugins/fullcalendar/packages/core/main.css')?>" rel='stylesheet' />
<link href="<?= Url::to('@web/plugins/fullcalendar/packages/daygrid/main.css')?>" rel='stylesheet' />

<div class="row">
    <div class="col-md-12">
    <div class="box box-primary">
      <div class="box-body">
        <div id="calendar"></div>
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