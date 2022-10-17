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


    $users = new \app\models\HwUsers();
    $_user = ArrayHelper::map($users->getUsers(),'id','username');

    ?>



<?php $form = \yii\bootstrap4\ActiveForm::begin(['id' => 'users-form']) ?>
    <?php
    echo \kartik\select2\Select2::widget([
        'name' => 'id_user',
        'data' => $_user,
        'options' => ['placeholder' => 'Выбрать сотрудника...'],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]);
    ?>
    <?= Html::submitButton('Перезакрепить', ['class' => 'btn btn-primary btn-block btn-sm mb-2']) ?>

<?php \yii\bootstrap4\ActiveForm::end(); ?>

