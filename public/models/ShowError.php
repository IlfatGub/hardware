<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * LoginForm is the model behind the login form.
 *
 * @property User|null $user This property is read-only.
 *
 */
class ShowError extends Model
{
    public $message;
    public $type;


    //вывод ошибки
    public static function getError($type, $message){
        self::message($type, $message);
    }

    /*
     * @return BootstrapNotify
     *
     * @var $message Сообшение для вывода
     * @var $model
     */
    public static function getSave($model, $message){
        try{
            if($model->save()){
                self::message('success',$message);
            }else{
                $error = '';
                foreach ($model->errors as $key => $value) {
                    $error .= '<br>'.$key.': '.$value[0];
                }
                self::message('danger','Ошибка записи. '.Yii::$app->controller->route.". ".$error);
            }
        }catch(\Exception $ex){
            self::message('danger', 'Ошибка');
        }
    }


    /*
     * @return BootstrapNotify
     *
     * @var $message Сообшение для вывода
     * @var $model Temp
     */
    public static function del($model, $message){
        try{
            if($model->delete()){
                self::message('success', $message);
            }else{
                $error = '';
                foreach ($model->errors as $key => $value) {
                    $error .= '<br>'.$key.': '.$value[0];
                }
                self::message('danger','Ошибка.'.$error);
            }
        }catch(\Exception $ex){
            self::message('danger', 'Ошибка');
        }
    }


    public static function message($type, $message){

        \Yii::$app->session->set(
            'message',
            [
                'type'      => $type,
                'message'   => $message,
                'delay'     => 5000,
                'placement_from'    => 'bottom',
                'placement_align'   => 'right',
            ]
        );
    }
}
