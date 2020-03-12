<?php
use yii\helpers\Url;
use yii\helpers\Html;
$data = json_decode($data);
$data2 = json_decode($data2);

$this->title = 'สมุดคุม ';
?>

</style>
<section class="content">
	<div class="row">
		<div class="col-md-6">
            <div class="row">
				<div class="col-md-12">
					<!-- Default box -->
					<div class="box box-danger">
						<div class="box-header with-border">
							<h3 class="box-title">คำสั่งศาลฯ</h3>
							<div class="box-tools pull-right">
								</div>
						</div>
						<div class="box-body">
							<div id="wra_example1" class="dataTables_wrapper form-inline dt-bootstrap">
								<table id="example1" class="table table-striped table-bordered" width="100%">
									<thead>
                                        
									</thead>
									<tbody>
                                    <?php foreach ($data as $d): ?>
										<tr>
                <td style="text-align: left;">คำสังศาลฯ ปี 
                <?= Html::a('<i class="fa fa-print"></i> '.$d->year , ['report_a','id'=>$d->year], [
                            // 'class' => 'btn btn-success btn-xs',
                            // 'data-id' => $model->id,
                            'target' => '_blank'
                        ]);?></td></tr>
										<?php endforeach;?>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
        <div class="col-md-6">
            <div class="row">
				<div class="col-md-12">
					<!-- Default box -->
					<div class="box box-danger">
						<div class="box-header with-border">
							<h3 class="box-title">คำสั่งสำนักงานฯ</h3>
							<div class="box-tools pull-right">
								</div>
						</div>
						<div class="box-body">
							<div id="wra_example1" class="dataTables_wrapper form-inline dt-bootstrap">
								<table id="example1" class="table table-striped table-bordered" width="100%">
									<thead>
                                        
									</thead>
									<tbody>
                                    <?php foreach ($data2 as $d): ?>
										<tr>
                                            <td style="text-align: left;">คำสังสำนักงานฯ ปี 
                                            <?= Html::a('<i class="fa fa-print"></i> '.$d->year , ['report_b','id'=>$d->year], [
                                                        // 'class' => 'btn btn-success btn-xs',
                                                        // 'data-id' => $model->id,
                                                        'target' => '_blank'
                                                    ]);?></td></tr>
										<?php endforeach;?>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
    </div>
</section>


