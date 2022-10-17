<?php

    namespace app\controllers;

    use app\components\template\Notify;
    use app\models\HwAct;
    use app\models\HwDepart;
    use app\models\HwDeviceType;
    use app\models\HwFio;
    use app\models\HwModel;
    use app\models\HwPodr;
    use app\models\HwPost;
    use app\models\HwSettings;
    use app\models\HwSpeceficDevice;
    use app\models\HwSpeceficModel;
    use app\models\HwStory;
    use app\models\HwTehnic;
    use app\models\HwTehnicRam;
    use app\models\HwUsers;
    use app\models\HwWh;
    use app\models\Login;
    use app\models\ShowError;
    use app\models\HwSearchTehnic;
    use http\Url;
    use kartik\mpdf\Pdf;
    use phpnt\bootstrapNotify\BootstrapNotify;
    use Yii;
    use yii\bootstrap\ActiveForm;
    use yii\filters\AccessControl;
    use yii\helpers\ArrayHelper;
    use yii\helpers\Html;
    use yii\web\Controller;
    use yii\web\Response;
    use yii\filters\VerbFilter;
    use app\models\LoginForm;

    class SiteController extends Controller
    {
        /**
         * {@inheritdoc}
         */
        public function behaviors()
        {
            return [
                'access' => [
                    'class' => AccessControl::className(),
                    'only' => ['model', 'users', 'tehnic', 'wh', 'reports', 'report'],
                    'rules' => [

                        [
                            'actions' => ['model', 'users', 'tehnic', 'wh', 'reports', 'report'],
                            'allow' => true,
                            'roles' => ['@'],
                        ],
                    ],
                ],
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'logout' => ['post'],
                    ],
                ],
            ];
        }

        /**
         * {@inheritdoc}
         */
        public function actions()
        {
            return [
                'error' => [
                    'class' => 'yii\web\ErrorAction',
                ],
                'captcha' => [
                    'class' => 'yii\captcha\CaptchaAction',
                    'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
                ],
            ];
        }

        /**
         * Displays homepage.
         *
         * @return string
         */
        public function actionIndex()
        {
            return $this->redirect('tehnic');
        }

        /**
         * Login action.
         *
         * @return Response|string
         */
        public function actionLogin()
        {
            $this->layout = "main-login";

            if (!Yii::$app->user->isGuest) {
                return $this->goHome();
            }

            $model = new LoginForm();

            if ($model->load(Yii::$app->request->post()) && $model->login()) {
                ShowError::getError('success', 'вход выполнен');
                return $this->goBack();
            }

            $model->password = '';
            return $this->render('login', [
                'model' => $model,
            ]);
        }

        /**
         * Logout action.
         *
         * @return Response
         */
        public function actionLogout()
        {
            Yii::$app->user->logout();

            return $this->goHome();
        }

        /**
         * Displays contact page.
         *
         * @return Response|string
         */
        public function actionContact()
        {
            $model = new ContactForm();
            if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
                Yii::$app->session->setFlash('contactFormSubmitted');

                return $this->refresh();
            }
            return $this->render('contact', [
                'model' => $model,
            ]);
        }

        /**
         * Displays about page.
         *
         * @return string
         */
        public function actionUsers($update = null, $delete = null, $visible = null, $search = null)
        {
            $model = new HwUsers();
            $model->visible = $visible;
            $model->search = $search;
            if (isset($update))
                $model = HwUsers::findOne($update);

            if (isset($delete)) {
                HwUsers::find()->where(['id' => $delete])->one()->delete();
                return $this->redirect('users');
            }

            if ($model->load(Yii::$app->request->post())) {

                $podr = new HwPodr();
                $post = new HwPost();
                $fio = new HwFio();

                try {
                    $model->id_org = $podr->setPodr($model->id_org, 1);
                    $model->id_podr = $podr->setPodr($model->id_podr, 2);
                    $model->id_depart = $podr->setPodr($model->id_depart, 3);
                    $model->id_post = $post->setPost($model->id_post);
                    $model->username = $fio->setFio($model->username);
                    $model->date_ct = strtotime('now');
                    $model->comment = Html::encode($model->comment);
                    $model->visible = $model->visible ? $model->visible : null;
                    $model->getSave();

                    return $this->redirect(['users', 'update' => isset($update) ? $update : $model->id]);
//                return $this->redirect('users');
                } catch (\Exception $ex) {
                    ShowError::getError('danger', 'ActiveDirectoryModel. Login <br> Ошибка входа. Логин или пароль не верны. <br>' . $ex->getMessage());
                }

            }

            $model->limit = 100;
            $users = $model->getUsers();

            return $this->render('users', compact('model', 'users'));
        }


        /**
         * Displays about page.
         *
         * @return string
         */
        public function actionModel($update = null, $delete = null)
        {
            $model = new HwModel();

            if (isset($update))
                $model = HwModel::findOne($update);

            if (isset($delete)) {
                $model->deleteById($delete);
                return $this->redirect('model');
            }

            if ($model->load(Yii::$app->request->post())) {

                try {
                    $model->setModel($update);

                    return $this->redirect('model');
                } catch (\Exception $ex) {
                    ShowError::getError('danger', $ex->getMessage());
                }

            }

            $data = $model->getModel();

            return $this->render('model', compact('model', 'data'));
        }


        /**
         * Displays about page.
         *
         * @return string
         */
        public function actionWh($update = null, $delete = null)
        {
            $model = new HwWh();

            if (isset($update))
                $model = HwWh::findOne($update);

            if (isset($delete)) {
                $model->deleteById($delete);
                return $this->redirect('wh');
            }

            if ($model->load(Yii::$app->request->post())) {

                try {
                    $model->setWh($update);

                    return $this->redirect('wh');
                } catch (\Exception $ex) {
                    ShowError::getError('danger', $ex->getMessage());
                }

            }

            $data = $model->getWh();

            return $this->render('wh', compact('model', 'data'));
        }

        public function actionFastAddTehnic()
        {
            $device_model = new HwModel();
            $model = new HwTehnic();
            $model->status_tehnic = HwStory::STATUS_CREATE;
            $model->scenario = $model::SCENARIO_FAST_CREATE;
            $model->check_serial = true;
            $model->_status = 1;
            $model->_wh = 1;

            //POSTehnic
            if ($model->load(Yii::$app->request->post())) {

                try {
                    $device_model->id = $model->_model;

                    $model->id_org = $model->_org;
                    $model->id_wh = $model->_wh;
                    $model->status = $model->_status;
                    $model->id_model = $model->_model;
                    $new_model = $model;
                    for ($i = 0; $i <= $model->count; $i++) {
                        $models = new HwTehnic();
                        $models->attributes = $new_model->attributes;
                        if ($models->setTehnic()) {
                            if ($device_model->getDeviceGroup() == 4) {
                                $ram = new HwTehnicRam();
                                $ram->name = $device_model->getModelName();
                                $ram->id_ram = $models->id;
                                $ram->date = strtotime('now');
                                $ram->type = $device_model->getDeviceType();
                                $ram->id_user = Yii::$app->user->id;
                                $ram->hw_depart = Yii::$app->user->identity->hw_depart;
                                $ram->save();
                            }
                        }
                    }
                    return $this->redirect(['tehnic']);
                } catch (\Exception $ex) {
                    echo "<pre>";
                    print_r($ex->getMessage());
                    die();
                }
            }

            return $this->renderAjax('fast-add-tehnic', compact('model'));
        }

        /**
         * Displays about page.
         *
         * @return string
         */
        public function actionTehnic($update = null, $delete = null, $id_user = null)
        {
            $old_data = null;

            $model = new HwTehnic();
            $model->status_tehnic = HwStory::STATUS_CREATE;
            $model->scenario = $model::SCENARIO_CREATE;

            //Обновляем
            if (isset($update))
                $model = HwTehnic::findOne($update);

            //Удаляем
            if (isset($delete)) {
                return $this->redirect(['tehnic', 'id_user' => $id_user, 'update' => $update]);
            }

            //Данные
            $data = $model->getTehnic($id_user);

            if (isset($id_user))
                $old_data = $model->getOldTehnic($id_user);

            //POSTehnic
            if ($model->load(Yii::$app->request->post())) {

                try {
                    if (isset($update))
                        $model->status_tehnic = $model->getStatus();

                    $model->serial = str_replace(array("\r\n", "\r", "\n", "\t", '  ', '    ', '    '), '', $model->serial);

                    if (!$model->check_serial) {
                        if (!$model->serial) {
                            ShowError::getError('danger', 'Поле серийный номер обязательный');
                            return $this->render('tehnic', compact('model', 'data', 'old_data'));
                        } else {
                            if (isset($update)) {
                                if (HwTehnic::find()->where(['serial' => $model->serial])->andFilterWhere(['<>', 'id', $model->id])->exists()) {
                                    ShowError::getError('danger', 'Серийный номер должен быть уникальным');
                                    return $this->render('tehnic', compact('model', 'data', 'old_data'));
                                }
                            }
                        }
                    }

                    $model->setTehnic($update);

                    return $this->redirect(['tehnic', 'id_user' => $id_user, 'update' => $update]);
                } catch (\Exception $ex) {
                    ShowError::getError('danger', $ex->getMessage());
                }

            }

            return $this->render('tehnic', compact('model', 'data', 'old_data'));
        }


        public function actionTest()
        {
//            $model =  HwTehnicRam::find()->where(['>', 'id_ram', 0])->orderBy(['name' => SORT_DESC])->all();
//
//            foreach ($model as $item) {
//                if (!HwTehnic::findOne($item->id_ram)){
//                    HwTehnicRam::findOne($item->id)->delete();
//                    echo $item->id_ram. ' ' . $item->name; echo "<br>";
//
//                }
//            }

        }


        /*
         * $id - ID старого владельца
         * $id_user - ID нового владельца
         */
        public function actionReOrder($id, $type = null)
        {
            $model = new HwTehnic();

            if (isset($_POST['id_user'])) {
                $id_user = isset($_POST['id_user']) ? $_POST['id_user'] : null;

                $model->id_new_user = $id_user;
                $model->id_user = $id;

                $model->reOrder();

                return $this->redirect(['tehnic', 'id_user' => $id]);
            } elseif ($type) {

                $model->id_new_user = null;
                $model->id_user = $id;
                $model->id_wh = HwWh::HW_WH;

                $model->reOrder();

                return $this->redirect(['tehnic', 'id_user' => $id]);
            }


            return $this->renderAjax('re-order',
                [
                    'model' => $model
                ]);
        }

        public function actionUserInfo($id)
        {

            $user = HwUsers::findOne($id);
            $hw_depart = new HwDepart();

            $model = HwTehnic::find()
                ->joinWith(['model', 'user', 'wh', 'org', 'typeDevice'])
                ->where(['id_user' => $id])
                ->andWhere(['in', 'hw_tehnic.hw_depart', $hw_depart->getAccessReadAndWrite(1)])
                ->all();

            return $this->renderAjax('user-info', compact('model', 'user'));

        }

        public function actionSearchSerial()
        {

            $model = new HwTehnic();

            if ($model->load(Yii::$app->request->post())) {

                $search = explode(';', $model->serial);
                $search = array_diff($search, array('', NULL, false));
                $search = array_filter(array_map('trim', $search));

                $query = HwTehnic::find()->joinWith(['user', 'typeDevice'])->joinWith(['model'])->joinWith(['org']);

                $query->where(['in', 'serial', $search]);

                $data = $query->all();

                return $this->render('search-serial',
                    [
                        'model' => $model,
                        'data' => $data
                    ]);


            }

            return $this->render('search-serial',
                [
                    'model' => $model
                ]);
        }

        public function actionSearch($search)
        {
            if ($search) {
                $model = new HwTehnic();
                $hw_depart = new HwDepart();

                $first_letter = substr($search, 0, 1);

                $query = HwTehnic::find()->joinWith(['user', 'typeDevice'])->joinWith(['model'])->joinWith(['org']);

                if ($first_letter == '=') {
                    $search = substr($search, 1, 1000);
                    $query->orFilterWhere(['=', 'hw_users.username', $search])
                        ->orFilterWhere(['=', 'hw_users.comment', $search])
                        ->orFilterWhere(['=', 'hw_tehnic.id', $search])
                        ->orFilterWhere(['=', 'hw_model.name', $search])
                        ->orFilterWhere(['=', 'serial', $search])
                        ->orFilterWhere(['=', 'hw_tehnic.comment', $search])
                        ->orFilterWhere(['=', 'old_passport', $search])
                        ->orFilterWhere(['=', 'hw_podr.name', $search]);
                } else {
                    $query->orFilterWhere(['like', 'hw_users.username', $search])
                        ->orFilterWhere(['like', 'hw_users.comment', $search])
                        ->orFilterWhere(['like', 'hw_tehnic.id', $search])
                        ->orFilterWhere(['like', 'hw_model.name', $search])
                        ->orFilterWhere(['like', 'serial', $search])
                        ->orFilterWhere(['like', 'hw_tehnic.comment', $search])
                        ->orFilterWhere(['like', 'old_passport', $search])
                        ->orFilterWhere(['like', 'hw_podr.name', $search]);
                }
                $query->andWhere(['in', 'hw_depart', $hw_depart->getAccessSearch(true)]);

                if (Yii::$app->user->can('Service'))
                    $query->andWhere(['in', 'hw_device_type.category', 3]);

                $data = $query->all();

            }
            return $this->render('search',
                [
                    'data' => $data,
                    'model' => $model,
                ]);
        }

        public function actionTehnicStory($id, $id_ram = null)
        {

            if ($id_ram) {
                $ram = HwTehnicRam::findOne($id_ram);

                $tehinc = HwTehnic::findOne($ram->id_tehnic);
                $tehinc->status_tehnic = HwStory::STATUS_DELL_RAM;
                $tehinc->comment = 'Удалено комплектующее. ' . $ram->name;
                HwStory::addStory($tehinc);

                $ram->id_tehnic = null;
                $ram->date = null;
                if ($ram->save()) {
                    if ($ram->id_ram) {
                        $tehinc = HwTehnic::findOne($ram->id_ram);
                        $tehinc->id_parent_tehnic = null;
                        $tehinc->save();
                    }
                }
            }

            $hw_depart = new HwDepart();

            $model = HwStory::find()
                ->joinWith(['depart', 'model'])
                ->where(['id_tehnic' => $id])
                ->andWhere(['in', 'hw_depart', $hw_depart->getAccessReadAndWrite(1)])
                ->all();

            $tehnic = HwTehnic::findOne($id);

            $ram = HwTehnicRam::find()->where(['id_tehnic' => $id])->all();

            return $this->renderAjax('tehnic-story', compact('model', 'tehnic', 'ram'));

        }


        public function actionAddComponent($id = null, $id_ram = null)
        {

            $specification = false; // для перехода в спецификации устройства

            $tehinc = HwTehnic::findOne($id);

            $hw_ram = new HwTehnicRam();

            if ($id_ram) {
                $hw_ram->id_ram = $id;
                $hw_ram->id = $id_ram;
                $specification = $hw_ram->delRam();
            }

            if ($_POST['component']) {
                $hw_ram->id_tehnic = $id;
                $hw_ram->name = $_POST['component'];
                $specification = $hw_ram->addRam();
            }

            $specification = false; // для перехода в спецификации устройства

            if ($specification) {
                // -------------------- Спецификация ----------------------
                $model = HwModel::findOne($tehinc->id_model);

                $specification = HwSpeceficModel::find()->where(['id_model' => $tehinc->id_model])->all();
                $all_specification = HwSpeceficModel::find()->all();

                $device = new HwSpeceficDevice(['id_device' => $model->type]);

                return $this->renderAjax('/model/specification',
                    [
                        'model' => $model,
                        'device_specif' => $device->getListDevice(),
                        'id_model' => $tehinc->id_model,
                        'id_tehnic' => $tehinc->id,
                        'specification' => $specification,
                        'all_specification' => $all_specification
                    ]);
                // -------------------- Спецификация ----------------------
            }

            return $this->renderAjax('add-component');
        }


        public function actionDepartment($org = null, $depart_name = null, $search = null)
        {
            $model = new HwUsers();

            if ($depart_name or $search) {
                $_s = $depart_name ? $depart_name : $search;
                $_s = preg_replace('/[\s]{2,}/', ' ', $_s);

                $podr = new HwPodr(['name' => $_s]);
                $_podr = $podr->getPodrByName();

                if ($_podr) {
                    $id_departs = ArrayHelper::map($_podr, 'id', 'id');
                    $query = HwUsers::find()->where(['id_depart' => $id_departs, 'id_org' => $org]);
                    $model = $query->all();
                }
            } else {
                $model = HwUsers::find()->where(['id_org' => $org])->all();

            }

            $podr = HwPodr::getOrg();

            return $this->render('department', [
                'podr' => $podr,
                'model' => $model,
            ]);

        }


        public function actionCamera(){
            return $this->render('camera');
        }

        /**
         * @param $id_user
         * @param $type
         *
         */
        public function actionDownload($id_user, $type)
        {
            $hw_depart = new HwDepart();
            if (in_array(Yii::$app->user->identity->hw_depart, $hw_depart->getAccessAct(true))):
                if ($type == 'pdf') {

                    $hw_act = new HwAct(['id_user' => $id_user]); // акт
                    $tehnic = new HwTehnic(['id_user' => $id_user]); // техника

                    $user = \app\models\HwUsers::findOne($id_user);

                    $fio = $user->username;
                    $org_id = $user->id_org;

                    switch ($org_id) {
                        case 1:
                            $org_name = "ООО \"Нефтехимремстрой\"";
                            break;
                        case 20:
                            $org_name = "ООО \"Нефтехимремстрой\"";
                            break;
                        case 57:
                            $org_name = "ООО \"Нефтехимремстрой\"";
                            break;
                        case 4:
                            $org_name = "ООО \"Нефтехимремстрой\"";
                            break;
                    }
                    $user_tehnic = $tehnic->getTehnicByUser(); // вся закрепеленная техника пользоателя

                    // get your HTML raw content without any layouts or scripts
                    $content = $this->renderPartial('/site/act', ['user' => $user, 'user_tehnic' => $user_tehnic, 'fio' => $fio, 'act_num' => $hw_act->getActNum() ]);

                    setlocale(LC_ALL, 'ru_RU', 'ru_RU.UTF-8', 'ru', 'russian');

                    // setup kartik\mpdf\Pdf component
                    $pdf = new Pdf([
                        // set to use core fonts only
                        'mode' => Pdf::MODE_UTF8,
                        'filename' => 'Акт ' . $fio . '.pdf',
                        // A4 paper format
                        'format' => Pdf::FORMAT_A4,
                        // portrait orientation
                        'orientation' => Pdf::ORIENT_PORTRAIT,
                        // stream to browser inline
                        'destination' => Pdf::DEST_DOWNLOAD,
                        // your html content input
                        'content' => $content,
                        // format content from your own css file if needed or use the
                        // enhanced bootstrap css built by Krajee for mPDF formatting
//                'cssFile' => '@vendor/kartik-v/yii2-mpdf/assets/kv-mpdf-bootstrap.min.css',
                        // any css to be embedded if required
                        'cssInline' => '.kv-heading-1{font-size:18px}',
                        // set mPDF properties on the fly
                        'options' => ['title' => 'Акт приема-передачи оборудования'],
                        // call mPDF methods on the fly
                        'methods' => [
                            'SetHeader' => [$org_name . '||' . date("d-m-Y")],
                            'SetFooter' => ['|Страница {PAGENO}|'],
                        ]
                    ]);

                    // return the pdf output as per the destination setting
                    return $pdf->render();
                } else {
                    Yii::$app->response->sendFile(\app\models\Hardware::actLink($id_user, 1));
                }
            endif;
        }


        public function actionPdf($id_user)
        {

            $user = \app\models\HwUsers::findOne($id_user);

            $fio = $user->username;
            $org_id = $user->id_org;

            switch ($org_id) {
                case 1:
                    $org_name = "ООО \"Завод строительных материалов и конструкций\"";
                    break;
                case 20:
                    $org_name = "ООО \"Нефтехимремстрой\"";
                    break;
                case 57:
                    $org_name = "ООО \"РемЭнергоМонтаж\"";
                    break;
                case 4:
                    $org_name = "АО \"Салаватнефтехимремстрой\"";
                    break;
            }

            $user_tehnic = \app\models\HwTehnic::find()
                ->joinWith(['typeDevice', 'model'])
                ->where(['id_user' => $id_user])->all();


            // get your HTML raw content without any layouts or scripts
            $content = $this->renderPartial('/site/act', ['user' => $user, 'user_tehnic' => $user_tehnic, 'fio' => $fio]);

            // setup kartik\mpdf\Pdf component
            $pdf = new Pdf([
                // set to use core fonts only
                'mode' => Pdf::MODE_UTF8,
                // A4 paper format
                'format' => Pdf::FORMAT_A4,
                // portrait orientation
                'orientation' => Pdf::ORIENT_PORTRAIT,
                // stream to browser inline
                'destination' => Pdf::DEST_DOWNLOAD,
                // your html content input
                'content' => $content,
                // format content from your own css file if needed or use the
                // enhanced bootstrap css built by Krajee for mPDF formatting
//                'cssFile' => '@vendor/kartik-v/yii2-mpdf/assets/kv-mpdf-bootstrap.min.css',
                // any css to be embedded if required
                'cssInline' => '.kv-heading-1{font-size:18px}',
                // set mPDF properties on the fly
                'options' => ['title' => 'Акт приема-передачи оборудования'],
                // call mPDF methods on the fly
                'methods' => [
                    'SetHeader' => [$org_name],
                    'SetFooter' => ['|Страница {PAGENO}|'],
                ]
            ]);

            // return the pdf output as per the destination setting
//            echo $pdf->Output('filename', 'D');
            return $pdf->render();
        }


        public function actionAct()
        {

            return $this->render('act');

        }

        public function actionView($wh = null)
        {

            $query = HwTehnic::find()->orderBy(['date_upd' => SORT_DESC]);

            if (isset($wh)) {
                $query->where(['id_wh' => $wh]);
            } elseif (isset($org)) {

            }
            $model = $query->all();
            return $this->render('view', compact('model', 'depart_full', 'tree'));

        }


        public function actionOrg($org, $id_depart = null)
        {

            $depart = new HwPodr();
            $depart->org = $org;
            if ($id_depart) {
                $depart->id_depart = $id_depart;
                return \app\components\template\UserView::widget(['users_list' => $depart->getUserByBd()]);
            }

            return $this->render('org',
                [
                    'users_list' => $depart->getUserByBd(), //актуальный список пользователей по организации и отделу
                    'tree' => $depart->departByApi(), // актуальный список всех отделов организации
                ]);
        }


        public function actionOrgbase($org, $id_depart = null)
        {

            $depart = new HwPodr();
            $depart->org = $org;
            if ($id_depart) {
                $depart->id_depart = $id_depart;
                return \app\components\template\UserView::widget(['users_list' => $depart->getUserByBd()]);
            }

            return $this->render('org',
                [
                    'users_list' => $depart->getUserByBd(), //актуальный список пользователей по организации и отделу
                    'tree' => $depart->departByApi(), // актуальный список всех отделов организации
                ]);
        }


        /**
         * @return string
         * Отчет.
         */
        public function actionReport($model_list = null)
        {
            $searchModel = new HwSearchTehnic();
            if ($model_list)
                $searchModel->model_list = $model_list;

            $dataProvider = $searchModel->search(\Yii::$app->request->queryParams);

            return $this->render('report', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]);
        }


        public function actionHwRam()
        {
            $model = new HwTehnicRam();

            //POST
            if ($model->load(Yii::$app->request->post())) {
                for ($i = 1; $i <= $model->count; $i++) {
                    $new_ram = new HwTehnicRam();
                    $new_ram->type = $model->type;
                    $new_ram->name = $model->name;
                    $new_ram->count = $model->count;
                    $new_ram->date_add = strtotime('now');
                    $new_ram->date = null;
                    $new_ram->id_user = Yii::$app->user->id;
                    $new_ram->hw_depart = Yii::$app->user->identity->hw_depart;
                    $new_ram->getSave();
                }

                return $this->redirect(['hw-ram']);
            }

            return $this->render('ram', [
                'model' => $model,
                'data' => $model::find()->all()
            ]);
        }

        public function actionReports($model = null, $wh = null)
        {
            if ($model) {

                $hw_depart = new HwDepart();


                if ($wh) {
                    $model_user = HwTehnic::find()->where(['id_model' => $model, 'id_wh' => $wh])->all();
                } else {
                    $model_user = HwTehnic::find()->andWhere(['id_model' => $model])->andWhere(['in', 'hw_depart', $hw_depart])->all();
                }

                echo \app\components\template\TehnicView::widget(
                    [
                        'model' => $model_user,
                        'title' => 'Вся техника по модели',
                        're' => true,
                        'field_wh' => true,
                        'field_date' => true,
                        'field_id' => false,
                        'field_id_org' => true,
                        'field_balance' => true,
                        'field_nomen' => true,
                    ]);

            } else {
                $query = HwTehnic::find()
                    ->joinWith(['model'])
                    ->select(['COUNT(*) AS cnt_model', 'id_model'])
                    ->groupBy(['id_model']);

                if ($wh)
                    $query->where(['id_wh' => $wh]);


                $model = $query->asArray()->all();
                return $this->render('reports', [
                    'model' => $model,
                ]);
            }

        }


        public function actionTehnicAjax($id, $field = null, $text = null)
        {
            try {
                $model = HwTehnic::findOne($id);

                $model->$field = $text;
                if ($result = $model->getSave(false, 'Запись обновлена'))
                    HwStory::addStory($model); //Добавялем историю

            } catch (\Exception $ex) {
                $result = (['result' => false, 'message' => 'Ошибка: ' . $ex->getMessage()]);
            }

            return json_encode($result);
        }

        public function actionPrint()
        {

            return $this->render('print');
        }

        public function actionQcode()
        {

            return $this->render('qcode');
        }


        public function actionSettings()
        {
            $model = Login::findOne(Yii::$app->user->id);

            //POST
            if ($model->load(Yii::$app->request->post())) {
                $model->getSave('Запись обновлена');

                return $this->redirect('settings');
            }

            return $this->render('settings', compact('model'));
        }


        public function actionApiPhone($search, $org = 'зсмик')
        {
            $model = new HwPodr();

            if (empty($org))
                $org = 'зсмик';

            $result = $model->getDepartId($org, $search);

            if ($result->Result) {
                return json_encode($result->Result);
            } else {
                return null;
            }
        }

        //Выводим уведомеление если пытаемся добавить пользователя с одинаковым ФИО
        public function actionExistsFio($fio)
        {
            try {
                $model = new HwUsers();
                $model->username = $fio;

                $data = [
                    'data' => $model->existsUsername() ? true : false,
                    'message' => Notify::widget(['text' => $model->getMessageNotify(), 'type' => 'alert-warning'])
                ];

                return json_encode($data);

            } catch (\Exception $ex) {
                echo print_r($ex);
            }

            return false;
        }
    }
