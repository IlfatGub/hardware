<?php

/**
 * @var  $model  \app\models\HwTehnic;
 *
 * @var  $title
 * @var  $color
 * @var  $re
 * @var  $type
 *
 * @var  $field_wh
 * @var  $field_date
 * @var  $field_fio
 * @var  $field_serial
 * @var  $field_balance
 * @var  $field_nomen
 * @var  $field_id
 * @var  $field_id_org
 * @var  $serial_array
 *
 * @var  $users_list \app\models\HwUsers
 * @var  $field_user \app\models\HwSettings
 */

use app\components\access\Redactor;
use app\models\HwTehnic;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;

$_update = isset($_GET['update']) ? $_GET['update'] : null;

$users = new \app\models\HwUsers();
$wh = new \app\models\HwWh();
$models = new \app\models\HwModel();

$_org = ArrayHelper::map(\app\models\HwPodr::getOrg(), 'id', 'name');
$_depart = ArrayHelper::map(\app\models\HwPodr::getDepart(), 'id', 'name');
$_user = ArrayHelper::map($users->getUsers(), 'id', 'username');
$_model = ArrayHelper::map($models->getModel(), 'id', 'name');
$_wh = ArrayHelper::map($wh->getWhFull(), 'id', 'name');

$_type = ArrayHelper::map(\app\models\HwDeviceType::getDeviceType(), 'id', 'icon');
$_type_component = ArrayHelper::map(\app\models\HwDeviceType::getDeviceType(), 'id', 'component');

$id_user = isset($_GET['id_user']) ? $_GET['id_user'] : null;

$disable = isset($_update) ? true : false;

$_counter = 1;
$color_sp = '';
?>


<div class="card">
	<div class="card-body p-0">

		<table class="table table-sm table-hover sort table_sort col-12" id="hw-tehnic" style="font-size: 10pt">
			<thead>
				<!--        выводим ФИО пользователя -->
				<?php if (isset($id_user)) : ?>
					<?php if (!$type) : ?>
						<tr class="fs-16">
							<th colspan="9">
								<?php echo $_user[$id_user] ?>
								<a href="<?= \yii\helpers\Url::toRoute(['users', 'update' => $id_user]) ?>" class="fas fa-edit"></a>
							</th>
						</tr>
					<?php endif; ?>
				<?php endif; ?>
				<!--        выводим ФИО пользователя -->

				<?php if ($title) : ?>
					<tr class="<?= $color ?>">
						<th colspan="19">

							<?php echo \app\components\template\CheckedField::widget() ?>

							<?= $title ?>

							<?php Redactor::begin(['role' => 'IT']) ?>
							<?php if ($re and $id_user) : ?>
								<?php Redactor::begin() ?>
								<?= Html::button("Перезакрепить", ['value' => Url::to(['site/re-order', 'id' => $id_user]), 'class' => 'py-0 px-1  btn btn-sm btn-success modalButton-lg float-right']); ?>
								<?= Html::a('На склад', [Url::to(['site/re-order', 'id' => $id_user, 'type' => 1])], ['class' => 'py-0 px-1 ml-2 mr-2 btn btn-sm btn-info float-right', 'data' => ['confirm' => 'Переместить на склад?'], 'title' => 'Переместить на склад']); ?>
								<?php Redactor::end() ?>
								<?= \app\components\template\DownloadButton::widget(['id_user' => $id_user]) ?>
								<?= Html::a('<i class="ml-2 fas fa-qrcode"></i> Паспорт', [Url::to(['site/qcode', 'id_user' => $id_user])], ['class' => 'py-0 px-1']); ?>
							<?php endif; ?>
							<?php Redactor::end() ?>
						</th>
					</tr>
				<?php endif; ?>
				<tr>
					<?php $none = in_array('counter', $field_user) ? '' : 'display-none' ?>
					<th id="field-counter" class=" field-counter <?= $none ?>">#</th>

					<?php $none = in_array('icon', $field_user) ? '' : 'display-none' ?>
					<th id="field-icon" class="field-icon <?= $none ?>"></th>

					<?php $none = in_array('id', $field_user) ? '' : 'display-none' ?>
					<th id="field-id" style="min-width: 100px" class="field-id <?= $none ?>">№</th>

					<?php $none = in_array('id_model', $field_user) ? '' : 'display-none' ?>
					<th id="field-id_model" style="max-width: 100px" class=" field-id_model <?= $none ?>">Устройство</th>

					<?php $none = in_array('device', $field_user) ? '' : 'display-none' ?>
					<th id="field-device" style="max-width: 100px" class=" field-device <?= $none ?>">Тип устройство</th>

					<?php $none = in_array('id_org', $field_user) ? '' : 'display-none' ?>
					<th id="field-id_org" class="field-id_org <?= $none ?>">Орг.</th>

					<?php $none = in_array('serial', $field_user) ? '' : 'display-none' ?>
					<th id="field-serial" class="field-serial <?= $none ?>">Серийный номер</th>

					<?php $none = in_array('fio', $field_user) ? '' : 'display-none' ?>
					<th id="field-fio" style="min-width: 250px" class="field-fio <?= $none ?>">ФИО</th>

					<?php $none = in_array('date_ct', $field_user) ? '' : 'display-none' ?>
					<th id="field-date_ct" style="width: 100px" class=" field-date_ct <?= $none ?>">Дата созд.</th>

					<?php $none = in_array('date_upd', $field_user) ? '' : 'display-none' ?>
					<th id="field-date_upd" style="width: 100px" class="field-date_upd <?= $none ?>">Дата изм.</th>

					<?php $none = in_array('hw_depart', $field_user) ? '' : 'display-none' ?>
					<th class="field-hw_depart <?= $none ?>"> Отдел</th>

					<?php $none = in_array('id_wh', $field_user) ? '' : 'display-none' ?>
					<th id="field-id_wh'" style="min-width: 130px" class="field-id_wh <?= $none ?>">Расположение</th>

					<?php $none = in_array('status', $field_user) ? '' : 'display-none' ?>
					<th id="field-status'" style="min-width: 130px" class="field-status <?= $none ?>">Статус</th>

					<?php $none = in_array('location', $field_user) ? '' : 'display-none' ?>
					<th id="field-location'" style="min-width: 130px" class="field-location <?= $none ?>">Местонахождение
					</th>

					<?php $none = in_array('nomen', $field_user) ? '' : 'display-none' ?>
					<th id="field-nomen" style="min-width: 130px" class="field-nomen <?= $none ?>">Номенклатура</th>

					<?php $none = in_array('balance', $field_user) ? '' : 'display-none' ?>
					<th id="field-balance" style="min-width: 130px" class="field-balance <?= $none ?>">На балансе</th>

					<?php $none = in_array('date_admission', $field_user) ? '' : 'display-none' ?>
					<th id="field-date_admission" style="min-width: 130px" class="field-date_admission <?= $none ?>">Дата
						приемки
					</th>

					<?php $none = in_array('date_warranty', $field_user) ? '' : 'display-none' ?>
					<th id="field-date_warranty" style="min-width: 130px" class="field-date_warranty <?= $none ?>">
						Гарантия
					</th>

					<?php $none = in_array('act_num', $field_user) ? '' : 'display-none' ?>
					<th id="field-act_num" style="min-width: 130px" class="field-act_num <?= $none ?>">Номер акта</th>

					<?php $none = in_array('verification', $field_user) ? '' : 'display-none' ?>
					<th id="field-verification" class="field-button <?= $none ?>">Верификация</th>

					<?php $none = in_array('button', $field_user) ? '' : 'display-none' ?>
					<th id="field-button" class="field-button <?= $none ?>">...</th>
				</tr>
			</thead>
			<tbody>


				<?php foreach ($model as $key => $item) : ?>
					<tr id="hw-tehnic-<?= $item->id ?>" class="<?= $item->id == $_update ? 'alert-warning' : '' ?>">

						<!--   Счетчик  -->
						<?php $none = in_array('counter', $field_user) ? '' : 'display-none' ?>
						<td class="field-counter <?= $none ?>">
							<i class="counter"></i>
						</td>
						<!--   Счетчик  -->


						<!--   Иконка устройства -->
						<?php $none = in_array('icon', $field_user) ? '' : 'display-none' ?>
						<td class="field-icon <?= $none ?>">
							<i class="<?= array_key_exists($item->model->type, $_type) ? $_type[$item->model->type] : null ?>"></i>
						</td>
						<!--   Иконка устройства -->

						<!--   Паспорт -->
						<?php $none = in_array('id', $field_user) ? '' : 'display-none' ?>

						<td class="field-id <?= $none ?>">
							<?= HwTehnic::getPassport($item->id) ?>
							<?= isset($item->old_passport) ? "($item->old_passport)" : '' ?>
						</td>
						<!--   Паспорт -->

						<!--   Устройства -->
						<?php $none = in_array('id_model', $field_user) ? '' : 'display-none' ?>

						<td style="max-width: 250px" class="field-id_model <?= $none ?>">
							<span style="display: none"> <?= $_model[$item->id_model] // Для корректной сортировки         
																	?> </span>
							<?php $icon = !\app\models\HwTehnic::existsRam($item->id) ? "<i class=\"fa fa-info mr-1 hw-cl-silver\" ></i>" : "<i class=\"fa fa-info mr-1 hw-cl-yellow\" ></i>" ?>

							<?= Html::button($icon, ['value' => Url::to(['site/tehnic-story', 'id' => $item->id]), 'class' => 'p-0 m-0 btn btn-sm btn-url modalButton-xl', 'title' => 'История по технике']); ?>
							<strong><?= $_model[$item->id_model] ?></strong>

							<?php if (array_key_exists($item->model->type, $_type_component)) : ?>
								<?php if ($_type_component[$item->model->type] <> 0) : ?>
									<?= Html::button("<i class=\"fas fa-plus-circle mr-1 hw-cl-green\" ></i>", ['value' => Url::to(['site/add-component', 'id' => $item->id]), 'class' => 'p-0 m-0 btn btn-sm btn-url modalButton-lg', 'id' => 'hw-component-add', 'title' => 'Добавить компонент']); ?>
								<?php endif; ?>
							<?php endif; ?>
						</td>
						<!--   Устройства -->

						<!--   Тип устройства -->
						<?php $none = in_array('device', $field_user) ? '' : 'display-none' ?>
						<td class="field-device <?= $none ?>"><?= $item->typeDevice->name ?></td>
						<!--   Тип устройствао -->

						<!--   Организация -->
						<?php $none = in_array('id_org', $field_user) ? '' : 'display-none' ?>
						<td class="field-id_org <?= $none ?>"><?= $_org[$item->id_org] ?></td>
						<!--   Организация -->


						<!--   Сериынйый номер -->
						<?php $none = in_array('serial', $field_user) ? '' : 'display-none' ?>
						<td class="field-serial <?= $none ?>">
							<?php if (Yii::$app->user->can('Admin')) : ?>
								<input class="form-control form-control-navbar form-control-sm border_no height-25 tehnic-input" data-key="serial" data-parent="/site/tehnic-ajax" id="<?= $item->id ?>" value="<?= $item->serial ?>">
							<?php else : ?>
								<?= $item->serial ?>
							<?php endif; ?>
						</td>
						<!--   Сериынйый номер -->


						<!--   ФИО  -->
						<?php $none = in_array('fio', $field_user) ? '' : 'display-none' ?>

						<td class="field-fio <?= $none ?>">
							<span style="display: none"> <?= isset($item->id_user) ? $_user[$item->id_user] : '' // Для корректной сортировки          
																	?> </span>

							<?= Html::button(isset($item->id_user) ? "<i class=\"fa fa-info-circle mr-1 hw-cl-silver\"></i>" : '', ['value' => Url::to(['site/user-info', 'id' => $item->id_user]), 'class' => 'p-0 m-0 btn btn-sm btn-url modalButton-lg', 'title' => 'История заявки']); ?>
							<?= Html::a(isset($item->id_user) ? $_user[$item->id_user] : '', [Url::to(['site/tehnic', 'id_user' => $item->id_user])]); ?>
						</td>
						<!--  ФИО  -->


						<!--     Дата создания  -->
						<?php $none = in_array('date_ct', $field_user) ? '' : 'display-none' ?>
						<td class="field-date_ct <?= $none ?>"><?= date('d-m-Y', $item->date_ct) ?></td>
						<!--     Дата создания  -->


						<!--     Дата послденего изменения   -->
						<?php $none = in_array('date_upd', $field_user) ? '' : 'display-none' ?>
						<td class="field-date_upd <?= $none ?>"><?= isset($item->date_upd) ? date('d-m-Y', $item->date_upd) : null ?></td>
						<!--     Дата послденего изменения   -->

						<!--   Принадлежность к отделу   -->
						<?php $none = in_array('hw_depart', $field_user) ? '' : 'display-none' ?>
						<td class="field-hw_depart <?= $none ?>"> <?= $item->depart->name ?></td>
						<!--    Принадлежность к отделу    -->

						<!--    Расположение   -->
						<?php $none = in_array('id_wh', $field_user) ? '' : 'display-none' ?>
						<td class="field-id_wh <?= $none ?>"><?= $_wh[$item->id_wh] ?></td>
						<!--    Расположение    -->

						<!--    Статус   -->
						<?php $none = in_array('status', $field_user) ? '' : 'display-none' ?>
						<td class="field-status <?= $none ?>"><?= $item->hwTehnicStatus->name ?></td>
						<!--    Статус    -->

						<!--    Местоположение   -->
						<?php $none = in_array('location', $field_user) ? '' : 'display-none' ?>
						<td class="field-location <?= $none ?>">
							<?php if (Yii::$app->user->can('Admin')) : ?>
								<input class="form-control form-control-navbar form-control-sm border_no height-25 tehnic-input" data-key="location" data-parent="/site/tehnic-ajax" id="<?= $item->id ?>" value="<?= $item->location ?>">
							<?php else : ?>
								<?= $item->location ?>
							<?php endif; ?>
						</td>
						<!--    Местоположение    -->

						<!--    Номенклатура   -->
						<?php $none = in_array('nomen', $field_user) ? '' : 'display-none' ?>
						<td class="field-nomen <?= $none ?>">
							<?php if (Yii::$app->user->can('Admin')) : ?>
								<input class="form-control form-control-navbar form-control-sm border_no height-25 tehnic-input" data-key="nomen" data-parent="/site/tehnic-ajax" id="<?= $item->id ?>" value="<?= $item->nomen ?>">
							<?php else : ?>
								<?= $item->nomen ?>
							<?php endif; ?>
						</td>
						<!--    Номенклатура    -->


						<!--    На балансе   -->
						<?php $none = in_array('balance', $field_user) ? '' : 'display-none' ?>
						<td class="field-balance <?= $none ?>">
							<div class="icheck-primary d-inline ml-2">
								<input type="checkbox" class="check-balance height-25" value="" name="todo1" id="<?= $item->id ?>" <?= $item->balance ? "checked" : '' ?>>
								<label for="todoCheck1"></label>
							</div>
						</td>
						<!--    На балансе    -->

						<!--    Местоположение   -->
						<?php $none = in_array('date_admission', $field_user) ? '' : 'display-none' ?>
						<td class="field-date_admission <?= $none ?>"><?= $item->date_admission ?></td>
						<!--    Местоположение    -->

						<!--    Местоположение   -->
						<?php $none = in_array('act_num', $field_user) ? '' : 'display-none' ?>
						<td class="field-act_num <?= $none ?>">
							<?php if (Yii::$app->user->can('Admin')) : ?>
								<input class="form-control form-control-navbar form-control-sm border_no height-25 tehnic-input" data-key="act_num" data-parent="/site/tehnic-ajax" id="<?= $item->id ?>" value="<?= $item->act_num ?>">
							<?php else : ?>
								<?= $item->act_num ?>
							<?php endif; ?>
						</td>
						<!--    Местоположение    -->

						<!--    Местоположение   -->
						<?php $none = in_array('date_warranty', $field_user) ? '' : 'display-none' ?>
						<td class="field-date_warranty <?= $none ?>"><?= $item->date_warranty ?></td>
						<!--    Местоположение    -->

						<!--    Верификация   -->
						<?php $none = in_array('verification', $field_user) ? '' : 'display-none' ?>
						<td class="field-verification <?= $none ?>">
							<?= Html::dropDownList(
								'depart',
								'string',
								HwTehnic::getVerifictaion(),
								[
									'readonly' => $disabled, 'class' => 'form-control form-control-sm p-0 m-0 ', 'options' => [$item->verification => ['Selected' => true]],
									'onchange' => '$.post(" ' . Url::toRoute(['site/tehnic-update']) . '?id=' .$item->id . '&verification=' . '"+encodeURIComponent($(this).val()));'
								]
							)
							?>
						</td>
						<!--    Верификация    -->

						<!--    кнопки упарвления    -->
						<?php $none = in_array('button', $field_user) ? '' : 'display-none' ?>
						<td style="width: 100px" class=" field-button <?= $none ?>">

							<div class="tools">
								<a href="<?= \yii\helpers\Url::toRoute(['tehnic', 'update' => $item->id, 'id_user' => $id_user]) ?>" class="fas fa-edit"></a>
								<?= Html::a('<i class="fas fa-qrcode"></i>', [Url::to(['site/qcode', 'id_tehnic' => $item->id])], ['class' => 'py-0 px-1']); ?>

								<?php Redactor::begin(['role' => 'IT']) ?>
								<?= Html::button("<i class=\"fa fa-cog mr-1 " . $color_sp . "\"></i>", ['value' => Url::to(['/model/specification', 'id_model' => $item->id_model, 'id_tehnic' => $item->id, 'serial_array' => $serial_array]), 'class' => 'p-0 m-0 btn btn-sm btn-url modalButton-lg', 'title' => 'История заявки']); ?>
								<?php Redactor::end() ?>
							</div>
						</td>
						<!--    кнопки упарвления   -->

					</tr>
				<?php $_counter++;
				endforeach; ?>
			</tbody>
		</table>
	</div>
</div>