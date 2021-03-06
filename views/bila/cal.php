<?php
use yii\helpers\Url;

$this->title = 'ปฏิทินใบลา';
$this->params['breadcrumbs'][] = ['label' => 'โปรแกรมใบลา', 'url' => ['bila/index']];
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
    allDay : true,

      eventClick: function(arg) {
        // opens events in a popup window
        // alert(arg.event.title);
        // window.open(arg.event.url,'width=800,height=600');
        // arg.jsEvent.preventDefault() // don't navigate in main tab
        // var fID = $(this).data("id");
			// alert(fID);
      var url_update = "view_cal";
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

<?php// var_dump($event);?>
<?php
$script = <<< JS
$(document).ready(function() {	
  
});
JS;
$this->registerJs($script);
?>