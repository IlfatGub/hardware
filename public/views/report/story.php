<?php
    use app\components\access\Admin;
    use app\models\Hardware;
    use app\models\HwDepart;
    use app\models\HwPodr;
    use app\models\HwSettings;
    use app\models\HwTehnic;
    use app\models\HwUsers;
    use kartik\date\DatePicker;
    use yii\grid\GridView;
    use kartik\export\ExportMenu;
    use yii\helpers\ArrayHelper;
    use yii\helpers\Html;
    use yii\helpers\Url;

    $user_model = new HwUsers();

    $_group = \app\models\HwDeviceType::getGroup();

    $_model = ArrayHelper::map(\app\models\HwModel::getModel(), 'id', 'name');
    $_wh = ArrayHelper::map(\app\models\HwWh::getWh(), 'id', 'name');
    $_org = ArrayHelper::map(\app\models\HwPodr::getOrg(), 'id', 'name');
    $_type = ArrayHelper::map(app\models\HwDeviceType::getDeviceType(), 'id', 'name');

    $_fio = ArrayHelper::map($user_model->getUsers(), 'id', 'username');

    $template = isset($_GET['template']) ? $_GET['template'] : '';
    $id_org = isset($_GET['id_org']) ? $_GET['id_org'] : '';

    $_hw_depart = ArrayHelper::map(HwDepart::find()->where(['type' => HwDepart::TYPE_PARENT_DEPART])->all(), 'id', 'name');

    $model_list = isset($_GET['model_list']) ? $_GET['model_list'] : null;

    $date_ct_to = isset($_GET['date_ct_to']) ? $_GET['date_ct_to'] : '';
    $date_ct_do = isset($_GET['date_ct_do']) ? $_GET['date_ct_do'] : '';
    $date_upd_to = isset($_GET['date_upd_to']) ? $_GET['date_upd_to'] : '';
    $date_upd_do = isset($_GET['date_upd_do']) ? $_GET['date_upd_do'] : '';

    $_depart_id = array();

    if ($id_org) {
        $hw_podr = HwPodr::find()->where(['id' => HwTehnic::getIdDepartList($id_org)])->orderBy(['name' => SORT_DESC])->all();

        $_depart = ArrayHelper::map($hw_podr, 'id', 'name');
        $_depart_id = ArrayHelper::map($hw_podr, 'id_depart', 'name');
    }

    $d_v = array();

    $_sett = \app\models\HwSettings::find()->andWhere(['type' => 2, 'tb1' => $template, 'id_user' => Yii::$app->user->id, 'visible' => 1])->all();

    if (Hardware::accessAdmin())
        $_sett = \app\models\HwSettings::find()->andWhere(['type' => 2, 'tb1' => $template, 'visible' => 1])->all();

    $d_v = ArrayHelper::map($_sett, 'tb3', 'tb3');

?>

<div class="row justify-content-md-center">

    <div class="col-12 m-3 fs-12 card fs-8">
        <?php
            $column = [
                ['class' => 'yii\grid\SerialColumn'],
            ];

            $id = [
                'attribute' => 'id_tehnic',
                'label' => 'Номер паспорта',
                'value' => function ($model) {
                    return HwTehnic::getPassport($model->id_tehnic);
                },
            ];

            $org = [
                'attribute' => 'id_org',
                'label' => 'Орг.',
                'filter' => $_org,
                'value' => function ($model) {
                    return isset($model->org->name) ? $model->org->name : '';
                },
            ];

            $serial = [
                'attribute' => 'serial',
                'filterInputOptions' => ['class' => 'form-control']
            ];

            $username = [
                'attribute' => 'id_user',
                'label' => 'Пользователь',
                'filter' => $_fio,
                'value' => function ($model) {
                    return isset($model->user->username) ? $model->user->username : '';
                },
            ];

            $date_ct = [
                'attribute' => 'date_ct',
                'filter' => DatePicker::widget([
                    'name' => 'date_ct_to',
                    'value' => $date_ct_to ? date('Y-m-d',$date_ct_to) : '',
                    'type' => DatePicker::TYPE_RANGE,
                    'name2' => 'date_ct_do',
                    'value2' => $date_ct_do ? date('Y-m-d',$date_ct_do) : '',
                    'separator' => '-',
                    'pluginOptions' => ['format' => 'yyyy-mm-dd', 'allowClear' => true]
                ]),
                'value' => function ($model) {
                    return date('Y-m-d', $model->date_ct);
                },
            ];

            $date_upd = [
                'attribute' => 'date_upd',
                'label' => 'Дата',
                'filter' => DatePicker::widget([
                    'name' => 'date_upd_to',
                    'value' => $date_upd_to ? date('Y-m-d' ,$date_upd_to) : '',
                    'type' => DatePicker::TYPE_RANGE,
                    'name2' => 'date_upd_do',
                    'value2' => $date_upd_do ? date('Y-m-d' ,$date_upd_do) : '',
                    'separator' => '-',
                    'pluginOptions' => ['format' => 'yyyy-mm-dd', 'allowClear' => true]
                ]),
                'value' => function ($model) {
                    return date('Y-m-d', $model->date);
                },
            ];

            $wh = [
                'attribute' => 'id_wh',
                'label' => 'Склад',
                'filter' => $_wh,
                'value' => function ($model) {
//                echo "<pre>"; print_r($model ); echo "</pre>";
                    return isset($model->wh->name) ? $model->wh->name : '';
                },
            ];

            $balance = [
                'attribute' => 'balance',
            ];
            $nomen = [
                'attribute' => 'nomen',
            ];

            $model = [
                'attribute' => 'id_model',
                'label' => 'Модель устройства',
                'filter' => $_model,
                'value' => function ($model) {
//                echo "<pre>"; print_r($model ); echo "</pre>";
                    return isset($model->tehnic->model->name) ? $model->tehnic->model->name : '';
                },
            ];

            $location = [
                'attribute' => 'location',
                'value' => function ($model) {
                    return isset($model->location) ? $model->location : '';
                },
                'filterInputOptions' => ['class' => 'form-control']
            ];

            $category = [
                'attribute' => 'category',
                'label' => 'Группа',
                'filter' => $_group,
                'value' => function ($model, $_group) {
                    $_g = \app\models\HwDeviceType::getGroup();

                    return array_key_exists($model->tehnic->typeDevice->category, $_g) ? $_g[$model->tehnic->typeDevice->category] : '';
                },
            ];

            $device = [
                'attribute' => 'type',
                'label' => 'Тип устройства',
                'filter' => $_type,
                'value' => function ($model, $_group) {
                    return isset($model->tehnic->typeDevice->name) ? $model->tehnic->typeDevice->name : '';
                },
            ];

            $_status = [
                'attribute' => 'status',
                'label' => 'Статус',
                'filter' => \app\models\HwTehnicStatus::getStatus(),
                'value' => function ($model) {
                    return isset($model->hwTehnicStatus->name) ? $model->hwTehnicStatus->name : '';
                },
            ];

            $date_admission = [
                'attribute' => 'date_admission',
            ];

            $act_num = [
                'attribute' => 'act_num',
            ];

            $date_warranty = [
                'attribute' => 'date_warranty',
            ];

            $hw_depart = [
                'attribute' => 'hw_depart',
                'filter' => $_hw_depart,
                'value' => function ($model) {
                    return $model->depart->name ? $model->depart->name : '';
                },
            ];

            $user_depart = [
                'attribute' => 'user_depart',
                'label' => 'Отдел',
                'filter' => $_depart_id,
                'value' => function ($model) {
                    return isset($model->user->depart->name) ? $model->user->depart->name : '';
                },
            ];


            $count = [
                'attribute' => 'location',
                'label' => 'Количетсво',
            ];
//
//            in_array('id', $d_v) ? $column[] = $id : null;
//            in_array('id_org', $d_v) ? $column[] = $org : null;
//            in_array('serial', $d_v) ? $column[] = $serial : null;
//            in_array('fio', $d_v) ? $column[] = $username : null;
//            in_array('date_ct', $d_v) ? $column[] = $date_ct : null;
//            in_array('date_upd', $d_v) ? $column[] = $date_upd : null;
//            in_array('wh', $d_v) ? $column[] = $wh : null;
//            in_array('balance', $d_v) ? $column[] = $balance : null;
//            in_array('nomen', $d_v) ? $column[] = $nomen : null;
//            in_array('id_model', $d_v) ? $column[] = $model : null;
//            in_array('location', $d_v) ? $column[] = $location : null;
//            in_array('category', $d_v) ? $column[] = $category : null;
//            in_array('device_type', $d_v) ? $column[] = $device : null;
//            in_array('status', $d_v) ? $column[] = $_status : null;
//            in_array('date_admission', $d_v) ? $column[] = $date_admission : null;
//            in_array('act_num', $d_v) ? $column[] = $act_num : null;
//            in_array('date_warranty', $d_v) ? $column[] = $date_warranty : null;
//            in_array('depart', $d_v) ? $column[] = $user_depart : null;
//            in_array('hw_depart', $d_v) ? $column[] = $hw_depart : null;

                $column[] = $id;
                $column[] = $org;
//                $column[] = $serial;
                $column[] = $username;
//                $column[] = $date_ct;
                $column[] = $date_upd;
                $column[] = $wh;
//                $column[] = $balance;
//                $column[] = $nomen;
                $column[] = $model;
//                $column[] = $location;
                $column[] = $category;
                $column[] = $device;
                $column[] = $_status;
//                $column[] = $user_depart;
                $column[] = $count;

                echo '

<div class="row">
    <div class="col-11">
        <ul id="nav2" class="mb-2 p-1"><li  class="mr-5" style="color: white; font-size: 12pt; font-weight: 600"> Выгрузить </li>' .
                    ExportMenu::widget([
                        'asDropdown' => false,
                        'dataProvider' => $dataProvider,
                        'columns' => $column,
                        'target' => ExportMenu::TARGET_BLANK,
                        'exportConfig' => [
                            ExportMenu::FORMAT_TEXT => false,
//                        ExportMenu::FORMAT_EXCEL_X => true,
                            ExportMenu::FORMAT_PDF => [
                                'pdfConfig' => [
                                    'methods' => [
                                        'SetTitle' => '',
                                        'SetSubject' => '',
                                        'SetHeader' => '',
                                        'SetFooter' => '',
                                        'SetAuthor' => '',
                                        'SetCreator' => '',
                                        'SetKeywords' => '',

                                    ]
                                ]
                            ],
                        ],
                    ]) .
                    '<li  class="mr-5 float-right" style="color: white; font-size: 12pt; font-weight: 600">  </li> </ul>
    </div>
        <div class="col-1">
        ' . HTML::a('Очистить поля', [\yii\helpers\Url::to(['/report/story'])], ['class' => 'btn btn-sm btn-danger']) . '
    </div>

</div> ';

                echo GridView::widget([
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    'tableOptions' => [
                        'class' => 'table table-sm table-bordered table-hover table-striped fs-12'
                    ],
                    'columns' => $column
                ]);

        ?>
    </div>
</div>




