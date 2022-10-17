<?php

namespace app\models;

use Yii;
use yii\bootstrap;
use yii\helpers\ArrayHelper;

Yii::$app->session->open();

/**
 *
 * @property string $username
 * @property string $login

 * @property int role
 * @property int status
 * @property int domain
 * @property int create_at
 *
 * @property int sett1
 * @property int sett2
 * @property int sett3
 * @property int sett4
 * @property int sett5
 * @property int sett6
 * @property int hw_depart
 *
 */

class Login  extends ModelInterface implements \yii\web\IdentityInterface
{
	//    public $id;
	const STATUS_ACTIVE = 10;
	const STATUS_DEACTIVATE = 50;
	public $rememberMe = false;

	public static function tableName()
	{
		return 'login';
	}


	public function rules()
	{
		return [
			[['role', 'domain', 'status'], 'integer'],
			[['username'], 'string', 'max' => 255],
			[['login'], 'string', 'max' => 32],
			//            [['domain'], 'string', 'max' => 50],
			[['sett1', 'sett2', 'sett3', 'sett4', 'sett5', 'sett6', 'hw_depart'], 'integer',]
		];
	}


	public static function findIdentity($id)
	{
		return static::findOne($id);
	}

	public static function findByIp($ip)
	{
		return Login::findOne(['ip' => $ip]);
	}

	public static function findByLogin($login)
	{
		return Login::findOne(['login' => $login]);
	}

	public function attributeLabels()
	{
		return [
			'sett1' => 'Тип техники - по умолчанию',
			'sett2' => 'Организация - по умолчанию',
			'sett3' => 'Склад - по умолчанию',
			'sett4' => 'Устройство - по умолчанию',
			'sett5' => 'Вид левого меню',
			'hw_depart' => 'Отдел в котором работает пользователь',
		];
	}

	/*
     * Вывод списка зарегестрированных пользователей
     * Только активнх
     */
	public static function getLoginList()
	{
		if (Login::find()->where(['status' => self::STATUS_ACTIVE])->select(['username'])->exists()) :
			return Login::find()->where(['status' => self::STATUS_ACTIVE])->orderBy(['username' => SORT_ASC])->all();
		endif;
		return false;
	}

	/*
     * Вывод списка зарегестрированных пользователей
     * Только активнх
     * SAP
     */
	public static function getLoginSap()
	{
		if (Login::find()->where(['visible' => 0])->andWhere(['depart' => 3])->select(['username'])->exists()) :
			return Login::find()->where(['visible' => 0])->andWhere(['depart' => 3])->orderBy(['username' => SORT_ASC])->all();
		endif;
		return false;
	}

	public function Username($id)
	{
		$model = Login::findOne(['id' => $id]);
		return $model->login;
	}

	public function Fio($id)
	{
		$model = Login::findOne(['id' => $id]);
		return isset($model->username) ? $model->username : '';
	}


	public static function getDomianList()
	{
		return [
			'1' => "СНХРС",
			'2' => "НХРС",
			'3' => "ЗСМиК",
			'4' => "Аудит-консалт",
		];
	}

	/**
	 * вывод настроек домена
	 * @param $domain
	 * @return array
	 */
	public static function getDomainSettings($domain)
	{

		$snhrs = ["10.224.177.30", "snhrs.ru"];
		$nhrs = ["10.224.100.1", "nhrs.ru"];
		$zsmik = ["10.224.200.1", "zsmik.com"];
		$consalt = ["10.224.90.1",  "a-consalt.ru"];

		switch ($domain) {
			case '1': //СНХРС
				return $snhrs;
				break;
			case '2': //НХРС
				return $nhrs;
				break;
			case '3': //ЗСМиК
				return $zsmik;
				break;
			case '4': //Аудит-Консалт
				return $consalt;
				break;
		}
	}



	public function addUser()
	{
		$model = new self();
		$settings = new HwSettings();

		$this->login = trim($this->login);
		if (!$login = Login::find()->where(['login' => $this->login])->one()) {
			$model->domain = 2;
			$model->hw_depart = 4;
			$model->login = $this->login;
			$model->status = $model::STATUS_ACTIVE;
			$model->create_at = strtotime('now');
			$model->username = 'ФИО';
			$model->role = 10;
			if ($model->getSave('Учетная запись добавлена')) {
				$settings->id_user = $model->id;
				$settings->addFirstSettings();
			}
		} else {
			$settings->id_user = $login->id;
			$settings->addFirstSettings();
			ShowError::getError('success', 'Учетная запись уже зарегестрирована в системе');
		}
	}


	/**
	 * @param $id
	 * @return string
	 * выводим почту
	 */
	public static function getLoginMail($id)
	{
		$model = self::findOne($id);
		return $model->login . "@" . self::getDomainSettings($model->domain)[1];
	}

	/**
	 * @param $attribute
	 * @return array
	 * Доступ к полям
	 */
	public function readonly($attribute)
	{
		$model = ['id_org', 'id_user', 'id_model', 'id_wh', 'status', 'location', 'serial', 'check_serial', 'act_num', 'date_admission', 'date_warranty', 'old_passport', 'comment'];

		if (Yii::$app->user->can('Admin'))
			return false;

		if (Yii::$app->user->can('User'))
			$model = ['id_org', 'id_user', 'id_model', 'id_wh', 'status', 'location', 'serial', 'check_serial', 'act_num', 'date_admission', 'date_warranty', 'old_passport', 'comment'];

		if (Yii::$app->user->can('IT') or Yii::$app->user->can('Service'))
			$model = ['id_org', 'id_user', 'id_model', 'id_wh',  'location', 'serial', 'check_serial', 'act_num', 'date_admission', 'date_warranty', 'old_passport', 'comment'];

		return $model ? (in_array($attribute, $model) ? true : false) : false;
	}

	/*
     * Проверка на наличие открытых заявок. Со статусом "В Работу"
     *
     * */
	public function validateLoginApp($id)
	{
		if ($id <> Yii::$app->user->id) {
			return App::find()
				->andWhere(['id_user' => $id])
				->andWhere(['status' => 1])
				->andWhere(['type' => null])
				->count();
		}
		return false;
	}

	public function getList()
	{
		return ArrayHelper::map(Login::find()->orderBy(['username' => SORT_ASC])->where(['visible' => 0])->all(), 'id', 'username');
	}


	/**
	 * @return int|string current user ID
	 */
	public function getId()
	{
		return $this->id;
	}

	/**
	 * @return string current user auth key
	 */
	public function getUsername()
	{
		return $this->username;
	}

	/**
	 * @inheritdoc
	 */
	public function getAuthKey()
	{
		return $this->authKey;
	}

	/**
	 * @inheritdoc
	 */
	public function validateAuthKey($authKey)
	{
		return $this->authKey === $authKey;
	}

	/**
	 * @inheritdoc
	 */
	public static function findIdentityByAccessToken($token, $type = null)
	{
		foreach (self::$users as $user) {
			if ($user['accessToken'] === $token) {
				return new static($user);
			}
		}

		return null;
	}
}
