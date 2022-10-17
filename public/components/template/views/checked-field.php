
<?php

    /**
     * @var  $field_list  \app\models\HwSettings;
     * @var  $field_user  \app\models\HwSettings;
     *
     */


    $field_user = \yii\helpers\ArrayHelper::map($field_user, 'tb3', 'tb3'); //отмеченнвые пользователем поля


    ?>


<div class="btn-group" role="group" style="z-index: 1035">
    <div class="btn-group" role="group">
        <button type="button" id="w0-cols" class="btn btn-default btn-sm dropdown-toggle"
                title="Выберите поля для вывода" data-toggle="dropdown" aria-haspopup="true"
                aria-expanded="false">
            <i class="fa fa-list"></i>
            <span class="caret"></span>
        </button>
        <ul id="w0-cols-list" class="dropdown-menu kv-checkbox-list p-2" role="menu" style="min-width: 300px" aria-labelledby="w0-cols">
            <?php foreach($field_list as $key => $item): ?>
                <?php $checked = $field_user ? (in_array($key, $field_user) ? 'checked' : '') : '' ; ?>
                <li>
                    <div class="checkbox">
                        <label class="" for="e">
                            <input type="checkbox" id="<?=$key?>" data-key="<?=Yii::$app->controller->action->id?>" class="p-2 m-2 checked-filed fs-8" <?=$checked?> <span class="kv-toggle-all"><?=$item?></span>
                        </label>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</div>
