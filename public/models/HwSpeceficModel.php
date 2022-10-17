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
     * @property int id_model
     * @property int value
     * @property int id_value
     * @property int id_tehnic
     *
     * @property string specification
     */

    class HwSpeceficModel extends ModelInterface
    {

        public static function tableName()
        {
            return 'hw_specification_model';
        }

        public function rules()
        {
            return [
                [['id_device', 'id_model', 'specification', 'value'], 'required'],
                [['visible', 'type', 'id_tehnic', 'id_device', 'id_model', 'id_value'], 'integer'],
                [['specification', 'value'], 'string'],
            ];
        }

        public function attributeLabels()
        {
            return [
                'id' => 'ID',
                'id_device' => 'Тип устройства',
                'id_model' => 'Модель устройства',
                'visible' => 'Видимость',
                'type' => 'Тип записи',
                'specification' => 'Характеристика модели',
                'value' => 'Значение',
            ];
        }


        /**
         * @param $model self
         */
        public function addSpecification($text, $type){
            if ($this->id_tehnic):
                HwSpeceficModel::deleteAll(['specification' => $this->specification, 'id_model' => $this->id_model, 'id_tehnic' => $this->id_tehnic]);
            else:
                HwSpeceficModel::deleteAll(['specification' => $this->specification, 'id_model' => $this->id_model]);
            endif;

            foreach (explode(',', $text) as $item) {

                try {
                    $sp = new HwSpeceficModel();
                    $sp->id_model = $this->id_model;
                    $sp->specification = $this->specification;
                    $sp->id_tehnic = $this->id_tehnic;
                    $sp->id_device =  $type;
                    $sp->value = HwSpeceficValue::findOne($item)->value;
                    $sp->visible = null;
                    $sp->id_value = $item;
                    $sp->type = null;
                    $sp->getSave();
                } catch (\Exception $ex) {
                    echo "<pre>"; print_r($ex->getMessage() ); die();
                }
            }
        }

        /**
         * @return bool
         * роверка на наличие спецификации
         */
        public function existsModelBySpecefic(){

            if (isset($this->id_tehnic))
                return self::find()->where(['id_model' =>  $this->id_model, 'specification' => $this->specification, 'id_tehnic' => $this->id_tehnic])->exists();

            return self::find()->where(['id_model' =>  $this->id_model, 'specification' => $this->specification])->exists();
        }


        public function existsTehnic(){
            return self::find()->where(['id_model' =>  $this->id_model, 'id_tehnic' => $this->id_tehnic])->exists();
        }

        public function speceficByTehnic(){

            if (!$this->id_tehnic)
                return $this;

            // Если нет техники. То аналогично добавляем характеристики из модели
            if (!$this->existsTehnic() and isset($this->id_tehnic)){
                foreach (self::find()->where(['id_model' =>  $this->id_model, 'id_tehnic' => null])->all() as $item) {
                    $new_s = new self();
                    $new_s->id_device =  $item->id_device;
                    $new_s->id_model =  $item->id_model;
                    $new_s->specification =  $item->specification;
                    $new_s->visible =  $item->visible;
                    $new_s->type =  $item->type;
                    $new_s->value =  $item->value;
                    $new_s->id_value =  $item->id_value;
                    $new_s->id_tehnic = $this->id_tehnic;
                    $new_s->save();
                }
                $result = self::find()->where(['id_model' =>  $this->id_model, 'specification' => $this->specification, 'id_tehnic' => $this->id_tehnic])->one();

            }else{
                if (!$result = self::find()->where(['id_model' =>  $this->id_model, 'specification' => $this->specification, 'id_tehnic' => $this->id_tehnic])->one())
                    return $this;
            }
            return  $result ? $result : $this;
        }

    }
