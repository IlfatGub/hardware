<?php

    namespace app\controllers;

    use app\models\HwDeviceType;
    use app\models\HwModel;
    use app\models\HwSpeceficDevice;
    use app\models\HwSpeceficModel;
    use app\models\HwSpeceficValue;
    use app\models\HwTehnic;
    use app\models\ShowError;
    use Yii;
    use yii\filters\AccessControl;
    use yii\helpers\Url;
    use yii\web\Controller;
    use yii\filters\VerbFilter;

    class ModelController extends Controller
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

        /**
         * Displays about page.
         *
         * @return string
         */
        public function actionSpecification($id_model = null, $id_tehnic = null)
        {
            $id_tehnic = isset($_GET['id_tehnic']) ? $_GET['id_tehnic'] : null;

            $model = HwModel::findOne($id_model);

            $specification = HwSpeceficModel::find()->where(['id_model' => $id_model, 'id_tehnic' => null])->all();
            $all_specification = HwSpeceficModel::find()->all();

            $device = new HwSpeceficDevice(['id_device' => $model->type]);

            return $this->renderAjax('specification',
                [
                    'model' => $model,
                    'device_specif' => $device->getListDevice(),
                    'id_model' => $id_model,
                    'id_tehnic' => $id_tehnic,
                    'specification' => $specification,
                    'all_specification' => $all_specification
                ]);
        }

        public function actionAddSpecific($specific, $text, $id, $id_tehnic = null, $serial_array = null )
        {
            $serial_array = isset($serial_array) ? explode(';', $serial_array) : null;


            $hw_model = HwModel::findOne($id);

            $model = new HwSpeceficModel();
            $model->id_model = $id;
            $model->specification = str_replace('[]', '' , $specific);
            $model->id_tehnic = $id_tehnic;
//            $model->type = $hw_model->type;
            $model->value = $text;

            $model = $model->speceficByTehnic();

            $model_sp = HwSpeceficModel::find()->where(['id_model' => $id, 'specification' => $specific])->andWhere(['is', 'id_tehnic', new \yii\db\Expression('null')])->one();

            if ($model_sp and !$model->id_tehnic)
                $model = $model_sp;

            try {
                if (isset($serial_array) && is_array($serial_array)){

                    foreach ($serial_array as $item) {
                        if (HwTehnic::findOne($id_tehnic)->id_model == HwTehnic::findOne($item)->id_model){
                            $model->id_tehnic = $item;
                            $model->addSpecification($text, $hw_model->type);
                        }
                    }

                }else{
                    $model->addSpecification($text, $hw_model->type);
                }

            } catch (\Exception $ex) {
                echo "<pre>"; print_r($ex->getMessage() ); die();
            }
        }

        /**
         * Displays about page.
         *
         * @return string
         */
        public function actionIndex($update = null, $delete = null)
        {
            $model = new HwModel();

            if (isset($update))
                $model = HwModel::findOne($update);

            if (isset($delete)) {
                $model->deleteById($delete);
                return $this->redirect('index');
            }

            if ($model->load(Yii::$app->request->post())) {

                try {
                    $model->setModel($update);

                    return $this->redirect('index');
                } catch (\Exception $ex) {
                    ShowError::getError('danger', $ex->getMessage());
                }

            }

            $data = $model->getModel();

            return $this->render('index', compact('model', 'data'));
        }

    }
