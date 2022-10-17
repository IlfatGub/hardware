<?php

    /**
     * @var  $templates  \app\models\HwSettings;
     */

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

    $_fio = ArrayHelper::map(\app\models\Login::find()->all(), 'id', 'username');
    $_hw_depart = ArrayHelper::map(HwDepart::find()->where(['type' => HwDepart::TYPE_PARENT_DEPART])->all(), 'id', 'name');

//
//    $id_wh = isset($_GET['id_wh']) ? $_GET['id_wh'] : '';
    $template = isset($_GET['template']) ? $_GET['template'] : '';
//    $id_org = isset($_GET['id_org']) ? $_GET['id_org'] : '';
//    $id_model = isset($_GET['id_model']) ? $_GET['id_model'] : '';
//    $type = isset($_GET['type']) ? $_GET['type'] : '';
//    $status = isset($_GET['status']) ? $_GET['status'] : '';
//    $id_user = isset($_GET['id_user']) ? $_GET['id_user'] : '';
//    $depart_id = isset($_GET['depart_id']) ? $_GET['depart_id'] : '';
//    $hw_depart = isset($_GET['hw_depart']) ? $_GET['hw_depart'] : '';
//    $_tehnic_user = isset($_GET['tehnic_user']) ? $_GET['tehnic_user'] : '';
//    $_tehnic_model = isset($_GET['tehnic_model']) ? $_GET['tehnic_model'] : '';

    $date_to = isset($_GET['date_to']) ? $_GET['date_to'] : '';
    $date_do = isset($_GET['date_do']) ? $_GET['date_do'] : '';

    $date_add_to = isset($_GET['date_add_to']) ? $_GET['date_add_to'] : '';
    $date_add_do = isset($_GET['date_add_do']) ? $_GET['date_add_do'] : '';

    $_depart_id = array();

    if ($id_org) {
        $hw_podr = HwPodr::find()->where(['id' => HwTehnic::getIdDepartList($id_org)])->orderBy(['name' => SORT_DESC])->all();

        $_depart = ArrayHelper::map($hw_podr, 'id', 'name');
        $_depart_id = ArrayHelper::map($hw_podr, 'id_depart', 'name');
    }

    $d_v = array();
    $_sett = \app\models\HwSettings::find()->andWhere(['type' => 2, 'tb1' => $template, 'id_user' => Yii::$app->user->id])->all();
    $d_v = ArrayHelper::map($_sett, 'tb3', 'tb3');

?>

<?= \app\components\template\ReportTemplate::widget()?>


<div class="row justify-content-md-center">

    <div class="col-12 m-3 fs-12 card fs-8">
        <?php
            $column = [
                ['class' => 'yii\grid\SerialColumn'],
            ];

            $name = [
                'attribute' => 'name',
            ];

            $id_tehnic = [
                'attribute' => 'id_tehnic',
                'value' => function ($model) {
                    return $model->id_tehnic ? $model->id_tehnic : '';
                },
            ];

            $type = [
                'attribute' => 'type',
                'filter' => $_type,
                'value' => function ($model) {
                    return $model->typeDevice->name;
                },
            ];

            $date = [
                'attribute' => 'date',
                'filter' => DatePicker::widget([
                    'name' => 'date_to',
                    'value' => $date_to ? $date_to : '',
                    'type' => DatePicker::TYPE_RANGE,
                    'name2' => 'date_do',
                    'value2' => $date_do ? $date_do : '',
                    'separator' => '-',
                    'pluginOptions' => ['format' => 'yyyy-mm-dd', 'allowClear' => true]
                ]),

                'value' => function ($model) {
                    return $model->date ? date('Y-m-d', $model->date) : '';
                },
            ];
            $date_add = [
                'attribute' => 'date_add',
                'filter' => DatePicker::widget([
                    'name' => 'date_add_to',
                    'value' => $date_add_to ? $date_add_to : '',
                    'type' => DatePicker::TYPE_RANGE,
                    'name2' => 'date_add_do',
                    'value2' => $date_add_do ? $date_add_do : '',
                    'separator' => '-',
                    'pluginOptions' => ['format' => 'yyyy-mm-dd', 'allowClear' => true]
                ]),
                'value' => function ($model) {
                    return $model->date_add ? date('Y-m-d', $model->date_add) : '';
                },
            ];

            $id_user = [
                'attribute' => 'id_user',
                'filter' => $_fio,
                'value' => function ($model) {
                    return $model->login->username;
                },
            ];

            $hw_depart = [
                'attribute' => 'hw_depart',
                'filter' => $_hw_depart,
                'value' => function ($model) {
                    return $model->depart->name ? $model->depart->name : '';
                },
            ];

            $tehnic_model = [
                'attribute' => 'tehnic_model',
                'label' => 'Модель техники',
                'value' => function ($model) {
                    return $model->tehnic->model->name ? $model->tehnic->model->name : '';
                },

            ];
            $tehnic_user = [
                'attribute' => 'tehnic_user',
                'label' => 'Пользователь техники',
                'value' => function ($model) {
                    return $model->tehnic->user->username ? $model->tehnic->user->username : '';
                },
            ];


            in_array('name', $d_v) ? $column[] = $name : null;
            in_array('id_tehnic', $d_v) ? $column[] = $id_tehnic : null;
            in_array('type', $d_v) ? $column[] = $type : null;
            in_array('date', $d_v) ? $column[] = $date : null;
            in_array('date_add', $d_v) ? $column[] = $date_add : null;
            in_array('id_user', $d_v) ? $column[] = $id_user : null;
            in_array('hw_depart', $d_v) ? $column[] = $hw_depart : null;
            in_array('tehnic_model', $d_v) ? $column[] = $tehnic_model : null;
            in_array('tehnic_user', $d_v) ? $column[] = $tehnic_user : null;

            if ($d_v) {
                echo ExportMenu::widget([
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
                ]);

                echo GridView::widget([
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    'tableOptions' => [
                        'class' => 'table table-sm table-bordered table-hover table-striped fs-12'
                    ],
                    'columns' => $column
                ]);
            }

        ?>
    </div>
</div>




