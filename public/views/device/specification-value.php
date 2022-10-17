<?php
    /**
     * @var  $model  \app\models\HwSpeceficValue;
     * @var  $specification;
     * @var  $specifications \app\models\HwSpeceficValue;
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
                <strong><?= $specification ?>  </strong>
                <small class="float-right">Значения</small>
            </div>

            <hr>

            <?php if ($specifications): ?>
                <?php $i = 0; ?>
                <?php foreach ($specifications as $item): ?>
                    <?= ++$i . '. ' . $item->value; ?>
                    <a href="<?= Url::toRoute(['specification-value', 'specification' => $specification, 'delete' => $item->value]) ?>"
                       class="float-right fs-14 hw-color-red project-upd-click">Удалить</a>
                    <hr>
                <?php endforeach; ?>
                <br>
            <?php endif; ?>

            <?php $form = \yii\bootstrap4\ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data', 'data-pjax' => true]]) ?>

            <div class="row">
                <div class="col-8">
                    <?php
                        echo $form->field($model, 'value')->textInput()->label(false);
                    ?>
                </div>
                <div class="col-4">
                    <?= Html::submitButton('Добавить', ['class' => 'btn btn-primary ml-2 mb-3 col-12']) ?>
                </div>

            </div>

            <?php \yii\bootstrap4\ActiveForm::end(); ?>

            <hr>

            <?php if (isset($_GET['id_device'])): ?>
                <button class="btn btn-outline-primary modalButton-lg mb-3"
                        value="<?= Url::toRoute(['/device/specification', 'id_device' => $_GET['id_device']]) ?>">
                    Вернуться к типу устройсва
                </button>
            <?php endif; ?>

        </div>
    </div>
</div>

<?php \yii\widgets\Pjax::end(); ?>
