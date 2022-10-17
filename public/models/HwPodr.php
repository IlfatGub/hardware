<?php

namespace app\models;

use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/**
 * Отддел, управления, организации
 *
 * @property string name
 *
 * @property int id
 * @property int parent_id
 * @property int id_depart
 * @property int type
 * @property int visible
 *
 *
 *
 * $type
 * 1 - Организация
 * 2 - Упарвление
 * 3 - Отдел
 */
class HwPodr extends ModelInterface
{
    public $org;


    public static function tableName()
    {
        return 'hw_podr';
    }

    public function rules()
    {
        return [
            [['parent_id','id_depart', 'name'], 'string', 'max' => 255],
            [['type', 'visible'], 'integer'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'name',
        ];
    }


    public function setPodr($name, $type)
    {
        $this->name = $name;
        $this->type = $type;

        if ($this->existsName()) {
            return $this->getIdByName();
        } else {
            $model = new self();
            $model->name = $this->name;
            $model->type = $this->type;
            $model->save();

            return $this->getIdByName();
        }
    }

    public function existsName()
    {
        return self::find()->where(['name' => $this->name, 'type' => $this->type])->exists();
    }


    public function getIdByName()
    {
        $result = self::find()->where(['name' => $this->name, 'type' => $this->type])->one();
        return $result->id;
    }

    public static function getPodrAll(){
        return self::find()->all();
    }

    public static function getOrg()
    {
        return self::find()->where(['type' => 1])->all();
    }

    public static function getPodr()
    {
        return self::find()->where(['type' => 2])->all();
    }

    public static function getDepart()
    {
        return self::find()->where(['type' => 3])->all();
    }

    public static function getMenu(){
        $result = array();
        foreach (self::getOrg() as $item){
            $result[] = ['label' =>  $item->name, 'url' => ['site/org' , 'org' => $item->id], 'iconStyle' => 'far'];

        }
        return $result;
    }

    /*
     * Получаем АйДи отдела
     */
    public static function getDepartId($org, $fio = null){

        if(isset($fio)){
            $fio = str_replace(" ", "%20",$fio);

            $id = $name = $parent = null;

            $zsm =  self::urlByFio($org, $fio);

            if (isset($zsm->Result)){
                foreach ($zsm->Result as $item) {
                    $id = $item->ID;
                    $name = $item->subdivision;
                    $parent = $item->Parent_ID;
                }
                if($name){
                    $depart = new HwPodr();
                    $depart->id_depart = $id;
                    $depart->parent_id = $parent;
                    $depart->name = $name;
                    $depart->type = 3;

                    $depart->getId();
                }
                return $zsm;
            }else{
                return false;
            }
        }
        return false;

    }

    /*
 * Выводим АйДи ФИО
 */
    public function getId(){
        if ($this->existsId()){
            $upd = self::findOne(['id_depart' => $this->id_depart]);
            $upd->name = trim($this->name);
            $upd->parent_id = $this->parent_id;
            $upd->type = 3;

            $upd->save();

            return $upd->id;

        }else{
            $model = new self();
            $model->name = trim($this->name);
            $model->parent_id = $this->parent_id;
            $model->id_depart = $this->id_depart;
            $model->type = 3;

            $model->save();

            return $model->id;
        }
    }


    public function existsId(){
        return self::find()->where(['id_depart' => $this->id_depart])->exists();
    }

    /**
     * @param $org
     * @param null $id_depart
     * @return mixed
     * url API для обращения
     */
    public static function url($org, $id_depart = null){
        switch (mb_strtolower(trim($org))) {
            case 'зсмик': //Зсмик
                $url = "http://10.224.182.4/zsmik_zup/hs/SitDesk/?type=Структура&name=";
                $url_full = isset($id_depart) ? $url.$id_depart : $url."FULL";
                break;
            case 'нхрс': //НХРС
                $url = "http://10.224.100.11/nhrs_zup_work/hs/SitDesk/?type=Структура&name=";
                $url_full = isset($id_depart) ? $url.$id_depart : $url."FULL";
                break;
            case 'рмз': //РМЗ
                $url = "http://zsm-sms01.zsmik.com/rmz_uso/hs/SitDesk/?type=Структура&name=";
                $url_full = isset($id_depart) ? $url.$id_depart : $url."FULL";
                break;
            case "снхрс": //СНХРС
                $url = "http://10.224.182.2/zsmik_zup_hav/hs/SitDesk/?type=Структура&name=";
                $url_full = isset($id_depart) ? $url.$id_depart : $url."FULL";
                break;
            case "итц": //ИТЦ
                $url = "http://10.224.182.2/zsmik_zup_hav/hs/SitDesk/?type=Структура&name=";
                $url_full = isset($id_depart) ? $url.$id_depart : $url."FULL";
                break;
            case "консалт": //Консалт
                $url = "http://10.224.100.11/nhrs_zup_work/hs/SitDesk/?type=Структура&name=";
                $url_full = isset($id_depart) ? $url.$id_depart : $url."FULL";
                break;
        }

        $data = json_decode(self::curl_buh($url_full));

        return $data ? $data : null;
    }

    /**
     * @param $org
     * @param null $id_depart
     * @return mixed
     * url API для обращения
     */
    public static function urlByFio($org, $fio){
        switch (mb_strtolower(trim($org))) {
            case "зсмик": //Зсмик
                $url = "http://10.224.182.4/zsmik_zup/hs/SitDesk/?type=Подразделение&name=$fio";
                break;
            case "нхрс": //НХРС
                $url = "http://10.224.100.11/nhrs_zup_work/hs/SitDesk/?type=Подразделение&name=$fio";
                break;
            case "рмз": //РМЗ
                $url = "http://zsm-sms01.zsmik.com/rmz_uso/hs/SitDesk/?type=Подразделение&name=$fio";
                break;
            case "снхрс": //СНХРС
                $url = "http://10.224.182.4/zsmik_zup/hs/SitDesk/?type=Подразделение&name=$fio";
                break;
            case "итц": //ИТЦ
                $url = "";
                break;
            case "рэм": // РЭМ
                $url = "";
                break;
            case "консалт": //Консалт
                $url = "http://10.224.100.11/nhrs_zup_work/hs/SitDesk/?type=Подразделение&name=$fio";
                break;
        }

        return json_decode(self::curl_buh($url));
    }

    public static function curl_buh($url){
        $username = "sitdesk";
//        $password = "123456";
        $password = "mW]nUC0~YK*";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($ch, CURLOPT_USERPWD, $username . ":" . $password);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        return curl_exec($ch);
    }


    public function departByApi(){
        if ($this->id_depart){
            $actual_list = self::url(self::findOne($this->org)->name, $this->id_depart)->Result;
        }else{
            $actual_list = self::url(self::findOne($this->org)->name)->Result;
        }
        return $actual_list;
    }

    public function getDepartApiID(){
        return ArrayHelper::map($this->departByApi(), 'ID', 'ID');
    }

    public function getDepartApiName(){
        return ArrayHelper::map($this->departByApi(), 'ID', 'subdivision');
    }

    public function getDepartByBdID(){
        return HwPodr::find()->select(['id'])->where(['id_depart' => $this->getDepartApiID()])->column();
    }

    public function getDepartByBd(){
        return HwPodr::find()->where(['id_depart' => $this->getDepartApiID()])->all();
    }

    public function getUserByBdId(){
        return  HwUsers::find()->select(['id'])->where(['id_depart' => $this->getDepartByBdID()])->column();
    }

    public function getUserByBd(){
        return  HwUsers::find()->where(['id_depart' => $this->getDepartByBdID()])->andWhere(['is', 'visible', new \yii\db\Expression('null')])->all();
    }

    public function existsPodrByName(){
        return self::find()->andFilterWhere(['like', 'name', $this->name])->exists();
    }

    public function getPodrByName(){
        if ($this->existsPodrByName()){
            return self::find()->andFilterWhere(['like', 'name', $this->name])->all();
        }
        return false;
    }
}
