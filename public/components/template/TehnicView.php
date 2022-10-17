<?php


/**
 * $type
 */

namespace app\components\template;


/**
 * Шаблон таблицы вывода
 *
 * @var $model HwTehnic
 *
 * @property string title
 * @property string color
 * @property string re
 * @property int type
 *
 *
 *
 *
 *
 *
 * Поле. Возможность выводить/ не выводить
 * @property int field_serial
 * @property int field_wh
 * @property int field_date
 * @property int field_fio
 * @property int field_balance
 * @property int field_nomen
 * @property int field_id
 * @property int field_id_org
 * @property array serial_array
 */

use app\models\HwSettings;
use app\models\HwTehnic;
use yii\base\Widget;
use yii\helpers\ArrayHelper;

class TehnicView extends Widget
{
    public $model;
    public $title;
    public $color;
    public $re;
    public $users_list;
    public $type;
    public $serial_array;

    public $field_serial;
    public $field_wh;
    public $field_date;
    public $field_fio;
    public $field_nomen;
    public $field_balance;
    public $field_id;
    public $field_id_org;

    public function init()
    {
        parent::init();
        if ($this->model === null)
            $this->model = 0;

        if ($this->users_list === null)
            $this->users_list = 0;

        if ($this->title === null)
            $this->title = 0;

        if ($this->color === null)
            $this->color = 'hw-bg-light-blue';

        if ($this->re === null)
            $this->re = false;

        if ($this->type === null)
            $this->type = false;

        if ($this->field_date === null)
            $this->field_date = true;

        if ($this->field_serial === null)
            $this->field_serial = true;

        if ($this->field_wh === null)
            $this->field_wh = true;

        if ($this->field_fio === null)
            $this->field_fio = true;

        if ($this->field_nomen === null)
            $this->field_nomen = false;

        if ($this->field_balance === null)
            $this->field_balance = false;

        if ($this->field_id === null)
            $this->field_id = true;

        if ($this->field_id_org === null)
            $this->field_id_org = true;

        if ($this->serial_array === null)
            $this->serial_array = null;

    }


    public function run()
    {
        $field = new HwSettings();

        return $this->render('tehnicView',[
            'model' => $this->model,
            'title' => $this->title,
            'color' => $this->color,
            'users_list' => $this->users_list,
            'field_user' => ArrayHelper::map($field->getTehnicUserField(),'tb3','tb3'),
            'field_list' => $field->getTehnicTableField(),
            're' => $this->re,
            'type' => $this->type,
            'field_serial' => $this->field_serial,
            'field_wh' => $this->field_wh,
            'field_date' => $this->field_date,
            'field_fio' => $this->field_fio,
            'field_balance' => $this->field_balance,
            'field_nomen' => $this->field_nomen,
            'field_id' => $this->field_id,
            'field_id_org' => $this->field_id_org,
            'serial_array' => $this->serial_array,
        ]);
    }
}