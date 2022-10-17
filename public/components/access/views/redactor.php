
<?php
    /**
     * @var  $role ;
     */
?>










<?php
    if ($role){
        if (Yii::$app->user->can('Admin') or Yii::$app->user->can($role)){
            echo $content;
        }
    }else{
        if (Yii::$app->user->can('Admin')){
            echo $content;
        }
    }
?>