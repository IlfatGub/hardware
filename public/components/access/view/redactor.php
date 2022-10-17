











<?php
    if (Yii::$app->user->can('redactor')){
        echo $content;
    }
?>