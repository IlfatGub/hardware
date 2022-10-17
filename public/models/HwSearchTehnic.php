<?php

    namespace app\models;

    use app\components\access\Admin;
    use app\models\HwTehnic;
    use Yii;
    use yii\base\Model;
    use yii\data\ActiveDataProvider;
    use yii\data\ArrayDataProvider;
    use yii\helpers\ArrayHelper;

    /**
     * DirectorySearch represents the model behind the search form about `app\module\admin\models\Directory`.
     */
    class HwSearchTehnic extends HwTehnic
    {

        public $model_list;

        public $date_upd, $date_upd_to, $date_upd_do;
        public $date_ct, $date_ct_to, $date_ct_do;

        public $category;
        public $model_name;
        public $user_depart;

        /**
         * @inheritdoc
         */
        public function scenarios()
        {
            // bypass scenarios() implementation in the parent class
            return Model::scenarios();
        }


        public function rules()
        {
            return [
                [['id', 'id_wh', 'id_user', 'name', 'balance', 'id_model', 'category', 'type', 'status', 'user_depart', 'id_org', 'hw_depart', 'verification'], 'integer'],
                [['location', 'serial', 'nomen', 'act_num'], 'string'],
                [['date_admission', 'date_warranty'], 'date'],
            ];
        }


        public function issetValue($field)
        {
            return isset($this->$field) ? $this->$field : null;
        }

        public function issetValueField($field, $array)
        {
            return $this->issetValue($field) ? $this->issetValue($field) : (array_key_exists($field, $array) ? $array[$field] : $this->$field);
        }


        public function issetVal($field)
        {
            return isset($_GET[$field]) ? $_GET[$field] : null;
        }

        public function issetValTehnic($field)
        {
            return isset($_GET['HwSearchTehnic'][$field]) ? $_GET['HwSearchTehnic'][$field] : null;
        }

        public function issetValField($field, $array)
        {
            return $this->issetVal($field) ? $this->issetVal($field) : (array_key_exists($field, $array) ? $array[$field] : $_GET[$field]);
        }

        public function issetValFieldTehnic($field, $array)
        {
            return $this->issetValTehnic($field) ? $this->issetValTehnic($field) : (array_key_exists($field, $array) ? $array[$field] : $_GET['HwSearchTehnic'][$field]);
        }

        public function getDateSearch($field, $field_to, $field_do)
        {
            if ($this->$field == 'current') {
                $this->$field_to = strtotime(date("Y-m-t", strtotime("-1 month")));
                $this->$field_do = strtotime('now');
            } elseif ($this->$field == 'previous') {
                $this->$field_to = strtotime(date("Y-m-01", strtotime("-1 month")));
                $this->$field_do = strtotime(date("Y-m-t", strtotime("-1 month")));
            } else {
                if ($this->$field_to or $this->$field_do) {
                    $this->$field_to = $_GET[$field_to] = strtotime($this->$field_to);
                    $this->$field_do = $_GET[$field_do] = strtotime($this->$field_do);
                } else {
                    $this->$field_to = null;
                    $this->$field_do = null;
                }
            }
        }

        /**
         * Creates data provider instance with search query applied
         *
         * @param array $params
         *
         * @return ActiveDataProvider
         */
        public function search($params)
        {
            $this->load($params);

            $template = isset($_GET['template']) ? $_GET['template'] : null;
            
            if ($template) {
                $hw_sett = new HwSettings();
                $hw_sett->tb1 = $template;
                $hw_sett->type = $hw_sett::TYPE_FIELD_REPORT;
                $hw_sett->tb3 = 'id_device';
                
                $by_specefic  = $hw_sett->getModelBySpecification(); //получаем список устройств по характеристикам
                $model_list = $by_specefic['model'];    //список моделей по характеристикам
                $tehnic_list = $by_specefic['tehnic'];  //список устройств по характеристикам
            }

            $this->date_ct = $this->issetVal('date_ct');
            $this->date_ct_to = $this->issetVal('date_ct_to');
            $this->date_ct_do = $this->issetVal('date_ct_do');
            $this->date_upd = $this->issetVal('date_upd');
            $this->date_upd_to = $this->issetVal('date_upd_to');
            $this->date_upd_do = $this->issetVal('date_upd_do');


            $tehnic_field = \app\models\HwSettings::find()->andWhere(['type' => 2, 'tb1' => $template, 'tb2' => 'tehnic', 'id_user' => Yii::$app->user->id])->all();
            $sp_field = \app\models\HwSettings::find()->andWhere(['type' => 2, 'tb1' => $template, 'tb2' => 'specification', 'id_user' => Yii::$app->user->id])->all();

            $d_v = ArrayHelper::map($tehnic_field, 'tb3', 'tb4'); //аттрибуты для запроса
            $sp_v = ArrayHelper::map($sp_field, 'tb3', 'tb4');

            if (Hardware::accessAdmin())
                $d_v = ArrayHelper::map(\app\models\HwSettings::find()->andWhere(['type' => 2, 'tb1' => $template])->all(), 'tb3', 'tb4');

            if ($d_v) {
                $this->id_model = $this->issetValueField('id_model', $d_v);
                $this->location = $this->issetValueField('location', $d_v);
                $this->category = $this->issetValueField('category', $d_v);
                $this->type = $this->issetValueField('type', $d_v);
                $this->status = $this->issetValueField('status', $d_v);
                $this->id_wh = $this->issetValueField('id_wh', $d_v);
                $this->nomen = $this->issetValueField('nomen', $d_v);
                $this->balance = $this->issetValueField('balance', $d_v);
                $this->id_org = $this->issetValueField('id_org', $d_v);
                $this->serial = $this->issetValueField('serial', $d_v);
                $this->id_user = $this->issetValueField('id_user', $d_v);
                $this->hw_depart = $this->issetValueField('hw_depart', $d_v);
                $this->act_num = $this->issetValueField('act_num', $d_v);
                $this->date_admission = $this->issetValueField('date_admission', $d_v);
                $this->date_warranty = $this->issetValueField('date_warranty', $d_v);
                $this->date_ct = $this->issetValField('date_ct', $d_v);
                $this->date_upd = $this->issetValField('date_upd', $d_v);
//                $this->verification = $this->issetValField('verification', $d_v);
            }

            $query = HwTehnic::find()
                ->joinWith(['wh', 'org', 'model', 'typeDevice', 'hwTehnicStatus'])
                ->joinWith(['user' => function ($q) {
                    $q->joinwith(['depart']);
                }]);

            // --- Дата обновления ---
            $this->getDateSearch('date_upd', 'date_upd_to', 'date_upd_do');
            $query->andFilterWhere(['>=', 'date_upd', $this->date_upd_to])
                ->andFilterWhere(['<=', 'date_upd', $this->date_upd_do]);
            // --- Дата обновления ---

            // --- Дата создания ---
            $this->getDateSearch('date_ct', 'date_ct_to', 'date_ct_do');
            $query->andFilterWhere(['>=', 'hw_tehnic.date_ct', $this->date_ct_to])
                ->andFilterWhere(['<=', 'hw_tehnic.date_ct', $this->date_ct_do]);
            // --- Дата создания ---


            //Поиск по моделям. Если через спецификацию, то ищем через массив
            if ($model_list) {
                $query->andFilterWhere(['in', 'hw_tehnic.id_model', $model_list]);
            } else {
                $query->andFilterWhere(['in', 'hw_tehnic.id_model', $this->id_model]);
            }

            if ($tehnic_list) {
                $query->orFilterWhere(['hw_tehnic.id' => $tehnic_list]);
            } else {
                if ($this->id)
                    $query->andFilterWhere(['hw_tehnic.id' => explode(',', $this->id)]);
            }

            if ($this->serial)
                $query->andFilterWhere(['hw_tehnic.serial' => explode(',', $this->serial)]);

            if ($this->category)
                $query->andFilterWhere(['hw_device_type.category' => explode(',', $this->category)]);

            if ($this->id_wh)
                $query->andFilterWhere(['hw_tehnic.id_wh' => $this->id_wh ? $this->id_wh : ArrayHelper::map(\app\models\HwWh::getWh(), 'id', 'id')]);

            if ($this->balance)
                $query->andFilterWhere(['hw_tehnic.balance' => explode(',', $this->balance)]);

            if ($this->nomen)
                $query->andFilterWhere(['hw_tehnic.id' => explode(',', $this->nomen)]);

            if ($this->hw_depart)
                $query->andFilterWhere(['hw_tehnic.hw_depart' => explode(',', $this->hw_depart)]);

            if ($this->id_user)
                $query->andFilterWhere(['hw_tehnic.id_user' => explode(',', $this->id_user)]);

            if ($this->status)
                $query->andFilterWhere(['hw_tehnic.status' => explode(',', $this->status)]);

            if ($this->location)
                $query->andFilterWhere(['hw_tehnic.location' => explode(',', $this->location)]);

            if ($this->id_org)
                $query->andFilterWhere(['hw_tehnic.id_org' => explode(',', $this->id_org)]);

            if ($this->date_admission)
                $query->andFilterWhere(['hw_tehnic.date_admission' => explode(',', $this->date_admission)]);

            if ($this->date_warranty)
                $query->andFilterWhere(['hw_tehnic.date_warranty' => explode(',', $this->date_warranty)]);

            if ($this->act_num)
                $query->andFilterWhere(['hw_tehnic.act_num' => explode(',', $this->act_num)]);

            if ($this->type)
                $query->andFilterWhere(['hw_device_type.id' => explode(',', $this->type)]);

            if ($this->verification)
                $query->andFilterWhere(['hw_tehnic.verification' => explode(',', $this->verification)]);


            $dataProvider = new ActiveDataProvider([
                'query' => $query,
                'pagination' => [
                    'pageSize' => 50
                ],
            ]);

            return $dataProvider;
        }
    }
