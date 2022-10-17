<?php

    namespace app\models;

    use Yii;

    /**
     * Характеристики устройства
     *
     * @property int id
     * @property int id_device
     * @property int visible
     * @property int type
     *
     * @property string specification
     */

    class HwSpeceficDevice extends ModelInterface
    {

        public static function tableName()
        {
            return 'hw_specification_device';
        }

        public function rules()
        {
            return [
                [['id_device', 'name'], 'required'],
                [['visible', 'type', 'id_device'], 'integer'],
                [['name'], 'string'],
            ];
        }

        public function attributeLabels()
        {
            return [
                'id' => 'ID',
                'id_device' => 'Тип устройства',
                'visible' => 'Видимость',
                'type' => 'Тип записи',
                'name' => 'Характеристика устройства',
            ];
        }

        public function getListDevice()
        {
            return self::find()->where(['visible' => 1])->andWhere(['id_device' => $this->id_device])->all();
        }

        public function addSpecific()
        {
            $this->_field = 'id_device';
            $this->_field_val = $this->id_device;

            if ($this->existsName()) {
                ShowError::getError('warning', 'Наименование уже существует. Активировано');
                return $this->visibleOn();
            } else {
                $model = new self();
                $model->name = trim($this->name);
                $model->id_device = $this->id_device;
                $model->visible = 1;
                $model->getSave();

                return $model->id;
            }
        }

    }
