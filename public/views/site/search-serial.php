<?php


    /**
     * @var  $model  \app\models\HwTehnic;
     *
     * @var  $data \app\models\HwTehnic
     */

    use yii\helpers\Html;


    if ($model->serial){
        $text2 = '<table class="table table-border table-sm hw-bg-light-yellow">';
        $id_tehnics = implode(';',array_values(\yii\helpers\ArrayHelper::map($data, 'id', 'id')));
        $serial = array_values(\yii\helpers\ArrayHelper::map($data, 'serial', 'serial'));

        $array_serial = array_filter(array_map('trim', explode(';', $model->serial)));

        $diff_array = array_diff($array_serial, $serial);

        $text2 .='<tr>';
        $text2 .='<th class="col-1"> В поиске </th><th class="col-1"> Нашлось </th><th> Не нашлись </th>';
        $text2 .='</tr>';
        $text2 .='<tr>';
        $text2 .='<td>'.count($array_serial).'</td><td class="col-1">'.count($serial).'</td><td>'.implode('; ',$diff_array).'</td>';
        $text2 .='</tr>';
        $text2 .='</table>';
    }
?>

<div class="card col-12 ">
    <div class="card-body login-card-body justify-content-md-center">
        <?php $form = \yii\bootstrap4\ActiveForm::begin(['id' => 'login-form']); ?>
            <?= $form->field($model, 'serial')
                ->label(false)
                ->textInput(['placeholder' => 'Серинйный номер', 'class'=>' form-control']) ?>
            <div class="col-4">
                <?= Html::submitButton('Искать', ['class' => 'btn btn-primary btn-block']) ?>
            </div>
        <?php \yii\bootstrap4\ActiveForm::end(); ?>
    </div>

    <?= $text2 ?>

    <?php if($data): ?>
        <?= \app\components\template\TehnicView::widget(
            [
                'model' => $data,
                'title' => 'Результаты поиск',
                're' => true,
                'serial_array' => $id_tehnics,
            ])
        ?>
    <?php endif; ?>
</div>
