<?php
    /**
     * Created by PhpStorm.
     * User: 01gig
     * Date: 26.05.2020
     * Time: 11:00
     */


    use app\models\HwPodr;
    use app\models\HwUsers;
    use app\models\HwWh;
    use app\models\Login;
    use yii\helpers\Html;
    use yii\helpers\Url;
    use yii\widgets\Pjax;

    //    echo "<pre>"; print_r($tehnic);

    //    echo "<pre>"; print_r($model ); die();


    /**
     * @var  $model  \app\models\HwStory;
     * @var  $tehnic  \app\models\HwStory;
     * @var  $user  HwUsers;
     * @var  $ram  \app\models\HwTehnicRam;
     */


    //    $podr = new \app\models\HwPodr(['id' => $user->id_podr]);
    //    $org = new \app\models\HwPodr(['id' => $user->id_org]);
    //    $depart = new \app\models\HwPodr(['id' => $user->id_depart]);
    //
    //    $_org = $org->getById()->name ?  $org->getById()->name.'. ' : '';
    //    $_podr = $podr->getById()->name ?  $podr->getById()->name.'. ' : '';
    //    $_depart = $depart->getById()->name ?  $depart->getById()->name.'. ' : '';

?>

<?php Pjax::begin(['enablePushState' => false]) ?>

<div class="callout callout-info text-center">
    <h3 class="my-1 py-1">
        <strong><?= str_pad($tehnic->id, 4, '0', STR_PAD_LEFT) ?></strong> <?= \app\models\HwModel::findOne($tehnic->id_model)->name ?>
    </h3>


    <blockquote class="blockquote text-right mt-1 pt-1" style="border-left:0px">
        <?php if ($ram): ?>
            <?php foreach ($ram as $item): ?>
                <footer class="blockquote-footer mt-1 pt-1">
                    <?= $item->name ?> <code><?= date('d-m-Y', $item->date_add) ?></code>
                    <?= Html::a('<i class="fas fa-trash-alt"></i>', Url::toRoute(['add-component' , 'id' => $tehnic->id, 'id_ram' => $item->id])) ?>
                    <br>
                </footer>
            <?php endforeach; ?>
        <?php endif; ?>
    </blockquote>

</div>


<div class="card">
    <div class="card-body p-0">
        <table class="table table-sm table-hover" style="font-size: 10pt">
            <thead>
            <tr>
                <th>На ком техника</th>
                <th>Кто вносил изм.</th>
                <th>Орг.</th>
                <th>Подр.</th>
                <th>Отдел</th>
                <th>Дата</th>
                <th>Склад</th>
                <th>serial</th>
                <th>nomen</th>
                <th>location</th>
                <th>Комментарий</th>
            </tr>
            </thead>
            <tbody>

            <?php foreach ($model as $item): ?>

                <?php  ?>

                <tr>
                    <td>
                        <?= Html::a(isset($item->id_user) ? HwUsers::findOne($item->id_user)->username : '', [Url::to(['site/tehnic', 'id_user' => $item->id_user])]); ?>
                    </td>
                    <td><?= Login::findOne($item->id_editor)->username ?></td>
                    <td><?= isset($item->id_org) ?  HwPodr::findOne($item->id_org)->name : '' ?></td>
                    <td><?= isset($item->id_podr) ?  HwPodr::findOne($item->id_podr)->name : '' ?></td>
                    <td><?= isset($item->id_depart) ?  HwPodr::findOne($item->id_depart)->name : '' ?></td>
                    <td><?= date('Y-m-d', $item->date) ?></td>
                    <td><?= $item->depart->name.'. '.HwWh::findOne($item->id_wh)->name ?></td>
                    <td><?= $item->serial ?></td>
                    <td><?= $item->nomen ?></td>
                    <td><?= $item->location ?></td>
                    <td><?= $item->comment ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>


<?php Pjax::end() ?>