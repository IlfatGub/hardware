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


<!--       Выбор столбцов      -->
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
<!--       Выбор столбцов      -->

<?php if ($error): ?>
    <div class="alert alert-danger" role="alert">
        <?= $error ?>
    </div>
<?php endif; ?>

<?php \yii\widgets\Pjax::end() ?>


