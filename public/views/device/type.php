<?php
    /**
     * Created by PhpStorm.
     * User: 01gig
     * Date: 21.05.2020
     * Time: 11:46
     */



    /**
     * @var  $model  \app\models\HwDeviceType;
     * @var  $data  \app\models\HwWh;
     *
     */

    use app\components\access\Redactor;
    use kartik\typeahead\Typeahead;
    use yii\helpers\ArrayHelper;
    use yii\helpers\Html;
    use yii\helpers\Url;

    $_update = isset($_GET['update']) ? $_GET['update'] : null;

    $_model = ArrayHelper::getColumn($model->getDeviceType(), 'name');

?>



<div class="col-12 row pt-3">

    <!-- Формая добавляения -->
    <div class="col-3">
        <?php $form = \yii\bootstrap4\ActiveForm::begin(['id' => 'users-form']) ?>
        <div class="col-12 card card-outline card-secondary">
            <div class="card-header mb-3">
                <h3 class="card-title">
                    <a href="<?= \yii\helpers\Url::toRoute(['type']) ?>" type="button" class="btn btn-default"><i class="fas fa-sync"></i></a>

                    Добавить склад</h3>

                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-times"></i>
                    </button>
                </div>
                <!-- /.card-tools -->
            </div>

            <?php

                // Usage with ActiveForm and model (with search term highlighting)
                echo $form->field($model, 'name')->widget(Typeahead::classname(), [
                    'options' => ['placeholder' => '', 'class' => 'form-control-sm', ],
                    'pluginOptions' => ['highlight'=>true],
                    'dataset' => [
                        [
                            'local' => $_model,
                            'limit' => 10
                        ]
                    ]
                ]);

                echo $form->field($model, 'icon')->textInput();

                echo $form->field($model, 'component')->dropDownList(['1' => 'Есть возможность', '0' => 'Нет возможности'], ['prompt' => 'Выбрать...']);
            ?>
            
            <?php Redactor::begin() ?>
                <?= Html::submitButton('Добавить', ['class' => 'btn btn-primary btn-block btn-sm mb-2']) ?>
            <?php Redactor::end() ?>

        </div>
        <?php \yii\bootstrap4\ActiveForm::end(); ?>
    </div>
    <!-- /.Форма добавляения -->


    <!-- Список пользователей -->
    <div class="col-9 ">
        <div class="card">
            <div class="card-body p-0">
                <table class="table table-sm table-hover table_sort" id="table-sort" style="font-size: 10pt">
                    <thead>
                    <tr>
                        <th>Наименование</th>
                        <th>Группа</th>
                        <th>Компоненты</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach($data as $item): ?>
                        <tr class="<?= $item->id == $_update ? 'alert-warning'  : ''?>">
                            <td>
                                <?php $color_sp = $item->specification ? 'hw-cl-blue' : 'hw-cl-silver'; ?>
                                    
                                <?php Redactor::begin() ?>
                                    <?= Html::button("<i class=\"fa fa-cog mr-1 ".$color_sp."\"></i>", ['value' => Url::to(['specification', 'id_device' => $item->id]), 'class' => 'p-0 m-0 btn btn-sm btn-url modalButton-lg', 'title' => 'История заявки']); ?>
                                <?php Redactor::end() ?>

                                <?= $item->name ?>
                            </td>
                            <td>
                                <?php if(Yii::$app->user->can('Admin')): ?>
                                    <?= Html::dropDownList('domain', [$item->category] , \app\models\HwDeviceType::getGroup(),
                                        ['class' => 'form-control form-control-sm hd-checkbox', 'prompt' => '-- группа --', 'data-key' => 'category', 'id' => $item->id, 'data-parent' => 'device']); ?>
                                <?php else: ?>
                                    <?=\app\models\HwDeviceType::getGroup()[$item->category]?>
                                <?php endif; ?>
                             </td>
                            <td><?= $item->component <> 0 ? '<span class="badge badge-success">Есть возможность</span>' : 'Нет возможности' ?></td>
                            <td> <i class="<?= $item->icon ?>"></i> </td>
                            <td style="width: 60px">
                                <div class="tools">
                                    <?php Redactor::begin() ?>
                                        <a href="<?= \yii\helpers\Url::toRoute(['type', 'update' => $item->id]) ?>" class="fas fa-edit"></a>
                                        <a href="<?= \yii\helpers\Url::toRoute(['type', 'delete' => $item->id]) ?>" class="fas fa-trash" style="color: red;"></a>
                                    <?php Redactor::end() ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!-- /.Список пользователей -->

</div>


<script>




</script>