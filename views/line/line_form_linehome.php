<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;


?>

		
					<div class="box box-primary">
						<div class="box-header with-border">
							<h3 class="box-title"><?= Html::encode($this->title) ?></h3>
							<div class="box-tools pull-right">
							</div>	
						</div>	
						<div class="box-body">
							<?php  $form = ActiveForm::begin();  ?>
                            <?=$form->field($LineHome, 'name_ser')?>
							<?=$form->field($LineHome, 'client_id')?>
							<?=$form->field($LineHome, 'client_secret')?>							
							<?=$form->field($LineHome, 'api_url')?>
							<?=$form->field($LineHome, 'callback_url')?>
							<?=Html::submitButton('บันทึก', ['class' => 'btn btn-success'])?>
							<?php ActiveForm::end()?>
						</div>			
					</div>
			