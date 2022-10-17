<?php
///**
// * Created by PhpStorm.
// * User: 01gig
// * Date: 21.05.2020
// * Time: 11:46
// */
//
//
//
///**
// * @var  $model  \app\models\HwWh;
// * @var  $data  \app\models\HwWh;
// */
//
//use app\models\HwPodr;
//use app\models\HwPost;
//use kartik\select2\Select2;
//use kartik\typeahead\Typeahead;
//use yii\helpers\ArrayHelper;
//use yii\helpers\Html;
//
//$_update = isset($_GET['update']) ? $_GET['update'] : null;
//
//$_model = ArrayHelper::getColumn($model->getDeviceType(), 'name');
//
//?>
<!---->
<!---->
<!---->
<!--<div class="col-12 row pt-3">-->
<!---->
<!--    <!-- Формая добавляения -->-->
<!--    <div class="col-3">-->
<!--        --><?php //$form = \yii\bootstrap4\ActiveForm::begin(['id' => 'users-form']) ?>
<!--        <div class="col-12 card card-outline card-secondary">-->
<!--            <div class="card-header mb-3">-->
<!--                <h3 class="card-title">-->
<!--                    <a href="--><?//= \yii\helpers\Url::toRoute(['device-type']) ?><!--" type="button" class="btn btn-default"><i class="fas fa-sync"></i></a>-->
<!---->
<!--                    Добавить склад</h3>-->
<!---->
<!--                <div class="card-tools">-->
<!--                    <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-times"></i>-->
<!--                    </button>-->
<!--                </div>-->
<!--                <!-- /.card-tools -->-->
<!--            </div>-->
<!---->
<!--            --><?php
//            // Usage with ActiveForm and model (with search term highlighting)
//            echo $form->field($model, 'name')->widget(Typeahead::classname(), [
//                'options' => ['placeholder' => '', 'class' => 'form-control-sm', ],
//                'pluginOptions' => ['highlight'=>true],
//                'dataset' => [
//                    [
//                        'local' => $_model,
//                        'limit' => 10
//                    ]
//                ]
//            ]);
//
//            echo $form->field($model, 'icon')->textInput();
//            echo $form->field($model, 'component')->dropDownList(['1' => 'Есть возможность', '0' => 'Нет возможности'], ['prompt' => 'Выбрать...']);
//            ?>
<!--            --><?//= Html::submitButton('Sign In', ['class' => 'btn btn-primary btn-block btn-sm mb-2']) ?>
<!--        </div>-->
<!--        --><?php //\yii\bootstrap4\ActiveForm::end(); ?>
<!--    </div>-->
<!---->
<!--    <!-- /.Формая добавляения -->-->
<!---->
<!---->
<!--    <!-- Список пользователей -->-->
<!---->
<!--    <div class="col-9 ">-->
<!--        <div class="card">-->
<!--            <div class="card-body p-0">-->
<!--                <table class="table table-sm table-hover table_sort" style="font-size: 10pt">-->
<!--                    <thead>-->
<!--                    <tr>-->
<!--                        <th>Наименование</th>-->
<!--                        <th>Компоненты</th>-->
<!--                    </tr>-->
<!--                    </thead>-->
<!--                    <tbody>-->
<!--                    --><?php //foreach($data as $item): ?>
<!--                        <tr class="--><?//= $item->id == $_update ? 'alert-warning'  : ''?><!--">-->
<!--                            <td>--><?//= $item->name ?><!--</td>-->
<!--                            <td>--><?//= $item->component <> 0 ? '<span class="badge badge-success">Есть возможность</span>' : 'Нет возможности' ?><!--</td>-->
<!--                            <td> <i class="--><?//= $item->icon ?><!--"></i> </td>-->
<!--                            <td style="width: 60px">-->
<!--                                <div class="tools">-->
<!--                                    <a href="--><?//= \yii\helpers\Url::toRoute(['device-type', 'update' => $item->id]) ?><!--" class="fas fa-edit"></a>-->
<!--                                    <a href="--><?//= \yii\helpers\Url::toRoute(['device-type', 'delete' => $item->id]) ?><!--" class="fas fa-trash" style="color: red;"></a>-->
<!--                                </div>-->
<!--                            </td>-->
<!--                        </tr>-->
<!--                    --><?php //endforeach; ?>
<!--                    </tbody>-->
<!--                </table>-->
<!--            </div>-->
<!--        </div>-->
<!--    </div>-->
<!--    <!-- /.Список пользователей -->-->
<!---->
<!--</div>-->
<!---->
<!---->
<!--<script>-->
<!---->
<!---->
<!---->
<!---->
<!--</script>-->