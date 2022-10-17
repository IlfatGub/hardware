<?php

    /**
     * Created by PhpStorm.
     * User: 01gig
     * Date: 21.10.2021
     * Time: 14:20
     */

    use app\components\access\Redactor;
    use app\models\Hardware;
    use app\models\HwSettings;
    use app\models\HwSpeceficModel;
    use yii\helpers\ArrayHelper;
    use yii\helpers\Html;
    use yii\helpers\Url;

    /**
     * @var  $devices  \app\models\HwDeviceType;
     * @var  $model  \app\models\HwSpeceficModel;
     * @var  $model_specific  \app\models\HwSpeceficDevice;
     * @var  $model_list ;
     */

    $id_device = isset($_GET['id_device']) ? $_GET['id_device'] : null;
    $specification = isset($_GET['specification']) ? $_GET['specification'] : null;
    $template = isset($_GET['template']) ? $_GET['template'] : null;

    $url_options = $_GET;
?>

<?php //echo "<pre class='fs-12'>"; print_r($_GET ); echo "</pre>"; ?>

<?php \yii\widgets\Pjax::begin() ?>

<div class="col-12 row  pt-3">
    <div class="col-3">

        <div class="card bg-light">
            <div class="card-body">
                <div class="fs-20">
                    <strong>Устройство</strong>
                    <?php if ($specification): ?>
                        <?= Html::a('Список техники', Url::to(['/site/report', 'model_list' => json_encode($model_list) ]), ['class' => 'mr-1 btn btn-sm btn-info btn-url float-right', 'title' => 'Изменить шаблон']); ?>
                    <?php endif; ?>
                </div>

                <hr>
                <div class="card-columns1">
                    <select class="form-control" onchange="location = this.value;">
                        <?php foreach ($devices as $item): ?>
                            <?php if ($item->id == $id_device) { ?>
                                <option value="<?= \yii\helpers\Url::toRoute(['specification', 'id_device' => $item->id]) ?>"
                                        selected><?= $item->name ?></option>;
                            <?php } else { ?>
                                <option value="<?= \yii\helpers\Url::toRoute(['specification', 'id_device' => $item->id]) ?>"><?= $item->name ?></option>;
                            <?php } ?>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
        </div>

        <?php if ($id_device): ?>

            <div class="card bg-light">
                <div class="card-body">
                    <div class="fs-20">
                        <strong>Характеристики</strong>
                        <?php if ($specification): ?>
                            <a href="<?= Url::toRoute(['specification', 'id_device' => $id_device]) ?>"
                               class="float-right fs-14 btn btn-sm btn-info" title="Очистить поля"><i
                                        class="fas fa-sync-alt"></i></a>
                            <?php if (!$template):
                                echo Html::button("<i class=\"fas fa-layer-group \"></i>", ['value' => Url::to(['/report/spec-template', $specification, 'id_device' => $id_device]), 'class' => 'mr-1 btn btn-sm btn-primary float-right modalButton-lg', 'title' => 'Создать шаблон']);
                            else:
                                echo Html::a('<i class="fa fa-trash" aria-hidden="true"></i>', Url::to(['/report/add-template', 'type' => HwSettings::TYPE_DELETE, 'template' => $template]), ['class' => 'mr-1 btn btn-sm btn-danger float-right', 'title' => 'Изменить шаблон']);
                            endif; ?>

                            <hr>
                        <?php endif; ?>
                    </div>

                    <hr>

                    <?php foreach ($model_specific as $key => $item): ?>
                        <label for=""> <?= $item->name ?> </label>
                        <?= Hardware::getOptionUrl(HwSpeceficModel::find()->where(['specification' => $item->name])->all(), $url_options, $item->name) ?>
                        <hr>
                    <?php endforeach; ?>

                </div>
            </div>

        <?php endif; ?>
    </div>

    <?php if ($id_device): ?>

    <div class="col-9">
        <div class="card  reports-model">

            <table class="table table-sm table-hover table_sort" style="font-size: 10pt">
                <tbody>

                <thead>
                <tr>
                    <th>Наименование</th>
                    <?php
                        foreach ($model_specific as $sp_item) {
                            echo "<th>" . $sp_item->name . "</th>";
                        }
                    ?>
                </tr>
                </thead>

                <?php foreach ($model as $item): ?>
                    <tr>
                        <td>
                            <?php Redactor::begin() ?>
                            <?= Html::button("<i class=\"fa fa-cog mr-1 hw-cl-silver\"></i>", ['value' => Url::to(['/model/specification', 'id_model' => $item->id]), 'class' => 'p-0 m-0 btn btn-sm btn-url modalButton-lg', 'title' => 'История заявки']); ?>
                            <?php Redactor::end() ?>
                            <?= $item->name ?>
                        </td>
                        <?php
                            foreach ($model_specific as $sp_item) {
                                if ($item->specifications) {
                                    $_sp = \yii\helpers\ArrayHelper::map($item->specifications, 'specification', 'value');
                                    if (array_key_exists($sp_item->name, $_sp)) {
                                        echo "<td>" . $_sp[$sp_item->name] . "</td>";
                                    } else {
                                        echo "<td>-</td>";
                                    }
                                } else {
                                    echo "<td>-</td>";
                                }
                            }
                        ?>

                    </tr>
                <?php endforeach; ?>

                </tbody>
            </table>
        </div>
    </div>

</div>
<?php endif; ?>
<?php \yii\widgets\Pjax::end() ?>
