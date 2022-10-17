<?php

    /**
     * @var  $model  \app\models\HwStory;
     * @var  $story  \app\models\HwStory;
     */

    use app\models\Hardware;
    use yii\helpers\ArrayHelper;

    $hw_model = ArrayHelper::map(\app\models\HwModel::getModel() ,'id', 'name');
    $status_color = \app\models\HwStory::getStatusColor();
    $status = \app\models\HwStory::getStatus();

?>

<style>
    .dropdown-menu-lg {
        max-width: 500px;
        min-width: 400px;
        padding: 0;
    }
</style>


<li class="nav-item dropdown">
    <a class="nav-link" data-toggle="dropdown" href="#">
        <?php if($type == 1): ?>
            <i class="far fa-bell"></i> Общее
        <?php else: ?>
            <i class="far fa-bell"></i> Мое
        <?php endif; ?>
        <span class="badge badge-warning navbar-badge"></span>
    </a>
    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
        <?php foreach($model as $item): ?>

            <div class="dropdown-divider"></div>
            <a href="<?= \yii\helpers\Url::toRoute(['site/tehnic', 'id_user' => $item->id_user]) ?>" class=" p-2 dropdown-item " >
                <div>

                    <?php if(isset($item->tehnic->type)): ?>
                        <i class="<?= \app\models\HwDeviceType::findOne($item->tehnic->type)->icon ?> fs-12"></i>

                    <small class="float-right ">
                        <?= isset($item->user->username) ? $item->user->username : '' ?>
                    </small>
                    <small>
                        <strong><?= \app\models\HwTehnic::getPassport($item->id_tehnic).'. '.$hw_model[$item->tehnic->id_model] ?></strong>
                    </small>.
                    <?php endif; ?>

                </div>
                <div>
                    <small><?= date('d-m-Y', $item->date).'. '. Hardware::fio($item->editor->username,1) ?></small>
                    <small class="float-right badge  <?=array_key_exists($item->status, $status_color)? $status_color[$item->status] : ''?>">
                        <?= array_key_exists($item->status, $status)? $status[$item->status] : ''; ?>
                    </small>
                </div>
            </a>
        <?php endforeach; ?>

    </div>
</li>
