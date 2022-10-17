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
class LoginForm extends Model
{
    public $username;
    public $password;
    public $domain;
    public $rememberMe = true;

    private $_user = false;


    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['username', 'password', 'domain'], 'required'],
            // rememberMe must be a boolean value
            ['rememberMe', 'boolean'],
            // password is validated by validatePassword()
//            ['password', 'validatePassword'],
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
//    public function validatePassword($attribute, $params)
//    {
//        if (!$this->hasErrors()) {
//            $user = $this->getUser();
//
//            if (!$user || !$user->validatePassword($this->password)) {
//                $this->addError($attribute, 'Incorrect username or password.');
//            }
//        }
//    }

    public function attributeLabels()
    {
        return [
            'rememberMe' => 'Запомнить меня',
            'username' => 'Логин',
            'password' => 'Пароль',
            'domain' => 'Организация',
        ];
    }
    /**
     * Авторизация через АД
     * при успешной авторизацци записываем в БД
     */
    public function login()
    {
        //Авторизация через АД
        $ad = new ActiveDirectory(['login' => $this->username, 'password' => $this->password, 'domain' => $this->domain]);
        if ($login = Login::findOne(['login' => $this->username])){
            if ($login->status == 10){
                if($ad->login()){
                    Yii::$app->user->login(Login::findByLogin($this->username), 0);
                    return true;
                }
            }else{
                ShowError::getError('danger', 'Login <br> Учетная запись заблокирована <br>');
            }
        }else{
            ShowError::getError('danger', 'Login <br> Учетная запись не зарегестрирована в системе <br>');
        }


        return false;
    }


    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    public function getUser()
    {
        if ($this->_user === false) {
            $this->_user = User::findByUsername($this->username);
        }

        return $this->_user;
    }
}
