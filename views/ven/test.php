<?php
use yii\helpers\Url;
use app\models\Ven;
use app\models\VenCom;
use app\models\VenChange;

// $models = Ven::findOne($id);
         $id = '1567276511';
// $model = Ven::findOne($id);
// $dB = date('Y-m-d');  

$ven_id1_old = 1567393517;
$ven_id2_old = 1568688241;
$models = VenChange::find()
    ->where(['ven_id1' => $ven_id2_old])
    ->orWhere(['ven_id2' => $ven_id1_old])    
    ->all();     

var_dump($models);

?>
            <table id="example1" class="table table-bordered table-striped dataTable" role="grid" >
                				
                <tbody>  
                                                
                    <?php foreach ($models as $model): ?>
                    <tr>						                
                        <td><?= $model->id?></td>	
                        <td><?php echo $model->DateThai_full($model->month)?></</td>									
                        <td> <?php echo $model->getProfileName()?> </td>
                        <td class = "text-center">
                            <?php// echo $model->status ;?>				        
                        </td>
                        
                    </tr>
                    <?php  endforeach; ?>								
                </tbody>
            </table> 