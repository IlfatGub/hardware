<?php


    use app\models\HwDeviceType;
    use kartik\widgets\Select2;
    use yii\helpers\Html;
    use yii\helpers\Url;

    $settings = new \app\models\HwSettings();

    $data_table = isset($_GET['data']) ? $_GET['data'] : null;

    $template = isset($_GET['template']) ? $_GET['template'] : '';

    if ($template) {
        $default_value = \app\models\HwSettings::find()->andWhere(['type' => 2, 'tb1' => $template])->all();
        $_def_map = \yii\helpers\ArrayHelper::map($default_value, 'tb3', 'tb4', 'tb2');

        $group = explode(',' ,$_def_map['tehnic']['category']);
        $category = explode(',' ,$_def_map['tehnic']['device_type']);
        $cat_list = \yii\helpers\ArrayHelper::map(HwDeviceType::find()->andFilterWhere(['in', 'category', explode(',', $group)])->all(), 'id', 'name');
    }

    $field_tehnic = $settings->getTehnicTableFieldReport();
    $field_ram_tehnic = $settings->getTehnicFiledDropdown();

    unset($field_tehnic['counter']);

    $reverse = $_def_map['specification']['reverse'] ? 'checked' : '';
?>


<?php $form = \yii\bootstrap4\ActiveForm::begin(['action' => ['add-templates', 'data' => $_GET['data'], 'template' => $template], 'options' => ['enctype' => 'multipart/form-data', 'data-pjax' => true]]) ?>
<!--       Выбор столбцов      -->
<div class="row">

    <div class="col-12">
        <?= $form->field($model, 'tb1')->textInput()->label('Название шаблона'); ?>

        <?php
            echo $form->field($model, 'tb3')->widget(Select2::classname(), [
                'data' => $field_tehnic,
                'size' => Select2::MEDIUM,
                'theme' => Select2::THEME_MATERIAL,
                'options' => ['placeholder' => '--- Поля ---', 'multiple' => true,],
                'pluginOptions' => [
                    'allowClear' => true,
                    'closeOnSelect' => false
                ],
            ])->label('Поля');
        ?>

        <div class="row">

            <div class="form-group col">
                <label for=""> Группа </label>
                <?php
                    echo Select2::widget([
                        'name' => 'group[]',
                        'data' => \app\models\HwDeviceType::getGroup(),
                        'id' => 'hw_group',
                        'value' => $group,
                        'size' => Select2::MEDIUM,
                        'theme' => Select2::THEME_MATERIAL,
                        'options' => [
                            'placeholder' => '-- Необходимо выбрать из списка --',
                            'class' => 'form-control hw-report-group',
                            'multiple' => true,
                            'data-key' => 'tb4',
                            'data-parent' => 'report',
                        ],
                    ]);
                ?>

                </select>
            </div>
            
            <div class="form-group col">
                <label for=""> Категория </label>
                <?php
                    echo Select2::widget([
                        'name' => 'category[]',
                        'data' => $group ? $cat_list : null,
                        'id' => 'hw-category',
                        'value' => $category,
                        'size' => Select2::MEDIUM,
                        'theme' => Select2::THEME_MATERIAL,
                        'options' => [
                            'placeholder' => '-- Необходимо выбрать из списка --',
                            'class' => 'form-control',
                            'multiple' => true,
                            'data-key' => 'tb4',
                            'data-parent' => 'report',
                        ],
                    ]);
                ?>
            </div>
        </div>

        <div class="form-group form-check">
            <input type="checkbox" class="form-check-input" id="exampleCheck1" name="reverse" <?=$reverse?>>
            <label class="form-check-label" for="exampleCheck1">Шаблон исключения. Для характеристик</label>
        </div>

        <div id="input_content">
            <?php
                $time = strtotime('now');
                foreach ($_def_map['tehnic'] as $key => $item):
                    if ($item && ($key <> 'category') && ($key <> 'device_type')) {
                        $time++;
                        echo \app\components\template\ReportInput::widget(['data' => 'tehnic', 'field' => $key, 'value' => $item, 'id' => $time]);
                    }
                endforeach;

                foreach ($_def_map['specification'] as $key => $item):
                    if ($item && ($key <> 'id_device') && ($key <> 'reverse')) {
                        $time++;
                        echo \app\components\template\ReportInput::widget(['data' => 'specification', 'field' => $key, 'value' => $item, 'id' => $time, 'id_device' =>$_def_map['specification']['id_device'] ]);
                    }
                endforeach;
            ?>
        </div>
        <button type="button" id="add-input" class="btn btn-outline-primary ">Добавить фильтр</button>

        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary col mt-5']) ?>
    </div>
</div>
<?php \yii\bootstrap4\ActiveForm::end(); ?>
<!--       Выбор столбцов      -->
<?php if ($error): ?>
    <div class="alert alert-danger" role="alert">
        <?= $error ?>
    </div>
<?php endif; ?>

