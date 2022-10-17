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
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;

/**
 * LoginForm is the model behind the login form.
 */
class Hardware extends Model
{

    public $ldap;
    public $server;
    public $type;

    const SITDESK_GET_PROBLEM_PARENT = "http://newdesk.zsmik.com/api/get-problem-parent";

    public function rules()
    {
        return [
            [['ldap'], 'string', 'min' => 4],
        ];
    }

    /*
     * ФИО
     * type = 1     -       выводи только Имени
     */
    public static function fio($fio, $type = null){
        if(isset($fio)){
            $var = explode(" ", trim($fio));
            if($type == 1){

                $name = array_key_exists(1, $var) ? $var[1] : '';
                $lastname = array_key_exists(0, $var) ? $var[0] : '';

                $text = $name.' '. mb_substr($lastname, 0, 1, 'UTF-8').'.';
            }else{
//                $text = $var[0];
//                print_r($var);
//                $text = array_key_exists(0, $var) ? $var[0] : ''.' '. array_key_exists(1, $var) ? $var[1] : '';
                $text = $fio;
            }
        }else{
            $text = $fio;
        }
        return $text;
    }
}
