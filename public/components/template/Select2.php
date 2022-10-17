<?php


/**
 * $type
 */

namespace app\components\template;


use yii\base\Widget;

class Select2 extends Widget
{
    public $model;
    public $data;
    public $name;
    public $attribute;
    public $form;
    public $disable;
    public $multiple;
    public $label;
    public $id;
    public $readonly;



    public function init()
    {
        parent::init();
        if ($this->model === null) {
            $this->model = 0;
        }
        if ($this->name === null) {
            $this->name = 1;
        }
        if ($this->data === null) {
            $this->data = 1;
        }
        if ($this->attribute === null) {
            $this->attribute = 1;
        }
        if ($this->form === null) {
            $this->form = 1;
        }
        if ($this->disable === null) {
            $this->disable = false;
        }
        if ($this->multiple === null) {
            $this->multiple = false;
        }
        if ($this->readonly === null) {
            $this->readonly = false;
        }
        if ($this->label === null) {
            $this->label = true;
        }
        if ($this->id === null) {
            $this->id = 0;
        }
    }


    public function run()
    {
        return $this->render('select2',[
            'model' => $this->model,
            'name' => $this->name,
            'data' => $this->data,
            'attribute' => $this->attribute,
            'form' => $this->form,
            'disable' => $this->disable,
            'multiple' => $this->multiple,
            'label' => $this->label,
            'id' => $this->id,
            'readonly' => $this->readonly,
        ]);
    }
}