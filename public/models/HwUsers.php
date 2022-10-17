<?php

namespace app\models;

use Yii;

/**
 * Пользователи
 *
 * @property string username
 * @property string date_ct
 * @property string search
 *
 * @property int id
 * @property int status
 * @property int id_podr
 * @property int id_post
 * @property int id_org
 * @property int id_depart
 * @property int visible
 * @property int comment
 *
 *
 */

class HwUsers extends \yii\db\ActiveRecord
{

    const SCENARIO_CREATE = 'create';

    public $search; // запрос для поиска
    public $limit; // устанавливаем колчиество выводимой информации из базы

    public static function tableName()
    {
        return 'hw_users';
    }


    public function beforeFind()
    {
        $this->username = trim($this->username);

        parent::afterFind(); // TODO: Change the autogenerated stub
    }

    public function rules()
    {
        return [
            [['username', 'id_org', 'id_depart'], 'required'],
            [['username'], 'string', 'max' => 255],
            [['status', 'id_post', 'id_podr', 'id_depart', 'id_org', 'visible', 'date_ct'], 'safe'],
            [['comment'], 'string', 'max' => 500],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => 'ФИО',
            'id_post' => 'Должность',
            'id_org' => 'Организация',
            'id_podr' => 'Подразделение',
            'id_depart' => 'Отдел',
            'comment' => 'Комментарий',
        ];
    }

    public function getDepart()
    {
        return $this->hasOne(HwPodr::className(), ['id' => 'id_depart']);
    }


    public function getUsers(){
        $query = HwUsers::find()->orderBy(['date_ct' => SORT_DESC])->joinWith(['depart']);

		  
		  $limit = $this->limit ?? 2000; //лимит

        //вывод уволенных сотрудников
        if ($this->visible){
            $query = $query->where(['hw_users.visible' => 1]);
        }

        //вывод пользователей, по поиску
        if ($this->search){
            $query->orFilterWhere(['like', 'username', $this->search])
                ->orFilterWhere(['like', 'hw_podr.name', $this->search])
                ->orFilterWhere(['like', 'comment', $this->search]);
        }

        return $query->limit($limit)->all();
    }



    //Вывод ошибки при сохранени
    public function getSave($message = 'Запись добавлена'){
        if($this->save()){
            ShowError::getError('success', $message);
        }else{
            $error = '';
            foreach ($this->errors as $key => $value) {
                $error .= '<br>'.$key.': '.$value[0];
            }
            ShowError::getError('danger', 'Ошибка записи.'.$error);
        }
    }

    // Выводим по ФИО.
    public function getUsersByUsername(){
        if($this->existsUsername())
            return $this::find()->where(['username' => $this->username])->asArray()->one();
        return false;
    }

    // Проверка на существавание записи по ФИО
    public function existsUsername(){
        return self::find()->where(['username' => $this->username])->exists();
    }

    //текст сообшения для увеодомаления
    public function getMessageNotify(){
        $_users = $this->getUsersByUsername();

        if ($_users){
            $message = '';

            $message .= '<strong>'.$this->username.'.</strong> <small><br> Пользователь с таким ФИО уже существует';

            $podr = HwPodr::findOne($_users['id_podr'])->name;
            $message .= "<br><a  style='color:black' href='".\yii\helpers\Url::toRoute(['tehnic' , 'id_user' => $_users['id']])."'> $this->username . $podr .  </a>" ;

            return $message.'</small>';
        }

        return false;
    }


    //Новая информация по пользователю, если она отличается(подразделение, отдел)
    public function getNewInfoByUsername($org_name){
        $_depart = HwPodr::getDepartId($org_name, $this->username);
        return  isset($_depart->Result) ? $_depart->Result : null;
    }
}