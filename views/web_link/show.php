<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use app\models\WebLink;

/* @var $this yii\web\View */
/* @var $model app\models\Profile */
/* @var $form yii\widgets\ActiveForm */
?>
<div class="text-center text-short-100">
	<p><h4><?=$model->name?></h4></p>
	<a href="<?=$model->link?>" class="" target="_blank"><i class="fa fa-gear fa-sm"></i> <?=$model->link?></a>
</div>
<div class="profile-form text-center">


</div>
<div class="text-center">
	<img src="<?= Url::to('@web'.WebLink::getImg($model->id)) ?>" alt="Smiley face" data-id= "<?=$model->id?>" class = "img-thumbnail">
	
</div>
<br>
<div class="profile-form text-center text-short-100">
	<a href="<?=$model->link?>" class="btn btn-success " target="_blank"><i class="fa fa-external-link"></i> <?=$model->name .' : '.$model->link?></a>
</div>

<div class="row">
	<br>
</div>
<div>
	<section>
		<div class="row">
			<article>
			<!-- <div class="jarviswidget jarviswidget-color-blueDark jarviswidget-sortable"> -->
				<table class="table table-bordered">
					<!-- <thead>
						<tr>
							<th>Column name</th>
							<th>Column name</th>
						</tr>
					</thead> -->
					<tbody>
						<?php foreach ($modelFiles as $modelFile):?>
						<tr>
							<td><?= $modelFile->name.'.'.$modelFile->type?></td>
							<?php
								echo '<td>';
								if($modelFile->type =='url'){
									echo '<a href="'.Url::to($modelFile->url).'"  target="_blank">'.$modelFile->url.'</a> ';														
								}else{
									echo '<a href="'.Url::to('@web/uploads/weblink/'.$model->id.'/'.$modelFile->file).'"  target="_blank">ดาวน์โหลด</a> ';
								}		
								echo '</td>';
							?>
							</tr>	
						<?php endforeach;?>												
					</tbody>
				</table>
			<!-- </div> -->
			</article>
		<div>
	</section>
</div>
<div>
	
</div>
