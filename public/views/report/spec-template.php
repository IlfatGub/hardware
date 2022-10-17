<?php
    /**
     * Created by PhpStorm.
     * User: 01gig
     * Date: 01.12.2021
     * Time: 15:34
     */

    use yii\helpers\Html;




    $settings = new \app\models\HwSettings();


?>




<?php $form = \yii\bootstrap4\ActiveForm::begin() ?>
<div class="row">
    <div class="col-12">
        <?= $form->field($model, 'tb1')->textInput()->label('Название шаблона'); ?>
        <?= \app\components\template\Select2::widget([
            'model' => $model,
            'form' => $form,
            'attribute' => 'tb3',
            'multiple' => true,
            'label' => 'Выбрать столбцы',
            'data' => $settings->getTehnicTableFieldReport()
        ]) ?>
        <?= Html::submitButton('Добавить', ['class' => 'btn btn-primary col']) ?>
    </div>
</div>
<?php \yii\bootstrap4\ActiveForm::end(); ?>
