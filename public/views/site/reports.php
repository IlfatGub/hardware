<?php

    use app\models\HwDepart;
    use yii\helpers\ArrayHelper;
    use yii\helpers\Html;
    use yii\helpers\Url;


    $hw_depart = new HwDepart();


    $_type = ArrayHelper::map(\app\models\HwDeviceType::getDeviceType(), 'id', 'name');
    $wh = \app\models\HwWh::find()->where(['visible' => 1])->andWhere(['in', 'hw_depart', $hw_depart->getAccessReadAndWrite(1)])->all();
    $id_wh = isset($_GET['wh']) ? $_GET['wh'] : null
?>

<div class="col-12 row  pt-3">
    <div class="col-4 ">

        <div class="card-columns">
            <?php foreach($wh as $item): ?>
                <?= Html::a($item->name, [Url::to(['site/reports', 'wh' => $item->id ])], ['class' =>  $id_wh == $item->id ? 'card col-12 mt-2 btn btn-secondary alert-secondary' : 'card col-12 mt-2 btn btn btn-outline-secondary']); ?>
            <?php endforeach; ?>
        </div>

        <div class="card ">
            <table class="table table-sm   table_sort table-hover" id="hw-tehnic">
                <thead>
                <tr>
                    <th>Наименование</th>
                    <th>Тип</th>
                    <th>Количество</th>
                </tr>
                </thead>
                <?php if($id_wh): ?>
                    <?php foreach ($model as $item): ?>
                        <tr id="<?= $item['id_model'] ?>" data-id="<?=$id_wh?>" class="reports-click">
                            <td><?= $item['model']['name'] ?></td>
                            <td><?= $_type[$item['model']['type']] ?></td>
                            <td><?= $item['cnt_model'] ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </table>
        </div>
    </div>

    <div class="col-8">
        <div class="card  reports-model">

        </div>
    </div>

</div>
