<?php

use yii\helpers\Html;
use yii\widgets\LinkPager;
use yii\data\Pagination;

?>

<table id="myTable" class="table table-hover table-bordered table-striped">
    <tbody>
        <tr>
            <th style="width: 10px">ID</th>
            <th>วันที่</th>
            <th>เรื่อง</th>
            <th >ผู้ส่ง</th>
            <!--<th class="text-center" style="width: 200px">เครื่องมือ</th>-->
        </tr>
        
<?php foreach ($models as $model): ?>

            <tr >
                <td> <?= $model->id; ?></td>
                <td><?= $model->create_at; ?> <?php
                    $phpdate = strtotime($model->create_at);
                    //echo "" . date("m", $phpdate)."" . date("m");
                    if (date("m", $phpdate) == (date("m") - 1)) {
                        echo '<i class="fa fa-gavel"></i>';
                    }
                    ?></td>
                <td>
                    <a href="?r=ppss/view_download&file_name=<?= $model->file; ?>" class="panel-title-right" target="<?php
                    if (Yii::$app->user->identity) {
                        echo '_blank';
                    }
                    ?>"><?= $model->name; ?></a>
                       <?php
                       if ($model->note == '1') {
                           echo '<i class="fa fa-check"></i>';
                       } else {

                           echo "<a  href='?r=ppss/bcheck&id=$model->id' class='btn btn-danger btn-xs'>ตรวจแล้ว กด!</a>";
                       }
                       ?>

                </td>
                <td> <?= $model->create_own ?></td>
<!--                <td class="" >
                    <a href="#" class="act-update btn btn-warning" data-id ="<?= $model->id ?>" data-toggle="modal" data-target="#activity-modal">แก้ไข</a>
                    <a class="btn btn-danger" href="?r=ppss/delete&id=<?= $model->id ?>" data-confirm="คุณแน่ใจนะ ที่จะลบ?" data-method="post"><i class="fa fa-fw fa-remove"></i> ลบ</a>
                </td>-->
            </tr>
<?php endforeach; ?>
    </tbody>
</table>

<?php
$script = <<< JS

$(document).ready(function() {

        $(".act-view").click(function(e) {
                var fID = $(this).data("id");
                $.get(
                    "?r=ppss/view",
                    {
                        id: fID
                    },
                    function (data)
                    {
                        $("#activity-modal").find(".modal-body").html(data);
                        $(".modal-body").html(data);
                        $(".modal-title").html("เปิดดูข้อมูลVbook");
                        $("#activity-modal").modal("show");
                    }
                );
            });

        $(".act-update").click(function(e) {

                var fID = $(this).data("id");
                $.get(
                    "?r=ppss/update",
                    {
                        id: fID
                    },
                    function (data)
                    {
                        $("#activity-modal").find(".modal-body").html(data);
                        $(".modal-body").html(data);
                        $(".modal-title").html("แก้ไขข้อมูลสมาชิก");
                        $("#activity-modal").modal("show");
                    }
                );
            });
});
JS;
$this->registerJs($script);
?>
