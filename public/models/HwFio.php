<?php

namespace app\models;

use yii\helpers\Html;

/**
 * Пользователи
 *
 * @property string name
 *
 * @property int id
 *
 */

class HwFio extends \yii\db\ActiveRecord
{

    const SCENARIO_CREATE = 'create';

    public static function tableName()
    {
        return 'hw_fio';
    }

    public function rules()
    {
        return [
            [['name'], 'string', 'max' => 255],
        ];
    }


//    public function scenarios()
//    {
//        $scenarios = parent::scenarios();
//        $scenarios[self::SCENARIO_CREATE] = ['username', 'id_post', 'id_podr', 'id_depart', 'id_org'];
//        return $scenarios;
//    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'ФИО',
        ];
    }



    public function setFio($name)
    {
        $this->name = Html::encode($name);

        if ($this->existsName()) {
            return $this->name;
        } else {
            $model = new self();
            $model->name = $this->name;
            $model->save();

            return $this->name;
        }
    }



    public function existsName()
    {
        return self::find()->where(['name' => $this->name])->exists();
    }


    public static function getFio()
    {
        return self::find()->all();
    }

}
