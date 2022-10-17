<?php
    /**
     * @var  $model  \app\models\HwUsers;
     * @var  $users  \app\models\HwUsers;
     */

    use app\components\access\Redactor;
    use app\models\HwPodr;
    use app\models\HwPost;
    use kartik\select2\Select2;
    use kartik\typeahead\Typeahead;
    use yii\helpers\ArrayHelper;
    use yii\helpers\Html;
    use yii\helpers\Url;
    use yii\widgets\ActiveForm;

    $podr = ArrayHelper::map(HwPodr::getPodrAll(), 'id', 'name');
    $post = ArrayHelper::map(HwPost::getPost(), 'id', 'name');

    $data = [
        'Alabama', 'Alaska', 'Arizona', 'Arkansas', 'California', 'Colorado',
        'Connecticut', 'Delaware', 'Florida', 'Georgia', 'Hawaii',
        'Idaho', 'Illinois', 'Indiana', 'Iowa', 'Kansas', 'Kentucky', 'Louisiana',
        'Maine', 'Maryland', 'Massachusetts', 'Michigan', 'Minnesota',
        'Mississippi', 'Missouri', 'Montana', 'Nebraska', 'Nevada', 'New Hampshire',
        'New Jersey', 'New Mexico', 'New York', 'North Carolina', 'North Dakota',
        'Ohio', 'Oklahoma', 'Oregon', 'Pennsylvania', 'Rhode Island',
        'South Carolina', 'South Dakota', 'Tennessee', 'Texas', 'Utah', 'Vermont',
        'Virginia', 'Washington', 'West Virginia', 'Wisconsin', 'Wyoming'
    ];

    $fio = ArrayHelper::getColumn(\app\models\HwFio::getFio(), 'name');

    $_update = isset($_GET['update']) ? $_GET['update'] : null;


    if (isset($_update)) {
        $model->id_podr = $podr[$model->id_podr];
        $model->id_org = $podr[$model->id_org];
        $model->id_depart = $podr[$model->id_depart];
        $model->id_post = $post[$model->id_post];

        $new_dep = $model->getNewInfoByUsername($model->id_org);

        if ($model->id_org != 'НХРС'){
            $new_dep_nh = $model->getNewInfoByUsername("НХРС");
        }

    }


?>


<div class="col-12 row pt-3">

    <div class="col-3">

        <!-- Формая добавляения -->
        <?php $form = \yii\bootstrap4\ActiveForm::begin(['id' => 'users-form']) ?>

        <div class="col-12 card card-outline card-secondary">

            <?php Redactor::begin() ?>


            <div class="card-header mb-3">
                <h3 class="card-title">
                    <a href="<?= \yii\helpers\Url::toRoute(['users']) ?>" type="button" class="btn btn-default"><i
                                class="fas fa-sync"></i></a>
                    Добавить пользователя</h3>
                

                <!-- /.card-tools -->
            </div>

            <?php if (isset($new_dep) && $model->id_depart != $new_dep[0]->subdivision): ?>
                <div class="info-box bg-info">
                    <div class="info-box-content">
                        <span class="info-box-number"><?= 'Отдел: ' . $new_dep[0]->subdivision ?></span>

                        <div class="progress">
                            <div class="progress-bar" style="width: 100%"></div>
                        </div>
                        <span class="progress-description">
                  <small class="">Автоматическое оповещение. Информация не актуальна</small>
                </span>
                    </div>
                    <!-- /.info-box-content -->
                </div>
            <?php endif; ?>

            <?php if (isset($new_dep_nh) && $model->id_depart != $new_dep_nh[0]->subdivision): ?>
                <div class="info-box bg-warning">
                    <div class="info-box-content">

                        Информация по НХРС
                        <div class="progress">
                            <div class="progress-bar" style="width: 100%"></div>
                        </div>

                        <span class="info-box-number"><?= 'Отдел: ' . $new_dep_nh[0]->subdivision ?></span>

                        <div class="progress">
                            <div class="progress-bar" style="width: 100%"></div>
                        </div>
                        <span class="progress-description">
                  <small class="">Автоматическое оповещение. Информация не актуальна</small>
                </span>
                    </div>
                    <!-- /.info-box-content -->
                </div>
            <?php endif; ?>



            <div id="info-text"></div>

            <?= $form->field($model, 'id_org')->widget(Select2::classname(), [
                'data' => ArrayHelper::map(HwPodr::getOrg(), 'name', 'name'),
                'options' => ['placeholder' => '', 'class' => 'form-control-sm'],
                'pluginOptions' => ['allowClear' => true, 'tags' => true,],
                'pluginEvents' => [
                    "select2:select" => "function() { actualInfoUser(); }",
                ]
            ]);
            ?>

            <?php
                // Usage with ActiveForm and model (with search term highlighting)
                echo $form->field($model, 'username')->widget(Typeahead::classname(), [
                    'options' => ['placeholder' => '', 'class' => 'form-control-sm', 'autocomplete' => 'off'],
                    'pluginOptions' => ['highlight' => true, 'minLength' => 0],
                    'dataset' => [
                        [
                            'local' => $fio,
                            'limit' => 10
                        ]
                    ]
                ]);
            ?>

            <?= $form->field($model, 'id_post')->widget(Select2::classname(), [
                'data' => ArrayHelper::map(\app\models\HwPost::getPost(), 'name', 'name'),
                'options' => ['placeholder' => '', 'class' => 'form-control-sm'],
                'pluginOptions' => ['allowClear' => true, 'tags' => true,]]);
            ?>

            <?= $form->field($model, 'id_podr')->widget(Select2::classname(), [
                'data' => ArrayHelper::map(HwPodr::getPodr(), 'name', 'name'),
                'options' => ['placeholder' => '', 'class' => 'form-control-sm'],
                'pluginOptions' => ['allowClear' => true, 'tags' => true,]]);
            ?>

            <?= $form->field($model, 'id_depart')->widget(Select2::classname(), [
                'data' => ArrayHelper::map(HwPodr::getDepart(), 'name', 'name'),
                'options' => ['placeholder' => '', 'class' => 'form-control-sm'],
                'pluginOptions' => ['allowClear' => true, 'tags' => true,]]);
            ?>


            <?= $form->field($model, 'visible')->checkbox()->label('Уволить') ?>

            <?= $form->field($model, 'comment')->textarea(); ?>


            <?= Html::submitButton(isset($_update) ? "Обновить" : 'Добавить', ['class' => 'btn btn-success btn-block btn-sm mb-2']) ?>

            <?php Redactor::end() ?>
            
            
        </div>
        <?php \yii\bootstrap4\ActiveForm::end(); ?>

        <!-- /.Формая добавляения -->



        <!-- Формая для поиска -->

        <div class="card p-3" style="border: 1px dotted silver">

            <div>

                <?php ActiveForm::begin(['action' => ['/site/users'], 'method' => 'get', 'options' => [ 'class'=> 'form-inline ']]); ?>
                <?= Html::input('search', 'search', '', [
                    'class' => 'form-control   col-12',
                    'placeholder' => 'Введите текст для поиска',
                    'id' => 'search',
                ]) ?>
                <?= Html::submitButton('Найти...', ['class' => 'btn btn-primary btn-block btn-sm mb-2 mt-2']) ?>

                <?php ActiveForm::end(); ?>

            </div>

            <?= Html::a('Вывести уволенных сотрудников', [Url::to(['site/users', 'visible' => 1])], ['class' => 'mt-2 btn btn btn-outline-secondary']); ?>

        </div>

        <!-- Формая для поиска -->

    </div>


    <!-- Список пользователей -->

    <div class="col-9 ">


        <div class="card">
            <div class="card-body p-0">
                <table class="table table-sm table-hover sort table_sort" id="hw-users" style="font-size: 10pt">
                    <thead>
                    <tr>
                        <th>ФИО</th>
                        <th>Должность</th>
                        <th>Организация</th>
                        <th>Управление</th>
                        <th>Отдел</th>
                        <th>...</th>
                    </tr>
                    </thead>
                    <?php foreach ($users as $item): ?>
                        <tr class="<?= $item->id == $_update ? 'alert-warning' : '' ?>  <?= isset($item->visible) ? 'hw-bg-silver' : '' ?>">
                            <td style="min-width: 300px">
                                <span style="display: none"><?= $item->username ?></span>

                                <?= Html::button("<i class=\"fa fa-info-circle mr-1 hw-cl-silver\"></i>", ['value' => Url::to(['site/user-info', 'id' => $item->id]), 'class' => 'p-0 m-0 btn btn-sm btn-url modalButton-lg', 'title' => 'История заявки']); ?>
                                <?= Html::a($item->username, [Url::to(['site/tehnic', 'id_user' => $item->id])]); ?>
                            </td>
                            <td><?= $post[$item->id_post] ?></td>
                            <td><?= $podr[$item->id_org] ?></td>
                            <td><?= isset($item->id_podr) ? $podr[$item->id_podr] : '' ?></td>
                            <td><?= $podr[$item->id_depart] ?> </td>
                            <td><?= $item->comment ?> </td>
                            
                            <?php Redactor::begin() ?>
                            <td style="width: 30px">
                                <div class="tools">
                                    <a href="<?= \yii\helpers\Url::toRoute(['users', 'update' => $item->id]) ?>"
                                       class="fas fa-edit"></a>
                                </div>
                            </td>
                            <?php Redactor::end() ?>
                            
                        </tr>
                    <?php endforeach; ?>
                </table>
            </div>
        </div>
    </div>
    <!-- /.Список пользователей -->

</div>
