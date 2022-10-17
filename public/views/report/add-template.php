<?php
    /**
     * @var  $model  \app\models\HwSettings;
     * @var  $type
     * @var  $template
     * @var  $error ;
     */


    use kartik\widgets\Select2;
    use yii\helpers\Html;
    use yii\helpers\Url;

    $settings = new \app\models\HwSettings();

    $data_table = isset($_GET['data']) ? $_GET['data'] : null;

    $template = isset($_GET['template']) ? $_GET['template'] : '';

    if ($template)
        $default_value = \app\models\HwSettings::find()->andWhere(['type' => 2, 'tb1' => $template])->all();

    $field_tehnic = $settings->getTehnicTableFieldReport();
    $field_ram = $settings->getRamField();

    $field_ram_dr = $settings->getRamFiledDropdown();
    $field_ram_tehnic = $settings->getTehnicFiledDropdown();

    unset($field_tehnic['counter'])
?>

<?php \yii\widgets\Pjax::begin(['id' => 'report', 'enablePushState' => false]) ?>

<!--          Выбор таблицы            -->
<?php if (!$type): ?>
    <div class="col-12 text-center">
        <label for="" class="fs-20"> Выбираем таблицу </label>
        <?php foreach ($settings->getReportType() as $key => $item): ?>
            <?= Html::button('Техника', ['value' => Url::to(['report/add-template', 'type' => 'field', 'data' => 'tehnic']), 'class' => ' col btn btn-lg btn-outline-primary modalButton-sm float-right mb-2', 'title' => 'История заявки']); ?>
        <?php endforeach; ?>

        <?= Html::button('отчет', ['value' => Url::to(['report/add-templates', 'type' => 'field', 'data' => $key]), 'class' => ' col btn btn-lg btn-outline-primary modalButton-sm float-right mb-2', 'title' => 'История заявки']); ?>
    </div>
<?php endif; ?>
<!--          Выбор таблицы            -->


<!--       Выбор столбцов      -->
<?php if ($type == 'field'): ?>
    <label for="" class="fs-20">
        <?= Html::button('<i class="fa fa-reply" aria-hidden="true"></i>', ['value' => Url::to(['report/add-template']), 'class' => 'btn btn-url modalButton-sm', 'title' => 'История заявки']); ?>
        <?= $data_table == 'ram' ? 'Комлектующие' : 'Характеристики' ?>
    </label>

    <?php $form = \yii\bootstrap4\ActiveForm::begin(['action' => ['add-template', 'data' => $_GET['data'], 'template' => $template], 'options' => ['enctype' => 'multipart/form-data', 'data-pjax' => true]]) ?>
    <div class="row">
        <div class="col-12">
            <?= $form->field($model, 'tb1')->textInput()->label('Название шаблона'); ?>
            <?php
                echo $form->field($model, 'tb3')->widget(Select2::classname(), [
                    'data' => $data_table == 'ram' ? $field_ram : $field_tehnic,
                    'options' => ['placeholder' => 'Select a state ...', 'multiple' => true,],
                    'pluginOptions' => [
                        'allowClear' => true,
                        'closeOnSelect' => false
                    ],
                ]);
                ?>
            <?= Html::submitButton('Далее', ['class' => 'btn btn-primary col mt-5']) ?>
        </div>
    </div>
    <?php \yii\bootstrap4\ActiveForm::end(); ?>
<?php endif; ?>
<!--       Выбор столбцов      -->


<!--    Устанавливаем занчение по умолчания для отчета     -->
<?php if ($type == 'default_value'): ?>
    <label for="" class="fs-20"> Значения по умолчанию </label>


    <?php foreach ($default_value as $item): ?>


        <!--        --><?php //if ($item->tb2 == 'ram' ? array_key_exists($item->tb3, $field_ram) : array_key_exists($item->tb3, $field_tehnic)): ?>

        <div class="row mt-2">
                <label for=""><?= $item->tb2 == 'ram' ? $field_ram[$item->tb3] : $field_tehnic[$item->tb3] ?></label>
                <?php if ($item->tb2 == 'ram' ? array_key_exists($item->tb3, $field_ram_dr) : array_key_exists($item->tb3, $field_ram_tehnic)): ?>

                    <?php
                    echo Select2::widget([
                        'name' => $item->tb3,
                        'data' => $item->tb2 == 'ram' ? $field_ram_dr[$item->tb3] : $field_ram_tehnic[$item->tb3],
                        'id' => $item->id,
                        'value' => explode(',', $item->tb4),
                        'options' => [
                            'placeholder' => '-- Необходимо выбрать из списка --',
                            'class' => 'hd-checkbox',
                            'multiple' => true,
                            'data-key' => 'tb4',
                            'data-parent' => 'report',
                        ],
                    ]);
                    ?>
                <?php else: ?>
                    <input class="form-control hd-checkbox" data-key="tb4"
                           data-parent="report" id="<?= $item->id ?>" value="<?= $item->tb4 ?>">
                <?php endif; ?>
        </div>


        <!--        --><?php //endif; ?>

    <?php endforeach; ?>
<?php endif; ?>
<!--    Устанавливаем занчение по умолчания для отчета     -->


<?php if ($error): ?>
    <div class="alert alert-danger" role="alert">
        <?= $error ?>
    </div>
<?php endif; ?>

<?php \yii\widgets\Pjax::end() ?>


