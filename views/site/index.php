<?php
use yii\helpers\Url;

/* @var $this yii\web\View */

$this->title = 'Dashboard';
$this->params['breadcrumbs'][] = $this->title;
?>
<link href="<?= Url::to('@web/plugins/fullcalendar/packages/core/main.css')?>" rel='stylesheet' />
<link href="<?= Url::to('@web/plugins/fullcalendar/packages/daygrid/main.css')?>" rel='stylesheet' />
<link href="<?= Url::to('@web/plugins/fullcalendar/packages/list/main.css')?>" rel='stylesheet' />

<div class="row">
  <div class="col-md-6">
              <!-- DIRECT CHAT -->
    <div class="box box-warning">
      <div class="box-header with-border">
        <h3 class="box-title">หนังสือเวียนล่าสุด.</h3>

        <div class="box-tools pull-right">
          <!-- <span data-toggle="tooltip" title="" class="badge bg-yellow" data-original-title="3 New Messages">3</span> -->
          <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
          </button>
         
        </div>
      </div>
                <!-- /.box-header -->
      <div class="box-body">
        <!-- Conversations are loaded here -->
        <div class="direct-chat-messages">
          <!-- Message. Default to the left -->

            <?php foreach ($models as $model): ?>
              <div class="direct-chat-msg">
                <div class="direct-chat-info clearfix">
                  <span class="direct-chat-name pull-left"><?=$model->ca_name?></span>
                  <span class="direct-chat-timestamp pull-right"><?=$model->created_at?></span>
                </div>
                <!-- /.direct-chat-info -->
                <img class="direct-chat-img" src="<?=Url::to(['img/pr.png'])?>" alt="message user image">
                <!-- /.direct-chat-img -->
                <div class="direct-chat-text">
                    <?= $model->file ?
                        '<a href="'.Url::to(['cletter/show','id' => $model->id]).'" class="uppercase" target="_blank">'.$model->name.'</a>'
                        :
                        $model->name;
                    ?>
                    
                <?php ?>
                    </div>
                <!-- /.direct-chat-text -->
              </div>
              <?php  endforeach; ?>

          </div>  
          <div class="box-footer text-center">
            <a href="<?=Url::to(['cletter/index'])?>" class="uppercase">View All </a>
          </div>       
        </div>
      </div>
    </div>
    <div class="col-md-6">
        <div class="box box-solid">
            <div class="box-header with-border">
            <h3 class="box-title"> </h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
                    <ol class="carousel-indicators">
                    <li data-target="#carousel-example-generic" data-slide-to="0" class=""></li>
                    <li data-target="#carousel-example-generic" data-slide-to="1" class=""></li>
                    <li data-target="#carousel-example-generic" data-slide-to="2" class="active"></li>
                    </ol>
                <div>
                <div class="carousel-inner">
                  <div class="item">
                    <img src="http://placehold.it/900x500/39CCCC/ffffff&amp;text=I+Love+Bootstrap" alt="First slide">

                    <div class="carousel-caption">
                      First Slide
                    </div>
                  </div>
                  <div class="item">
                    <img src="http://placehold.it/900x500/3c8dbc/ffffff&amp;text=I+Love+Bootstrap" alt="Second slide">

                    <div class="carousel-caption">
                      Second Slide
                    </div>
                  </div>
                  <div class="item active">
                    <img src="http://placehold.it/900x500/f39c12/ffffff&amp;text=I+Love+Bootstrap" alt="Third slide">

                    <div class="carousel-caption">
                      Third Slide
                    </div>
                  </div>
                </div>
                <a class="left carousel-control" href="#carousel-example-generic" data-slide="prev">
                  <span class="fa fa-angle-left"></span>
                </a>
                <a class="right carousel-control" href="#carousel-example-generic" data-slide="next">
                  <span class="fa fa-angle-right"></span>
                </a>          

            </div>
        </div>
    </div>      
    </div>    
</div>

<!-- <div class="row"> -->
  <div class="col-md-12">
    <div class="box box-primary">
      <div class="box-body">
        <div id="calendar"></div>
      </div>
    </div>
  </div>
</div>
<script src="<?= Url::to('@web/plugins/fullcalendar/packages/core/main.js')?>"></script>
<script src="<?= Url::to('@web/plugins/fullcalendar/packages/interaction/main.js')?>"></script>
<script src="<?= Url::to('@web/plugins/fullcalendar/packages/daygrid/main.js')?>"></script>
<script src="<?= Url::to('@web/plugins/fullcalendar/packages/list/main.js')?>"></script>
<script src="<?= Url::to('@web/plugins/fullcalendar/packages/google-calendar/main.js')?>"></script>
<script>
  document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');

    var calendar = new FullCalendar.Calendar(calendarEl, {
		monthNamesShort: ['Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic'],   
		lang: 'th',    
      plugins: [ 'interaction', 'dayGrid', 'list', 'googleCalendar' ],

      header: {
        left: 'prev,next today',
        center: 'title',
        right: 'dayGridMonth,listYear'
      },

      locale: 'th',

      displayEventTime: true, // don't show the time column in list view

      eventTimeFormat: { // like '14:30:00'
        hour: '2-digit',
        minute: '2-digit',
        // second: '2-digit'
      },

      // THIS KEY WON'T WORK IN PRODUCTION!!!
      // To make your own Google API key, follow the directions here:
      // http://fullcalendar.io/docs/google_calendar/
      googleCalendarApiKey: 'AIzaSyCtp0KVVxbk9VapZoU-X4J6uaulYafzMQw',

      // US Holidays
      events: 'pkkjc.coj@gmail.com',

      eventClick: function(arg) {
        // opens events in a popup window
        window.open(arg.event.url, 'google-calendar-event', 'width=800,height=600');

        arg.jsEvent.preventDefault() // don't navigate in main tab
      },

      
    });

    calendar.render();
  });

</script>

<?php
$script = <<< JS
    

JS;
$this->registerJs($script);
?>
