<?php
    /**
     * Created by PhpStorm.
     * User: 01gig
     * Date: 03.07.2020
     * Time: 10:40
     */

    use PhpOffice\PhpWord\Element\Table;
    use PhpOffice\PhpWord\TemplateProcessor;

    $file= 'act_'.Yii::$app->user->id.'.docx';

    $fio = \app\models\HwUsers::findOne(16)->username;

    $user_tehnic = \app\models\HwTehnic::find()
        ->joinWith(['typeDevice', 'model'])
        ->where(['id_user' => 16])->all();


    $styleCell = array('valign'=>'center');


    $table = new Table(array('borderSize' => 0, 'borderColor' => 'silver' ,'borderTopSize' => 40));
    $table->addRow();
    $table->addCell(1000)->addText('Инв.№')->setParagraphStyle([ 'color' => 'FF0000']);
    $table->addCell(2300)->addText('Тип Устройства');
    $table->addCell(3500)->addText('Модель устройства');
    $table->addCell(2500)->addText('С/н');
    $table->addCell(1500)->addText('Дата установки');
    foreach ($user_tehnic as $item) {
        $table->addRow();
        $table->addCell(200)->addText(\app\models\HwTehnic::getPassport($item->id));
        $table->addCell(200)->addText($item->typeDevice->name);
        $table->addCell()->addText($item->model->name);
        $table->addCell()->addText($item->serial);
        $table->addCell()->addText(date('d.m.Y', $item->date_upd));
    }

    $templateWord = new TemplateProcessor('ZSMIK_USR.docx');
    $templateWord->setValue('fio', $fio);
    $templateWord->setComplexBlock('passport', $table);
    $templateWord->saveAs($file);

    echo "<a href='$file'>Скачать Акт</a>";


