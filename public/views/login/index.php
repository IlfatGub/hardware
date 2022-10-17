<?php
    /**
     * @var  $model  \app\models\Login;
     * @var  $data  \app\models\Login;
     *
     */

    use app\components\template\Select2;
    use app\models\HwDepart;
    use kartik\typeahead\Typeahead;
    use yii\helpers\ArrayHelper;
    use yii\helpers\Html;
    use yii\helpers\Url;
    use app\models\Login;


    $login = ArrayHelper::map(Login::getLoginList(), 'id', 'username');

?>



<div class="col-12 row pt-3">

    <!-- Формая добавляения -->
    <div class="col-3">
        <?php $form = \yii\bootstrap4\ActiveForm::begin(['id' => 'depart-form']) ?>
        <div class="col-12 card card-outline card-secondary">
            <div class="card-header mb-3">
                <h3 class="card-title">
                    <a href="<?= \yii\helpers\Url::toRoute(['type']) ?>" type="button" class="btn btn-default"><i class="fas fa-sync"></i></a>

                    Добавляем пользователя</h3>

                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-times"></i>
                    </button>
                </div>
                <!-- /.card-tools -->



            </div>


            <?php echo $form->field($model, 'login')->textInput(); ?>

            <?= Html::submitButton('Добавить', ['class' => 'btn btn-primary btn-block btn-sm mb-2']) ?>
        </div>
        <?php \yii\bootstrap4\ActiveForm::end(); ?>
    </div>



    <div class="col-9 ">
        <div class="card">
            <div class="card-body p-0">
                <table class="table table-sm table-hover table_sort" id="hw-depart"  style="font-size: 10pt">
                    <thead>
                    <tr>
                        <th class="col-1"></th>
                        <th class="col-3">Отдел</th>
                        <th class="col-1">ФИО</th>
                        <th class="col-3">Отдел</th>
                        <th class="col-3">Домен</th>
                        <th class="col-2"> - </th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach($data as $item): ?>
                        <?php $checked_visible = $item->status == 10 ? 'checked' : '' ?>
                        <?php $active = $item->status == 10 ? '' : 'alert-secondary' ?>

                        <tr class="<?=$active?>">
                            <td><?= $item->id ?></td>

                            <td>
                                <input class="form-control form-control-navbar form-control-sm border_no height-25 hd-checkbox" data-key="username" data-parent="login"
                                       id="<?= $item->id ?>"  value="<?= $item->username ?>">
                            </td>

                            <td><?= $item->login ?></td>
                            <td>
                                <?= Html::dropDownList('name', [$item->hw_depart] , ArrayHelper::map(HwDepart::find()->where(['type' => HwDepart::TYPE_PARENT_DEPART])->all(),'id', 'name'),
                                    ['class' => 'form-control form-control-sm hd-checkbox', 'prompt' => '-- отдел --', 'data-key' => 'hw_depart', 'id' => $item->id, 'data-parent' => 'login']) ?>
                            </td>
                            <td>
                                <?= Html::dropDownList('domain', [$item->domain] , Login::getDomianList(),
                                    ['class' => 'form-control form-control-sm hd-checkbox', 'prompt' => '-- отдел --', 'data-key' => 'domain', 'id' => $item->id, 'data-parent' => 'login']) ?>
                            </td>
                            <td><input type="checkbox" class="hd-checkbox"  <?= $checked_visible = $item->status == 10 ? 'checked' : ''?> data-key="status" id="<?= $item->id ?>"  data-parent="login" ></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>


<script>




</script>