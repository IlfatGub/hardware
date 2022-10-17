<?php

    /**
     * @var  $model  \app\models\HwUsers;
     * @var  $depart  \app\models\HwPodr;
     * @var  $tree
     * @var  $depart_full
     * @var  $users_lisl \app\models\HwUsers;
     */

    use app\models\HwPodr;
    use app\models\HwPost;
    use kartik\select2\Select2;
    use kartik\typeahead\Typeahead;
    use yii\helpers\ArrayHelper;
    use yii\helpers\Html;

    $id_depart = isset($_GET['id_depart']) ? $_GET['id_depart'] : null;

    $tops = array_keys(array_column($tree, 'Parent_ID'), "00000000-0000-0000-0000-000000000000");
    $t = ArrayHelper::map($tree, 'Parent_ID', 'subdivision', 'ID');

    function build_tree($tree, $id)
    {
        $keys = array_keys(array_column($tree, 'Parent_ID'), $id);
        echo "<ul>";
        foreach ($keys as $key) {
            echo '<li class="mt-2"><a href="#" id="'.$tree[$key]->ID.'" class="hw-cl-black hw-org-click">' . $tree[$key]->subdivision . '</a>.';
            build_tree($tree, $tree[$key]->ID);
        }
        echo "</ul>";
    }

?>

<?php \yii\widgets\Pjax::begin() ?>

<div class="col-12 row pt-3">

    <div class="col-4 card p-2">

        <ul class="treeCSS">
            <li><h5>ЗСМиК</h5>
                <?php
                    $tops = array_keys(array_column($tree, 'Parent_ID'), "00000000-0000-0000-0000-000000000000");
                    foreach ($tops as $top) {
                        echo "<ul>";
                        echo '<li class="mt-2"><a href="#" id="'.$tree[$top]->ID.'" class="hw-cl-black hw-org-click">' . $tree[$top]->subdivision . '</a>.';
                        build_tree($tree, $tree[$top]->ID);
                        echo "</ul>";
                    }
                ?>
        </ul>

    </div>

    <!-- Список пользователей -->
    <div class="col-8" id="hw-org-content">

        <?php echo \app\components\template\UserView::widget(
            [
                'users_list' => $users_list,
            ])
        ?>
        <!-- /.Список пользователей -->
    </div>
</div>


<script>
    (function () {
        var ul = document.querySelectorAll('.treeCSS > li:not(:only-child) ul, .treeCSS ul ul');
        for (var i = 0; i < ul.length; i++) {
            var div = document.createElement('div');
            div.className = 'drop';
            div.innerHTML = '+'; // картинки лучше выравниваются, т.к. символы на одном браузере ровно выглядят, на другом — чуть съезжают
            ul[i].parentNode.insertBefore(div, ul[i].previousSibling);
            div.onclick = function () {
                this.innerHTML = (this.innerHTML == '+' ? '−' : '+');
                this.className = (this.className == 'drop' ? 'drop dropM' : 'drop');
            }
        }
    })();
</script>


<?php \yii\widgets\Pjax::end() ?>
