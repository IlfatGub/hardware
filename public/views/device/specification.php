<?php
    /**
     * @var  $model  \app\models\HwSpeceficDevice;
     * @var  $specific_data  \app\models\HwSpeceficDevice;
     * @var  $id_device ;
     * @var  $id_model ;
     */


    use kartik\widgets\Typeahead;
    use yii\helpers\ArrayHelper;
    use yii\helpers\Html;
    use yii\helpers\Url; ?>


<?php \yii\widgets\Pjax::begin(['id' => 'device_specification', 'enablePushState' => false]) ?>



<div class="col-sm-12">
    <div class="card bg-light">
        <div class="card-body">


            <div class="fs-20">
                <strong><?= \app\models\HwDeviceType::findOne($id_device)->name ?>  </strong>
                <small class="float-right">Характеристики</small>
            </div>

            <hr>

            <?php if ($specific_data): ?>
                <?php $i = 0; ?>
                <?php foreach ($specific_data as $item): ?>

                    <button class="btn btn-sm btn-outline-primary modalButton-lg"
                            value="<?= Url::toRoute(['/device/specification-value', 'specification' => $item->name, 'id_device' => $id_device ]) ?>">
                        <?= ++$i . '. ' . $item->name; ?>
                    </button>

                    <a href="<?= Url::toRoute(['specification', 'id_device' => $id_device, 'delete' => $item->name]) ?>"
                       class="float-right fs-14 hw-color-red project-upd-click btn btn-sm btn-outline-danger">Удалить</a>
                    <hr>
                <?php endforeach; ?>
                <br>
            <?php endif; ?>

            <?php $form = \yii\bootstrap4\ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data', 'data-pjax' => true]]) ?>

            <div class="row">
                <div class="col-8">
                    <?php
                        echo $form->field($model, 'name')->widget(Typeahead::classname(), [
                            'options' => ['placeholder' => 'Наименование', 'class' => 'form-control', 'autocomplete' => 'off'],
                            'pluginOptions' => ['highlight' => true],
                            'dataset' => [
                                [
                                    'local' => ArrayHelper::getColumn($model->getAllListVisible(true), 'name', 'name'),
                                    'limit' => 10
                                ]
                            ]
                        ])->label(false);
                    ?>
                </div>
                <div class="col-4">
                    <?= Html::submitButton('Добавить', ['class' => 'btn btn-primary ml-2 mb-3 col-12']) ?>
                </div>
            </div>

            <?php \yii\bootstrap4\ActiveForm::end(); ?>

            <hr>

            <?php if ($id_model): ?>
                <button class="btn btn-outline-primary modalButton-lg mb-3"
                        value="<?= Url::toRoute(['/model/specification', 'id_model' => $id_model]) ?>">
                    Вернуться к модели устройсва
                </button>
            <?php endif; ?>

        </div>
    </div>
</div>

<?php \yii\widgets\Pjax::end(); ?>
