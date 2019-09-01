<?php
use yii\helpers\Url;
use app\models\Ven;
use app\models\VenCom;
use app\models\VenChange;

// $models = Ven::findOne($id);
        
        // if($model->ven_time == '16:30:55'){  
            $dB = date('Y-m-d', strtotime('2019-09-7')); 
            $dB1 = date('Y-m-d', strtotime('-1 day', strtotime('2019-09-7')));

            $models = Ven::find()
            ->where([
                'ven_date' => $dB,
                'ven_time' => ['08:30:01','08:30:11','08:30:22','16:30:55'],
                'status' => [1,2,3],
                // 'status' => 2,
                // 'status' => 1,
                // 'user_id' => Yii::$app->user->identity->id,
                'user_id' => 23,
            ])
            ->orWhere([
                'ven_date' => $dB1,
                'ven_time' => ['16:30:55'],
                'status' => [1,2,3],
                'user_id' => 23,
                ])
                ->all();
                     ///
            
        // }


?>
            <table id="example1" class="table table-bordered table-striped dataTable" role="grid" >
                				
                <tbody>  
                                                
                    <?php foreach ($models as $model): ?>
                    <tr>						                
                        <td><?=$model->id?></td>	
                        <td><?=$model->DateThai_full($model->ven_date)?></</td>									
                        <td> <?=$model->getProfileName()?> <?= VenCom::getVen_time()[$model->ven_time];?></td>
                        <td class = "text-center">
                            <?=$model->status ;?>				        
                        </td>
                        
                    </tr>
                    <?php  endforeach; ?>								
                </tbody>
            </table> 