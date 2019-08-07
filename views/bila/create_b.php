<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

use kartik\date\DatePicker;


?>

<div class="widget-body no-padding">

    <?php 
    $form = ActiveForm::begin([
		'id' => 'weblink-form',
		'options' => [
            'class' => 'smart-form',
            'novalidate'=>'novalidate',
            'enctype' => 'multipart/form-data'
        ],
        //'layout' => 'horizontal',
        'fieldConfig' => [
            //'template' => "{label}{input}{error}",
            'labelOptions' => ['class' => 'label'],
        ],
        'enableAjaxValidation' => true,
	]);  ?>
<?= $form->field($model, 'user_id')->hiddenInput(['readonly' => true, 'value' => Yii::$app->user->identity->id])->label(false) ?>
<?= $form->field($model, 'date_create')->hiddenInput(['readonly' => true, 'value' => date("Y-m-d")])->label(false) ?>


    <fieldset>
			<div class="row">
				<section class="col col-5">
					<label class="input">
                        <?= $form->field($model, 'date_begin')->widget(DatePicker::classname(), [
                                'options' => ['placeholder' => 'ลาตั้งแต่วันที่'],
                                'pluginOptions' => [
                                    'autoclose'=>true,
                                    'format' => 'yyyy-mm-dd'
                                ]])->label(false);;
                        ?>
					</label>
				</section>
				<section class="col col-5">
					<label class="input">
                        <?= $form->field($model, 'date_end')->widget(DatePicker::classname(), [
                            'options' => ['placeholder' => 'ถึงวันที่'],
                            'pluginOptions' => [
                                'autoclose'=>true,
                                'format' => 'yyyy-mm-dd'
                            ]])->label(false);;
                        ?>
					</label>
				</section>
                    <?= $form->field($model, 'date_total', [
                            'inputOptions' => [
                                'placeholder' => $model->getAttributeLabel('date_total')
                            ],
                            'template' => '<section class="col col-2"><label class="input">{label}</label> <label class="input">{input}<b class="tooltip tooltip-top-right">'.$model->getAttributeLabel('date_total').'</b></label><em for="name" class="invalid">{error}{hint}</em></section>'
                        ])->label(false);
                    ?>
				<!-- <section class="col col-2">
					<label class="input">                                                        
						<input type="text" placeholder="มีกำหนด(วัน)" data-cip-id="cIPJQ342845641">
					</label>
				</section> -->
            </div>
            <div>
				<section >
					<label class="input">
						<input type="text" placeholder="เนื่องจาก" data-cip-id="cIPJQ342845642">
					</label>
				</section>												
            </div>
        </fieldset>
        <fieldset>    
            <div class="row">
				<section class="col col-5">
					<label class="input">
                        <?= $form->field($model, 'dateO_begin')->widget(DatePicker::classname(), [
                            'options' => ['placeholder' => 'ลาครั้งสุดท้าย ตั้งแต่วันที่'],
                            'pluginOptions' => [
                                'autoclose'=>true,
                                'format' => 'yyyy-mm-dd'
                            ]])->label(false);;
                        ?>
                    </label>
				</section>
			    <section class="col col-5">
					<label class="input">
                        <?= $form->field($model, 'dateO_end')->widget(DatePicker::classname(), [
                            'options' => ['placeholder' => 'ถึงวันที่'],
                            'pluginOptions' => [
                                'autoclose'=>true,
                                'format' => 'yyyy-mm-dd'
                            ]])->label(false);;
                        ?>
                    </label>
				</section>
                <?= $form->field($model, 'dateO_total', [
                            'inputOptions' => [
                                'placeholder' => $model->getAttributeLabel('date_total')
                            ],
                            'template' => '<section class="col col-2"><label class="input">{label}</label> <label class="input">{input}<b class="tooltip tooltip-top-right">'.$model->getAttributeLabel('date_total').'</b></label><em for="name" class="invalid">{error}{hint}</em></section>'
                        ])->label(false);
                    ?>
                <!-- <section class="col col-2">
					<label class="input">
						<input type="text" placeholder="มีกำหนด(วัน)" data-cip-id="cIPJQ342845644">
					</label>
				</section> -->
			</div>
                                        
			<div class="row">
            <?= $form->field($model, 't1', [
                            'inputOptions' => [
                                'placeholder' => $model->getAttributeLabel('t1')
                            ],
                            'template' => '<section class="col col-3"><label class="input">{label}</label> <label class="input">{input}<b class="tooltip tooltip-top-right">'.$model->getAttributeLabel('t1').'</b></label><em for="name" class="invalid">{error}{hint}</em></section>'
                        ])->label(false);
                    ?>

				<!-- <section class="col col-3">
					<label class="input">
						<input type="text" placeholder="เคยลามาแล้วรวม" data-cip-id="cIPJQ342845645">
					</label>
				</section> -->
            </div>
        </fieldset>
        <fieldset>   
            <?= $form->field($model, 'address', [
                            'inputOptions' => [
                                'placeholder' => $model->getAttributeLabel('address')
                            ],
                            'template' => '<section><label class="input">{label}</label> <label class="input">{input}<b class="tooltip tooltip-top-right">'.$model->getAttributeLabel('address').'</b></label><em for="name" class="invalid">{error}{hint}</em></section>'
                        ])->label(false);
                    ?>
			<!-- <section>
				<label class="input">
					<input type="text" placeholder="ระหว่างลาติดต่อได้ที่.." data-cip-id="cIPJQ342845654">
				</label>
			</section> -->
		</fieldset>

 
<fieldset class="text-right"> 
    <?= Html::resetButton('Reset', ['class' => 'btn btn-warning btn-lg']) ?>
     <?= Html::submitButton('Save', ['class' => 'btn btn-primary btn-lg']) ?>
</fieldset>

    <?php ActiveForm::end(); ?>

</div>
