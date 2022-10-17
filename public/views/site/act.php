<?php

    /**
     * $var $act_num
     */
    use app\models\Hardware;
    use app\models\HwTehnicRam;

?>

<div>
    <div style="font-size: 25pt; text-align: center; background: #2E75B5; color: white;">Акт приема-передачи оборудования</div>
    <div style="text-align: center;"><?= isset($act_num) ? "№ акта ".$act_num : '' ?></div>
    <br>

    <br>

    <div style="font-weight: bold; font-size: 12pt">
        &emsp; &emsp; Кулябин Алексей Сергеевич, именуемый в дальнейшем    «Сторона 1», с одной стороны и <?= $fio ?>, именуемый в дальнейшем «Сторона 2», с другой стороны, вместе именуемые «Стороны», составили настоящий Акт о нижеследующем:
    </div>
    <div style="font-weight: bold; font-size: 12pt">
        &emsp; &emsp; 1. &emsp; Сторона 1 передает, а Сторона 2 принимает следующую технику в пользование:
    </div>

    <br>
    <br>


    <table border="1" class="table table-sm" style="border-collapse: collapse; border: 1px solid black;">
        <tr>
            <td style="text-align: center; font-weight: bold">Инв.№</td>
            <td style="text-align: center; font-weight: bold">Тип устройства</td>
            <td style="text-align: center; font-weight: bold">Модель устройства</td>
            <td style="text-align: center; font-weight: bold">С/н</td>
            <td style="text-align: center; font-weight: bold">Дата</td>
        </tr>
        <?php foreach($user_tehnic as $item): ?>
            <?php
            $ram = new HwTehnicRam(['id_tehnic' => $item->id]);
                $device_name  = $item->typeDevice->name;
                $old_pass = isset($item->old_passport) ? '<br>('.$item->old_passport.')' : '';

                //проверка на дополнительное оборудование
                if ($ram->existsRam()){
                    $_rams = $ram->getRamByTehnic();
                    $device_name .= ".";
                    foreach ($_rams as $_ram) {
                        $device_name .= ' + '.$_ram->name.'';
                    }
                }
                ?>

            <tr style="padding: 3px">
                <td style="text-align: center"><?=\app\models\HwTehnic::getPassport($item->id).$old_pass?></td>
                <td>&nbsp;<?=$device_name?>&nbsp;</td>
                <td>&nbsp;<?=$item->model->name?>&nbsp;</td>
                <td>&nbsp;<?=$item->serial?>&nbsp;</td>
                <td>&nbsp;<?=date('d.m.Y', $item->date_upd)?>&nbsp;</td>
            </tr>
        <?php endforeach; ?>

    </table>

    <br>

    <div style="font-weight: bold; font-size: 12pt">
        &emsp; &emsp; 2. &emsp; Настоящий акт составлен в двух экземплярах, по одному для каждой из Сторон.
    </div>

    <br>
    <br>

    <div style="text-align: center; font-size: 12pt">Подписи сторон</div>
    <br>
    <table class="table table-sm" style="text-align: center;  font-size: 12pt">
        <tr>
            <td>
                Передал: <br>
                Сторона 1: <br><br>
                ______________ / Кулябин А.С. /
            </td>
            <td>
                Принял: <br>
                Сторона 2: <br><br>
                ______________ / <?= Hardware::fio($fio, 2) ?> /
            </td>
        </tr>
    </table>
</div>