<?php

    use yii\helpers\Url;

    $hw_depart = new \app\models\HwDepart();
?>



<?php if(in_array(Yii::$app->user->identity->hw_depart, $hw_depart->getAccessAct(true))): ?>
    <a href='<?= Url::toRoute(['site/download', 'id_user' => $id_user, 'type' => 'word']) ?>' target="_blank" title="Скачать акт Word">
        <i class="far fa-file-word fs-18"></i>
    </a>
    <a href='<?= Url::toRoute(['site/download', 'id_user' => $id_user, 'type' => 'pdf']) ?>' target="_blank" title=" Скачать акт Pdf">
        <i class="mx-1 far fa-file-pdf fs-18 hw-color-red"></i>
    </a>
<?php endif; ?>
