<?php
    namespace app\components\navbar;


    use app\models\HwStory;
    use yii\base\Widget;

    class LastStory extends Widget
    {

        public $type;

        public function init()
        {
            parent::init();
            if ($this->type === null) {
                $this->type = 0;
            }
        }


        public function run()
        {
            if ($this->type == 0){
                $model = HwStory::find()->joinWith(['tehnic', 'user', 'editor'])->where(['id_editor' => \Yii::$app->user->id])->limit(10)->orderBy(['date' => SORT_DESC])->all();
            }else{
                $model = HwStory::find()->joinWith(['tehnic', 'user', 'editor'])->limit(10)->orderBy(['date' => SORT_DESC])->all();
            }

            return $this->render('last-story', [
                'model' => $model,
                'type' => $this->type,
            ]);
        }
    }