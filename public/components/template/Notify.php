<?php


    /**
     * Оповщение
     * Вспылающие
     */

    namespace app\components\template;

    use yii\base\Widget;

    class Notify extends Widget
    {
        public $text;
        public $type;


        public function init()
        {
            parent::init();
            if ($this->text === null) {
                $this->text = 0;
            }
            if ($this->type === null) {
                $this->type = 'alert-danger';
            }
        }

        public function run()
        {
            return $this->render('notify', [
                'text' => $this->text,
                'type' => $this->type,
            ]);
        }
    }