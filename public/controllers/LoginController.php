<?php

    namespace app\controllers;

    use app\models\HwDepart;
    use app\models\HwSettings;
    use app\models\Login;
    use app\models\ShowError;
    use Yii;
    use yii\filters\AccessControl;
    use yii\web\Controller;
    use yii\filters\VerbFilter;

    class LoginController extends Controller
    {
        /**
         * {@inheritdoc}
         */
        public function behaviors()
        {
            return [

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

        public function actionIndex()
        {
            $model = new Login();

            if ($model->load(Yii::$app->request->post())) {
                $model->login = trim($model->login);
                try {
                    $model->addUser();
                } catch (\Exception $ex) {
                    echo "<pre>"; print_r($ex->getMessage() ); die();
                }

                return $this->redirect('index');
            }

            return $this->render('index',
                [
                    'model' => $model,
                    'data' => $model::find()->all(),
                ]);
        }

        public function actionUpdate($id, $field, $text = null)
        {

            try {
                $model = Login::findOne($id);
                if ($field == 'status')
                    $text = $model->status == Login::STATUS_ACTIVE ? Login::STATUS_DEACTIVATE : Login::STATUS_ACTIVE;

                $model->$field = $text;
                $result = $model->getSave(false, 'Запись Пользователя обновлена');

            } catch (\Exception $ex) {
                $result = (['result' =>false, 'message' => 'Ошибка: '. $ex->getMessage()]);
            }

            return json_encode($result);
        }


        public function actionSettingsField($field, $action){
            $settings = new HwSettings();
            try {
                if (!$upd = $settings::find()->andWhere(['tb1' => $action, 'tb2' => Yii::$app->user->id, 'tb3' => $field, 'type' => $settings::TYPE_FIELD_TEHNIC])->one()){
                    $settings->tb1 = $action;
                    $settings->tb2 = Yii::$app->user->id;
                    $settings->tb3 = $field;
                    $settings->type = $settings::TYPE_FIELD_TEHNIC;
                    $result = $settings->getSave(false);
                }else{
                    if($upd->delete()){
                        $result = (['result' =>true, 'message' => 'Запись обновлена']);
                    }
                }
            } catch (\Exception $ex) {
                $result = (['result' =>false, 'message' => 'Ошибка: '. $ex->getMessage()]);
            }

            return json_encode($result);
        }

    }
