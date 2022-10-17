<?php

    namespace app\controllers;

    use app\models\HwDeviceType;
    use app\models\HwSpeceficDevice;
    use app\models\HwSpeceficValue;
    use app\models\ShowError;
    use Yii;
    use yii\filters\AccessControl;
    use yii\helpers\Url;
    use yii\web\Controller;
    use yii\filters\VerbFilter;

    class DeviceController extends Controller
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



        public function actionSpecification($id_device, $delete = null, $id_model = null){

            $model = new HwSpeceficDevice();
            $model->id_device = $id_device;

            if ($delete)
                HwSpeceficDevice::updateAll(['visible' => null],['id_device' => $id_device, 'name' => $delete]);

            if ($model->load(Yii::$app->request->post())) {
                //Добавляем характкристики для устройства
                    try {
                        $model->addSpecific();
                    } catch (\Exception $ex) {
                        ShowError::getError('danger', $ex->getMessage());
                    }
            }

            $specific_data = $model->getListDevice(); // Список характеристик для устройства

            return $this->renderAjax('specification',
                [
                    'model' => $model,
                    'specific_data' => $specific_data,
                    'id_device' => $id_device,
                    'id_model' => $id_model
                ]);

        }


        public function actionUpdate($id, $field, $text = null)
        {

            try {
                $model = HwDeviceType::findOne($id);
                $model->$field = $text;
                $result = $model->getSave(false, 'Запись обновлена');

            } catch (\Exception $ex) {
                $result = (['result' =>false, 'message' => 'Ошибка: '. $ex->getMessage()]);
            }

            return json_encode($result);
        }

        public function actionSpecificationValue($specification, $delete = null){

            $model = new HwSpeceficValue();
            $model->specification = $specification;

            if ($delete)
                $model::updateAll(['visible' => null],['specification' => $specification, 'value' => $delete]);

            if ($model->load(Yii::$app->request->post())) {
                //Добавляем значение характкристики
                $model->addValue();
            }

            $specifications = $model->getSearchField('specification',$specification, 'visible', 1)->all();


            return $this->renderAjax('specification-value',
                [
                    'model' => $model,
                    'specifications' => $specifications,
                    'specification' => $specification,
                ]);

        }

        /**
         * Displays about page.
         *
         * @return string
         */
        public function actionType($update = null, $delete = null)
        {
            $model = new HwDeviceType();

            if (isset($update))
                $model = HwDeviceType::findOne($update);

            if (isset($delete)) {
                $model->deleteById($delete);
                return $this->redirect('type');
            }

            if ($model->load(Yii::$app->request->post())) {

                //Добавляем устрйство
                if ($model->name){
                    try {
                        $model->setDeviceType($update);

                        return $this->redirect('type');
                    } catch (\Exception $ex) {
                        ShowError::getError('danger', $ex->getMessage());
                    }
                }

            }

            $data = $model->getDeviceType(); //Список устройств

            return $this->render('type',
                [
                    'model' => $model,
                    'data' => $data,
                ]);
        }

    }
