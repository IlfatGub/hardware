<?php
    /**
     * @var  $title
     * @var  $color
     * @var  $re
     * @var  $users_list \app\models\HwUsers
     */

    use app\models\HwTehnic;
    use yii\helpers\ArrayHelper;
    use yii\helpers\Html;
    use yii\helpers\Url;

    $_update = isset($_GET['update']) ? $_GET['update'] : null;

    $users = new \app\models\HwUsers();
    $wh = new \app\models\HwWh();
    $models = new \app\models\HwModel();

    $_depart = ArrayHelper::map(\app\models\HwPodr::getDepart(), 'id', 'name');
    $_user = ArrayHelper::map($users->getUsers(), 'id', 'username');

    $id_user = isset($_GET['id_user']) ? $_GET['id_user'] : null;
    $org = isset($_GET['org']) ? $_GET['org'] : null;

    $disable = isset($_update) ? true : false;
?>

<!--        выводим список пользователей организации/отдела-->
<?php if (count($users_list) > 1): ?>
    <div class="card  mb-2">
        <div class="card-body p-0">
            <table class="table table-sm table-hover sort table_sort" id="hw-tehnic" style="font-size: 10pt">
                <thead>
                <tr>
                    <th>ФИО</th>
                    <th>Отдел</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($users_list as $item): ?>
                    <?php $depart_name = \app\models\Hardware::getDepartNormalView($_depart[$item->id_depart]) ?>
                    <tr id="hw-tehnic-users">
                        <td>
                            <span style="display: none"> <?= isset($item->id) ? $item->username : '' // Для корректной сортировки    ?>  </span>
                            <?= Html::button(isset($item->id) ? "<i class=\"fa fa-info-circle mr-1 hw-bg-light-yellow\"></i>" : '', ['value' => Url::to(['site/user-info', 'id' => $item->id]), 'class' => 'p-0 pb-1 m-0 btn btn-sm btn-url modalButton-lg', 'title' => 'Информация']); ?>

                            <?= \app\components\template\DownloadButton::widget(['id_user' => $item->id]) ?>

                            <?= Html::a(isset($item->id) ? $item->username : '', [Url::to(['site/tehnic', 'id_user' => $item->id])] , ['style' => 'color:black']); ?>
                        </td>

                        <td>
                            <?php if (strlen($depart_name[1]) > 1): ?>
                                <small class="fs-12">
                                    <code>
                                        <a href="<?= Url::toRoute(['site/department', 'depart_name' => $depart_name[1], 'org' => $org ]) ?>"><?= $depart_name[1] ?></a>
                                    </code>
                                </small> <br>
                            <?php endif; ?>
                            <a href="<?= Url::toRoute(['site/department', 'depart_name' => $depart_name[0], 'org' => $org ]) ?>" style="color: black"><?= $depart_name[0] ?></a>

                        </td>

                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
<?php endif; ?>
<!--        выводим список пользщователей организации/отдела-->

