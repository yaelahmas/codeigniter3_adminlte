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
					<button type="button" class="btn btn-primary btn-create">Add New User</button>
				</h3>
			</div>
			<div class="box-body table-responsive">
				<table id="tableUser" class="table table-bordered table-striped">
					<thead>
						<tr>
							<th>No</th>
							<th>Image</th>
							<th>Name</th>
							<th>Email</th>
							<th>Role</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody></tbody>
				</table>
			</div>
		</div>
	</section>
</div>

<div class="modal fade" id="modal-form-user" style="display: none;">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title"></h4>
			</div>
			<?= form_open(current_url(), ['method' => 'post', 'id' => 'form-user', 'enctype' => 'multipart/form-data']); ?>
			<?= form_input(['type' => 'hidden', 'id' => 'user_id', 'name' => 'user_id']); ?>
			<div class="modal-body">
				<div id="message"></div>
				<div class="row">
					<div class="col-md-5">
						<div class="form-group">
							<label for="image">Image</label>
							<input type="file" class="d-none" style="opacity: 0;" name="image" id="image">
							<a href="javascript:void(0);" title="Change photo" onclick="event.preventDefault();document.getElementById('image').click();">
								<div class="modal-image-preview"></div>
							</a>
						</div>
					</div>
					<div class="col-md-7">
						<div class="form-group">
							<label for="name">Name</label>
							<input type="text" class="form-control" id="name" placeholder="Name" name="name">
							<div class="help-block"></div>
						</div>
						<div class="form-group">
							<label for="email">Email</label>
							<input type="text" class="form-control" id="email" placeholder="Email" name="email">
							<div class="help-block"></div>
						</div>
						<div class="form-group">
							<label for="role_id">Role</label>
							<select class="form-control" id="role_id" name="role_id" style="width: 100%;"></select>
							<div class="help-block"></div>
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="submit" class="btn btn-primary modal-button"></button>
				<button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
			</div>
			<?= form_close(); ?>
		</div>
	</div>
</div>
<script>
	var tableUser;
	var form;
	var formdata;
	var method;
	var url;

	$(document).ready(function() {
		$(document).on('change', '#image', function() {
			previewImageModal(this);
		});

		tableUser = $('#tableUser').DataTable({
			processing: true,
			serverSide: true,
			autoWidth: false,
			ajax: ({
				url: "<?= base_url('user/datatables'); ?>",
				dataType: "json",
			}),
			info: true,
			fnCreatedRow: function(row, data, index) {
				$('td', row).eq(0).html(index + 1);
			},
			columnDefs: [{
				orderable: false,
				targets: [0, 1, 5]
			}],
			order: [
				[2, 'asc']
			],
			destroy: true,
		});

		getSelectDataRole();

		$(document).on('click', '.btn-create', function(e) {
			e.preventDefault();
			method = 'create';

			$('#form-user')[0].reset();
			$('.form-group').removeClass('has-error');
			$('.help-block').empty();

			$('#modal-form-user').modal('show');
			$('.modal-title').text('Add New User');
			$('.modal-button').text('Save Data');
			$('.modal-image-preview').html(`<img style="width: 100%" src="<?= base_url('public/images/default.png'); ?>"/>`);
		});

		$(document).on('click', '.btn-edit', function(e) {
			e.preventDefault();
			method = 'update';

			$('.form-group').removeClass('has-error');
			$('.help-block').empty();

			$.ajax({
				type: "GET",
				url: "<?= base_url('user/get'); ?>",
				data: {
					user_id: $(this).data('id')
				},
				dataType: "json",
				success: function(response) {
					$('[name="user_id"]').val(response.user_id);
					$('[name="name"]').val(response.name);
					$('[name="email"]').val(response.email);
					$('[name="role_id"]').val(response.role_id).prop('selected', true);
					$('.modal-image-preview').html(`<img style="width: 100%" src="` + response.image + `"/>`);

					$('#modal-form-user').modal('show');
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
			var user_id = $(this).data('id');
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
					$.get("<?= base_url('user/delete'); ?>", {
							user_id: user_id
						},
						function(response, textStatus, jqXHR) {
							if (response.statusCode == 200) {
								Swal.fire({
									icon: "success",
									title: "Deleted!",
									text: response.message,
								});
								tableUser.ajax.reload(null, false);
							} else if (response.statusCode == 403) {
								Swal.fire({
									icon: "error",
									title: "Oops...",
									text: response.message,
								});
							} else if (response.statusCode == 500) {
								Swal.fire({
									icon: "error",
									title: "Oops...",
									text: response.message,
								});
								tableUser.ajax.reload(null, false);
							}
						},
						"json"
					);
				}
			});
			return false;
		});

		$(document).on('submit', '#form-user', function(e) {
			e.preventDefault();
			form = this;
			formdata = new FormData(form);

			if (method == 'create') {
				url = '<?= base_url('user/create'); ?>';
			} else {
				url = '<?= base_url('user/update'); ?>';
			}

			$.ajax({
				type: "POST",
				url: url,
				data: formdata,
				processData: false,
				contentType: false,
				dataType: "json",
				success: function(response) {
					$('.form-group').removeClass('has-error');
					$('.help-block').text('');
					if (response.statusCode == 200) {
						$('#modal-form-user').modal('hide');
						Swal.fire({
							icon: "success",
							title: "Good job!",
							text: response.message,
						});
						tableUser.ajax.reload(null, false);
					} else {
						if (response.statusCode == 400) {
							$.each(response.message, function(index, value) {
								$('#' + index).parent().find('.help-block').text(value);
								$('#' + index).parent().addClass(value.length > 0 ? 'has-error' : '');
							});
						} else if (response.statusCode == 403) {
							Swal.fire({
								icon: "error",
								title: "Oops...",
								text: response.message,
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

		function getSelectDataRole() {
			$.ajax({
				type: "GET",
				url: "<?= base_url('role/get'); ?>",
				dataType: "json",
				success: function(response) {
					var i;
					var html = '<option value="">--- Select ---</option>';
					for (i = 0; i < response.length; i++) {
						html += '<option value=' + response[i].role_id + '>' + response[i].role + '</option>';
					}
					$('#role_id').html(html);
				},
				error: function(xhr, ajaxOptions, thrownError) {
					console.log(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
				}
			});
			return false;
		}

		function previewImageModal(input) {
			if (input.files && input.files[0]) {
				var reader = new FileReader();
				reader.onload = function(e) {
					$('.modal-image-preview').html(`<img style="width: 100%" src="` + e.target.result + `"/>`);
				}
				reader.readAsDataURL(input.files[0]);
			}
		}
	});
</script>