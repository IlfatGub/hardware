<?php

    namespace app\models;

    use Yii;

    /**
     * Характеристики устройства
     *
     * @property int id
     * @property int type
     * @property int visible
     *
     * @property string specification
     * @property string value
     */
    class HwSpeceficValue extends ModelInterface
    {

        public static function tableName()
        {
            return 'hw_specification_value';
        }

        public function rules()
        {
            return [
                [['value', 'specification'], 'required'],
                [['type', 'visible'], 'integer'],
                [['specification', 'value'], 'string'],
            ];
        }

        public function attributeLabels()
        {
            return [
                'id' => 'ID',
                'value' => 'Значение',
                'type' => 'Тип записи',
                'specification' => 'Характеристика',
            ];
        }

        public function addValue()
        {
            try {
                if ($upd_record = $this->existsField('specification', $this->specification , 'value', $this->value)) {
                    ShowError::getError('warning', 'Значение для хареактеристики уже существует. Значение активировано');
                    $upd_record->visible = 1;
                    $upd_record->save();
                    return $upd_record->id;
                } else {
                    $model = new self();
                    $model->value = trim($this->value);
                    $model->specification = trim($this->specification);
                    $model->visible = 1;
                    $model->getSave();

                    return $model->id;
                }
            } catch (\Exception $ex) {
                ShowError::getError('danger', $ex->getMessage());
            }
        }


    }
