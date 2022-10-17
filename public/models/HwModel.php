<?php

namespace app\models;

use yii\helpers\Html;

/**
 * Модели
 *
 * @property string name
 * @property string vendor
 *
 * @property int id
 * @property int type
 * @property int visible
 *
 */

class HwModel extends ModelInterface
{

    public static function tableName()
    {
        return 'hw_model';
    }

    public function rules()
    {
        return [
            [['name', 'vendor', 'type'], 'required'],
            [['name'], 'string', 'max' => 255],
            [['vendor'], 'string', 'max' => 100],
            [['type', 'visible'], 'integer'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Наименование техники',
            'vendor' => 'Производитель',
            'type' => 'Тип техники',
        ];
    }

    public function getSpecification()
    {
        return $this->hasOne(HwSpeceficModel::className(), ['id_model' => 'id']);
    }

    public function getSpecifications()
    {
        return $this->hasMany(HwSpeceficModel::className(), ['id_model' => 'id']);
    }

    public function getTypeDevice()
    {
        return $this->hasOne(HwDeviceType::className(), ['id' => 'type']);
    }

    public function setModel($upd = null)
    {
        if (!isset($upd)){
            $this->addWh();
        }else{
            $this->updWh();
        }
    }




    public function addWh() {
        if ($this->existsName()) {
            $this->setVisibleByName(1);
            ShowError::getError('success', 'запись существует');
            return $this->getIdByName();
        } else {
            $model = new self();
            $model->name = $this->name;
            $model->type = $this->type;
            $model->vendor = $this->vendor;
            $model->getSave();
        }
    }

    public function updWh(){
        if ($this->existsName()) {
//            $this->setVisibleByName(1);
            ShowError::getError('warning', 'Запись с таким наименованием уже сущетсвует');
//            return $this->getIdByName();
        } else {
            $model = $this->getById();
            $model->name = $this->name;
            $model->type = $this->type;
            $model->getSave();
        }
    }


    public function setVisibleByName($vis = null){
        $model = self::findOne(['name' => $this->name, 'type' => $this->type]);
        $model->visible = $vis;
        $model->save();
    }

    public function existsName()
    {
        return self::find()->where(['name' => $this->name, 'type' => $this->type])->exists();
    }


    public function getIdByName()
    {
        $result = self::find()->where(['name' => $this->name])->one();
        return $result->id;
    }


    public function getSepcificationQuery($id_device){
        return HwModel::find()->select('hw_specification_model.id_model as id_model')->where(['hw_specification_model.id_device' => $id_device])->joinWith(['specifications']);
    }

    public static function getTypeList(){
        return [
            '1' => 'Компьютер',
            '2' => 'Смартфон',
            '3' => 'Телефон',
            '4' => 'МФУ',
            '5' => 'Сканер',
            
            '6' => 'Принтер'
        ];
    }

    public static function getModel()
    {
        return self::find()->joinWith(['typeDevice'])->andWhere(['hw_model.visible' => 1])->orderBy(['name' =>SORT_ASC])->joinWith('specification')->all();
    }


    public function getDeviceType(){
        return $this::findOne($this->id)->type;
    }

    public function getDeviceGroup(){
        $dev_id = $this->getDeviceType();
        return HwDeviceType::findOne($dev_id)->category;
    }

    public function getModelName(){
        return $this::findOne($this->id)->name;
    }
    
}
