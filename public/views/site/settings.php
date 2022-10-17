<?php
    /**
     * Created by PhpStorm.
     * User: 01gig
     * Date: 23.06.2020
     * Time: 11:07
     */


    use app\components\template\Select2;
    use yii\helpers\ArrayHelper;
    use yii\helpers\Html;


    $wh = new \app\models\HwWh();
    $models = new \app\models\HwModel();

    $_org = ArrayHelper::map(\app\models\HwPodr::getOrg(), 'id', 'name');
    $_model = ArrayHelper::map($models->getModel(), 'id', 'name');
    $_wh = ArrayHelper::map($wh->getWh(), 'id', 'name');
    $_type = ArrayHelper::map(\app\models\HwDeviceType::getDeviceType(), 'id', 'name');


    $users = new \app\models\HwUsers();
    $_user = ArrayHelper::map($users->getUsers(), 'id', 'username');

?>


<div class="col-5 ml-4 pt-4">
    <div class="card">
        <div class="card-body ">
            <?php $form = \yii\bootstrap4\ActiveForm::begin(['id' => 'users-form']) ?>

            <?= \app\components\template\Select2::widget(['model' => $model, 'form' => $form, 'attribute' => 'sett1', 'data' => $_type]) ?>
            <?= \app\components\template\Select2::widget(['model' => $model, 'form' => $form, 'attribute' => 'sett2', 'data' => $_org]) ?>
            <?= \app\components\template\Select2::widget(['model' => $model, 'form' => $form, 'attribute' => 'sett3', 'data' => $_wh]) ?>
            <?= \app\components\template\Select2::widget(['model' => $model, 'form' => $form, 'attribute' => 'sett4', 'data' => $_model]) ?>
            <?= \app\components\template\Select2::widget(['model' => $model, 'form' => $form, 'attribute' => 'sett5', 'data' => ['1' => 'Полный', '2' => 'Минимальный']]) ?>


            <?= Html::submitButton('Отправить', ['class' => 'btn btn-primary btn-block btn-sm mb-2']) ?>

            <?php \yii\bootstrap4\ActiveForm::end(); ?>
        </div>
    </div>
</div>

