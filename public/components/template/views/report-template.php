<?php
    /**
     * Created by PhpStorm.
     * User: 01gig
     * Date: 03.12.2021
     * Time: 14:38
     */

    /**
     * @var  $templates  \app\models\HwSettings;
     * @var  $type ;
     */

    use app\models\HwSettings;
    use yii\helpers\Html;
    use yii\helpers\Url;
    use app\models\Login;

    $template = isset($_GET['template']) ? $_GET['template'] : '';
    $data = isset($_GET['data']) ? $_GET['data'] : '';

    $usr = '';
    $redirect = false;

?>


<div>
    <div class="card mt-2 ">
        <div class="card-body">
            <div class="fs-20">
                <strong>Отчеты</strong>
                <?php if (!$type): ?>
                    <?= Html::button("Создать шаблон", ['value' => Url::to(['/report/add-templates']), 'class' => 'btn btn-primary modalButton-lg float-right', 'title' => 'Создать шаблон']); ?>
                <?php endif; ?>
            </div>
        </div>
    </div>


    <table class="table card col-12">
        <tbody class="col-12">
        <?php foreach ($templates as $item): ?>
            <?php $name_templ = '<strong>' . $item->tb1 . '</strong>'; ?>
            <?php $usr = Yii::$app->user->id == 1 ? '<br>'.\app\models\Hardware::fio(Login::findOne($item->id_user)->username, 1) : ''; ?>

            <tr class="report-template">
                <td class="col-2">
                    <?=$name_templ?>
                </td>
                <td class="col-7">
                    <?= HwSettings::getBridgeReport($item->tb1) ?>
                </td>
                <td class="col-3">
                    <?= Html::a(
                        'Сгенерировать отчет',
                        [Url::to(['/site/report', 'template' => $item->tb1])],
                        ['class' => $template == $item->tb1 ? ' ml-1 btn btn-sm btn-secondary alert-secondary float-right' : 'ml-1 btn btn-sm btn-outline-secondary float-right']
                    ); ?>
                    <?php if ($template == $item->tb1) {
                        echo Html::a('Удалить', Url::to(['/report/add-template', 'type' => HwSettings::TYPE_DELETE, 'data' => $data, 'template' => $template]), ['class' => 'ml-1 btn btn-sm btn-outline-danger float-right', 'title' => 'Удалить шаблон', 'data' => ['confirm' => 'Удалить?']]);
                        echo Html::button('Редактировать', ['value' => Url::to(['/report/add-templates', 'type' => 'field', 'data' => $data, 'template' => $template]), 'class' => ' ml-1 btn btn-sm  btn-outline-primary modalButton-lg float-right', 'title' => 'Изменить шаблон']);
                    } ?>
                </td>
            </tr>

        <?php endforeach; ?>
        </tbody>
    </table>