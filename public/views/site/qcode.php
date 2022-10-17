<?php


    use app\models\HwTehnic;
    use app\models\HwUsers;
    use yii\helpers\ArrayHelper;
    use yii\helpers\Html;
    use yii\helpers\Url;

    use xj\qrcode\QRcode;
    use xj\qrcode\widgets\Text;
    use xj\qrcode\widgets\Email;
    use xj\qrcode\widgets\Card;

    $id_user = isset($_GET['id_user']) ? $_GET['id_user'] : null;
    $id_tehnic = isset($_GET['id_tehnic']) ? $_GET['id_tehnic'] : null;

    $query = HwTehnic::find()->joinWith(['model', 'user', 'wh', 'org']);

    $username = null;

    if($id_tehnic){
        $query->where(['hw_tehnic.id' => $id_tehnic]);
        $_th = HwTehnic::findOne($id_tehnic)->id_user;
        $_usr_query = HwUsers::find()->where(['id' => $_th])->one();

        $username = isset($_usr_query->username) ? $_usr_query->username : null;
    }else{
        $query->where(['id_user' => $id_user]);
        $username = HwUsers::find()->where(['id' => $id_user])->one()->username;
    }
    $model = $query->all();

?>

<style>
    @page {
        size: landscape;
        margin: 0;
        padding: 0;
    }

    @media print {
        html, body {
        }
        .qcode_main{
            margin-left: -15px;
            margin-top: 55px
        }
        @page {
            size: landscape;
            margin: 0;
            padding: 0;
        }
        .no_print{
            display: none;
        }
    }
    .qcode_main{
        page-break-after:always;
        max-width: 315px;
        border-bottom: 1px dashed black;
        border-radius: 0px;
    }
</style>

<div class="row no_print">
    <div class="no_print card p-2 mt-3 bg-info col-3" >
        <div>
            <input type="checkbox" id="full_checkbox" style="display: inline-block" checked/> <span style="display: inline-block">Выделить/Отменить выделение</span>
        </div>
    </div>

    <?php if(isset($username)): ?>
        <div class="no_print card p-2 mt-3 ml-2 text-center hw-bg-light-yellow col-3" >
            <div>
                <?= $username ?>
            </div>
        </div>
    <?php endif; ?>
</div>


<?php foreach ($model as $item): ?>
        <div class="info-box mb-3 mt-2 qcode_main qcode" id="qcode_<?=$item->id?>">
        <span class="info-box-icon" style="min-width: 100px">
            <?php
                echo Text::widget([
                    'outputDir' => '@webroot/upload/qrcode',
                    'outputDirWeb' => '@web/upload/qrcode',
                    'ecLevel' => QRcode::QR_ECLEVEL_L,
                    'text' => \app\models\HwTehnic::getPassport($item->id),
                    'size' => 6,
                ]);
            ?>

        </span>

            <div class="info-box-content" style="max-width: 200px; font-size: 16pt">
                <span class="info-box-number mb-1" style="white-space: pre-wrap; font-size: 20pt;border-bottom: 1px dashed black; padding-bottom: 6px;">№<?=  HwTehnic::getPassport($item->id) ?><?=isset($item->old_passport) ? '('.$item->old_passport.')' : ''?><input type="checkbox" id="<?=$item->id?>" checked class="float-right no_print qcode-checkbox"> </span>
                <span class="info-box-number" style=" "><?= $item->org->name ?> </span>
                <span class="info-box-text" style="white-space: pre-wrap"><?= $item->typeDevice->name?></span>
                <span class="info-box-text" style="white-space: pre-wrap"><?= $item->model->name ?></span>
                <span class="info-box-text" style="white-space: pre-wrap">С/н: <?= $item->serial ?></span>


            </div>
            <!-- /.info-box-content -->


        </div>


<?php endforeach; ?>


