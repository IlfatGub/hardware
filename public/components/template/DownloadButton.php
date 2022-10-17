<?php


    /**
     * Кнопки для скачивания АКТ приему передачи
     */

    namespace app\components\template;

    use yii\base\Widget;

    class DownloadButton extends Widget
    {
        public $id_user;

        public function init()
        {
            parent::init();
            if ($this->id_user === null) {
                $this->id_user = 0;
            }
        }

        public function run()
        {
            return $this->render('downloadButton', [
                'id_user' => $this->id_user,
            ]);
        }
    }