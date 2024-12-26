<?php
// echo "<pre>";
// print_r($module);
// echo "</pre>";
// exit;
?>

<div class="modal fade" id="modal-form-role-access" style="display: none;" data-backdrop="static" data-keyboard="false">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title">Role Access : <?= $role['role']; ?> <input type="checkbox" class="check_all"></h4>
			</div>
			<?= form_open(current_url(), ['method' => 'post', 'id' => 'form-role-access']); ?>
			<?= form_input(['type' => 'hidden', 'name' => 'role_id', 'value' => $role['role_id']]); ?>
			<div class="modal-body">
				<table id="tableRoleAccess" class="table table-bordered table-striped">
					<thead>
						<tr>
							<th>Module</th>
							<th><input type="checkbox" class="check_column"> Access</th>
							<th>Create</th>
							<th>Read</th>
							<th>Update</th>
							<th>Delete</th>
						</tr>
					</thead>
					<tbody>
						<?php
						if (!empty($module)) {
							foreach ($module as $record) {
								$key = array_search($record['module'], array_column($roleAccess, 'module'));
								$matrix = (array) $roleAccess[$key];
						?>
								<tr>
									<td><b><?= $record['module'] ?></b> <input type="hidden" name="access[<?= $record['module'] ?>][module]" value="<?php echo $record['module'] ?>" /> </td>
									<td><input type='checkbox' class="check_row" name='access[<?= $record['module'] ?>][access]' <?= ($matrix['access'] == 1) ? 'checked' : ''; ?> /></td>
									<td><input type='checkbox' name='access[<?= $record['module'] ?>][create_records]' <?= ($matrix['create_records'] == 1) ? 'checked' : ''; ?> /></td>
									<td><input type='checkbox' name='access[<?= $record['module'] ?>][read_records]' <?= ($matrix['read_records'] == 1) ? 'checked' : ''; ?> /></td>
									<td><input type='checkbox' name='access[<?= $record['module'] ?>][update_records]' <?= ($matrix['update_records'] == 1) ? 'checked' : ''; ?> /></td>
									<td><input type='checkbox' name='access[<?= $record['module'] ?>][delete_records]' <?= ($matrix['delete_records'] == 1) ? 'checked' : ''; ?> /></td>
								</tr>
						<?php
							}
						}
						?>
					</tbody>
				</table>
			</div>
			<div class="modal-footer">
				<button type="submit" class="btn btn-primary btn-flat">Save Data</button>
				<button type="button" class="btn btn-danger btn-flat" data-dismiss="modal">Cancel</button>
			</div>
			<?= form_close(); ?>
		</div>
	</div>
</div>

<script>
	$(document).ready(function() {
		var table = $('#tableRoleAccess').DataTable({
			columnDefs: [{
				orderable: false,
				targets: [1, 2, 3, 4, 5]
			}],
			order: [
				[0, 'asc']
			],
		});

		$('.check_all').on('click', function() {
			$('input').prop('checked', this.checked);
		});

		$('.check_column').on('click', function() {
			var index = $(this).closest('tr').index();
			var isChecked = $(this).prop('checked');
			$('.check_row').each(function() {
				var checkboxes = $(this).closest('tr').find(':checkbox');
				checkboxes.eq(index).prop('checked', isChecked);
			});
		});

		$('.check_row').on('click', function() {
			var checkboxes = $(this).closest('tr').find(':checkbox');
			checkboxes.prop('checked', this.checked);
		});

		$(document).on('submit', '#form-role-access', function(e) {
			e.preventDefault();

			$.ajax({
				type: "POST",
				url: "<?= base_url('role/set-role-access') ?>",
				data: $(this).serialize(),
				dataType: "json",
				success: function(response) {
					$('#modal-form-role-access').modal('hide');
					if (response.statusCode == 200) {
						Swal.fire({
							position: "top-end",
							icon: "success",
							title: "Good job!",
							text: response.message,
							showConfirmButton: false,
							timer: 2500
						});
					} else {
						Swal.fire({
							position: "top-end",
							icon: "error",
							title: "Oops...",
							text: response.message,
							showConfirmButton: false,
							timer: 2500
						});
					}
				},
				error: function(xhr, ajaxOptions, thrownError) {
					console.log(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
				}
			});
			return false;
		});
	});
</script>
