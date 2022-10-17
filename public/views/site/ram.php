<?php


    /**
     * @var  $model  \app\models\HwTehnicRam;
     * @var  $data  \app\models\HwTehnicRam
     */

    use app\components\access\Redactor;
    use kartik\widgets\Select2;
    use yii\helpers\ArrayHelper;
    use yii\helpers\Html;


    $_models = \yii\helpers\ArrayHelper::map($data, 'id', 'name');
    $device_type = ArrayHelper::map(\app\models\HwDeviceType::getDeviceType(), 'id', 'name');


?>


<div class="col-12 row pt-3">
    <!-- Формая добавляения -->
    <div class="col-3">
        <?php $form = \yii\bootstrap4\ActiveForm::begin(['id' => 'users-form']) ?>
        <div class="col-12 card card-outline card-secondary">

            <?= $form->field($model, 'name')->textInput(); ?>
            <?= $form->field($model, 'count')->textInput() ?>

            <?= $form->field($model, 'type')->widget(Select2::classname(), [
                'data' => $device_type,
                'options' => ['placeholder' => '', 'class' => 'form-control-sm'],
                'pluginOptions' => ['allowClear' => true, 'tags' => true,]]);
            ?>

            <?php Redactor::begin() ?>
                <?= Html::submitButton('Отправить', ['class' => 'btn btn-primary btn-block btn-sm mb-2']) ?>
            <?php Redactor::end() ?>
        </div>
        <?php \yii\bootstrap4\ActiveForm::end(); ?>
    </div>

    <!-- /.Формая добавляения -->

    <!-- Список техники -->

    <div class="col-9 ">


        <div class="card">
            <table class="table table-sm table-hover sort table_sort" id="hw-tehnic" style="font-size: 10pt">
                <thead>
                <tr class="hw-bg-light-blue">
                    <th colspan="9"> Комплектующие</th>
                </tr>
                <tr>
                    <th>Модель</th>
                    <th colspan="9"> Количество</th>
                </tr>
                </thead>

                <tbody>
                <?php foreach (array_unique($_models) as $item): ?>

                    <tr id="hw-tehnic-1245" class="">
                        <td>
                            <?= $item ?>
                        </td>
                        <td><?= count(array_keys($_models, $item)) ?></td>
                    </tr>
                <?php endforeach; ?>

                </tbody>
            </table>
        </div>


        <div class="card">
            <table class="table table-sm table-hover sort table_sort" id="hw-tehnic" style="font-size: 10pt">
                <thead>
                <tr class="hw-bg-light-blue">
                    <th colspan="9"> Полный перечень комплектующих</th>
                </tr>
                <tr>
                    <th>Модель</th>
                    <th>Закрепелна за техникой</th>
                    <th>Дата добавления</th>
                    <th>Тип устройства</th>
                </tr>
                </thead>
                <tbody>

                <?php foreach ($data as $item): ?>
                    <tr id="hw-tehnic-1245" class="">
                        <td>
                            <?= $item->name ?>
                        </td>
                        <td>
                            <a href="<?= \yii\helpers\Url::toRoute(['search', 'search' => '=' . $item->id_tehnic]) ?>"><?= $item->id_tehnic ?></a>
                        </td>
                        <td><?= isset($item->date) ? date('Y-m-d', $item->date) : '' ?></td>
                        <td><?= $device_type[$item->type] ?></td>
                    </tr>
                <?php endforeach; ?>

                </tbody>
            </table>
        </div>


    </div>
    <!-- /.Список техники -->

</div>



