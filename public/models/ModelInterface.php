<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\models;


use app\models\ShowError;

abstract class ModelInterface extends  \yii\db\ActiveRecord
{

    public $_field;
    public $_field_val;

    //Вывод ошибки при сохранени
    public function getSave($show = true, $message = 'Запись добавлена'){

        if($this->save()){
            if ($show)
                ShowError::getError('success', $message);
            $result = true;
        }else{
            $error = '';
            foreach ($this->errors as $key => $value) {
                $error .= '<br>'.$key.': '.$value[0];
            }
            if ($show)
                ShowError::getError('danger', 'Ошибка записи.'.$error);

            $result = false; $message = $error;

            echo "<pre>";
            print_r($this->errors);
            die();
        }

        return ['result' => $result, 'message' => $message, 'data' => $this];
    }


    public function existsId()
    {
        return self::find()->where(['id' => $this->id])->exists();
    }

    public function setVisibleById($vis = null){
        $model = self::findOne(['id' => $this->id]);
        $model->visible = $vis;
        $model->save();
    }

    public function deleteById($id)
    {
        $this->id = $id;

        if ($this->existsId()) {
            $this->setVisibleById();
        }
    }

    public function getById(){
        return self::findOne($this->id);
    }

    //Проверка на наличия записи
    public function existsName()
    {
        $query = self::find()->where(['name' => $this->name]);

        if ($this->_field):
            $model = $query->andWhere([$this->_field => $this->_field_val])->exists();
        else:
            $model = $query->exists();
        endif;

        return  $model;
    }

    public function getRecord(){
        $query = self::find()->where(['name' => $this->name]);

        if ($this->_field):
            $model = $query->andWhere([$this->_field => $this->_field_val])->one();
        else:
            $model = $query->one();
        endif;

        return  $model;
    }

    //Меняем видимость записи на противоположную
    public function setVisibleToogle()
    {
        $model = $this->getRecord();
        $this->visible = $this->visible ? null : 1;
        $this->save();
    }

    // Устанавливаем видимость записи как видимый
    public function visibleOn($model = null){
        $model = isset($model) ? $model : $this->getRecord();
        $model->visible = 1;
        $model->save();

        return $model->id;
    }

    // Устанавливаем видимость записи как скрытый
    public function visibleOff(){
        $model = $this->getRecord();
        $model->visible = null;
        $model->save();
    }

    public function getId(){
        return $this->id;
    }

    public function existsField($field, $value, $field2 = null, $value2 = null){
        return  $this::find()->where([$field => $value, $field2 => $value2])->one();
    }

    public function getSearchField($field, $val, $field2 = null, $val2 = null, $sort = null){
        $query = $this::find()->where([$field => $val]);

        if ($field2 and $val2)
            $query = $query->andWhere([$field2 => $val2]);

        if ($sort)
            $query = $query->orderBy([$sort => SORT_ASC]);

        return $query;
    }

    //выводим Все активные записи
    public function getAllListVisible($distinct = null)
    {
        if($distinct)
            return self::find()->select(['name'])->where(['visible' => 1])->orderBy(['name' => SORT_ASC])->distinct()->all();

        return self::find()->where(['visible' => 1])->all();
    }

    //вывлдим все записи
    public function getAllList($distinct = null)
    {
        if($distinct)
            return self::find()->select(['name'])->distinct()->all();

        return self::find()->all();
    }
}
