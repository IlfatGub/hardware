<?php

namespace app\models;

use yii\helpers\Html;

/**
 * Долэности
 *
 * @property string name
 *
 * @property int id
 *
 */

class HwPost extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return 'hw_post';
    }

    public function rules()
    {
        return [
            [['name'], 'string', 'max' => 255],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'name',
        ];
    }


    public function setPost($name)
    {
        $this->name = Html::encode($name);

        if ($this->existsName()) {
            return $this->getIdByName();
        } else {
            $model = new self();
            $model->name = $this->name;
            $model->save();

            return $this->getIdByName();
        }
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


    public static function getPost()
    {
        return self::find()->all();
    }

}
