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
					<button type="button" class="btn btn-primary btn-flat btn-create">Add New Module</button>
				</h3>
			</div>
			<div class="box-body table-responsive">
				<table id="tableModule" class="table table-bordered table-striped">
					<thead>
						<tr>
							<th>No</th>
							<th>Module</th>
							<th>URL</th>
							<th>Status</th>
							<th>Login?</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody></tbody>
				</table>
			</div>
		</div>
	</section>
</div>

<div class="modal fade" id="modal-form-module" style="display: none;">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title"></h4>
			</div>
			<?= form_open(current_url(), ['method' => 'post', 'id' => 'form-module']); ?>
			<?= form_input(['type' => 'hidden', 'id' => 'module_id', 'name' => 'module_id']); ?>
			<?= form_input(['type' => 'hidden', 'class' => 'url_module', 'name' => 'url_module']); ?>
			<div class="modal-body">
				<div id="message"></div>
				<div class="form-group">
					<label for="nama_module">Name module</label>
					<input type="text" class="form-control" id="nama_module" placeholder="Name module" name="nama_module">
					<div class="help-block"></div>
				</div>
				<div class="form-group">
					<input type="text" class="form-control url_module" placeholder="URL module" disabled="disabled">
				</div>
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label for="status_module">Status</label>
							<select class="form-control" id="status_module" name="status_module">
								<option value="">--- Select ---</option>
								<option value="active">Active</option>
								<option value="not active">Not Active</option>
								<option value="under development">Under Development</option>
							</select>
							<div class="help-block"></div>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label for="login">Login?</label>
							<select class="form-control" id="login" name="login">
								<option value="">--- Select ---</option>
								<option value="yes">Yes</option>
								<option value="no">No</option>
								<option value="restrict">Restrict</option>
							</select>
							<div class="help-block"></div>
						</div>
					</div>
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
<script>
	var tableModule;
	var method;
	var url;

	$(document).ready(function() {
		$('#nama_module').on('keyup', function() {
			var nama_module = $(this).val().toLowerCase().replace(/[&\/\\#^, +()$~%.'":*?<>{}]/g, '-');
			$('.url_module').val(nama_module);
		});

		tableModule = $('#tableModule').DataTable({
			processing: true,
			serverSide: true,
			autoWidth: false,
			ajax: ({
				url: "<?= base_url('module/datatables'); ?>",
				dataType: "json",
			}),
			info: true,
			fnCreatedRow: function(row, data, index) {
				$('td', row).eq(0).html(index + 1);
			},
			columnDefs: [{
				orderable: false,
				targets: [0, 5]
			}],
			order: [
				[1, 'asc']
			],
			destroy: true,
		});

		$(document).on('click', '.btn-create', function(e) {
			method = 'create';
			$('#form-module')[0].reset();

			$('.form-group').removeClass('has-error');
			$('.help-block').empty();

			$('#modal-form-module').modal('show');
			$('.modal-title').text('Add New Module');
			$('.modal-button').text('Save Data');
		});

		$(document).on('click', '.btn-edit', function(e) {
			e.preventDefault();
			method = 'update';

			$('#form-module')[0].reset();
			$('.form-group').removeClass('has-error');
			$('.help-block').empty();

			$.ajax({
				type: "GET",
				url: "<?= base_url('module/get'); ?>",
				data: {
					module_id: $(this).data('id')
				},
				dataType: "json",
				success: function(response) {
					$('[name="module_id"]').val(response.module_id);
					$('[name="nama_module"]').val(response.nama_module);
					$('.url_module').val(response.url_module);
					$('[name="status_module"]').val(response.status_module).prop('selected', true);
					$('[name="login"]').val(response.login).prop('selected', true);

					$('#modal-form-module').modal('show');
					$('.modal-title').text('Edit Module');
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
			var module_id = $(this).data('id');
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
					$.get("<?= base_url('module/delete'); ?>", {
							module_id: module_id
						},
						function(response, textStatus, jqXHR) {
							if (response.statusCode == 200) {
								Swal.fire({
									icon: "success",
									title: "Deleted!",
									text: response.message,
								});
								tableModule.ajax.reload(null, false);
							}

							if (response.statusCode == 500) {
								Swal.fire({
									icon: "error",
									title: "Oops...",
									text: response.message,
								});
								tableModule.ajax.reload(null, false);
							}
						},
						"json"
					);
				}
			});
			return false;
		});

		$(document).on('submit', '#form-module', function(e) {
			e.preventDefault();

			if (method == 'create') {
				url = '<?= base_url('module/create'); ?>';
			} else {
				url = '<?= base_url('module/update'); ?>';
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
						$('#modal-form-module').modal('hide');
						Swal.fire({
							icon: "success",
							title: "Good job!",
							text: response.message,
						});
						tableModule.ajax.reload(null, false);
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
	});
</script>