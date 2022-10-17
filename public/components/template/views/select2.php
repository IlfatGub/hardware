<?php
use kartik\select2\Select2;
?>




<?= $form->field($model, $attribute)->widget(Select2::classname(), [
    'data' => $data ? $data : 1,
    'options' => ['placeholder' => ' - ', 'class' => 'form-control-sm', 'multiple' => $multiple],
    'pluginOptions' => ['allowClear' => true, 'tags' => true, 'disabled' => $readonly],
    'disabled' => $disable])->label($label);
?>


