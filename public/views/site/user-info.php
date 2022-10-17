<?php
/**
 * Created by PhpStorm.
 * User: 01gig
 * Date: 26.05.2020
 * Time: 11:00
 */

/**
 * @var  $model  \app\models\HwTehnic;
 * @var  $user  \app\models\HwUsers;
 */


$podr = new \app\models\HwPodr(['id' => $user->id_podr]);
$org = new \app\models\HwPodr(['id' => $user->id_org]);
$depart = new \app\models\HwPodr(['id' => $user->id_depart]);

$_org = $org->getById()->name ?  $org->getById()->name.'. ' : '';
$_podr = $podr->getById()->name ?  $podr->getById()->name.'. ' : '';
$_depart = $depart->getById()->name ?  $depart->getById()->name.'. ' : '';

?>

<div class="callout callout-info">
    <h5><i class="fas fa-user"></i> <?= $user->username ?></h5>
    <?= $_org . $_podr . $_depart?>
</div>

<div class="card">
    <div class="card-body p-0">
        <table class="table table-sm table-hover" style="font-size: 10pt">
            <thead>
            <tr>
                <th>№</th>
                <th>Устройство</th>
                <th>Серийный номер</th>
                <th>ФИО</th>
                <th>Склад</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach($model as $item): ?>
                <tr>
                    <td><?= $item->id ?></td>
                    <td><strong><?= $item->model->name ?></strong></td>
                    <td><?= $item->serial ?></td>
                    <td><?= $item->user->username ?></td>
                    <td><?= $item->wh->name ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
