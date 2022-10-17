<?php
    /**
     * @var  $model  \app\models\HwDepart;
     * @var  $data  \app\models\HwDepart;
     * @var  $depart  \app\models\HwDepart;
     *
     */

    use app\components\access\Redactor;
    use app\components\template\Select2;
    use app\models\HwDepart;
    use kartik\typeahead\Typeahead;
    use yii\helpers\ArrayHelper;
    use yii\helpers\Html;
    use yii\helpers\Url;
    use app\models\Login;


    $login = ArrayHelper::map(Login::getLoginList(), 'id', 'username');
    $hw_depart = new HwDepart();

?>

<div class="col-12 row pt-3">
    <!-- Формая добавляения -->
    <div class="col-3">
        <?php $form = \yii\bootstrap4\ActiveForm::begin(['id' => 'depart-form']) ?>
        <div class="col-12 card card-outline card-secondary">
            <div class="card-header mb-3">
                <h3 class="card-title">
                    <a href="<?= \yii\helpers\Url::toRoute(['type']) ?>" type="button" class="btn btn-default"><i class="fas fa-sync"></i></a>

                    Добавляем пользователя</h3>

                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-times"></i>
                    </button>
                </div>
                <!-- /.card-tools -->

            </div>
            <?php echo Select2::widget(['model' => $model, 'form' => $form, 'attribute' => 'name', 'data' => ArrayHelper::map(HwDepart::find()->where(['type' => $model::TYPE_PARENT_DEPART])->all(), 'name', 'name')]) ?>

            <?php echo Select2::widget(['model' => $model, 'form' => $form, 'attribute' => 'id_user', 'data' => ArrayHelper::map(Login::getLoginList(), 'id', 'username')]) ?>


            <?= Html::submitButton('Добавить', ['class' => 'btn btn-primary btn-block btn-sm mb-2']) ?>
        </div>
        <?php \yii\bootstrap4\ActiveForm::end(); ?>

        <div class="card">
            <table class="table table-sm table-hover table_sort" id="hw-depart"  style="font-size: 10pt">
                <thead>
                <tr>
                    <th class="col-1">#</th>
                    <th class="col-6">Отдел</th>
                    <th class="col">Акт</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach($depart as $item): ?>
                    <tr>
                        <td class="col-1"><?= $item->id ?></td>
                        <td class="col-10"><?= $item->name ?></td>
                        <td class="col-2"><input type="checkbox" class="hd-checkbox"  <?= $item->access_act == 1 ? 'checked' : '' ?> id="<?= $item->id ?>"  data-parent="depart" data-key="access_act"></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="col-9 ">
        <div class="card">
            <div class="card-body p-0">
                <table class="table table-sm table-hover table_sort" id="hw-depart"  style="font-size: 10pt">
                    <thead>
                    <tr>
                        <th class="col-6">Отдел</th>
                        <th class="col-6">ФИО</th>
                        <th class="col">Чтение</th>
                        <th class="col">Запись</th>
                        <th class="col">Поиск</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach($data as $item): ?>
                        <tr class="<?= $item->visible == 1 ? '' : 'alert-secondary'  ?>">
                            <td><?= $item->name ?></td>
                            <td><?= $login[$item->id_user] ?></td>
                            <td><input type="checkbox" class="hd-checkbox"  <?= $item->access_read == 1 ? 'checked' : '' ?> id="<?= $item->id ?>"  data-parent="depart" data-key="access_read"></td>
                            <td><input type="checkbox" class="hd-checkbox"  <?= $item->access_write == 1 ? 'checked' : '' ?> id="<?= $item->id ?>"  data-parent="depart" data-key="access_write"></td>
                            <td><input type="checkbox" class="hd-checkbox"  <?= $item->access_search == 1 ? 'checked' : '' ?> id="<?= $item->id ?>"  data-parent="depart" data-key="access_search"></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
