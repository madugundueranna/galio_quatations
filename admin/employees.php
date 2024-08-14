<?php
session_start();
include('functions/functions.php');
include('../config/dbcon.php');
include('functions/session.php');
include('functions/myfunctions.php');
include_once("includes/header.php");
$sql = "SELECT e.*,b.name branch_name FROM  `employees` AS e 
JOIN  branches AS b ON b.id=e.branch_id";
$result = mysqli_query($conn, $sql);


if (isset($_GET['deleteID']) && isset($_GET['status'])) {
	$data['status'] = $_GET['status'];
	$inactive_employee_update_id = updateRecord('employees', $data, $_GET['deleteID']);
	if ($inactive_employee_update_id) {
		redirecte("employees.php", "Employee Inactive Successfully");
	}
}
?>
<?php include('includes/sidebar.php'); ?>
<!-- [ navigation menu ] end -->
<div class="pcoded-content">
	<!-- [ breadcrumb ] start -->
	<div class="page-header">
		<div class="page-block">
			<div class="row align-items-center">
				<div class="col-md-12">
					<ul class="breadcrumb">
						<li class="breadcrumb-item">
							<i class="feather icon-home"></i>
						</li>
						<li class="breadcrumb-item"><a href="employees.php">Employees</a></li>
					</ul>
					<span class="fa-pull-right">
						<a href="employee.php" class="btn btn-danger btn-sm"><i>add employee</i></a></span>
				</div>
			</div>
		</div>
	</div>
	<!-- [ breadcrumb ] end -->
	<div class="pcoded-inner-content">
		<!-- Main-body start -->
		<div class="main-body">
			<div class="page-wrapper">

				<!-- Page-body start -->
				<div class="page-body">
					<form method="post">

						<div class="row">
							<div class="col-md-12">
								<div class="card table-card">
									<div class="card-header bg-info">
										<h5>Employees</h5>
									</div>
									<div class="card-block p-b-0">
										<div class="table-responsive">
											<table class="table table-bordered table-hover m-b-0" id="employees_table">
												<thead>

													<tr>
														<th>S.NO</th>
														<th>Name</th>
														<th>Ph No</th>
														<th>Branch</th>
														<th>Created Date</th>
														<th>Status</th>
														<th class="not-export-column">Actions</th>
													</tr>
												</thead>
												<tbody>
													<?php
													if ($result) {
														$i = 1;
														while ($row = mysqli_fetch_assoc($result)) {

													?>
															<tr>
																<td><?php echo $i++; ?></td>
																<td><?php echo $row["name"]; ?></td>
																<td><?php echo $row["phone"]; ?></td>
																<td><?php echo $row['branch_name']; ?></td>
																<td><?php echo date('d-m-Y', strtotime($row['created_at'])); ?></td>
																<td><?php if ($row['status'] == 1) echo "Active";
																	else echo "InActive" ?></td>

																<td>
																	<a class="btn btn-primary" href="employee.php?id=<?php echo $row["id"] ?>">Edit</a>

																	<!-- <input style="font-size: 32px; padding: 15px;" class="fas fa-toggle-on" type="checkbox" name="flexSwitchCheckDefault1" id="flexSwitchCheckDefault1" <?php if ($row["status"] == 1) echo 'checked'; ?> onclick="inactiveEmployee('<?php echo $row['id']; ?>')"> -->
																	<a class="text-danger" id="flexSwitchCheckDefault1_<?php echo $row['id']; ?>" onclick="inactiveEmployee('<?php echo $row['id']; ?>', <?php echo $row['status']; ?>)">
																		<i class="<?php echo $row['status'] == 1 ? 'fas fa-toggle-on' : 'fas fa-toggle-off'; ?> fa-2x" style="font-size: 1.5em;"></i>
																	</a>

																</td>
															</tr>
													<?php
														}
													}
													?>
												</tbody>
											</table>
										</div>
									</div>
								</div>
							</div>
						</div>
				</div>
			</div>
			</form>
		</div>
		<!-- Page-body end -->
	</div>



	<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
	<script>
		$(document).ready(function() {
			$('#select-all').change(function() {
				var isChecked = $(this).prop('checked');
				$('.checkbox').prop('checked', isChecked);
			});
		});

		$(document).ready(function() {
			var table = $('#employees_table').DataTable({
				dom: 'lBifrtip',
				lengthMenu: [
					[5, 10, 25, 50, 100, -1],
					[5, 10, 25, 50, 100, "All"]
				],
				buttons: [{
						extend: 'excel',
						title: "EmployeeDetails",
						exportOptions: {
							columns: ":not(.not-export-column)"
						}
					},
					{
						extend: 'pdf',
						title: "EmployeeDetails",
						exportOptions: {
							columns: ":not(.not-export-column)"
						}
					}
				]
			});
		});


		function inactiveEmployee(id, currentStatus) {
			var status = currentStatus ? 0 : 1; // Toggle the status

			var text = status ? 'You want to Activate the Employee!' : 'You want to Inactivate the Employee!';

			Swal.fire({
				title: 'Are you sure?',
				text: text,
				icon: 'warning',
				showCancelButton: true,
				confirmButtonColor: '#d33',
				cancelButtonColor: '#3085d6',
				confirmButtonText: 'Yes'
			}).then((result) => {
				if (result.isConfirmed) {
					window.location.href = 'employees.php?deleteID=' + id + '&status=' + status;
				}
			});
		}
	</script>
	<?php include_once("includes/footer.php") ?>