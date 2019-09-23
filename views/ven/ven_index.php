<?php
use yii\helpers\Url;


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
      
      locale: 'th',

      displayEventTime: false, // don't show the time column in list view
            
      events: <?=$event?>,
    // {
    //   'title'  : 'event1',
    //   "start"  : '2019-08-01'
    // },
    // {
    //   title  : 'event2',
    //   start  : '2019-08-05',
    //   end    : '2019-08-07'
    // }],
    firstDay : 1, //monday
    allDay : true,

      
      eventClick: function(info) {
        // alert('Event: ' + info.event.title);
        var url_show = "ven_show";
        $.get(url_show,{id: info.event.id},function (data){
            	$("#activity-modal").find(".modal-body").html(data);
            	$(".modal-body").html(data);
            	$(".modal-title").html("view");
            	$("#activity-modal").modal("show");
        	});
        // alert(info.event.id);
      }

      
    });

    calendar.render();
  });

</script>

<?php// var_dump($event);?>
<?php



$script = <<< JS
$(document).ready(function() {	
  var url_update = "update";
    	$(".fc-day-top").click(function(e) {            
			var fID = $(this).data("date");
			// alert(fID);
        	// $.get(url_update,{id: fID},function (data){
          //   	$("#activity-modal").find(".modal-body").html(data);
          //   	$(".modal-body").html(data);
          //   	$(".modal-title").html("แก้ไขข้อมูล");
          //   	$("#activity-modal").modal("show");
        	// });
		});
});


JS;
$this->registerJs($script);
?>