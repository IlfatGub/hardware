<?php
/**
 * Created by PhpStorm.
 * User: 01gig
 * Date: 21.05.2020
 * Time: 11:46
 */



/**
 * @var  $model  \app\models\HwModel;
 * @var  $data  \app\models\HwModel;
 */

use app\models\HwPodr;
use app\models\HwPost;
use kartik\select2\Select2;
use kartik\typeahead\Typeahead;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

//$type = $model->getTypeList();

$device_type = ArrayHelper::map(\app\models\HwDeviceType::getDeviceType(), 'id','name');

$_update = isset($_GET['update']) ? $_GET['update'] : null;

$_model = ArrayHelper::getColumn($model->getModel(), 'name');

$model->type = isset($model->type) ? $model->type :  Yii::$app->user->identity->sett1;


$model_type = ArrayHelper::map($data, 'id', 'id', 'type');
$model_vendor = ArrayHelper::map($data,  'id', 'vendor');
$model_name = ArrayHelper::map($data,  'id', 'name');



?>

<div class="col-12 row pt-3">

    <!-- Формая добавляения -->
    <div class="col-3">
        <?php $form = \yii\bootstrap4\ActiveForm::begin(['id' => 'users-form']) ?>
        <div class="col-12 card card-outline card-secondary">


            <div class="card-header mb-3">
                <h3 class="card-title">
                    <a href="<?= \yii\helpers\Url::toRoute(['model']) ?>" type="button" class="btn btn-default"><i class="fas fa-sync"></i></a>

                    Добавить модель</h3>

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
            ?>

            <?= $form->field($model, 'vendor')->textInput();
            ?>

            <?= $form->field($model, 'type')->widget(Select2::classname(), [
                'data' =>  $device_type,
                'options' => ['placeholder' => '', 'class' => 'form-control-sm'],
                'pluginOptions' => ['allowClear' => true,  'tags' => true,] ]);
            ?>

            <?= Html::submitButton('Sign In', ['class' => 'btn btn-primary btn-block btn-sm mb-2']) ?>
        </div>
        <?php \yii\bootstrap4\ActiveForm::end(); ?>


        <div class="col-12 card card-outline card-secondary">

            <ul class="treeCSS ml-1 my-2">
                <li> Модели
                    <?php foreach($model_type as $tp => $ids): ?>
                        <ul>
                            <li><?= $device_type[$tp] ?>
                                <ul>
                                    <?php foreach($ids as $id): ?>
                                    <li>
                                        <a href="<?= \yii\helpers\Url::toRoute(['model', 'update' => $id]) ?>"><?= $model_name[$id] ?></a>

                                        <?php endforeach; ?>
                                </ul>
                        </ul>
                    <?php endforeach; ?>
                </li>
            </ul>

        </div>
    </div>

    <!-- /.Формая добавляения -->


    <!-- Список пользователей -->

    <div class="col-9 ">
        <div class="card">
            <div class="card-body p-0">

                <table class="table table-sm table-hover table_sort" style="font-size: 10pt">
                    <thead>
                    <tr>
                        <th>Наименование</th>
                        <th>Производитель</th>
                        <th>Тип устройства</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach($data as $item): ?>
                        <tr class="<?= $item->id == $_update ? 'alert-warning'  : ''?>">
                            <td> <?= $item->name ?></td>
                            <td><?= $item->vendor ?></td>
                            <td><?= $device_type[$item->type] ?></td>
                            <td style="width: 60px">
                                <div class="tools">
                                    <a href="<?= \yii\helpers\Url::toRoute(['model', 'update' => $item->id]) ?>" class="fas fa-edit"></a>
                                    <a href="<?= \yii\helpers\Url::toRoute(['model', 'delete' => $item->id]) ?>" class="fas fa-trash" style="color: red;"></a>
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
    (function () {
        var ul = document.querySelectorAll('.treeCSS > li:not(:only-child) ul, .treeCSS ul ul');
        for (var i = 0; i < ul.length; i++) {
            var div = document.createElement('div');
            div.className = 'drop';
            div.innerHTML = '+'; // картинки лучше выравниваются, т.к. символы на одном браузере ровно выглядят, на другом — чуть съезжают
            ul[i].parentNode.insertBefore(div, ul[i].previousSibling);
            div.onclick = function () {
                this.innerHTML = (this.innerHTML == '+' ? '−' : '+');
                this.className = (this.className == 'drop' ? 'drop dropM' : 'drop');
            }
        }
    })();
</script>
