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
    use yii\helpers\Url;


    $id = isset($_GET['id']) ? $_GET['id'] : null;
    $activ_ram = null;
    
    if($id)
        $activ_ram = \app\models\HwTehnicRam::find()->where(['id_tehnic' => $_GET['id']])->all();
?>

<?php \yii\widgets\Pjax::begin(['id' => 'hw-ram', 'enablePushState' => false]) ?>

<?php
    $users = new \app\models\HwTehnicRam();
    $_user = ArrayHelper::map($users->getTehnicRamActual(),'name','name');
?>

<?php if($activ_ram): ?>
    <blockquote class="blockquote text-right mt-1 pt-1" style="border-left:0px">
        <?php foreach ($activ_ram as $item): ?>
            <footer class="blockquote-footer mt-1 pt-1">
                <?= $item->name ?> <code><?= date('d-m-Y', $item->date_add) ?></code>
                <?= Html::a('<i class="fas fa-trash-alt"></i>', Url::toRoute(['add-component' , 'id' => $item->id_tehnic, 'id_ram' => $item->id])) ?>
                <br>
            </footer>
        <?php endforeach; ?>
    </blockquote>
<?php endif; ?>
    
<?php $form = \yii\bootstrap4\ActiveForm::begin(['id' => 'users-form', 'options' => ['enctype' => 'multipart/form-data', 'data-pjax' => true]]) ?>

<div class="row">
    <div class="col-9">
        <?php
            echo \kartik\select2\Select2::widget([
                'name' => 'component',
                'data' => $_user,
                'options' => ['placeholder' => 'Выбрать компонент'],
                'pluginOptions' => [
                    'allowClear' => true,
                    'tags' => true,
                ],
            ]);
        ?>
    </div>
    <div class="col-3">
        <?= Html::submitButton('Добавить', ['class' => 'btn btn-primary btn-block btn-sm mb-2']) ?>
    </div>
</div>

<?php \yii\bootstrap4\ActiveForm::end(); ?>

<?php \yii\widgets\Pjax::end() ?>

