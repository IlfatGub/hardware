<?php

    namespace app\models;

    use app\models\HwTehnic;
    use Yii;
    use yii\base\Model;
    use yii\data\ActiveDataProvider;
    use yii\data\ArrayDataProvider;
    use yii\helpers\ArrayHelper;

    /**
     * DirectorySearch represents the model behind the search form about `app\module\admin\models\Directory`.
     */
    class HwSerarchRam extends HwTehnicRam
    {


        public $tehnic_user;
        public $tehnic_model;
        public $name;
        public $hw_depart;
        public $id_tehnic;
        public $date_add, $date_add_do, $date_add_to;
        public $date, $date_do, $date_to;

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
                [['id', 'id_tehnic', 'date', 'id_user', 'hw_depart', 'type','ram_type','id_ram'], 'integer'],
                [['location', 'name', 'username', 'tehnic_user','tehnic_model'], 'string'],
            ];
        }

        public function issetVal($field)
        {
            return isset($this->$field) ?  $this->$field : null;
        }


        public function issetValField($field, $array)
        {
            return $this->issetVal($field) ? $this->issetVal($field) : (array_key_exists($field, $array) ? $array[$field] : $this->$field);
        }


        public function getDateSearch($field, $field_to, $field_do){
            if ($this->$field== 'current') {
                $this->$field_to = strtotime(date("Y-m-t", strtotime("-1 month")));
                $this->$field_do = strtotime('now');
            } elseif ($this->$field== 'previous') {
                $this->$field_to = strtotime(date("Y-m-01", strtotime("-1 month")));
                $this->$field_do = strtotime(date("Y-m-t", strtotime("-1 month")));
            } else {
                if ($this->$field_to or $this->$field_do) {
                    $this->$field_to = strtotime($this->$field_to );
                    $this->$field_do = strtotime($this->$field_do );
                } else {
                    $this->$field_to = null;
                    $this->$field_do = null;
                }
            }
        }

        public function issetValTehnic($field)
        {
            return isset($_GET['HwSerarchRam'][$field]) ? $_GET['HwSerarchRam'][$field] : null;
        }

        public function issetValFieldTehnic($field, $array)
        {
            $val = $this->issetValTehnic($field) ? $this->issetValTehnic($field) : (array_key_exists($field, $array) ? $array[$field] : $_GET['HwSerarchRam'][$field]);
            $_GET[$field] = $val;
            $this->$field = $val;
            return $val;
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

            $this->date = isset($_GET['date']) ? $_GET['date'] : null;
            $this->date_to = isset($_GET['date_to']) ? $_GET['date_to'] : null;
            $this->date_do = isset($_GET['date_do']) ? $_GET['date_do'] : null;

            $this->date_add = isset($_GET['date_add']) ? $_GET['date_add'] : null;
            $this->date_add_to = isset($_GET['date_add_to']) ? $_GET['date_add_to'] : null;
            $this->date_add_do = isset($_GET['date_add_do']) ? $_GET['date_add_do'] : null;


            $d_v = ArrayHelper::map(\app\models\HwSettings::find()->andWhere(['type' => 2, 'tb1' => $template, 'id_user' => Yii::$app->user->id])->all(), 'tb3', 'tb4');

            if ($d_v) {

                $this->id_user = $this->issetValField('id_user', $d_v);
                $this->name = $this->issetValField('name', $d_v);
                $this->type = $this->issetValField('type', $d_v);
                $this->id_tehnic = $this->issetValField('id_tehnic', $d_v);
                $this->tehnic_user = $this->issetValField('tehnic_user', $d_v);
                $this->tehnic_model = $this->issetValField('tehnic_model', $d_v);
                $this->hw_depart = $this->issetValField('hw_depart', $d_v);
            }

            $query = HwTehnicRam::find()
                ->joinWith(['login', 'typeDevice', 'depart'])
                ->joinWith(['tehnic' => function ($q) {
                    $q->joinwith(['user', 'model']);
                }]);


            // --- Дата обновления ---
            $this->getDateSearch('date_add', 'date_add_to', 'date_add_do');
            $query->andFilterWhere(['>=', 'date_add', $this->date_add_to])
                ->andFilterWhere(['<=', 'date_add', $this->date_add_do]);
            // --- Дата обновления ---


            // --- Дата создания ---
            $this->getDateSearch('date', 'date_to', 'date_do');
            $query->andFilterWhere(['>=', 'date', $this->date_to])
                ->andFilterWhere(['<=', 'date', $this->date_do]);
            // --- Дата создания ---

            $query->andFilterWhere(['like', 'hw_tehnic_ram.name',  $this->name]);
            $query->andFilterWhere(['like', 'hw_tehnic_ram.id_tehnic', $this->id_tehnic]);
            $query->andFilterWhere(['like', 'hw_model.name', $this->tehnic_model]);
            $query->andFilterWhere(['like', 'hw_users.username', $this->tehnic_user]);

            $query->andFilterWhere([
                'hw_tehnic_ram.type' => $this->type,
                'hw_tehnic_ram.id_user' => $this->id_user,
                'hw_tehnic_ram.hw_depart' => $this->hw_depart,
            ]);

            $dataProvider = new ActiveDataProvider([
                'query' => $query,
                'pagination' => [
                    'pageSize' => 50
                ],
            ]);


            return $dataProvider;
        }


    }
