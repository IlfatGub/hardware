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
    class HwSearchTehnicStory extends HwTehnic
    {

        public $model_list;

        public $date_upd, $date_upd_to, $date_upd_do;
        public $date_ct, $date_ct_to, $date_ct_do;

        public $category;
        public $id_tehnic;
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
                [['id', 'id_wh', 'id_user', 'name', 'balance', 'id_model', 'category', 'type', 'status', 'user_depart', 'id_org', 'hw_depart', 'id_tehnic'], 'integer'],
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

            $this->date_ct = $this->issetVal('date_ct');
            $this->date_ct_to = $this->issetVal('date_ct_to');
            $this->date_ct_do = $this->issetVal('date_ct_do');
            $this->date_upd = $this->issetVal('date_upd');
            $this->date_upd_to = $this->issetVal('date_upd_to');
            $this->date_upd_do = $this->issetVal('date_upd_do');


            $query = HwStory::find()

                ->select(
                    [
                        'COUNT(*) AS location',
                        'hw_story.id_org',
                        'hw_story.id_wh',
                        'hw_story.date',
                        'hw_story.id_user',
                        'hw_story.status',
                        'hw_story.id_tehnic',
                    ]
                )
                ->joinWith(['wh', 'org', 'hwTehnicStatus', 'model', 'tehnic' => function ($q) {
                    $q->joinwith(['model', 'typeDevice']);
                }])
                ->joinWith(['user' => function ($q) {
                    $q->joinwith(['depart']);
                }]);


            // --- Дата обновления ---
            $this->getDateSearch('date_upd', 'date_upd_to', 'date_upd_do');


            $query->andFilterWhere(['>=', 'hw_story.date', $this->date_upd_to])
                ->andFilterWhere(['<=', 'hw_story.date', $this->date_upd_do]);
            // --- Дата обновления ---

//            // --- Дата создания ---
//            $this->getDateSearch('date_ct', 'date_ct_to', 'date_ct_do');
//            $query->andFilterWhere(['>=', 'hw_story.date_ct', $this->date_ct_to])
//                ->andFilterWhere(['<=', 'hw_story.date_ct', $this->date_ct_do]);
//            // --- Дата создания ---


            $query->andFilterWhere(['in', 'hw_story.id_model', $this->id_model]);
            if ($this->id)
                $query->andFilterWhere(['hw_story.id' => explode(',', $this->id)]);

            if ($this->id_tehnic)
                $query->andFilterWhere(['hw_story.id_tehnic' => explode(',', $this->id_tehnic)]);

            if ($this->serial)
                $query->andFilterWhere(['hw_story.serial' => explode(',', $this->serial)]);

            if ($this->category)
                $query->andFilterWhere(['hw_device_type.category' => explode(',', $this->category)]);

            if ($this->type)
                $query->andFilterWhere(['hw_device_type.id' => explode(',', $this->type)]);

            if ($this->id_wh)
                $query->andFilterWhere(['hw_story.id_wh' => $this->id_wh ? $this->id_wh : ArrayHelper::map(\app\models\HwWh::getWh(), 'id', 'id')]);

            if ($this->balance)
                $query->andFilterWhere(['hw_story.balance' => explode(',', $this->balance)]);

            if ($this->nomen)
                $query->andFilterWhere(['hw_story.id' => explode(',', $this->nomen)]);

            if ($this->hw_depart)
                $query->andFilterWhere(['hw_story.hw_depart' => explode(',', $this->hw_depart)]);

            if ($this->id_user)
                $query->andFilterWhere(['hw_story.id_user' => explode(',', $this->id_user)]);

            if ($this->status)
                $query->andFilterWhere(['hw_story.status' => explode(',', $this->status)]);

//            if ($this->location)
//                $query->andFilterWhere(['hw_story.location' => explode(',', $this->location)]);

            if ($this->id_org)
                $query->andFilterWhere(['hw_story.id_org' => explode(',', $this->id_org)]);

            if ($this->date_admission)
                $query->andFilterWhere(['hw_story.date_admission' => explode(',', $this->date_admission)]);

            if ($this->date_warranty)
                $query->andFilterWhere(['hw_story.date_warranty' => explode(',', $this->date_warranty)]);

            if ($this->act_num)
                $query->andFilterWhere(['hw_story.act_num' => explode(',', $this->act_num)]);



            $query->groupBy(['id_tehnic']);


            if ($this->location)
                $query->having(['>=', 'location', $this->location]);
//                $query->andFilterWhere(['hw_story.act_num' => explode(',', $this->act_num)]);

            $dataProvider = new ActiveDataProvider([
                'query' => $query,
                'pagination' => [
                    'pageSize' => 50
                ],
            ]);


            return $dataProvider;
        }


    }
