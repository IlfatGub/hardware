











<?php
    if (Yii::$app->user->can('SuperAdmin')){
        echo $content;
    }
?>