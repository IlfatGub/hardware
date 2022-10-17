<?php

    /**
     * @var  $model  \app\models\HwUsers;
     * @var  $depart  \app\models\HwPodr;
     * @var  $tree
     * @var  $depart_full
     */

    use app\models\HwPodr;
    use app\models\HwPost;
    use kartik\select2\Select2;
    use kartik\typeahead\Typeahead;
    use yii\helpers\ArrayHelper;
    use yii\helpers\Html;


?>


<div class="col-12 row pt-3">

    <!-- Список пользователей -->
    <div class="col-12" >


        <?php echo \app\components\template\TehnicView::widget(
            [
                'model' => $model,
                'title' => isset($_GET['wh']) ?  \app\models\HwWh::findOne($_GET['wh'])->name : '',
            ])
        ?>
        <!-- /.Список пользователей -->
    </div>
</div>

