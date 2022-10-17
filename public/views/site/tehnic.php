<?php
    /**
     * Created by PhpStorm.
     * User: 01gig
     * Date: 21.05.2020
     * Time: 11:46
     */

    /**
     * @var  $model  \app\models\HwTehnic;
     * @var  $data  \app\models\HwTehnic;
     * @var  $old_data  \app\models\HwTehnic;
     */

    use kartik\widgets\DatePicker;
    use yii\helpers\ArrayHelper;
    use yii\helpers\Html;
    use yii\helpers\Url;

    $_update = isset($_GET['update']) ? $_GET['update'] : null;
    $_user_id = isset($_GET['id_user']) ? $_GET['id_user'] : null;

    $role = new \app\models\Login();

    $users = new \app\models\HwUsers();
    $wh = new \app\models\HwWh();
    $models = new \app\models\HwModel();

    $_org = ArrayHelper::map(\app\models\HwPodr::getOrg(), 'id', 'name');
    $_user = ArrayHelper::map($users->getUsers(), 'id', 'username');
    $_model = ArrayHelper::map($models->getModel(), 'id', 'name');
    $_wh = ArrayHelper::map($wh->getWh(), 'id', 'name');
    $_wh = ArrayHelper::map($wh->getWh(), 'id', 'name');

    $id_user = isset($_GET['id_user']) ? $_GET['id_user'] : null;

    $model->id_org = isset($model->id_org) ? $model->id_org : Yii::$app->user->identity->sett2;
    $model->id_wh = isset($model->id_wh) ? $model->id_wh : Yii::$app->user->identity->sett3;

    $_user_id ? $model->id_user = $_user_id : null;
    $_user_id ? $model->id_wh = 11 : null;

    $disable = isset($_update) ? true : false;

    if (!$model->serial)
        $model->check_serial = true;
?>

<div class="col-12 row pt-3">
    <!-- Формая добавляения -->
    <div class="col-3">

        <div class="col-12 card card-outline card-secondary">
            <div class="card-header mb-3">
                <h3 class="card-title">
                    <a href="<?= \yii\helpers\Url::toRoute(['tehnic']) ?>" type="button" title="Очистить" class="btn btn- btn-default"><i
                                class="fas fa-sync"></i></a>
                    Добавить устройство</h3>

                <?php \app\components\access\Redactor::begin() ?>
                    <div class="card-tools">
                        <button class="btn btn-outline-dark modalButton-lg" title="Добавление партиями" value="<?= Url::toRoute(['/site/fast-add-tehnic']) ?>">
                            <i class="fas fa-shipping-fast"></i>
                        </button>
                    </div>
                <?php \app\components\access\Redactor::end() ?>
                <!-- /.card-tools -->
            </div>

            <?php $form = \yii\bootstrap4\ActiveForm::begin(['id' => 'users-form']) ?>

            <?= \app\components\template\Select2::widget(['model' => $model, 'form' => $form, 'attribute' => 'id_org', 'data' => $_org, 'readonly' => $role->readonly('id_org')]) ?>
            <?= \app\components\template\Select2::widget(['model' => $model, 'form' => $form, 'attribute' => 'id_user', 'data' => $_user, 'readonly' => $role->readonly('id_org')]) ?>
            <?php if(Yii::$app->user->id == 1): ?>
                <?= \app\components\template\Select2::widget(['model' => $model, 'form' => $form, 'attribute' => 'id_model', 'data' => $_model]) ?>
            <?php else: ?>
                <?= \app\components\template\Select2::widget(['model' => $model, 'form' => $form, 'attribute' => 'id_model', 'data' => $_model, 'readonly' => $role->readonly('id_model')]) ?>
            <?php endif; ?>
            <?= \app\components\template\Select2::widget(['model' => $model, 'form' => $form, 'attribute' => 'id_wh', 'data' => $_wh, 'readonly' => $role->readonly('id_org')]) ?>

            <?= \app\components\template\Select2::widget(['model' => $model, 'form' => $form, 'attribute' => 'status', 'data' => \app\models\HwTehnicStatus::getStatus(), 'readonly' => $role->readonly('status')]) ?>

            <?= $form->field($model, 'location')->textInput(['readonly'=> $role->readonly('location')]); ?>
            <?= $form->field($model, 'serial')->textInput(['readonly'=> $role->readonly('serial')]); ?>
            <?= $form->field($model, 'check_serial')->checkbox(['readonly'=> $role->readonly('check_serial')]) ?>

            <?= $form->field($model, 'act_num')->textInput(['readonly'=> $role->readonly('act_num')]); ?>

            <?php
                // Usage with model and Active Form (with no default initial value)
                echo $form->field($model, 'date_admission')->widget(DatePicker::classname(), [
                    'options' => ['placeholder' => 'Дата приемки', 'autocomplete' => 'off'],
                    'disabled'=> $role->readonly('date_warranty'),
                    'pluginOptions' => [
                        'autoclose' => true,
                        'format' => 'yyyy-m-d',
                    ]
                ]);
            ?>

            <?php
                // Usage with model and Active Form (with no default initial value)
                echo $form->field($model, 'date_warranty')->widget(DatePicker::classname(), [
                    'options' => ['placeholder' => 'Гарания', 'autocomplete' => 'off'],
                    'disabled'=> $role->readonly('date_warranty'),
                    'pluginOptions' => [
                        'autoclose' => true,
                        'format' => 'yyyy-m-d',
                    ]
                ]);
            ?>
            <?= $form->field($model, 'old_passport')->textInput(['readonly'=> $role->readonly('old_passport')]); ?>
            <?= $form->field($model, 'comment')->textarea(['readonly'=> $role->readonly('comment')]); ?>

            <?= Html::submitButton('Отправить', ['class' => 'btn btn-primary btn-block btn-sm mb-2']) ?>

            <?php \yii\bootstrap4\ActiveForm::end(); ?>


        </div>
    </div>

    <!-- /.Формая добавляения -->

    <!-- Список техники -->

    <div class="col-9 ">

        <?= \app\components\template\TehnicView::widget(
            [
                'model' => $data,
                'title' => 'Техника закрепленная за сотрудником',
                're' => true,
            ])
        ?>

        <?php if ($old_data and !Yii::$app->user->can('Service')): ?>
            <div class="mt-5">
                <?php echo \app\components\template\TehnicView::widget(
                    [
                        'model' => $old_data,
                        'title' => 'Техника ранее закрепленная за сотрудником ',
                        'color' => 'portal-bg-light-red',
                        'type' => 1
                    ])
                ?>
            </div>

        <?php endif; ?>
    </div>
    <!-- /.Список техники -->

</div>

