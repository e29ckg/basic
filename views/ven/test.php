<?php
use yii\helpers\Url;
use app\models\Ven;
use app\models\VenCom;
use app\models\VenChange;

// $models = Ven::findOne($id);
         $id = '1567276511';
$model = Ven::findOne($id);
$dB = date('Y-m-d');       

// if($model->ven_time == '08:30:11' || $model->ven_time == '08:30:22'){
//     $modelVO = Ven::find()->where([
//         'user_id' => Yii::$app->user->identity->id,
//         'ven_month' => $model->ven_month,
//         'ven_time' => ['08:30:11','08:30:22'],
//         'status' => 1,
//         ])                
//         ->andWhere("ven_date >= $dB")
//         ->count();             
//         return  $modelVO ? $modelVO : null ; //จำนวนเวรที่สามารถเปลียนได้
// } 

if($model->ven_time == '16:30:55'){
    $modelVO = Ven::find()->where([
        'user_id' => Yii::$app->user->identity->id,
        'ven_month' => $model->ven_month,
        'ven_time' => $model->ven_time,
        'status' => 1,
        ])                
        ->andWhere("ven_date >= $dB")
        ->count();             
        // return  $modelVO ? $modelVO : null ; //จำนวนเวรที่สามารถเปลียนได้
}

// if($model->ven_time == '08:30:01'){
//     $modelVO = Ven::find()->where([
//         'user_id' => Yii::$app->user->identity->id,
//         'ven_month' => $model->ven_month,
//         'ven_time' => $model->ven_time,
//         'status' => 1,
//         ])                
//         ->andWhere("ven_date >= $dB")
//         ->count();             
//         return  $modelVO ? $modelVO : null ; //จำนวนเวรที่สามารถเปลียนได้
// }

// if($model->ven_time == '16:30:00'){
//     $modelVO = Ven::find()->where([
//         'user_id' => Yii::$app->user->identity->id,
//         'ven_month' => $model->ven_month,
//         'ven_time' => $model->ven_time,
//         'status' => 1,
//         ])                
//         ->andWhere("ven_date >= $dB")
//         ->count();             
//         return  $modelVO ? $modelVO : null ; //จำนวนเวรที่สามารถเปลียนได้
// }
// return  isset($modelVO) ? $modelVO : '1w,j,u' ; //จำนวนเวรที่สามารถเปลียนได้
var_dump($modelVO);

?>
            <table id="example1" class="table table-bordered table-striped dataTable" role="grid" >
                				
                <tbody>  
                                                
                    <?php //foreach ($models as $model): ?>
                    <tr>						                
                        <td><?php// echo $model->id?></td>	
                        <td><?php// echo $model->DateThai_full($model->ven_date)?></</td>									
                        <td> <?php// echo $model->getProfileName()?> <?php// echo VenCom::getVen_time()[$model->ven_time];?></td>
                        <td class = "text-center">
                            <?php// echo $model->status ;?>				        
                        </td>
                        
                    </tr>
                    <?php//  endforeach; ?>								
                </tbody>
            </table> 