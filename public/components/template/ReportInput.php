<?php


    /**
     * Оповщение
     * Вспылающие
     */

    namespace app\components\template;

    use yii\base\Widget;

    class ReportInput extends Widget
    {
        public $data;
        public $field;
        public $value;
        public $id;
        public $id_device;

        public function init()
        {
            parent::init();
            if ($this->data === null) { $this->data = 0;  }
            if ($this->field === null) { $this->field = 0;  }
            if ($this->value === null) { $this->value = 0;  }
            if ($this->id === null)  { $this->id = 0;  }
            if ($this->id_device === null) { $this->id_device = 0;  }
        }

        public function run()
        {
            return $this->render('report-input', [
                'data' => $this->data,
                'field' => $this->field,
                'value' => $this->value,
                'id' => $this->id,
                'id_device' => $this->id_device,
            ]);
        }
    }