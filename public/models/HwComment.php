<?php

    namespace app\models;

    use yii\helpers\ArrayHelper;
    use yii\helpers\Html;

    /**
     * Комментарий хардваре
     *
     * @property string name
     *
     * @property int id
     * @property int id_user
     * @property int type
     * @property int visible
     * @property int date_ct
     *
     *
     * $type
     * 1 - Комментарий для техники
     *
     *
     */
    class HwComment extends ModelInterface
    {


        CONST  COMMENT_TYPE = 1;


        public static function tableName()
        {
            return 'hw_comment';
        }

        public function rules()
        {
            return [
                [['name'], 'string', 'max' => 255],
                [['type', 'visible', 'id_user', 'date_ct', 'id'], 'integer'],
            ];
        }


        public function beforeSave($insert)
        {
            $this->date_ct = strtotime('now');
            $this->id_user = \Yii::$app->user->id;

            return parent::beforeSave($insert); // TODO: Change the autogenerated stub
        }


        public function attributeLabels()
        {
            return [
                'id' => 'ID',
                'name' => 'name',
                'type' => 'type',
                'visible' => 'visible',
                'id_user' => 'id_user',
            ];
        }

        /**
         * @param $tehnic HwTehnic;
         */
        public function addComment()
        {
            try {
                $model = new self();
                $model->name = "1";
                $model->id_user = 1;
                $model->date_ct = 1;
                $model->type = $this::COMMENT_TYPE;
                $model->visible = $this::COMMENT_TYPE;
                $model->getSave();
            } catch (\Exception $ex) {
                ShowError::getError('danger', $ex->getMessage());
            }
        }

    }
