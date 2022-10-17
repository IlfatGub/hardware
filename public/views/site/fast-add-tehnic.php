




<?php

    /**
     * @var  $model  \app\models\HwTehnic;
     */

use app\components\template\Select2;
    use app\models\HwTehnicStatus;
    use kartik\widgets\DatePicker;
    use yii\bootstrap\Html;
    use yii\helpers\ArrayHelper;

    $users = new \app\models\HwUsers();
    $wh = new \app\models\HwWh();
    $models = new \app\models\HwModel();

    $_org = ArrayHelper::map(\app\models\HwPodr::getOrg(), 'id', 'name');
    $_user = ArrayHelper::map($users->getUsers(), 'id', 'username');
    $_model = ArrayHelper::map($models->getModel(), 'id', 'name');
    $_wh = ArrayHelper::map($wh->getWh(), 'id', 'name');
    $_wh = ArrayHelper::map($wh->getWh(), 'id', 'name');

    $model->date_admission = date('Y-m-d');
    $model->date_warranty = date( 'Y-m-d', strtotime('+1 year'));
?>

<?php yii\widgets\Pjax::begin(['id' => 'fastadd', 'enablePushState' => false]) ?>
<div class="col">
    <?php $form = \yii\bootstrap4\ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data', 'data-pjax' => true]]) ?>
    <div class="col-12 card card-outline card-secondary">

        <div class="row">
            <div class="col">
                <?= Select2::widget(['model' => $model, 'form' => $form, 'attribute' => '_org', 'data' => $_org]) ?>
            </div>
            <div class="col">
                <?= Select2::widget(['model' => $model, 'form' => $form, 'attribute' => '_model', 'data' => $_model]) ?>
            </div>
        </div>

        <div class="row">
            <div class="col">
                <?= Select2::widget(['model' => $model, 'form' => $form, 'attribute' => '_wh', 'data' => $_wh]) ?>
            </div>
            <div class="col">
                <?= Select2::widget(['model' => $model, 'form' => $form, 'attribute' => '_status', 'data' => HwTehnicStatus::getStatus()]) ?>
            </div>
        </div>

        <div class="row">
            <div class="col">
                <label for="">Дата приемки</label>
                <input type="date" id="hwtehnic-date_admission" name="HwTehnic[date_admission]" class="form-control" value="<?= $model->date_admission ?>">
            </div>
            <div class="col">
                <label for="">Гарантия</label>
                <input type="date" id="hwtehnic-date_warranty" name="HwTehnic[date_warranty]" class="form-control" value="<?= $model->date_warranty ?>">
            </div>
        </div>


        <?= $form->field($model, 'act_num')->textInput(); ?>
        <?= Select2::widget(['model' => $model, 'form' => $form, 'attribute' => 'count', 'data' => [1,2,3,4,5,6,7,8,10,11,12,13,14,15,16,17,18,19]]) ?>
        <?= $form->field($model, 'comment')->textarea(); ?>
        <?= Html::submitButton('Отправить', ['class' => 'btn btn-primary btn-block btn-sm mb-2']) ?>
    </div>
    <?php \yii\bootstrap4\ActiveForm::end(); ?>
</div>
<?php yii\widgets\Pjax::end() ?>


<script type="text/javascript">
    $(document).ready(function(){
        $('#modalContent').removeAttr('tabindex');
    });
</script>
