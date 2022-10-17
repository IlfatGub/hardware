<?php


    /**
     * $type
     */

    namespace app\components\template;


    /**
     * Шаблон таблицы вывода пользователей
     *
     */

    use app\models\HwTehnic;
    use yii\base\Widget;

    class UserView extends Widget
    {
        public $users_list;

        public function init()
        {
            if ($this->users_list === null) {
                $this->users_list = 0;
            }
        }

        public function run()
        {
            return $this->render('userView',[
                'users_list' => $this->users_list,
            ]);
        }
    }