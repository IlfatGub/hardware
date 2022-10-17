<?php

    namespace app\controllers;

    use app\models\HwDepart;
    use app\models\HwDeviceType;
    use app\models\HwSpeceficDevice;
    use app\models\HwSpeceficValue;
    use app\models\ShowError;
    use Yii;
    use yii\filters\AccessControl;
    use yii\helpers\Url;
    use yii\web\Controller;
    use yii\filters\VerbFilter;

    class DepartController extends Controller
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

        public function actionIndex(){

            $model = new HwDepart();

            if ($model->load(Yii::$app->request->post())) {
                $model->id_depart = $model->addDepart();
                $model->addUser();
                return $this->redirect('index');
            }

            return $this->render('index',
                [
                    'model' => $model,
                    'data' => $model->getSearchField('type', 1, null,null, 'name')->all(),
                    'depart' => $model->getSearchField('type', 100, null,null, 'name')->all(),
                ]);
        }

        public function actionUpdate($id, $field, $text = null){

            $model = HwDepart::findOne($id);
            if ($field == 'access_read' or $field == 'access_write' or $field == 'access_search' or $field == 'visible' or $field == 'access_act') {
                $model->$field = $model->$field == 1 ? null : 1;
            }else{
                $model->$field = $field;
            }

            $result = $model->getSave(false, 'Запись Отдела обновлена');

            return json_encode($result);
        }

    }
