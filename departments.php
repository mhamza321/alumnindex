<?php include('admin/db_connect.php'); ?>

<div class="container-fluid">
	<br /><br /> <br /> <br />
	<div class="col-lg-12">
		<div class="row">
			<div class="col-md-3">
				<?php include 'navbar.php' ?>
			</div>
			<!-- FORM Panel -->
			<div class="col-md-4">
				<form action="" id="manage-dept">
					<div class="card">
						<div class="card-header">
							<?php //echo "Welcome back " . $_SESSION['login_org_id'] . "!"  
							?>
							<b>Add Department</b>
						</div>
						<div class="card-body">
							<input type="hidden" name="id">
							<input type="hidden" name="org_id" value="<?php echo $_SESSION['login_org_id'] ?>">
							<div class="form-group">
								<label class="control-label">Department Name</label>
								<input type="text" class="form-control" name="name">
							</div>

						</div>

						<div class="card-footer">
							<div class="row">
								<div class="col-md-12">
									<button class="btn btn-sm btn-primary col-sm-3 offset-md-3"> Save</button>
									<button class="btn btn-sm btn-default col-sm-3" type="button" onclick="$('#manage-dept').get(0).reset()"> Cancel</button>
								</div>
							</div>
						</div>
					</div>
				</form>
			</div>
			<!-- FORM Panel -->

			<!-- Table Panel -->
			<div class="col-md-5">
				<div class="card">
					<div class="card-header">
						<b>Department List</b>
					</div>
					<div class="card-body">
						<table class="table table-bordered table-hover">
							<thead>
								<tr>
									<th class="text-center">#</th>
									<th class="text-center">Department Name</th>
									<th class="text-center">Action</th>
								</tr>
							</thead>
							<tbody>
								<?php
								$i = 1;
								$course = $conn->query("SELECT * FROM departments where org_id = " . $_SESSION['login_org_id']);
								while ($row = $course->fetch_assoc()) :
								?>
									<tr>
										<td class="text-center"><?php echo $i++ ?></td>
										<td class="">
											<?php echo $row['name'] ?>
										</td>
										<td class="text-center">
											<button class="btn btn-sm btn-primary edit_course" type="button" data-id="<?php echo $row['id'] ?>" data-course="<?php echo $row['name'] ?>">Edit</button>
											<button class="btn btn-sm btn-danger delete_dept" type="button" data-id="<?php echo $row['id'] ?>">Delete</button>
										</td>
									</tr>
								<?php endwhile; ?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
			<!-- Table Panel -->
		</div>
	</div>

</div>
<style>
	td {
		vertical-align: middle !important;
	}
</style>
<script>
	$('#manage-dept').submit(function(e) {
		e.preventDefault()
		start_load()
		$.ajax({
			url: 'admin/ajax.php?action=save_dept',
			data: new FormData($(this)[0]),
			cache: false,
			contentType: false,
			processData: false,
			method: 'POST',
			type: 'POST',
			success: function(resp) {
				if (resp == 1) {
					alert_toast("Data successfully added", 'success')
					setTimeout(function() {
						location.reload()
					}, 1500)

				} else if (resp == 2) {
					alert_toast("Data successfully updated", 'success')
					setTimeout(function() {
						location.reload()
					}, 1500)

				}
			}
		})
	})
	$('.edit_course').click(function() {
		start_load()
		var cat = $('#manage-dept')
		cat.get(0).reset()
		cat.find("[name='id']").val($(this).attr('data-id'))
		cat.find("[name='name']").val($(this).attr('data-course'))
		end_load()
	})
	$('.delete_dept').click(function() {
		_conf("Are you sure to delete this course?", "delete_dept", [$(this).attr('data-id')])
	})

	function delete_dept($id) {
		start_load()
		$.ajax({
			url: 'admin/ajax.php?action=delete_dept',
			method: 'POST',
			data: {
				id: $id
			},
			success: function(resp) {
				if (resp == 1) {
					alert_toast("Data successfully deleted", 'success')
					setTimeout(function() {
						location.reload()
					}, 1500)

				}
			}
		})
	}
	// $('table').dataTable()
</script>