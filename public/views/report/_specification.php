<?php

    use app\components\access\Redactor;
    use yii\helpers\Html;
    use yii\helpers\Url;

?>


<?php if ($id_device): ?>

    <div class="col-9">
        <div class="card  reports-model">

            <table class="table table-sm table-hover table_sort" style="font-size: 10pt">
                <tbody>

                <thead>
                <tr>
                    <th>Наименование</th>
                    <?php
                        foreach ($model_specific as $sp_item) {
                            echo "<th>" . $sp_item->name . "</th>";
                        }
                    ?>
                </tr>
                </thead>

                <?php foreach ($model as $item): ?>
                    <tr>
                        <td>
                            <?php Redactor::begin() ?>
                            <?= Html::button("<i class=\"fa fa-cog mr-1 hw-cl-silver\"></i>", ['value' => Url::to(['/model/specification', 'id_model' => $item->id]), 'class' => 'p-0 m-0 btn btn-sm btn-url modalButton-lg', 'title' => 'История заявки']); ?>
                            <?php Redactor::end() ?>
                            <?= $item->name ?>
                        </td>
                        <?php
                            foreach ($model_specific as $sp_item) {
                                if ($item->specifications) {
                                    $_sp = \yii\helpers\ArrayHelper::map($item->specifications, 'specification', 'value');
                                    if (array_key_exists($sp_item->name, $_sp)) {
                                        echo "<td>" . $_sp[$sp_item->name] . "</td>";
                                    } else {
                                        echo "<td>-</td>";
                                    }
                                } else {
                                    echo "<td>-</td>";
                                }
                            }
                        ?>

                    </tr>
                <?php endforeach; ?>

                </tbody>
            </table>
        </div>
    </div>

    </div>
<?php endif; ?>