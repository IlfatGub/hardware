<?php

    namespace app\models;

    use Yii;
    use yii\helpers\ArrayHelper;

    /**
     * Статус техники
     *
     * @property int id
     * @property int visible
     * @property int type
     *
     * @property string name
     */

    class HwTehnicStatus extends ModelInterface
    {

        public static function tableName()
        {
            return 'hw_tehnic_status';
        }

        public function rules()
        {
            return [
                [['name'], 'required'],
                [['visible', 'type',], 'integer'],
                [['name'], 'string'],
            ];
        }

        public function attributeLabels()
        {
            return [
                'id' => 'ID',
                'visible' => 'Видимость',
                'type' => 'Тип записи',
                'name' => 'Статус',
            ];
        }

        public function getStatus(){
            return ArrayHelper::map(self::getAllListVisible(), 'id', 'name');
        }
    }
