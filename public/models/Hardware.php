<?php

namespace app\models;

use app\components\template\AppInfoTemplate;
use app\controllers\NameCaseLib\Library\NCLNameCaseRu;
use app\modules\admin\models\AppComment;
use app\modules\admin\models\AppContent;
use app\modules\admin\models\AppFiles;
use app\modules\admin\models\FioCase;
use app\modules\admin\models\Login;
use app\modules\admin\models\MyDate;
use app\modules\admin\models\Podr;
use Yii;
use yii\base\Model;
use app\modules\admin\models\App;
use PhpOffice\PhpWord\Element\Table;
use PhpOffice\PhpWord\TemplateProcessor;
use yii\helpers\ArrayHelper;

/**
 * Hardware
 *
 * @property string fio
 *
 * @property int type
 *
 */
class Hardware extends Model
{

    public $type;
    public $fio;

    public function rules()
    {
        return [
            [['ldap'], 'string', 'min' => 4],
        ];
    }

    /*
     * ФИО
     * type = 1 - выводи только Имени
     */
    public static function fio($fio, $type = null){
        if(isset($fio)){
            $var = explode(" ", trim($fio));
            if($type == 1) {
                $name = array_key_exists(1, $var) ? $var[1] : '';
                $lastname = mb_substr(array_key_exists(0, $var) ? $var[0] : '', 0, 1, 'UTF-8');
                $lastname_2 = mb_substr(array_key_exists(0, $var) ? $var[2] : '', 0, 1, 'UTF-8');

                $text = $name . ' ' . $lastname . '.';
            }elseif($type == 2){
                $name = mb_substr(array_key_exists(1, $var) ? $var[1] : '', 0, 1, 'UTF-8');
                $lastname = array_key_exists(0, $var) ? $var[0] : '';
                $lastname_2 = mb_substr(array_key_exists(0, $var) ? $var[2] : '', 0, 1, 'UTF-8');

                $text = $lastname . ' ' . $name . '.' . $lastname_2. '.';
            }else{
                $text = $fio;
            }
        }else{
            $text = $fio;
        }
        return $text;
    }

    /**
     * Выводим ФИО авторизованного пользователя
     */
    public static function getUsername(){
        if (Yii::$app->user->identity){
            echo self::fio(Yii::$app->user->identity->username, 1);
        }
    }


    public static function accessAdmin(){
        return Yii::$app->user->can('SuperAdmin') ? true : false;
    }

    public function accessDownloadDocument(){
        return [19];
    }

    public static function getOptionUrl($model, $url_options = array(), $field, $type = null)
    {
        $_model = $type ? $model : array_unique(ArrayHelper::map($model, 'id', 'value'));

        asort($_model);

        $_filter_label = '';

        $remark_filter = isset($url_options['specification']) ? $url_options['specification'] : null;

        if (!$remark_filter) {
            $remark_filter = [1 => 1];
        } else {
            unset($remark_filter[0]);
        }
        $d = $remark_filter;

        $select = " <select class=\"form-control form-control-sm\" onchange=\"location = this.value;\">";
        $select .= "<option></option>";

        foreach ($_model as $key => $item):
            $item_value = $type ? $key : $item;
            $color = '';
            if (array_key_exists($field,$remark_filter)) {
                if (is_array($remark_filter[$field])) { //задаем цвет элементу если выбран как фильтр
                    foreach ($remark_filter[$field] as $_key => $get_item) {
                        if ($get_item == $item_value) {
                            $color = 'portal-bg-light-red';
                            $d[$field][$_key] = null;
                            if ($_filter_label <> $item)
                                echo "<a href='" . \yii\helpers\Url::toRoute(['specification', 'specification' => $d,  'id_device' => $_GET['id_device']]) . "'><small>$item</small></a> / ";
                            $d[$field][$_key] = $item_value;
                            $_filter_label = $item;
                        }
                    }
                }
            }else{
                $d[$field] = '';
            }

            if ($remark_filter)
                $select .= "<option class='$color' value=" . \yii\helpers\Url::toRoute(['specification', 'specification' => array_merge($remark_filter, [$field => self::add_element($d[$field], $item_value)]), 'id_device' => $_GET['id_device']]) . ">" . $item . "</option>";
        endforeach;

        $select .= "</select>";
        return $select;
    }

    // добавляем элемент в GET запрос по требованиям фильтра
    public static function add_element($arr_item , $item){
        $array = array();

        if (!is_array($arr_item)) {
            if ($arr_item) {
                $val = $arr_item;
                $project_item = array();
                $array[] = $val;
                $array[] = $item;
            }else{
                $array[]= $item;
            }
        }else{
            $array = $arr_item;
            $array[]= $item;
        }

        return $array;
    }

    public static function actLink($id_user, $type = null){
        $user = \app\models\HwUsers::findOne($id_user);

        $fio = $user->username;
        $org_id = $user->id_org;

        switch ($org_id){
            case 1: $org_name = "ООО \"Завод строительных материалов и конструкций"; break;
            case 20: $org_name = "ООО \"Нефтехимремстрой"; break;
            case 57: $org_name = "ООО \"РемЭнергоМонтаж"; break;
            case 4: $org_name = "АО \"Салаватнефтехимремстрой"; break;
        }

        $user_tehnic = \app\models\HwTehnic::find()
            ->joinWith(['typeDevice', 'model'])
            ->where(['id_user' => $id_user])->all();

        $styleCell = array('valign'=>'center');

        $table = new Table(array('borderSize' => 0, 'borderColor' => 'black' ,'borderTopSize' => 1));
        foreach ($user_tehnic as $item) {
            $ram = new HwTehnicRam(['id_tehnic' => $item->id]);

            $device_name  = $item->typeDevice->name;
            $old_pass = isset($item->old_passport) ? PHP_EOL.'('.$item->old_passport.')' : '';

            //проверкат на дополнительное оборудование
            if ($ram->existsRam()){
                $_rams = $ram->getRamByTehnic();
                $device_name .= ".";
                foreach ($_rams as $_ram) {
                    $device_name .= ' + '.$_ram->name.'';
                }
            }

            $table->addRow();
            $table->addCell(800)->addText(\app\models\HwTehnic::getPassport($item->id).$old_pass);
            $table->addCell(2500)->addText($device_name);
            $table->addCell(3700)->addText($item->model->name);
            $table->addCell(2300)->addText($item->serial);
            $table->addCell(1500)->addText(date('d.m.Y', $item->date_upd));


        }

        $file= 'act/Act_'.$fio.'.docx';

        $templateWord = new TemplateProcessor('template_zsmik.docx');
        $templateWord->setValue('fio', $fio);
        $templateWord->setValue('date', date('d-m-Y'));
        $templateWord->setComplexBlock('passport', $table);
        $templateWord->setValue('org_name', $org_name);
        $templateWord->setValue('fio_replace', Hardware::fio($fio, 2));
        $templateWord->saveAs($file);

        if (!$type){
            echo "<a href='$file'><i class=\"ml-2 fas fa-download\"></i> Акт</a>";
        }else{
            return $file;
        }

    }



    public static function replaceDepartName($depart_name){
        $array_replace_do = ['ОП "Салаватский" ООО "НХРС"'];
        $array_replace_to = ['Салават'];
        return str_replace($array_replace_do, $array_replace_to, $depart_name);
    }


    /**
     * @param $depar_name
     * Приводи название отдела в читабльеный, нормальный вид
     */
    public static function getDepartNormalView($depart_name){
        $departs = explode('.', $depart_name);
        if (is_array($departs)){
            $actual_name = array_pop($departs);
            return [ $actual_name, implode(". ", $departs)];
        }
    }

}
