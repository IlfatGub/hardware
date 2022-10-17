<?php

    namespace app\models;

    use Yii;
    use yii\helpers\ArrayHelper;

    /**
     * Настройки.
     *
     * @property string name
     *
     * @property int id
     * @property string tb1
     * @property string tb2
     * @property string tb3
     * @property string tb4
     *
     * @property int type
     * @property int id_user
     * @property int visible
     *
     *
     * $type Тип настроек
     * $type = 1  Отображаемые столбцы
     * tb1 - action
     * tb2 - id_user
     * tb3 - field
     *
     * $type = 2  Шаблоны для отчетов. Поля
     * tb1 - name
     * tb2 - action
     * tb3 - field
     * tb3 - field_value
     *
     * $type = 3  Шаблоны для отчетов. Значения по умолчанию
     * tb1 - name
     * tb2 - action
     * tb3 - field
     * tb3 - field_value
     */
    class HwSettings extends ModelInterface
    {

        const TYPE_FIELD_TEHNIC = 1;
        const TYPE_FIELD_REPORT = 2;
        const TYPE_FIELD_REPORT_VALUE = 3;

        const TYPE_DELETE = 4;
        const TYPE_ACCESS = 1;

        public $old_field;
        public $template;
        public $data;
        public $error;

        const SCENARIO_REPORT = 'report';

//        public $id_user;

        public static function tableName()
        {
            return 'hw_settings';
        }

        public function rules()
        {
            return [
                [['tb1', 'tb2', 'tb3', 'tb4', 'id_user'], 'safe'],
                [['tb3', 'tb1'], 'required', 'on' => self::SCENARIO_REPORT, 'message' => 'Поля не должны быть пустыми'],
                [['type', 'visible'], 'integer'],
            ];
        }

        public function attributeLabels()
        {
            return [
                'id' => 'ID',
            ];
        }

        //Список столбцов для вывода талицы техника
        public function getTehnicTableField()
        {
            return [
                'counter' => 'Счетчик',
                'id' => 'Номер паспорта',
                'id_org' => 'Организация',
                'serial' => 'Серийный номер',
                'fio' => 'ФИО',
                'date_ct' => 'Дата создания',
                'date_upd' => 'Дата последнего изменения',
                'id_wh' => 'Склад',
                'balance' => 'На балансе',
                'nomen' => 'Номенклатура',
                'part' => 'Номер партии',
                'hw_depart' => 'Прнадлежность склада к отделу',
                'id_model' => 'Устройство',
                'button' => 'Кнопки редактирования',
                'icon' => 'Иконка устройства',
                'location' => 'Местонахождение',
                'device' => 'Тип устройствао',
                'status' => 'Статус',
                'date_admission' => 'Дата приемки',
                'date_warranty' => 'Гарантия',
                'act_num' => 'Номер акта приема-передачи оборудования',
            ];
        }

        //Список столбцов для вывода талицы техника
        public function getTehnicTableFieldReport()
        {
            return [
                'id' => 'Номер паспорта',
                'id_org' => 'Организация',
                'serial' => 'Серийный номер',
                'fio' => 'ФИО',
                'date_ct' => 'Дата создания',
                'date_upd' => 'Дата последнего изменения',
                'id_wh' => 'Склад',
                'balance' => 'На балансе',
                'nomen' => 'Номенклатура',
                'hw_depart' => 'Прнадлежность склада к отделу',
                'id_model' => 'Устройство',
                'location' => 'Местонахождение',
                'category' => 'Группа',
                'status' => 'Статус',
                'device_type' => 'Тип устройства',
                'depart' => 'Отдел пользователя',
                'date_admission' => 'Дата приемки',
                'date_warranty' => 'Гарантия',
                'act_num' => 'Номер акта приема-передачи оборудования',
            ];
        }


        public function getRamFiledDropdown()
        {
            return [
//                'name' => ArrayHelper::map(HwTehnicRam::getTehnicRamActual(),'name','name'),
                'type' => ArrayHelper::map(HwDeviceType::getDeviceType(), 'id', 'name'),
                'id_user' => ArrayHelper::map(Login::find()->all(), 'id', 'username'),
                'hw_depart' => ArrayHelper::map(HwDepart::find()->where(['type' => HwDepart::TYPE_PARENT_DEPART])->all(), 'id', 'name'),
                'date' => ['current' => 'Текущий месяц', 'previous' => 'Предыдущий  месяц'],
                'date_add' => ['current' => 'Текущий месяц', 'previous' => 'Предыдущий  месяц'],
            ];

        }


        public function getTehnicFiledDropdown()
        {
            return [
                'id_org' => ArrayHelper::map(\app\models\HwPodr::getOrg(), 'id', 'name'),
                'hw_depart' => ArrayHelper::map(HwDepart::find()->where(['type' => HwDepart::TYPE_PARENT_DEPART])->all(), 'id', 'name'),
                'device_type' => ArrayHelper::map(HwDeviceType::getDeviceType(), 'id', 'name'),
                'id_model' => ArrayHelper::map(\app\models\HwModel::getModel(), 'id', 'name'),
                'status' => \app\models\HwTehnicStatus::getStatus(),
                'category' => HwDeviceType::getGroup(),
                'id_wh' => ArrayHelper::map(\app\models\HwWh::getWh(), 'id', 'name'),
                'date_ct' => ['current' => 'Текущий месяц', 'previous' => 'Предыдущий  месяц'],
                'date_upd' => ['current' => 'Текущий месяц', 'previous' => 'Предыдущий  месяц'],
            ];

        }

        public function getRamField()
        {
            return [
                'name' => 'Наименование',
                'id_tehnic' => 'Техника',
                'type' => 'Тип устройства',
                'date' => 'Дата добавления/удаления компл. из техники',
                'date_add' => 'Дата внесения в систему',
                'id_user' => 'Пользователь',
                'hw_depart' => 'Отдел',
                'tehnic_model' => 'Модель техники',
                'tehnic_user' => 'Пользователь техники',
            ];
        }


        public function getUrlAndNameForTemplate($table)
        {
            $result = array();
            switch ($table) {
                case 'tehnic':
                    $result = ['<small>Техника</small>', '/site/report'];
                    break;
                case 'ram':
                    $result = ['<small>Комплектующие</small>', '/report/report'];
                    break;
                case 'specification':
                    $result = ['<small>Характеристика</small>', '/site/report'];
                    break;
            }
            return $result;
        }


        public static function getBridgeReport($template){
            
            $hw_sett = new HwSettings();
            $field = $hw_sett->getTehnicTableFieldReport();
            $field_dr = $hw_sett->getTehnicFiledDropdown();
            $_field_item = '';

            $report_field = $hw_sett::find()->select(['tb3'])->where(['tb1' => $template, 'type' => 2, 'visible' => 1, 'id_user' => Yii::$app->user->id])->column();
            
            $specefication_field = $hw_sett::find()
                ->where(['tb1' => $template, 'type' => 2, 'tb2' => 'specification', 'id_user' => Yii::$app->user->id])
                ->andFilterWhere(['is not', 'tb4', new \yii\db\Expression('null')])
                ->asArray()->all();

            $tehnic_field = $hw_sett::find()
                ->where(['tb1' => $template, 'type' => 2, 'tb2' => 'tehnic', 'id_user' => Yii::$app->user->id])
                ->andFilterWhere(['is not', 'tb4', new \yii\db\Expression('null')])
                ->asArray()->all();

            foreach ($report_field as $item) {
                $_field_item .= $field[$item].', ';
            }

            echo "<div class='report-field'>";
            echo "<small class='fs-12' style='color: ;'><em>".trim(trim($_field_item), ',')."</em></small>";
            echo "</div>";


            echo "<div class='report-bridge'>";
            if ($specefication_field){
                echo "<small class='fs-14' style='color: ;'>";
                foreach ($specefication_field as $item) {
                    if ($item['tb3'] == 'id_device'){
                        echo '<strong>Тип устройства</strong>('.HwDeviceType::findOne($item['tb4'])->name.')';
                    }elseif ($item['tb3'] == 'reverse'){
                        echo '<strong>Шаблон исключения</strong>';
                    }else{
                        $_sp = '<strong>'.$item['tb3'].'</strong>';
                        $_val = '('.str_replace(',', ', ', $item['tb4']).')';
                        echo $_sp.$_val.', ';
                    }
                }
                echo "</small>";
            }

            if ($tehnic_field){
                if ($specefication_field)
                    echo "<br>";
                echo "<small class='fs-12'>";
                foreach ($tehnic_field as $item) {
                    $_f= '';
                        $_sp = '<strong>'.$field[$item['tb3']].': </strong>';
                        $_arr =  $field_dr[$item['tb3']];
                        foreach (explode(',', $item['tb4']) as $item) {
                            $_f .= $_arr[$item].', ';
                        }
                        $_val = trim(trim($_f), ',');
                        echo $_sp.$_val.' | ';
                }
                echo "</small>";
            }
            echo "</div>";


        }
        
        public function getModelBySpecification(){

            $_GET['id_device'] = $id_device = $this->getValueTemplateField();

            $device_field = HwSpeceficDevice::find()->select(['name'])->where(['id_device' => $id_device, 'visible' => 1])->column();

            $values = ArrayHelper::map($this->getTemplateField(), 'id', 'tb4', 'tb3');

            $reverse = $values['reverse'] ? 'in' : 'in';
            $_reverse = $values['reverse'];

            foreach ($values as $key => $value) {
                if (!in_array($key, $device_field)) {
                    unset($values[$key]);
                }
            }

            $_GET['specification'] = $values;

            unset($_GET['specification']['id_device']);

            $_filter = isset($_GET['specification']) ? $_GET['specification'] : null;

            // запрос при выбра фильтра
            if ($_filter) {
                unset($_filter[0]);
                $i = 1;
                $array_w = array();

                foreach ($_filter as $key => $items) {
                    $val = [];
                    foreach ($items as $item) {
                        if ($item){
                            $val[] = $item;
                        }
                    }

                    $query = HwModel::find()
                        ->select('hw_specification_model.id_model as id_model')
                        ->joinWith(['specifications'])
                        ->where(['hw_specification_model.id_device' => $id_device])
                        ->andWhere(['=', 'hw_specification_model.specification', $key])
                        ->andWhere(['is', 'hw_specification_model.id_tehnic',  new \yii\db\Expression('null')])
                        ->andWhere([$reverse, 'hw_specification_model.value', explode(',',$val[0])]);
                    $_model = $query->column();

                    $__tehnic = HwTehnic::find()->select(['id'])->where(['in', 'id_model', $_model])->column();


                    $query = HwModel::find()
                        ->select('hw_specification_model.id_tehnic as id_tehnic')
                        ->joinWith(['specifications'])
                        ->where(['hw_specification_model.id_device' => $id_device])
                        ->andWhere(['=', 'hw_specification_model.specification', $key])
                        ->andWhere(['>', 'hw_specification_model.id_tehnic', 0])
                        ->andWhere([$reverse, 'hw_specification_model.value', explode(',',$val[0])]);

                    $_tehnic = $query->column();



                    $_tehnic = array_merge($_tehnic, $__tehnic);


                    if ($_reverse){
                        $_tehnic = HwTehnic::find()
                            ->select('hw_tehnic.id')
                            ->joinWith(['model'])
                            ->where(['hw_model.type' => $id_device])
                            ->andWhere(['not in', 'hw_tehnic.id', $_tehnic])->column();
                    }



                    if ($i > 1) {
                        $array_w = array_intersect($array_w, $_model);
                    } else {
                        $array_w = $_model;
                    }
                    $i++;
                }

            }
            $_model = is_array($_model) ? array_unique($_model) : null;
            $_tehnic = is_array($_tehnic) ? array_unique($_tehnic) : null;

            return ['model' => $_model, 'tehnic' => $_tehnic];
        }

        public function deleteTemplate(){
            return $this::deleteAll(['id_user' => Yii::$app->user->id, 'tb1' => $this->template]);
        }

        public function setDefaultValueByTemplate()
        {
            if ($this->template) {
                $filed_tmp = $this::find()->select(['tb3'])->where(['tb1' => $this->template, 'id_user' => Yii::$app->user->id, 'visible' => 1])->column();
                $this->tb1 = $this->template;
                $this->tb3 = $filed_tmp;
                $this->old_field = $filed_tmp;
            }
        }


        public function _addField(){

            $one = HwSettings::find()->where(['type' => $this::TYPE_FIELD_REPORT, 'tb1' => $this->tb1, 'tb3'=>$this->tb3, 'id_user' => Yii::$app->user->id])->one();

            if ($one)
                $st = $one;

            $st = new HwSettings();
            $st->tb1 = $this->tb1;         //название
            $st->tb2 = $this->tb2;   //таблица
            $st->tb3 = $this->tb3;   //таблица
            $st->tb4 = $this->tb4;         //столбцы
            $st->id_user = Yii::$app->user->id;
            $st->type = $this::TYPE_FIELD_REPORT;
            $st->visible = $one ? $one->visible : $this->visible;
            $st->save();
        }


        public function addField()
        {

            $name = str_replace(" ", "_", $this->tb1);

            $tehnic_field = self::getTehnicTableFieldReport();
            $ram_field = self::getRamField();

            $field = $this->data == 'ram' ? $ram_field : $tehnic_field;

            //добавляем или удаляем столбцы при обновлении шаблона
            if ($this->template) {

                $table = HwSettings::findOne($this->template);
                //Добавляем столбцы если их нет
                foreach ($this->tb3 as $item) {
                    if (in_array($item, $this->old_field)) {
                    } else {
                        $st = HwSettings::find()->where(['tb1' => $this->template, 'tb2'=> $this->data, 'tb3' => $item])->one();
                        $st->tb1 = $name;         //название
                        $st->tb2 = HwSettings::findOne(['tb1' => $this->template, 'id_user' => Yii::$app->user->id])->tb2;   //таблица
                        $st->tb3 = $item;         //столбцы
                        $st->id_user = Yii::$app->user->id;
                        $st->type = $this::TYPE_FIELD_REPORT;
                        $st->visible = 1;
                        $st->save();
                    }
                }



                //удаляем столбцы
                foreach ($this->old_field as $item) {
                    if (!in_array($item, $this->tb3) and array_key_exists($item, $field)) {
                        $upd = $this::findOne(['id_user' => Yii::$app->user->id, 'tb1' => $this->template, 'tb3' => $item]);
                        $upd->visible = 2;
                        $upd->save();
                    }
                }

            } else {
                if (!HwSettings::find()->where(['type' => $this::TYPE_FIELD_REPORT, 'tb1' => $name, 'id_user' => Yii::$app->user->id])->exists()) {
                    foreach ($this->tb3 as $item) {
                        $st = new HwSettings();
                        $st->tb1 = $name;
                        $st->tb2 = $this->data;
                        $st->tb3 = $item;
                        $st->visible = 1;
                        $st->id_user = Yii::$app->user->id;
                        $st->type = $this::TYPE_FIELD_REPORT;
                        $st->save();
                    }

                    //удаляем столбцы
                    foreach ($tehnic_field as $key => $item) {
                        if (!in_array($key, $this->tb3)) {
                            $st = new HwSettings();
                            $st->tb1 = $name;
                            $st->tb2 = $this->data;
                            $st->tb3 = $key;
                            $st->visible = 2;
                            $st->id_user = Yii::$app->user->id;
                            $st->type = $this::TYPE_FIELD_REPORT;
                            $st->save();
                        }
                    }
                } else {
                    $this->error = 'Такое название шаблона уже есть';
                }
            }
        }

        public function getReportType()
        {
            return [
                'tehnic' => 'Техника',
                'ram' => 'Комплектующие',
            ];
        }

        //первчиные настройки для пользователя
        public function getField()
        {
            return [
                'count',
                'id',
                'id_org',
                'serial',
                'fio',
                'id_wh',
                'id_model',
                'icon',
                'button',
                'status',
                'location',
            ];
        }

        // экшены
        public function getAction()
        {
            return [
                'tehnic',
                'search',
                'reports',
                'view',
            ];
        }


        public function addSpeceficReportField()
        {
            $new_model = new HwSettings();
            $new_model->tb1 = $this->tb1;
            $new_model->tb2 = 'specification';
            $new_model->type = $this::TYPE_FIELD_REPORT;
            $new_model->tb3 = $this->tb3;
            $new_model->tb4 = $this->tb4;
            $new_model->id_user = Yii::$app->user->id;
            $new_model->save();
        }

        // Добавляем первичные настройки дя пользвоателя
        public function addFirstSettings()
        {

            $this::deleteAll(['tb2' => $this->id_user]);

            foreach ($this->getAction() as $action) {
                foreach ($this->getField() as $field) {
                    $model = new HwSettings();
                    $model->tb2 = $this->id_user;
                    $model->tb1 = $action;
                    $model->tb3 = $field;
                    $model->type = $this::TYPE_FIELD_TEHNIC;
                    $model->save();
                }
            }
        }


        public function existsNameAndUser()
        {
            return HwSettings::find()->where(['id_user' => $this->id_user, 'tb1' => $this->tb1])->exists();
        }


        public function getTemplateField()
        {
            return HwSettings::find()->where(['tb1' => $this->tb1, 'type' => $this->type])->all();
        }


        //выводим значение определенного шаблона определленного поля
        public function getValueTemplateField()
        {
            $res = HwSettings::find()->where(['tb1' => $this->tb1, 'tb3' => $this->tb3, 'type' => $this->type])->one();
            return $res->tb4;

        }


        public function getTehnicUserField()
        {
            if (HwSettings::find()->andWhere(['tb2' => Yii::$app->user->id, 'tb1' => Yii::$app->controller->action->id, 'type' => $this::TYPE_FIELD_TEHNIC])->one()) {
                return HwSettings::find()->andWhere(['tb2' => Yii::$app->user->id, 'tb1' => Yii::$app->controller->action->id, 'type' => $this::TYPE_FIELD_TEHNIC])->all();
            }
            return false;
        }

        public function getReportTemplate()
        {
            if (Yii::$app->user->id == 1)
                return self::find()
                    ->select(['tb1', 'id_user'])
                    ->where(['type' => self::TYPE_FIELD_REPORT])
                    ->andWhere(['tb2' => $this->type])
                    ->distinct(['tb1'])->all();

            return self::find()
                ->select(['tb1', 'id_user'])
                ->where(['type' => self::TYPE_FIELD_REPORT])
                ->andWhere(['id_user' => Yii::$app->user->id])
                ->andWhere(['tb2' => $this->type])
                ->distinct(['tb1'])->all();
        }
    }
