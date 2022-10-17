
<?php
    /**
     * @var  $org  \app\models\HwPodr;
     */


    use yii\bootstrap4\ActiveForm;
    use yii\helpers\Html;
    use yii\helpers\Url;


    $org = isset($_GET['org']) ? $_GET['org'] : null

    ?>

<div class="col-12 row pt-3">
    <!-- Формая добавляения -->
    <div class="col-3">
        <!-- Формая для поиска -->

        <div class="card p-3" style="border: 1px dotted silver">

            <div class="mt-2">
                <?php ActiveForm::begin(['action' => ['/site/department', 'org' => $org], 'method' => 'get', 'options' => [ 'class'=> 'form-inline ']]); ?>
                <?= Html::input('search', 'search', '', [
                    'class' => 'form-control   col-12',
                    'placeholder' => 'Введите отдел/подразделение',
                    'id' => 'search',
                ]) ?>
                <?= Html::submitButton('Найти...', ['class' => 'btn btn-primary btn-block btn-sm mb-2 mt-2']) ?>

                <?php ActiveForm::end(); ?>
            </div>


            <div class="card-columns mt-5">
                <?php foreach($podr as $item): ?>
                    <?= Html::a($item->name, [Url::to(['site/department', 'org' => $item->id ])], ['class' =>  $org == $item->id ? 'card col-12 mt-2 btn btn-secondary alert-secondary' : 'card mt-2 btn btn btn-outline-secondary']); ?>
                <?php endforeach; ?>
            </div>

        </div>

        <!-- Формая для поиска -->

    </div>

    <!-- /.Формая добавляения -->


    <!-- Список техники -->
    <div class="col-9 ">

        <?php if($model): ?>
        <?php echo \app\components\template\UserView::widget(
            [
                'users_list' => $model,
            ])
        ?>
        <?php endif; ?>

    </div>
    <!-- /.Список техники -->


</div>



