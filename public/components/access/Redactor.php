<?php


    /**
     * Доступ для редактирования
     */

    namespace app\components\access;

    use app\models\HwSettings;
    use Yii;
    use yii\base\Widget;

    class Redactor extends Widget
    {

        public $role;
        
        public function init()
        {
            parent::init();
            if ($this->role === null) {
                $this->role = 0;
            }
            ob_start();
        }

        public function run()
        {
            $content = ob_get_clean();
            return $this->render('redactor', [
                'content' => $content,
                'role' => $this->role,
            ]);
        }
    }