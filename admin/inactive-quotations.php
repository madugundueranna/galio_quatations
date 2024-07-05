<?php
session_start();
include('functions/functions.php');
include('../config/dbcon.php');
include('functions/session.php');
include('functions/myfunctions.php');
include('includes/header.php');
$error = array();
$search_Query = "";
$status = 0;
if (isset($_POST['search'])) {
	$start_date = $_POST['start_date'];
	$end_date = $_POST['end_date'];
	if (empty($_POST['start_date'])) {
		$error['start_date'] = "From Date is Required";
	}
	if (!empty($start_date) && !empty($end_date) && strtotime($start_date) > strtotime($end_date)) {
		$error['start_date'] = "From Date Greater than To Date";
	}
	if (count($error) == 0) {
		if (!empty($start_date) && !empty($end_date)) {
			$search_Query .= "AND q.created_at BETWEEN '$start_date' AND '$end_date'";
		} else if (!empty($start_date)) {
			$search_Query .= " AND DATE_FORMAT(q.created_at, '%Y-%m-%d') BETWEEN '$start_date' AND '" . date('Y-m-d') . "'";
		}
		if ($_SESSION['ROLE'] != 1) {
			$where_check = " AND q.created_by=" . $_SESSION['USER_ID'];
		} else {
			$where_check = " AND 1";
		}
		$sql_inactive_display = "SELECT b.name branch_name,q.* FROM `quotations` q 
        join employees e on e.id=q.`created_by`
        join branches b on b.id=e.branch_id
        where 1 $search_Query AND q.status=0 $where_check";
		$result_inactive_display = mysqli_query($conn, $sql_inactive_display);
	} else {
		if ($_SESSION['ROLE'] != 1) {
			$where_check = " AND q.created_by=" . $_SESSION['USER_ID'];
		} else {
			$where_check = " AND 1";
		}
		$sql_inactive_display = "SELECT b.name branch_name,q.* FROM `quotations` q 
        join employees e on e.id=q.`created_by`
        join branches b on b.id=e.branch_id
        where 1 $search_Query AND q.status=0 $where_check";
		$result_inactive_display = mysqli_query($conn, $sql_inactive_display);
	}
} else {
	if ($_SESSION['ROLE'] != 1) {
		$where_check = " AND q.created_by=" . $_SESSION['USER_ID'];
	} else {
		$where_check = " AND 1";
	}
	$sql_inactive_display = "SELECT b.name branch_name,q.* FROM `quotations` q 
	join employees e on e.id=q.`created_by`
	join branches b on b.id=e.branch_id
	where 1 $search_Query AND q.status=0 $where_check";
	$result_inactive_display = mysqli_query($conn, $sql_inactive_display);
}
if (isset($_GET['id'])) {
	$status = 0;
	$data['status'] = $status;
	$data['updated_at'] = date('Y-m-d H:i:s');
	$data['updated_by'] = $_SESSION['USER_ID'];
	$inactive_quotation_update_id = updateRecord('quotations', $data, $_GET['id']);
	if ($inactive_quotation_update_id) {
		redirecte("inactive-quotations.php", "Inactive Quotation Successfully");
	}
}
?>
<header>
	<style>
		.error {
			color: red;
		}
	</style>
</header>
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
							<a href="index.html">
								<i class="feather icon-home"></i>
							</a>
						</li>
						<li class="breadcrumb-item"><a href="#!">Deleted Quotations</a></li>
					</ul>
				</div>
			</div>
		</div>
	</div>
	<!-- [ breadcrumb ] end -->
	<div class="pcoded-inner-content">
		<!-- Main-body start -->
		<div class="main-body">
			<div class="page-wrapper">
				<form method="post">
					<!-- Page-body start -->
					<div class="page-body">
						<div class="row">
							<div class="col-md-12">
								<div class="card">
									<div class="card-block ">
										<div class="row">
											<div class="col-md-3">
												<label>From Date</label><span style="color: red;">*</span>
												<input type="date" name="start_date" class="form-control" value="<?php if (isset($_POST['start_date']))  echo $_POST['start_date']; ?>">
												<span class="error"><?php if (isset($error['start_date']))  echo $error['start_date']; ?></span>
											</div>
											<div class="col-md-3">
												<label>To Date</label>
												<input type="date" name="end_date" class="form-control" value="<?php if (isset($_POST['end_date']))  echo $_POST['end_date']; ?>">
												<span class="error"><?php if (isset($error['end_date']))  echo $error['end_date']; ?></span>
											</div>
											<div class="col-md-3 pt-4">
												<input type="submit" name="search" value="Search" class="btn btn-danger">
											</div>
										</div>
									</div>
								</div>
							</div>

				</form>
				<div class="col-md-12">
					<div class="card table-card">
						<div class="card-header bg-info">
							<h5>Deleted Quotations</h5>
						</div>
						<div class="card-block p-b-0">
							<div class="table-responsive">
								<table class="table table-bordered table-hover m-b-0" id="Inactive_quotations_table">
									<thead>
										<tr>
											<th>Branch</th>
											<th>Q.No</th>
											<th>Vehicle</th>
											<th>Created Date</th>
											<th>Actions</th>
										</tr>
									</thead>
									<tbody>
										<?php
										if ($result_inactive_display) {
											while ($row = mysqli_fetch_assoc($result_inactive_display)) {
										?>
												<tr>
													<td><?php echo $row['branch_name']; ?></td>
													<td><?php echo $row['quotation_no']; ?></td>
													<td><?php echo $row['vehicle_model']; ?></td>
													<td><?php echo date('d-m-Y', strtotime($row['created_at'])); ?></td>
													<td>
														<a data-toggle="modal" data-target="#large-Modal" onclick="GetDetails('<?php echo $row['id']; ?>')"><i class="feather icon-eye fa-2x" style="font-size: 1.5em;"></i></a>

														<!-- <a class="text-danger" href="inactive-quotations.php?id=<?php echo $row['id']; ?>"><i class="feather icon-trash-2"></i></a> -->
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
</div>
<!-- Page-body end -->

<div class="modal fade" id="large-Modal" tabindex="-1" role="dialog">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="quotation_no"></h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<p>Customer Name: <span class="text-danger" id="customer_name"></span></p>
				<p>Vehicle: <span class="text-danger" id="vehicle_model"></span></p>
				<p>Branch: <span class="text-danger" id="branch_name"></span></p>
				<p>Date: <span class="text-danger" id="quotation_created_at"></span></p>

				<div class="table-responsive" id="table_name">

				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default waves-effect " data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>
<script>
	

	function GetDetails(id) {
		$.ajax({
			type: "post",
			url: "ajax_list_quotations.php",
			data: {
				displayData: true,
				id: id
			},
			success: function(data) {
				var response = JSON.parse(data);

				
				$("#quotation_no").text("Quotation No: " + response.quotation_no);
				$("#customer_name").text(response.customer_name); // Changed from .val() to .text()
				$("#vehicle_model").text(response.vehicle_model); // Corrected the field name
				$("#branch_name").text(response.branch_name);
				$("#quotation_created_at").text(formatDate(response.quotation_created_at));

				// Handle items
				var table = '<table class="table table-bordered table-hover m-b-0">';
				table += '<thead><tr class="bg-info"><th>Item</th><th>Economy</th><th>Good</th><th>Premium</th></tr></thead>';
				table += '<tbody>';

				$.each(response.items, function(index, item) {
					var bestPriceFormatted = parseInt(item.best_price).toString();
					var economyFormatted = parseInt(item.economy).toString();
					var premiumFormatted = parseInt(item.premium).toString();

					table += '<tr><td>' + item.item_name + '</td><td>' + economyFormatted + '</td><td>' + bestPriceFormatted +
					 '</td><td>' + premiumFormatted + '</td></tr>' ;
				});



				

				// table += '<tfoot>';
				table += '<tr class="bg-light">';
				table += '<td colspan="1">Total</td>'; // For the empty cell in the first column
				table += '<td id="economy_total">' + response.economy_total + '</td>';
				table += '<td id="best_price_total">' + response.best_price_total + '</td>';
				table += '<td id="premium_total">' + response.premium_total + '</td>';
				table += '</tr>';
				// table += '</tfoot>';

				
				table += '<tr class="bg-light">';
				table += '<td colspan="3">Labour Charges</td>';
				table += '<td id="overall_total">' + response.labour_charges + '</td>';
				table += '</tr>';
				table += '</tfoot>';
			


				table += '<tfoot>';
				table += '<tr class="bg-light">';
				table += '<td colspan="3">Grand Total</td>';
				table += '<td id="overall_total">' + response.overall_total + '</td>';
				table += '</tr>';
				table += '</tfoot>';
				table += '</tbody>';

				// Add a row for overall total


				table += '</table>';

				$("#table_name").html(table);

			}
		});
	}

	const formatDate = dateString => {
		const date = new Date(dateString);
		const day = date.getDate().toString().padStart(2, '0');
		const month = (date.getMonth() + 1).toString().padStart(2, '0');
		const year = date.getFullYear();
		return `${day}-${month}-${year}`;
	};

	$(document).ready(function() {
		var table = $('#Inactive_quotations_table').DataTable({
			dom: 'lBifrtip',
			lengthMenu: [
				[5, 10, 25, 50, 100, -1],
				[5, 10, 25, 50, 100, "All"]
			],
			buttons: [{
					extend: 'excel',
					title: "DeletedQuotations",
					exportOptions: {
						columns: ":not(.not-export-column)"
					}
				},
				{
					extend: 'pdf',
					title: "DeletedQuotations",
					exportOptions: {
						columns: ":not(.not-export-column)"
					}
				}
			]
		});
	});
</script>
<?php include('includes/footer.php'); ?>