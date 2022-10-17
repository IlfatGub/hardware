<?php

    namespace app\models;

    use Yii;
    use yii\helpers\ArrayHelper;

    /**
     * Отделы.
     * Пользователи которые имеют досуп к складм определенного отдела
     *
     * @property string name
     *
     * @property int id
     * @property int id_user
     * @property int id_depart
     * @property int type
     * @property int visible
     *
     * @property int access_read            Доступ на чтение
     * @property int access_write           Доступ на редактирование
     * @property int access_search          Доступ на посик
     *
     *
     * $type Тип доступа пользвателя для опредленного отедла
     * $type = 1 - доступ предоставлен
     * $type = 100 -  Название отдела
     */
    class HwDepart extends ModelInterface
    {

        const TYPE_PARENT_DEPART = 100;
        const TYPE_ACCESS = 1;

        public static function tableName()
        {
            return 'hw_depart';
        }

        public function rules()
        {
            return [
                [['name'], 'required'],
                [['name'], 'string', 'max' => 255],
                [['id_user', 'type', 'visible', 'access_read', 'access_write', 'access_search'], 'integer'],
            ];
        }

        public function attributeLabels()
        {
            return [
                'id' => 'ID',
                'name' => 'Наименование отдела',
                'id_user' => 'Пользователь',
                'id_depart' => 'Пользователь',
                'type' => 'Тип записи',
                'visible' => 'Видимоость',
                'access_read' => 'На чтение',
                'access_write' => 'На запись',
                'access_search' => 'Поиск',
            ];
        }


        public function addDepart()
        {
            try {

                $this->_field = 'type';
                $this->_field_val = self::TYPE_PARENT_DEPART;

                if ($this->existsName()) {
                    ShowError::getError('warning', 'Наименование уже существует. Активировано');
                    return $this->visibleOn();
                } else {
//                echo $this->name; echo "<br>";
//                echo "<pre>"; print_r($this ); die();

                    $model = new self();
                    $model->name = trim($this->name);
                    $model->type = self::TYPE_PARENT_DEPART;
                    $model->getSave();
                    return $model->id;
                }
            } catch (\Exception $ex) {
                ShowError::getError('danger', $ex->getMessage());
            }
        }


        public function addUser()
        {
            if ($this->id_user) {
                try {
                    $this->_field = 'type';
                    $this->_field_val = self::TYPE_PARENT_DEPART;

                    if ($record = $this->existsField('id_depart', $this->id_depart, 'id_user', $this->id_user)) {
                        ShowError::getError('warning', 'Пользователь уже добавлен. Активирован');
                        return $this->visibleOn($record);
                    } else {
                        $model = new self();
                        $model->name = trim($this->name);
                        $model->id_user = $this->id_user;
                        $model->id_depart = $this->id_depart;
                        $model->type = self::TYPE_ACCESS;
                        $model->visible = 1;
                        $model->getSave();
                    }
                } catch (\Exception $ex) {
                    ShowError::getError('danger', $ex->getMessage());
                }
            }else{
                ShowError::getError('warning', 'Пользователь не указан');
            }

        }

        //Доступ для чтения
        public function getAccessAct($map = null){
            $model = self::getSearchField('access_act',  1 ,'type', 100)->all();

            return $map ? ArrayHelper::map($model, 'id', 'id') : $model;
        }

        //Доступ для чтения и записи
        public function getAccessReadAndWrite($map = null){
            $_read = self::getAccessRead($map);
            $_write = self::getAccessWrite($map);

            return  array_unique(array_merge($_read, $_write));
        }

        //Доступ для чтения
        public function getAccessRead($map = null){
            $model = self::getSearchField('access_read',  1 ,'id_user', Yii::$app->user->id)->all();

            return $map ? ArrayHelper::map($model, 'id_depart', 'id_depart') : $model;
        }

        //Доступ для записи
        public function getAccessWrite($map = null){
            $model = self::getSearchField('access_write',  1 ,'id_user', Yii::$app->user->id)->all();

            return $map ? ArrayHelper::map($model, 'id_depart', 'id_depart') : $model;
        }

        //Дотсуп для поиска
        public function getAccessSearch($map = null){
            $model = self::getSearchField('access_search',  1 ,'id_user', Yii::$app->user->id)->all();

            return $map ? ArrayHelper::map($model, 'id_depart', 'id_depart') : $model;
        }

    }
