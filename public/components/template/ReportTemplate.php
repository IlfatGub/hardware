<?php


    /**
     * Оповщение
     * Вспылающие
     */

    namespace app\components\template;

    use app\models\HwSettings;
    use yii\base\Widget;

    class ReportTemplate extends Widget
    {
        public $type;


        public function init()
        {
            parent::init();
            if ($this->type === null) {
                $this->type = 0;
            }
        }

        public function run()
        {
            $hw_settings = new HwSettings();
            $hw_settings->type = $this->type;

            return $this->render('report-template', [
                'type' => $this->type,
                'templates' => $hw_settings->getReportTemplate()
            ]);
        }
    }