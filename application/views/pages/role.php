<div class="content-wrapper">
	<section class="content-header">
		<h1>
			Blank page
			<small>it all starts here</small>
		</h1>
		<ol class="breadcrumb">
			<li>
				<a href="#"><i class="fa fa-dashboard"></i> Home</a>
			</li>
			<li><a href="#">Examples</a></li>
			<li class="active">Blank page</li>
		</ol>
	</section>

	<section class="content">
		<div class="box">
			<div class="box-header with-border">
				<h3 class="box-title">
					<button type="button" class="btn btn-primary btn-flat btn-create">Add New Role</button>
				</h3>
			</div>
			<div class="box-body table-responsive">
				<table id="tableRole" class="table table-bordered table-striped">
					<thead>
						<tr>
							<th>No</th>
							<th>Role</th>
							<th>Default Module</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody></tbody>
				</table>
			</div>
		</div>
	</section>
</div>

<div class="modal fade" id="modal-form-role" style="display: none;">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title"></h4>
			</div>
			<?= form_open(current_url(), ['method' => 'post', 'id' => 'form-role']); ?>
			<?= form_input(['type' => 'hidden', 'id' => 'role_id', 'name' => 'role_id']); ?>
			<div class="modal-body">
				<div class="form-group">
					<label for="role">Role</label>
					<input type="text" class="form-control" id="role" placeholder="Role" name="role">
					<div class="help-block"></div>
				</div>
				<div class="form-group">
					<label for="module_id">Default Module</label>
					<select class="form-control select2" id="module_id" name="module_id" style="width: 100%;"></select>
					<div class="help-block"></div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="submit" class="btn btn-primary btn-flat modal-button"></button>
				<button type="button" class="btn btn-danger btn-flat" data-dismiss="modal">Cancel</button>
			</div>
			<?= form_close(); ?>
		</div>
	</div>
</div>

<div class="viewRoleAccess" style="display: none;"></div>
<script>
	var tableRole;
	var method;
	var url;

	$(document).ready(function() {
		getSelectDataModule();

		tableRole = $('#tableRole').DataTable({
			processing: true,
			serverSide: true,
			autoWidth: false,
			ajax: ({
				url: "<?= base_url('role/datatables'); ?>",
				dataType: "json",
			}),
			info: true,
			fnCreatedRow: function(row, data, index) {
				$('td', row).eq(0).html(index + 1);
			},
			columnDefs: [{
				orderable: false,
				targets: [0, 3]
			}],
			order: [
				[1, 'asc']
			],
			destroy: true,
		});

		$(document).on('click', '.btn-access', function(e) {
			$.ajax({
				type: "GET",
				url: "<?= base_url('role/get-role-access'); ?>",
				data: {
					role_id: $(this).data('id')
				},
				dataType: "json",
				success: function(response) {
					$('.viewRoleAccess').html(response.data).show();
					$('#modal-form-role-access').modal('show');
				},
				error: function(xhr, ajaxOptions, thrownError) {
					console.log(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
				}
			});
			return false;
		});

		$(document).on('click', '.btn-create', function(e) {
			method = 'create';
			$('#form-role')[0].reset();
			$('[name="module_id"]').select2().val('');

			$('.form-group').removeClass('has-error');
			$('.help-block').empty();

			$('#modal-form-role').modal('show');
			$('.modal-title').text('Add New Role');
			$('.modal-button').text('Save Data');
		});

		$(document).on('click', '.btn-edit', function(e) {
			e.preventDefault();
			method = 'update';

			$('#form-role')[0].reset();
			$('.form-group').removeClass('has-error');
			$('.help-block').empty();

			$.ajax({
				type: "GET",
				url: "<?= base_url('role/get'); ?>",
				data: {
					role_id: $(this).data('id')
				},
				dataType: "json",
				success: function(response) {
					$('[name="role_id"]').val(response.role_id);
					$('[name="role"]').val(response.role);
					$('[name="module_id"]').select2().val(response.module_id).trigger("change");

					$('#modal-form-role').modal('show');
					$('.modal-title').text('Edit Role');
					$('.modal-button').text('Edit Data');

				},
				error: function(xhr, ajaxOptions, thrownError) {
					console.log(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
				}
			});
			return false;
		});

		$(document).on('click', '.btn-delete', function(e) {
			e.preventDefault();
			var role_id = $(this).data('id');
			Swal.fire({
				icon: "warning",
				title: "Are you sure?",
				text: "Data will be deleted",
				showCancelButton: true,
				confirmButtonColor: "#3c8dbc",
				cancelButtonColor: "#dd4b39",
				confirmButtonText: "Yes, delete it",
			}).then((result) => {
				if (result.isConfirmed) {
					$.get("<?= base_url('role/delete'); ?>", {
							role_id: role_id
						},
						function(response, textStatus, jqXHR) {
							if (response.statusCode == 200) {
								Swal.fire({
									icon: "success",
									title: "Deleted!",
									text: response.message,
								});
								tableRole.ajax.reload(null, false);
							}

							if (response.statusCode == 500) {
								Swal.fire({
									icon: "error",
									title: "Oops...",
									text: response.message,
								});
								tableRole.ajax.reload(null, false);
							}
						},
						"json"
					);
				}
			});
			return false;
		});

		$(document).on('submit', '#form-role', function(e) {
			e.preventDefault();

			if (method == 'create') {
				url = '<?= base_url('role/create'); ?>';
			} else {
				url = '<?= base_url('role/update'); ?>';
			}

			$.ajax({
				type: "POST",
				url: url,
				data: $(this).serialize(),
				dataType: "json",
				success: function(response) {
					$('.form-group').removeClass('has-error');
					$('.help-block').text('');
					if (response.statusCode == 200) {
						$('#modal-form-role').modal('hide');
						Swal.fire({
							icon: "success",
							title: "Good job!",
							text: response.message,
						});
						tableRole.ajax.reload(null, false);
					} else {
						if (response.statusCode == 400) {
							$.each(response.message, function(index, value) {
								$('#' + index).parent().find('.help-block').text(value);
								$('#' + index).parent().addClass(value.length > 0 ? 'has-error' : '');
							});
						} else if (response.statusCode == 500) {
							Swal.fire({
								icon: "error",
								title: "Oops...",
								text: response.message,
							});
						}
					}
				},
				error: function(xhr, ajaxOptions, thrownError) {
					console.log(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
				}
			});
			return false;
		});

		function getSelectDataModule() {
			$.ajax({
				type: "GET",
				url: "<?= base_url('module/get'); ?>",
				dataType: "json",
				success: function(response) {
					$('.select2').select2();
					var i;
					var html = '<option value="">--- Select ---</option>';
					for (i = 0; i < response.length; i++) {
						html += '<option value=' + response[i].module_id + '>' + response[i].nama_module + '</option>';
					}
					$('#module_id').html(html);
				},
				error: function(xhr, ajaxOptions, thrownError) {
					console.log(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
				}
			});
			return false;
		}
	});
</script>