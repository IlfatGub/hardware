<?php
    /**
     * Created by PhpStorm.
     * User: 01gig
     * Date: 21.10.2021
     * Time: 14:20
     */

    /**
     * @var  $device_specif  \app\models\HwSpeceficDevice;
     * @var  $id_model
     * @var  $id_tehnic
     * @var  $specification \app\models\HwSpeceficModel;
     * @var  $all_specification \app\models\HwSpeceficModel;
     */

    use app\models\HwSpeceficModel;
    use kartik\typeahead\Typeahead;
    use kartik\widgets\Select2;
    use yii\bootstrap4\Modal;
    use yii\helpers\ArrayHelper;
    use yii\helpers\Html;
    use yii\helpers\Url;

    $sp_value = new \app\models\HwSpeceficValue();

    $_id_tehnic = isset($_GET['id_tehnic']) ? $_GET['id_tehnic'] : null;

    $id_tehnic_exists = $id_tehnic ? HwSpeceficModel::findOne(['id_tehnic' => $id_tehnic]) : null;

    $serial_array = isset($_GET['serial_array']) ? $_GET['serial_array'] : null;

    $color_label = $id_tehnic_exists ? 'alert-secondary' : 'alert-dark';
    $spec_label = $id_tehnic_exists ? 'tehnic' : 'model';

    $model_name = \app\models\HwModel::findOne($id_model)->name;
?>

<?php \yii\widgets\Pjax::begin(['id' => 'model_specification', 'enablePushState' => false]) ?>

<?php
    $_sp = ArrayHelper::map($specification, 'specification', 'id_value');
    $_all_sp = ArrayHelper::map($all_specification, 'specification', 'value');
?>

<div class="col-sm-12">
    <div class="card bg-light">
        <div class="card-body">

            <div class="fs-20 alert <?=$color_label?>">
                <strong><?= $model_name ?> <?= $_id_tehnic ? '(№ '.$_id_tehnic.')' : '' ?></strong>
                <small class="float-right">Характеристики/<?=$spec_label?></small>
            </div>

            <hr>

            <?php foreach ($device_specif as $item): ?>
                <div class="row col-12 mb-3">
                    <div class="col-3">
                        <label class="control-label" data-content=""><?= $item->name ?></label>
                    </div>
                    <div class="col-9">
                        <?php
                            $val = HwSpeceficModel::find()->where(['specification' => $item->name, 'id_model' => $id_model, 'id_tehnic' => $id_tehnic_exists ? $id_tehnic : null])->select(['id_value'])->column();
                            
                            echo Select2::widget([
                                'name' => $item->name,
                                'value' => $val,
                                'data' => ArrayHelper::map($sp_value->getSearchField('specification', $item->name, 'visible', 1)->all(), 'id', 'value'),
                                'options' => [
                                    'multiple' => true,
                                    'placeholder' => 'Select a type ...',
                                    'data-id' => $id_model,
                                    'data-parent' => $id_tehnic,
                                    'data-content' => $serial_array,
                                    'class' => 'specification'
                                ],
                            ]);
                        ?>
                    </div>
                </div>
                <hr>
            <?php endforeach; ?>

            <?php if(isset($serial_array)): ?>
                <div class="form-check mb-3 alert alert-primary">
                    <div class="mx-2">
                        <input type="checkbox" class="form-check-input" id="specifiaction_cbox">
                        <label class="form-check-label" for="exampleCheck1">
                            Изменить конфигурацию для: <?=  implode('; ', explode(';',$serial_array)) ?> </label>
                        <hr>
                        <p class="mb-0">Конфигурация примениться только к устройствам одной модели. В данном случае
                            <strong><?= $model_name ?></strong> </p>
                    </div>
                </div>
            <?php endif; ?>

            <button class="btn btn-sm btn-outline-primary modalButton-lg"
                    value="<?= Url::toRoute(['/device/specification', 'id_device' => $model->type, 'id_model' => $id_model]) ?>">
                Перейти к устройству: <?= \app\models\HwDeviceType::findOne($model->type)->name ?>
            </button>

        </div>
    </div>
</div>

<?php
    $script = <<< JS
        $('.specification').change(function(e){
            var name = $(this).attr('name');
            var text = $(this).val();
            var id = $(this).attr('data-id');
            var id_tehnic = $(this).attr('data-parent');
            var serial_array = $(this).attr('data-content');
            
            var url;

            if (id_tehnic) {
                if ($("#specifiaction_cbox").is(':checked')){
                    url ="specific=" + name + "&text=" + text + "&id=" + id + "&id_tehnic=" + id_tehnic + "&serial_array=" + serial_array;
                }else{
                    url ="specific=" + name + "&text=" + text + "&id=" + id + "&id_tehnic=" + id_tehnic;
                }
            }else{
               url ="specific=" + name + "&text=" + text + "&id=" + id;
            }
           if (text){
                $.ajax({
                    type: "GET",
                    url: "/model/add-specific",
                    data: url,
                    success: function (data, e) {
                        $('#side-content').html(data);
                    }
                });
           } 
        });
JS;
    $this->registerJs($script);
?>

<?php \yii\widgets\Pjax::end(); ?>

