<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use app\models\WebLink;

/* @var $this yii\web\View */
/* @var $model app\models\Profile */
/* @var $form yii\widgets\ActiveForm */
?>
<div class="row">
<div class="col-md-12">
<div class="text-center text-short-100">
	<p><h4><?=$model->name?></h4></p>
	<a href="<?=$model->link?>" class="" target="_blank"><i class="fa fa-gear fa-sm"></i> <?=$model->link?></a>
</div>
</div>
</div>

<div class="row">
<div class="col-md-12">
<div class="text-center">
	<img src="<?= Url::to('@web'.WebLink::getImg($model->id)) ?>" alt="Smiley face" data-id= "<?=$model->id?>" class = "img-thumbnail">
	
</div>
</div>
</div>
<br>

<div class="row">
	<div class="col-md-12">
		<div class="profile-form text-center text-short-100">
			<a href="<?=$model->link?>" class="btn btn-success " target="_blank"><i class="fa fa-external-link"></i> <?=$model->name .' : '.$model->link?></a>
		</div>
	</div>
</div>


<div class="row">      
    <div class="box-body">
		<table class="table table-bordered">
			<tbody>
				<?php foreach ($modelFiles as $modelFile):?>
				<tr>
					<td><?= $modelFile->name.'.'.$modelFile->type?></td>
					<td>
						<?=	$modelFile->type =='url' ?
								'<a href="'.Url::to($modelFile->url).'"  target="_blank" >Link</a> '														
							:
								'<a href="'.Url::to('@web/uploads/weblink/'.$model->id.'/'.$modelFile->file).'"  target="_blank">ดาวน์โหลด</a> '
							;		
						?>					
					</td>
				</tr>	
				<?php endforeach;?>												
			</tbody>
		</table>
 	</div>
</div>




