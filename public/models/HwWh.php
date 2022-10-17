<?php

namespace app\models;

use Yii;
use yii\helpers\Html;

/**
 * Склады
 *
 * @property string name
 *
 * @property int id
 * @property int hw_depart
 *
 */

class HwWh extends ModelInterface
{

    const HW_WH = 12; //расположенгие на сладе
    const HW_USERS = 10; //расположенгие у пользователя

    public static function tableName()
    {
        return 'hw_wh';
    }

    public function rules()
    {
        return [
            [['name'], 'string', 'max' => 255],
            [['hw_depart'], 'integer'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Наименование',
            'hw_depart' => 'Принадлежность к отделу',
        ];
    }


    public function getDepart()
    {
        return $this->hasOne(HwDepart::className(), ['id' => 'hw_depart']);
    }

    public function setWh($upd = null)
    {
        if (!isset($upd)){
            $this->addWh();
        }else{
            $this->updWh();
        }

    }

    public function addWh() {
        if ($this->existsField('name', $this->name, 'hw_depart', Yii::$app->user->identity->hw_depart)) {
            $this->setVisibleByName(1);
            ShowError::getError('warning', 'Запись Добавлена');
            return $this->getIdByName();
        } else {
            $model = new self();
            $model->name = $this->name;
            $model->hw_depart = \Yii::$app->user->identity->hw_depart;
            $model->getSave();
            return $this->getIdByName();
        }
    }

    public function updWh(){
        if ($this->existsName()) {
//            $this->setVisibleByName(1);
            ShowError::getError('warning', 'Запись с таким наименованием уже сущетсвует');
            return $this->getIdByName();
        } else {
            $model = $this->getById();
            $model->name = $this->name;
            $model->getSave('Запис изменена');
            return $this->getIdByName();
        }
    }

    public function setVisibleByName($vis = null){
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

    public static function getWh()
    {
        $hw_depart = new HwDepart();
        return self::find()->joinWith(['depart'])->where(['hw_wh.visible' => 1])->andWhere(['in', 'hw_depart', $hw_depart->getAccessWrite(1)])->all();
    }


    public static function getWhFull()
    {
        $hw_depart = new HwDepart();
        return self::find()->where(['visible' => 1])->all();
    }


    public static function getMenu(){
        $result = array();
        foreach (self::getWh() as $item){

            $result[] = ['label' =>  $item->depart->name.'. '.$item->name, 'url' => ['site/view' , 'wh' => $item->id], 'iconStyle' => 'far'];

        }
        return $result;
    }

}
