<?php

namespace app\models;

use Yii;

/**
 * Пользователи
 *
 * @property int id
 * @property int id_tehnic
 * @property int id_user
 * @property int id_editor
 * @property int id_depart
 * @property int id_podr
 * @property int id_org
 * @property int id_wh
 * @property int date
 * @property int status
 * @property int status_tehnic
 * @property int id_model
 * @property int hw_depart
 * @property int date_warranty
 * @property int date_admission
 * @property int balance
 * @property string act_num
 * @property string comment
 * @property string nomen
 * @property string location
 * @property string serial
 */

class HwStory extends ModelInterface
{

    const SCENARIO_CREATE = 'create';

    const STATUS_CREATE = 1;
    const STATUS_MOVE = 2;
    const STATUS_WH = 3;
    const STATUS_UPDATE = 4;
    const STATUS_CHANGE_STATUS = 5;
    const STATUS_ADD_RAM = 6;
    const STATUS_DELL_RAM = 7;

    public static function tableName()
    {
        return 'hw_story';
    }

    public function rules()
    {
        return [
            [['id_tehnic', 'id_editor'], 'required'],
            [['id_tehnic', 'id_user', 'id_editor', 'id_depart', 'id_podr', 'id_org', 'id_wh', 'date' ,'status', 'hw_depart', 'id_model', 'balance'], 'integer'],
            [['comment', 'serial','act_num','nomen'], 'string'],
            [['date_warranty', 'date_admission'], 'safe'],
        ];
    }


    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'hw_depart' => 'Принадлежность к отделу',
        ];
    }




    public function getWh()
    {
        return $this->hasOne(HwWh::className(), ['id' => 'id_wh']);
    }

    public function getOrg()
    {
        return $this->hasOne(HwPodr::className(), ['id' => 'id_org']);
    }

    public function getDepart()
    {
        return $this->hasOne(HwDepart::className(), ['id' => 'hw_depart']);
    }


    public function getUser()
    {
        return $this->hasOne(HwUsers::className(), ['id' => 'id_user']);
    }

    public function getModel()
    {
        return $this->hasOne(HwModel::className(), ['id' => 'id_model']);
    }

    public function getTypeDevice()
    {
        return $this->hasOne(HwDeviceType::className(), ['id' => 'type']);
    }

    public function getHwTehnicStatus()
    {
        return $this->hasOne(HwTehnicStatus::className(), ['id' => 'status']);
    }

    public function getRam()
    {
        return $this->hasOne(HwTehnicRam::className(), ['id' => 'id_tehnic']);
    }

    public static function getPassport($id)
    {
        return str_pad($id, 4, '0', STR_PAD_LEFT);
    }

    public function getTehnic()
    {
        return $this->hasOne(HwTehnic::className(), ['id' => 'id_tehnic']);
    }
    public function getEditor()
    {
        return $this->hasOne(Login::className(), ['id' => 'id_editor']);
    }



    public static function getStatus(){
        return [
            '1' => 'Новый',
            '2' => 'Перезакреплен',
            '3' => 'Перемещен на склад',
            '4' => 'Обновлен',
            '5' => 'Изменен статус',
            '6' => 'Добавлено комплектующее',
            '7' => 'Удалено комплектующее',
        ];
    }

    public static function getStatusColor(){
        return [
            '1' => 'hw-bg-light-green',

            '2' => 'hw-bg-light-blue',
            '3' => 'hw-bg-light-yellow',
            '4' => 'hw-bg-light-red',
            '5' => 'hw-bg-light-red',
            '6' => 'hw-bg-light-red',
            '7' => 'hw-bg-light-red',
        ];
    }

    /**
     * @param $tehnic HwTehnic
     */
    public static function addStory($tehnic){

        try {
            $model = new self();
            $model->id_tehnic = $tehnic->id;
            $model->id_wh = $tehnic->id_wh;
            $model->id_editor = Yii::$app->user->id;
            $model->date = strtotime("now");
            $model->status = $tehnic->status_tehnic;
            $model->status_tehnic = $tehnic->status;
            $model->location = $tehnic->location;
            $model->date_warranty = $tehnic->date_warranty;
            $model->date_admission = $tehnic->date_admission;
            $model->act_num = $tehnic->act_num;
            $model->comment = $tehnic->comment;
            $model->nomen = $tehnic->nomen;
            $model->balance = $tehnic->balance;
            $model->serial = $tehnic->serial;
            $model->id_model = $tehnic->id_model;
            $model->hw_depart = Yii::$app->user->identity->hw_depart;
            if ($tehnic->id_user){
                $_user = HwUsers::findOne($tehnic->id_user);
                $model->id_user = $tehnic->id_user;
                $model->id_org = $_user->id_org;
                $model->id_podr = $_user->id_podr;
                $model->id_depart = $_user->id_depart;
            }
            $model->getSave();
        } catch (\Exception $ex) {
            echo "<pre>"; print_r( $ex->getMessage() ); die();
        }


    }


}
