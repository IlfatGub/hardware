<?php
    /**
     * Created by PhpStorm.
     * User: 01gig
     * Date: 13.12.2021
     * Time: 16:14
     */


    /**
     * @var  $data
     * @var  $field
     * @var  $value
     * @var  $id
     */
    $time = $id ? $id : strtotime('now');

    use app\models\HwSpeceficValue;

    $hw_settings = new \app\models\HwSettings();

    //
    //
//    echo $data;
//    echo "<br>";
//    echo $field;
//    echo "<br>";
//    echo $value;
//    echo "<br>";
?>

<!--<h2>Content</h2>-->



<div class="form-row">

    <div class="form-group col-md-4">
        <label for="">
            <small>Фильтр</small>
        </label>
        <select name="filter[]" id="hw-filter-<?= $time ?>" data-id="<?= $time ?>"
                class="form-control hw-report-filter">

            <option value="" <?= !$data ? 'selected' : null ?> ></option>

            <option value="tehnic" <?= $data === 'tehnic' ? 'selected' : null ?> >Поля</option>
            <option value="specification" <?= $data === 'specification' ? 'selected' : null ?>>Характеристики</option>
            <option value="ram" <?= $data === 'ram' ? 'selected' : null ?>>Комплектующие</option>

        </select>
    </div>

    <div class="form-group col-md-4">
        <label for="">
            <small>Оператор</small>
        </label>
        <select name="<?= $data ? $data . '[]' : 'field[]' ?>" id="hw-field-<?= $time ?>" data-id="<?= $time ?>"
                class="form-control hw-report-field">
            <?php
                if ($data === 'tehnic') {
                    foreach ($hw_settings->getTehnicTableFieldReport() as $key => $item) {
                        $selected = $field == $key ? 'selected' : null;
                        echo "<option value = '" . $key . "' " . $selected . ">" . $item . "</option>";
                    }
                };

                if ($data === 'specification') {
                    foreach (\app\models\HwSpeceficDevice::find()->where(['id_device' => $id_device, 'visible' => 1])->all() as $item) {
                        $selected = $field == $item->name ? 'selected' : null;
                        echo "<option value = '" . $item->name . "' " . $selected . ">" . $item->name . "</option>";
                    }
                };

            ?>
        </select>
    </div>

    <div class="form-group col-md-4">
        <label for="">
            <small>Значение</small>
        </label>
        <select name="<?= $field ? $field . '[]' : 'field[]' ?>" id="hwvalue<?= $time ?>" data-id="<?= $time ?>"  multiple class="col-12 e1<?= $time ?>">
            <?php
                if ($data == 'tehnic') {
                    if (array_key_exists($field, $hw_settings->getTehnicFiledDropdown())) {
                        $_val = explode(',', $value);
                        foreach ($hw_settings->getTehnicFiledDropdown()[$field] as $key => $item) {
                            $selected = in_array($key, $_val) ? 'selected' : null;
                            echo "<option value = '" . $key . "' " . $selected . ">" . $item . "</option>";
                        }
                    }
                };

                if ($data == 'specification' ) {
                    $_val = explode(',', $value);
                    foreach (HwSpeceficValue::find()->where(['specification' => $field, 'visible' => 1])->all() as $item) {
                        $selected = in_array($item->value, $_val) ? 'selected' : null;
                        echo "<option value = '" . $item->value . "' " . $selected . ">" . $item->value . "</option>";
                    }
                };
            ?>
        </select>
    </div>
</div>


<?php //if($data): ?>
    <script>
        $(document).ready(function () {
            //$('#hw-filter-<?//=$time?>//').val(<?//=$data?>//).trigger('change');
            //$('#hw-field-<?//=$time?>//').val(<?//=$field?>//).click();
            //
            //$('#hw-filter-<?//=$time?>//').prop('disabled', true);
            //$('#hw-field-<?//=$time?>//').prop('disabled', true);
        });
    </script>

<?php //endif; ?>


<script>
    $(document).ready(function () {
        
        $('#hwvalue<?=$time?>').select2({
            tags: [],
            maximumInputLength: 10,
            allowClear: true,
        });
    });
</script>
