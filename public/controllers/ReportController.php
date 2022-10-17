<?php

    namespace app\controllers;

    use app\components\template\ReportInput;
    use app\models\HwDeviceType;
    use app\models\HwModel;
    use app\models\HwSearchTehnicStory;
    use app\models\HwSerarchRam;
    use app\models\HwSettings;
    use app\models\HwSpeceficDevice;
    use app\models\HwSpeceficModel;
    use app\models\HwSpeceficValue;
    use app\models\HwStory;
    use app\models\ShowError;
    use Yii;
    use yii\filters\AccessControl;
    use yii\helpers\ArrayHelper;
    use yii\helpers\Url;
    use yii\web\Controller;
    use yii\filters\VerbFilter;

    class ReportController extends Controller
    {
        /**
         * {@inheritdoc}
         */
        public function behaviors()
        {
            return [
                'access' => [
                    'class' => AccessControl::className(),
                    'only' => ['logout', 'index', 'model', 'users'],
                    'rules' => [
                        [
                            'actions' => ['logout', 'index'],
                            'allow' => true,
                            'roles' => ['@'],
                        ],
                        [
                            'actions' => ['model', 'users'],
                            'allow' => true,
                            'roles' => ['redactor'],
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



//        public function actionSpecificationValue($id_device){
//            $_device = new HwDeviceType();
//            $devices = $_device->getAllListVisible();
//
//            $model = new HwModel();
//            $model_specific = new HwSpeceficModel();
//
//            if ($id_device){
//                $model = HwModel::find()->where(['hw_model.type' => $id_device])->joinWith(['specifications'])->all();
//                $model_specific = HwSpeceficDevice::find()->where(['id_device' => $id_device, 'visible' => 1])->all();
//            }
//
//            $_filter = isset($_GET['specification']) ? $_GET['specification'] : null;
//
//            // запрос при выбра фильтра
//            if ($_filter){
//                unset($_filter[0]);
//                $i = 1;
//                $array_w = array();
//
//                foreach ($_filter as $key => $items) {
//                    $val = [];
//                    foreach ($items as $item) {
//                        $val[] = $item;
//                    }
//
//                    $_m = HwModel::find()->select('hw_specification_model.id_model as id_model')->where(['hw_specification_model.id_device' => $id_device])->joinWith(['specifications'])->andWhere(['=', 'hw_specification_model.specification', $key])->andWhere(['in', 'hw_specification_model.value', $val])->column();
//
//                    if ($i > 1){
//                        $array_w = array_intersect($array_w, $_m);
//                    }else{
//                        $array_w = $_m;
//                    }
//                    $i++;
//                }
//
//
//                $model = HwModel::find()
//                    ->where(['hw_specification_model.id_device' => $id_device])->joinWith(['specifications'])
//                    ->andWhere(['in', 'hw_specification_model.id_model', $array_w])->all();
//            }
//
//            return $this->render('_specification',
//                [
//                    'devices' => $devices,
//                    'model' => $model,
//                    'model_specific' => $model_specific,
//                    'model_list' => isset($array_w) ? $array_w : null,
//                ]);
//
//        }


        /**
         * Displays about page.
         *
         * @return string
         */
        public function actionSpecification($id_device = null, $template = null, $redirect = false)
        {
            if ($template){
                $hw_sett = new HwSettings();
                $hw_sett->tb1 = $template;
                $hw_sett->type = $hw_sett::TYPE_FIELD_REPORT;
                $hw_sett->tb3 = 'id_device';

                $_GET['id_device'] = $id_device = $hw_sett->getValueTemplateField();

                $device_field = HwSpeceficDevice::find()->select(['name'])->where(['id_device' => $id_device, 'visible' => 1])->column();

                $values = ArrayHelper::map($hw_sett->getTemplateField(), 'id', 'tb4', 'tb3');

                foreach ($values as $key => $value){
                    if (!in_array($key, $device_field)){
                        unset($values[$key]);
                    }
                }

                $_GET['specification'] = $values;

                unset($_GET['specification']['id_device']);
            }

            $_device = new HwDeviceType();
            $devices = $_device->getAllListVisible();

            $model = new HwModel();
            $model_specific = new HwSpeceficModel();

            if ($id_device){
                $model = HwModel::find()->where(['hw_model.type' => $id_device])->joinWith(['specifications'])->all();
                $model_specific = HwSpeceficDevice::find()->where(['id_device' => $id_device, 'visible' => 1])->all();
            }

            $_filter = isset($_GET['specification']) ? $_GET['specification'] : null;

            // запрос при выбра фильтра
            if ($_filter){
                unset($_filter[0]);
                $i = 1;
                $array_w = array();

                foreach ($_filter as $key => $items) {
                    $val = [];
                    foreach ($items as $item) {
                        $val[] = $item;
                    }

                    $_m = HwModel::find()->select('hw_specification_model.id_model as id_model')->where(['hw_specification_model.id_device' => $id_device])->joinWith(['specifications'])->andWhere(['=', 'hw_specification_model.specification', $key])->andWhere(['in', 'hw_specification_model.value', $val])->column();

                    if ($i > 1){
                        $array_w = array_intersect($array_w, $_m);
                    }else{
                        $array_w = $_m;
                    }
                    $i++;
                }


                $model = HwModel::find()
                    ->where(['hw_specification_model.id_device' => $id_device])->joinWith(['specifications'])
                    ->andWhere(['in', 'hw_specification_model.id_model', $array_w])->all();


                if ($template and !$redirect)
                    return $this->redirect(['/site/report', 'model_list' => json_encode($array_w), 'template' => $template]);
            }

            return $this->render('specification',
                [
                    'devices' => $devices,
                    'model' => $model,
                    'model_specific' => $model_specific,
                    'model_list' => isset($array_w) ? $array_w : null,
                ]);
        }



        public function actionRam(){
            $hw_settings = new HwSettings();

            $searchModel = new HwSerarchRam();
            $dataProvider = $searchModel->search(\Yii::$app->request->queryParams);

            return $this->render('ram', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
                'templates' => $hw_settings->getReportTemplate()
            ]);
        }


        public function actionStory(){
            $searchModel = new HwSearchTehnicStory();
            $dataProvider = $searchModel->search(\Yii::$app->request->queryParams);

            return $this->render('story', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]);
        }


        public function actionSpecTemplate(){

            $model = new HwSettings();

            if ($model->load(Yii::$app->request->post())) {
                $field_list = $model->tb3;
                $model->id_user = Yii::$app->user->id;
                $model->tb1 = $name = str_replace(" ", "_", $model->tb1);
                if (!$model->existsNameAndUser()){

                    foreach ($_GET[1] as $key => $items){

                        if (is_array($items)){
                            foreach ($items as $item) {
                                $model->tb3 = $key;
                                $model->tb4 = $item;
                                $model->addSpeceficReportField();
                            }
                        }else{
                            if ($key != 0){
                                $model->tb3 = $key;
                                $model->tb4 = $items;
                                $model->addSpeceficReportField();

                            }
                        }
                    }

                    $model->tb3 = 'id_device';
                    $model->tb4 = $_GET['id_device'];
                    $model->addSpeceficReportField();

                    foreach ($field_list as $item) {
                        $new_spec = new HwSettings();
                        $new_spec->type = $model::TYPE_FIELD_REPORT;
                        $new_spec->tb1 = $model->tb1;         //название
                        $new_spec->tb2 = 'specification';               //таблица
                        $new_spec->tb3 = $item;               //столбцы
                        $new_spec->id_user = Yii::$app->user->id;
                        $new_spec->save();
                    }

                }
                return $this->redirect(['/report/specification']);
            }

            return $this->renderAjax('spec-template',
                [
                    'model' => $model,
                ]);

        }

        public function actionUpdate($text, $id, $field){
            try {
                $model = HwSettings::findOne($id);
                $model->$field = $text;
                $model->type = $model::TYPE_FIELD_REPORT_VALUE;

                if (HwSettings::find()->where(['type' => $model::TYPE_FIELD_REPORT_VALUE, 'tb3'=>$field]))


                $result = $model->getSave(false, 'Запись обновлена');

            } catch (\Exception $ex) {
                $result = (['result' =>false, 'message' => 'Ошибка: '. $ex->getMessage()]);
            }

            return json_encode($result);
        }


        public function actionAddInput(){
            echo ReportInput::widget();
        }


        public function actionGetCategory($group){
            if ($cat = HwDeviceType::find()->andFilterWhere(['in', 'category', explode(',',$group)])->all()) {
                foreach ($cat as $item) {
                    echo "<option value = '" . $item->id . "'>" . $item->name . "</option>";
                }
            }
        }

        public function actionGetField($filter, $category = null){
            $settings = new HwSettings();

            if ($filter == 'tehnic') {
                echo "<option>Выбрать...</option>";
                foreach ($settings->getTehnicTableFieldReport() as $key => $item) {
                    echo "<option value = '" . $key . "'>" . $item . "</option>";
                }
            }

            if ($filter == 'specification' and $category){
                echo "<option>Выбрать...</option>";
                foreach (HwSpeceficDevice::find()->where(['id_device' => $category, 'visible' => 1])->all() as $item) {
                    echo "<option value = '" . $item->name . "'>" . $item->name . "</option>";
                }
            }
        }


        public function actionGetValue($filter, $field){
            $settings = new HwSettings();

            if ($filter == 'specification'){

                foreach (HwSpeceficValue::find()->where(['specification' => $field, 'visible' => 1])->all() as $item) {
                    echo "<option value = '" . $item->value . "'>" . $item->value . "</option>";
                }
            }

            if ($filter == 'tehnic'){
                foreach ($settings->getTehnicFiledDropdown()[$field] as $key => $item) {
                    echo "<option value = '" . $key . "'>" . $item . "</option>";
                }
            }
        }

        public function actionAddTemplates($type = null, $data = null, $template = null){

            $model = new HwSettings();
            $model->scenario = $model::SCENARIO_REPORT;
            $model->template = $template;
            $model->data = $data;

            //если выбран шаблон, то заполняем переменные значениями
            $model->setDefaultValueByTemplate();

            if ($type == $model::TYPE_DELETE){
                $model::deleteAll(['tb1' => $template, 'id_user' => Yii::$app->user->id]);
                return $this->redirect(Yii::$app->request->referrer);
            }

            if ($model->load(Yii::$app->request->post())) {

                $tehnic_field = isset($_POST['tehnic']) ? $_POST['tehnic'] : null;
                
                $sp_field = isset($_POST['specification']) ? $_POST['specification'] : null;

                $model->tb2 = 'tehnic';

                if ($template)
                    $model->deleteTemplate();

                if (!HwSettings::find()->where(['type' => $model::TYPE_FIELD_REPORT, 'tb1' => $model->tb1, 'tb2' => $model->tb2, 'id_user' => Yii::$app->user->id])->exists()) {

                    foreach ($model->tb3 as $item) {
                        $model->tb3 = $item;
                        $model->tb4 = implode(',', $_POST[$item]);;
                        $model->visible = 1;
                        $model->_addField();
                    }

                    if ($_POST['group']){
                        $model->tb3 = 'category';
                        $model->tb4 = implode(',', $_POST['group']);;
                        $model->visible = 2;
                        $model->_addField();
                    }

                    if ($_POST['category']){
                        $model->tb3 = 'device_type';
                        $model->tb4 = implode(',', $_POST['category']);
                        $model->visible = 2;
                        $model->_addField();
                    }

                    if ($tehnic_field){
                        foreach ($tehnic_field as $item) {
                            if (isset($item)){
                                $model->tb3 = $item;
                                $model->tb4 = implode(',', $_POST[$item]);
                                $model->visible = 2;
                                $model->_addField();
                            }
                        }
                    }

                    if ($sp_field){
                        
                        foreach ($sp_field as $item) {
                            $model->tb3 = $item;
                            $item = str_replace(' ', '_', $item);

                            if ($_POST[$item]){
//                                $model->tb3 = $item;
                                $model->tb2 = 'specification';
                                $model->tb4 = implode(',', $_POST[ $item]);
                                $model->visible = 2;
                                $model->_addField();
                            }
                        }

                        $model->tb3 = 'id_device';
                        $model->tb2 = 'specification';
                        $model->tb4 = implode(',', $_POST['category']);
                        $model->visible = 2;
                        $model->_addField();
                    }

                    if ($_POST['reverse']){
                        $model->tb3 = 'reverse';
                        $model->tb2 = 'specification';
                        $model->tb4 = true;
                        $model->visible = 2;
                        $model->_addField();
                    }


                }

                return $this->redirect(Url::toRoute(['/site/report', 'template' => $model->template]));
            }

            return $this->renderAjax('add-templates',
                [
                    'model' => $model,
                    'type' => $type,
                    'error' => $model->error,
                ]);
        }


        public function actionAddTemplate($type = null, $data = null, $template = null){
            $model = new HwSettings();
            $model->scenario = $model::SCENARIO_REPORT;
            $model->template = $template;
            $model->data = $data;

            //если выбран шаблон, то заполняем переменные значениями
            $model->setDefaultValueByTemplate();

            if ($type == $model::TYPE_DELETE){
                $model::deleteAll(['tb1' => $template, 'id_user' => Yii::$app->user->id]);
                return $this->redirect(Yii::$app->request->referrer);

            }

            if ($model->load(Yii::$app->request->post())) {

                $model->addField();

                return $this->renderAjax('add-template',
                    [
                        'model' => $model,
                        'type' => 'default_value',
                        'template' => $model->tb1,
                        'error' => $model->error,
                    ]);
            }

            return $this->renderAjax('add-template',
                [
                    'model' => $model,
                    'type' => $type,
                    'error' => $model->error,
                ]);
        }
    }
