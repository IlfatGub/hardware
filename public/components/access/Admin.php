<?php


    /**
     * Доступ для редактирования
     */

    namespace app\components\access;

    use app\models\HwSettings;
    use Yii;
    use yii\base\Widget;

    class Admin extends Widget
    {

        public function init()
        {
            parent::init();
            ob_start();
        }

        public function run()
        {
            $content = ob_get_clean();
            return $this->render('admin', [
                'content' => $content,
            ]);
        }
    }