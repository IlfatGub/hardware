<?php


    /**
     * Кнопки для скачивания АКТ приему передачи
     */

    namespace app\components\template;

    use app\models\HwSettings;
    use Yii;
    use yii\base\Widget;

    class CheckedField extends Widget
    {

        public function init()
        {
            parent::init();

        }

        public function run()
        {
            $field = new HwSettings();

            return $this->render('checked-field', [
                'field_list' => $field->getTehnicTableField(),
                'field_user' => $field->getTehnicUserField(),
            ]);
        }
    }