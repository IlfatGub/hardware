<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * Работа с ActiveDirectry
 *
 */



class ActiveDirectory extends Model
{
    const  LDAP_PORT = "389";

    public $password;
    public $login;
    public $domain;
    public $access;


    //Список Доменов
    public static function getDomain(){
        return [
            '3' => 'ЗСМиК',
            '1' => 'СНХРС',
            '2' => 'НХРС',
            '4' => 'Аудит-Консалт',
        ];
    }


    //Список Доменов
    public static function getDomainPrefix(){
        return [
            '3' => '@zsmik.com',
            '1' => '@snhrs.ru',
            '2' => '@nhrs.ru',
            '4' => '@a-consalt.ru',
        ];
    }

    /**
     * Получаем конфигуррацю сервера по Названию домена
     * 1 - СНХРС, 2 - ЗСМиК....
     * @param $srv
     */
    public function getDomainConfig($domain){
        $srvConfig = '';
        $srv_password = "321qweR";

        $snhrs = [
            'ip' => '10.224.177.30',
            'user' => 'www_ldap@snhrs.ru',
            'pass' => $srv_password,
            'dc' => 'DC=snhrs,DC=ru',
            'mail' => 'snhrs.ru'
        ];

        $nhrs = [
            'ip' => '10.224.100.1',
            'user' => 'www_ldap@nhrs.ru',
            'pass' => $srv_password,
            'dc' => 'DC=nhrs,DC=ruu',
            'mail' => 'nhrs.ru'
        ];

        $zsmik = [
            'ip' => '10.224.200.1',
            'user' => 'www_ldap@zsmik.com',
            'pass' => $srv_password,
            'dc' => 'DC=zsmik,DC=com',
            'mail' => 'zsmik.com'
        ];

        $consalt = [
            'ip' => '10.224.90.1',
            'user' => 'www_ldap@a-consalt.ru',
            'pass' => $srv_password,
            'dc' => 'DC=consalt,DC=ru',
            'mail' => 'a-consalt.ru'
        ];
        switch ($domain){
            case 1://СНХРС
                $srvConfig = $snhrs;
                break;
            case 2://НХРС
                $srvConfig = $nhrs;
                break;
            case 3://ЗСМиК
                $srvConfig = $zsmik;
                break;
            case 4://Аудит-Консалт
                $srvConfig = $consalt;
                break;
        }

        return $srvConfig;
    }

    //Коннектимся к АД
    private static function connect($host){
        $ldap = ldap_connect($host, self::LDAP_PORT) or die("Cant connect to LDAP Server");
        ldap_set_option($ldap, LDAP_OPT_PROTOCOL_VERSION, 3);
        ldap_set_option($ldap, LDAP_OPT_REFERRALS, 0);
        return $ldap;
    }

    /**
     * Авторизация через АД.
     */
    public function Login(){
        //Получаем конфигурацию домена
        $domainConfig = self::getDomainConfig(Login::findOne(['login' => $this->login])->domain);
        //Привеодим учетку в нормальный вид
        $mail = $this->login.'@'.$domainConfig['mail'];
        //Коннект к АД
        $ldap = self::connect($domainConfig['ip']);
        try{
            if (ldap_bind($ldap, $mail, $this->password)) {
                ldap_unbind($ldap);
                return true;
            } else {
                self::denyPassword();
                return false;
            }
        }catch(\Exception $ex){
            ShowError::getError('danger', 'ActiveDirectoryModel. Login <br> Ошибка входа. Логин или пароль не верны. <br>'.$ex->getMessage());
        }

        return false;
    }

    /**
     * выводи информации о пользователе
     */
    public function LoginInfo(){
        return self::getSerach();
    }

    /*
     * Поиск пользователей и групп. Вывод соответствующи параметров
     *
     * @param string $srv
     * @param array $search
     * @param string $srv_login
     * @param string $srv_password
     * @param string $dn
     * @param integer $type  Тип запроса. 1 = Поиск по пользователям. 2 = Поиск по группам
     *
     * @return array
     */
    public function setSearch($srv, $search, $srv_login, $srv_password, $dn, $type = 1){
        $a = array();

        $ldap = self::connect($srv);

        if ($type == 1) {
            $filter = "(&(objectCategory=user)(|(cn=".trim($search).")(sAMAccountName=".trim($search).")))";   //тип запроса, поиск по пользоватлям
            $attr = array("cn", "mail", "title", "department", "description", "sAMAccountName", "ou", "memberof"); // поля которые будут возвращены. Пустой массив, выведет все поля
        }else{
            $filter = "(&(objectCategory=group)(cn=".trim(preg_replace('/\s/', '', $search))."*)(!(userAccountControl:1.2.840.113556.1.4.803:=2)))"; //тип запроса, поиск по группам
            $attr = array("cn", "description", "member"); // поля которые будут возвращены. Пустой массив, выведет все поля
        }

        ldap_bind($ldap, $srv_login, $srv_password);
        $result = ldap_search($ldap, $dn, $filter, $attr);
        $result_entries = ldap_get_entries($ldap, $result);

        ldap_unbind($ldap); //Закрываем сесиию

        $result = array_merge($a, $result_entries);
//        echo "<pre>";
//        print_r($result); die();
        return $result;

    }

    /*
     * Поиск в ActiveDirectory доступа
     */
    public function getAccessSerach()
    {
        //Получаем конфигруацию домена
        $domainConfig = self::getDomainConfig();

        //Поиск по АД
        $result = self::setSearch($domainConfig['ip'], $this->access, $domainConfig['user'], $domainConfig['pass'], $domainConfig['dc'], 2);

        //Если поиск ничего не вернул, возвращаем false
        if($result['count'] == 0){
            return false;
        }

        //Выводим информацию о Доступе
        $result = self::getAccessInfo($result);

        return (object)$result;
    }

    /**
     * @return mixed
     * Выводим описание доступа
     */
    public function getAccessDescription(){
        return $this->getAccessSerach()->description;
    }



    /*
     * Поиск в ActiveDirectory пользователя
     */
    public function getSerach()
    {
        //Получаем конфигруацию домена
        $domainConfig = self::getDomainConfig();

        //Поиск по АД
        $result = self::setSearch($domainConfig['ip'], $this->login, $domainConfig['user'], $domainConfig['pass'], $domainConfig['dc']);
//        $result = self::setSearch($domainConfig['ip'], '01mon', $domainConfig['user'], $domainConfig['pass'], $domainConfig['dc']);

        //Если поиск ничего не вернул, возвращаем false
        if($result['count'] == 0){
            return false;
        }

        //Приводим текст в читабельный вид
        $result = $this->getNoramlUserInfo($result);

        return (object)$result;
    }


    /**
     * Выводим информацию о Доступе
     */
    public function getAccessInfo($text){
        $result = [
            'description' => isset($text[0]['description'][0]) ? $text[0]['description'][0] : '',
        ];

        return $result;
    }


    /**
     * Приводим в нормальный вид строу содержащую информацю и отделе
     */
    public static function getNormalDepartInfo($text){
        $depart = '';
        //Приводим в нормальный вид Отдел.
        if($text){
            //создаем массив из строки
            $dn = explode(',', $text);
            foreach ($dn as $item){
                if(strpos($item , 'OU=') !== false){
                    $dep[] = str_replace(['OU=', '2 ZSMIK'], '', $item);
                }
            }
            foreach (array_reverse ($dep) as $item){
                if($item)
                    $depart .= $item.'. ';
            }
        }
        return $depart;
    }

    /**
     * Приводим в нормальный вид строу содержащую информацю и доступах
     */
    public static function getNormalAccessInfo($text){
        $access = array();
        if(isset($text)){
            foreach ($text as $item) {
                $memberof = explode(',', $item);
                $memberof = str_replace('CN=', '', array_shift($memberof));
                $access[] = $memberof;
            }
        }

        //Убираем первый элемент, он содержит колчиество доступов
        array_shift($access);

        return $access;
    }

    /**
     * Приводим в нормальный ввид информаицю полученную после поиск в Active Directory
     */
    public function getNoramlUserInfo($text){
        //Приводим в нормальный вид Доступы
        if(isset($text[0]['memberof'])){
            $access = self::getNormalAccessInfo($text[0]['memberof']);
        }

        //Приводим в нормальный вид Отдел.
        $depart = self::getNormalDepartInfo($text[0]['dn']);

        //Описание пользователя. Обычно там должность
        $descripion = isset($text[0]['description'][0]) ? $text[0]['description'][0] : '';

        $result = [
            'username' => $text[0]['cn'][0],
            'title' => isset($text[0]['title'][0]) ? $text[0]['title'][0] : $descripion,
            'login' => $text[0]['samaccountname'][0],
            'mail' => isset($text[0]['mail'][0]) ?  $text[0]['mail'][0] : null,
            'depart' => $depart,
            'domain' => $this->domain,
            'access' => isset($access) ? $this->validateAccess($access) : '',
        ];

        return $result;
    }

    /*
     * Оставляем из доступов только необходимые.
     */
    public function validateAccess($access){
        if (isset($access)){
            $result = array();
            $text = ['Access', '1c', 'internet'];
            for ($i=0; $i < count($access); $i++) {
                $pos = strpos(mb_strtolower($access[$i]), 'access');
                $pos1 = strpos(mb_strtolower($access[$i]), '1c');
                $pos2 = strpos(mb_strtolower($access[$i]), 'internet');
//                echo mb_strtolower($access[$i]).' - '.'access '.$pos .'<br>';
                if($pos !== false or $pos1 !== false or $pos2 !== false) {
                    $result[] = $access[$i];
                }
            }
            return $result;
        }

    }

    public function denyAccess(){
        Yii::$app->session->set('error', 'У вас нет доступа к порталу');
        Yii::$app->user->logout(false);
    }

    public static function denyPassword(){
        ShowError::getError('danger', 'Login <br> Ваш логин или пароль – не верны <br>');

//        Yii::$app->session->set('error', 'Ваш логин или пароль – не верны');
//        Yii::$app->user->logout(false);
    }


}
