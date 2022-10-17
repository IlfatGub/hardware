<?php

    namespace app\models;

    use Yii;
    use yii\helpers\Html;

    /**
     * Долэности
     *
     * @property string name
     *
     * @property int id_tehnic
     * @property int id
     * @property int date
     * @property int id_user
     * @property int hw_depart
     * @property int type
     * @property int date_add
     * @property int id_ram
     *
     */
    class HwTehnicRam extends ModelInterface
    {

        public $count;
        const SCENARIO_UPDATE = 'update';

        public static function tableName()
        {
            return 'hw_tehnic_ram';
        }

        public function rules()
        {
            return [
                [['name', 'type'], 'required'],
                [['name', 'type'], 'required', 'on' => self::SCENARIO_UPDATE],
                [['name'], 'string', 'max' => 255],
                [['id_tehnic', 'id_user', 'count', 'type', 'hw_depart', 'date_add', 'id_ram'], 'integer'],
            ];
        }

        public function attributeLabels()
        {
            return [
                'id' => 'ID',
                'name' => 'Наименование',
                'type' => 'Тип устройства',
                'id_user' => 'Пользователь',
                'hw_depart' => 'Отдел',
                'id_tehnic' => 'Техника',
                'count' => 'Количество',
                'date_add' => 'Дата добавления в систему',
                'date' => 'Дата добавления в технику',
            ];
        }


        public function getTypeDevice()
        {
            return $this->hasOne(HwDeviceType::className(), ['id' => 'type']);
        }

        public function getLogin()
        {
            return $this->hasOne(Login::className(), ['id' => 'id_user']);
        }

        public function getTehnic()
        {
            return $this->hasOne(HwTehnic::className(), ['id' => 'id_tehnic']);
        }

        public function getDepart()
        {
            return $this->hasOne(HwDepart::className(), ['id' => 'hw_depart']);
        }

        public function getTehnicRam()
        {
            return self::find()->orderBy(['date' => SORT_DESC])->all();
        }

        public function getTehnicRamActual()
        {
            return self::find()->orderBy(['date' => SORT_DESC])->where(['id_tehnic' => null])->andFilterWhere(['>', 'id_ram', 0])->all();
        }


        public function addRam()
        {
            try{

            $tehinc = HwTehnic::findOne($this->id_tehnic);

            $_upd = HwTehnicRam::find()->where(['id_tehnic' => null, 'name' => $this->name])->andFilterWhere(['>', 'id_ram', 0])->one();
            $_upd->scenario = HwTehnicRam::SCENARIO_UPDATE;
            $_upd->date_add = strtotime('now');
            $_upd->id_user = Yii::$app->user->id;
//            $_upd->name = $this->name;
            $_upd->id_tehnic = $this->id_tehnic;

            if ($_upd->save()) {
                $ram = HwTehnic::findOne($_upd->id_ram);

                $ram->id_parent_tehnic = isset($this->id_tehnic) ? $this->id_tehnic : null;
//                $ram->date_upd = strtotime('now');
                $ram->date_warranty = strtotime($ram->date_warranty);
                $ram->date_admission = strtotime($ram->date_admission);
                if ($ram->getSave()){
                    $tehinc->status_tehnic = HwStory::STATUS_ADD_RAM;
                    $tehinc->comment = 'Добавлено комплектующее. ' . $this->name;
                    $tehinc->id_wh = null;
                    HwStory::addStory($tehinc);
                }
                return true;
            }
            } catch (\Exception $ex) {
                ShowError::getError('danger', $ex->getMessage());
                echo "<pre>";
                print_r($ex->getMessage());
                die();
            }

            return false;
        }


        public function delRam()
        {
            try{
                $ram = HwTehnicRam::findOne($this->id);
                $_th = HwTehnic::findOne($this->id_ram);
                $_th->status_tehnic = HwStory::STATUS_DELL_RAM;
                $_th->comment = 'Удалено комплектующее. ' . $ram->name;
                $_th->id_wh = null;
                HwStory::addStory($_th);

                $ram->id_tehnic = null;
                $ram->date_add = null;
                if ($ram->save()) {
                    if ($ram->id_ram) {
                        $_th = HwTehnic::findOne($ram->id_ram);
                        $_th->id_parent_tehnic = null;
                        $_th->date_warranty = strtotime($_th->date_warranty);
                        $_th->date_admission = strtotime($_th->date_admission);
//                        $_th->date_upd = strtotime('now');
                        $_th->save();

                        return true;
                    }
                }
            } catch (\Exception $ex) {
                ShowError::getError('danger', $ex->getMessage());
                echo "<pre>";
                print_r($ex->getMessage());
                die();
            }
            return false;
        }

        public function getRamByTehnic()
        {
            if ($this->existsRam()) {
                return self::find()->where(['id_tehnic' => $this->id_tehnic])->all();
            }
            return false;
        }

        public function existsRam()
        {
            return self::find()->where(['id_tehnic' => $this->id_tehnic])->exists();
        }


    }
