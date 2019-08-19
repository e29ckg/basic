<?php
use yii\helpers\Url;


?>
<link href="<?= Url::to('@web/plugins/fullcalendar/packages/core/main.css')?>" rel='stylesheet' />
<link href="<?= Url::to('@web/plugins/fullcalendar/packages/daygrid/main.css')?>" rel='stylesheet' />
<script>
      document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');

        var calendar = new FullCalendar.Calendar(calendarEl, {
            timeZone: 'local',
          plugins: [ 'dayGrid' ],

          // events    : [],
                        
        });
        var event = calendar.getEventById('a') // an event object!
        var start = event.start // a property (a Date object)
        console.log(start.toISOString()) // "2018-09-01T00:00:00.000Z"
        // console.log(end.toISOString()) // "2018-09-01T00:00:00.000Z"

        calendar.render();
      });

    </script>
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
  

</script>
<?= var_dump($event);?>
<?php
$script = <<< JS
$(document).ready(function() {	
    
});
JS;
$this->registerJs($script);
?>