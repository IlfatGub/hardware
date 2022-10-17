<?php

    namespace app\controllers;

    use app\models\AppProject;
    use app\models\AppProjectHistory;
    use app\models\Depart;
    use app\models\HwTehnic;
    use app\models\HwUsers;
    use app\models\Sitdesk;
    use app\models\Temp;
    use app\modules\admin\models\AppAnalog;
    use app\modules\admin\models\AppComment;
    use app\modules\admin\models\AppContent;
    use app\modules\admin\models\AppFiles;
    use app\modules\admin\models\AppRemind;
    use app\modules\admin\models\Buh;
    use app\modules\admin\models\Comment;
    use app\modules\admin\models\Fio;
    use app\modules\admin\models\History;
    use app\modules\admin\models\Login;
    use app\modules\admin\models\MyDate;
    use app\modules\admin\models\Problem;
    use app\modules\admin\models\Status;
    use app\modules\admin\models\Userlog;
    use app\modules\admin\models\App;
    use yii\debug\models\search\Log;
    use yii\helpers\Html;
    use yii\rest\ActiveController;


    class ApiController extends ActiveController
    {
        public $modelClass = 'app\models\Api';


        public function actionGetUserTehnic(){

            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

            $username = isset($_POST['username']) ? $_POST['username'] : null;

            if ($username){
                $hw_user = new HwUsers();
                $hw_user->username = $username;

                if ($user = $hw_user->getUsersByUsername()){

                    $hw_tehnic =  new HwTehnic();
                    $hw_tehnic->id_user = $user['id'];

                    $tehnic = $hw_tehnic->getTehnicByUserId();

                    if ($tehnic)
                        return [
                            'status' => true,
                            'data' => $tehnic
                        ];
                }
            }

            return [
                'status' => false,
            ];

        }

    }
