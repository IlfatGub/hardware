<?php

    namespace app\models;

    use yii\helpers\Html;

    /**
     * Склады
     *
     * @property string name
     * @property string icon
     *
     * @property int id
     * @property int component
     * @property int category
     *
     */
    class HwDeviceType extends ModelInterface
    {
        public static function tableName()
        {
            return 'hw_device_type';
        }

        public function rules()
        {
            return [
                [['name', 'icon'], 'string', 'max' => 255],
                [['component', 'category'], 'integer'],
            ];
        }

        public function attributeLabels()
        {
            return [
                'id' => 'ID',
                'name' => 'Наименование',
                'icon' => 'Иконка',
                'component' => 'Возможность добавления компонента',
                'category' => 'Группа',
            ];
        }

        public function getSpecification()
        {
            return $this->hasOne(HwSpeceficDevice::className(), ['id_device' => 'id']);
        }

        public function setDeviceType($upd = null)
        {
            if (!isset($upd)) {
                $this->addDeviceType();
            } else {
                $this->updDeviceType();
            }

        }


        public function getGroup(){
            return [
                '1' => 'Компьютер',
                '2' => 'Устройство отображения',
                '3' => 'Оргтехника',
                '4' => 'Комплектующие',
                '5' => 'Устройства связи',
            ];
        }

        public function addDeviceType()
        {
            if ($this->existsName()) {
                $this->setVisibleByName(1);
                ShowError::getError('warning', 'Запись Добавлена');
                return $this->getIdByName();
            } else {
                $model = new self();
                $model->name = $this->name;
                $model->icon = $this->icon;
                $model->component = $this->component;
                $model->getSave();
                return $this->getIdByName();
            }
        }

        public function updDeviceType()
        {
//        if ($this->existsName()) {
//            ShowError::getError('warning', 'Запись с таким наименованием уже сущетсвует');
//            return $this->getIdByName();
//        } else {
            $model = $this->getById();
            $model->name = $this->name;
            $model->icon = $this->icon;
            $model->component = $this->component;

            $model->getSave('Запис изменена');
            return $this->getIdByName();
//        }
        }

        public function setVisibleByName($vis = null)
        {
            $model = self::findOne(['name' => $this->name]);
            $model->visible = $vis;
            $model->save();
        }

        public function existsName()
        {
            return self::find()->where(['name' => $this->name])->exists();
        }

        public function getIdByName()
        {
            $result = self::find()->where(['name' => $this->name])->one();
            return $result->id;
        }

        public static function getDeviceType()
        {
            return self::find()->where(['hw_device_type.visible' => 1])->joinWith(['specification'])->all();
        }


        public static function getMenu()
        {
            $result = array();
            foreach (self::getDeviceType() as $item) {
                $result[] = ['label' => $item->name, 'url' => ['site/view', 'wh' => $item->id], 'iconStyle' => 'far'];

            }
            return $result;
        }

        public function getId(){
            $this->id;
        }

    }
