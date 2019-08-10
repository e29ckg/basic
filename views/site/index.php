<?php
use yii\helpers\Url;

/* @var $this yii\web\View */

$this->title = 'Home';
?>
<div class="row">
  <div class="col-md-6">
              <!-- DIRECT CHAT -->
    <div class="box box-warning direct-chat direct-chat-warning">
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
                        '<a href="'.Url::to(['cletter/show','file' => $model->file]).'" class="uppercase" target="_blank">'.$model->name.'</a>'
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
