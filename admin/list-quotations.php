<?php
session_start();
include('../config/dbcon.php');
include('functions/session.php');
include('functions/myfunctions.php');
include('includes/header.php');
$error = array();
$search_Query = "";
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
			$search_Query .= " AND DATE_FORMAT(q.created_at, '%Y-%m-%d') BETWEEN '$start_date' AND '$end_date'";
		} else if (!empty($start_date)) {
			$search_Query .= " AND DATE_FORMAT(q.created_at, '%Y-%m-%d') BETWEEN '$start_date' AND '" . date('Y-m-d') . "'";
		}
		if ($_SESSION['ROLE'] != 1) {
			$where_check = " AND q.created_by=" . $_SESSION['USER_ID'];
		} else {
			$where_check = " AND 1";
		}

		$sql = "SELECT b.name branch_name,q.* FROM `quotations` q 
				join employees e on e.id=q.created_by
				join branches b on b.id=e.branch_id
				where 1 $search_Query AND q.status=1 $where_check";

		$result = mysqli_query($conn, $sql);
	} else {
		if ($_SESSION['ROLE'] != 1) {
			$where_check = " AND q.created_by=" . $_SESSION['USER_ID'];
		} else {
			$where_check = " AND 1";
		}
		$sql = "SELECT b.name branch_name,q.* FROM `quotations` q 
				join employees e on e.id=q.`created_by`
				join branches b on b.id=e.branch_id
				where 1 and q.status=1 $where_check";

		$result = mysqli_query($conn, $sql);
	}
} else {
	if ($_SESSION['ROLE'] != 1) {
		$where_check = " AND q.created_by=" . $_SESSION['USER_ID'];
	} else {
		$where_check = " AND 1";
	}
	$sql = "SELECT b.name branch_name,q.* FROM `quotations` q 
	join employees e on e.id=q.`created_by`
	join branches b on b.id=e.branch_id
	where 1 and q.status=1 $where_check";

	$result = mysqli_query($conn, $sql);
}
?>
<header>
	<style>
		.error {
			color: red;
		}

		.share-button {
			color: #007bff;
			/* Adjust the color as needed */
			cursor: pointer;
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
							<i class="feather icon-home"></i>
						</li>
						<li class="breadcrumb-item"><a href="#!">Quotations</a></li>
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
												<!-- <a href="#" class="btn btn-danger" name="search">Search</a> -->
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
							<h5>Quotations</h5>
						</div>
						<div class="card-block p-b-0">
							<div class="table-responsive">
								<table class="table table-bordered table-hover m-b-0" id="Listquotations_table">
									<thead>
										<tr>
											<th>Branch</th>
											<th>Q.No</th>
											<th>Vehicle</th>
											<th>Created Date</th>
											<th class="not-export-column">Actions</th>
										</tr>
									</thead>
									<tbody>
										<?php

										if ($result) {
											while ($row = mysqli_fetch_assoc($result)) {
										?>
												<tr>
													<td><?php echo $row['branch_name']; ?></td>
													<td><?php echo $row['quotation_no']; ?></td>
													<td><?php echo $row['vehicle_model']; ?></td>
													<td><?php echo date('d-m-Y', strtotime($row['created_at'])); ?></td>

													<td>
														<a data-toggle="modal" data-target="#large-Modal" onclick="GetDetails('<?php echo $row['id']; ?>')"><i class="feather icon-eye fa-2x" style="font-size: 1.5em;"></i></a>
														<span style="margin: 0 5px;"></span>
														<!-- <a class="text-danger" href="inactive-quotations.php?id=<?php echo $row['id']; ?>"><i class="feather icon-trash-2 fa-2x" style="font-size: 1.5em;"></i></a> -->

														<a class="text-danger" onclick="confirmDelete('<?php echo $row['id']; ?>')">
															<i class="fas fa-toggle-on fa-2x" style="font-size: 1.5em;"></i>
														</a>


														<span style="margin: 0 5px;"></span>

														<a href="javascript:void(0);" onclick="shareQuotation('<?php echo $row['id']; ?>')">
															<i class="fas fa-share"></i>
														</a>
														<span style="margin: 0 5px;"></span>

														<a href="javascript:void(0);" onclick="printQuotation('<?php echo $row['id']; ?>')">
															<i class="fas fa-print"></i>
														</a>
													</td>
													<!-- Inside the loop -->

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
				<h5 class="modal-title" id="quotation_no"><span></span></h5>
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

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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
						'</td><td>' + premiumFormatted + '</td></tr>';
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
				table += '<td id="overall_total">' + parseInt(response.labour_charges) + '</td>';
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



	function confirmDelete(id) {
		Swal.fire({
			title: 'Are you sure?',
			text: 'You want to inactive the quotation!',
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#d33',
			cancelButtonColor: '#3085d6',
			confirmButtonText: 'Yes'
		}).then((result) => {
			if (result.isConfirmed) {
				// If the user clicks Yes, redirect to the delete page with the id
				window.location.href = 'inactive-quotations.php?id=' + id;
			}
		});
	}


	function shareQuotation(id) {
		$.ajax({
			type: "post",
			url: "ajax_list_quotations.php",
			data: {
				displayData: true,
				id: id
			},
			success: function(data) {
				var response = JSON.parse(data);

				var textToShare = `
Customer Name: ${response.customer_name}
Vehicle Model: ${response.vehicle_model}
Branch Name : ${response.branch_name}

Items:
${padRight('Item Name', 25)} | ${padRight('Economy', 10)} | ${padRight('Best Price', 10)} | ${padRight('Premium', 10)}
${response.items.map(item => `
${padRight(item.item_name, 25)} | ${padRight(removeDecimal(item.economy), 10)} | ${padRight(removeDecimal(item.best_price), 10)} | ${padRight(removeDecimal(item.premium), 10)}
`).join('')}
----------------------------------------------------
${padRight('Economy Total:', 25)} ${padRight(removeDecimal(response.economy_total), 10)}
${padRight('Best Price Total:', 25)} ${padRight(removeDecimal(response.best_price_total), 10)}
${padRight('Premium Total:', 25)} ${padRight(removeDecimal(response.premium_total), 10)}
${padRight('Labour Charges:', 25)} ${padRight(removeDecimal(response.labour_charges), 10)}
${padRight('Grand Total:', 25)} ${padRight(removeDecimal(response.overall_total), 10)}
`;

				// Attempt to use the Share API
				if (navigator.share) {
					navigator.share({
						title: 'Quotation Details',
						text: textToShare
					}).then(() => {
						console.log('Shared successfully');
					}).catch((error) => {
						console.error('Error sharing:', error);
					});
				} else {
					copyTextToClipboard(textToShare);
					alert('Quotation details copied to clipboard. You can now paste it in other apps.');
				}
			}
		});
	}

	function copyTextToClipboard(text) {
		var textarea = document.createElement('textarea');
		textarea.value = text;
		document.body.appendChild(textarea);
		textarea.select();
		document.execCommand('copy');
		document.body.removeChild(textarea);
	}

	function padLeft(value, length) {
		return String(value).padStart(length);
	}

	function padRight(value, length) {
		return String(value).padEnd(length);
	}

	function removeDecimal(value) {
		// Remove decimal part and return the integer
		return parseInt(value, 10);
	}



	function printQuotation(id) {
		$.ajax({
			type: "post",
			url: "ajax_list_quotations.php",
			data: {
				displayData: true,
				id: id
			},
			success: function(data) {
				var response = JSON.parse(data);

				// Create a hidden iframe
				var printFrame = document.createElement('iframe');
				printFrame.style.visibility = 'hidden';
				document.body.appendChild(printFrame);

				// Display customer name and vehicle model
				printFrame.contentDocument.write('<p>Customer Name: ' + response.customer_name + '</p>');
				printFrame.contentDocument.write('<p>Branch Name: ' + response.branch_name + '</p>');
				printFrame.contentDocument.write('<p>Vehicle Model: ' + response.vehicle_model + '</p>');

				// Create the table as you already did
				var table = '<table class="table table-bordered table-hover m-b-0">';
				table += '<thead><tr class="bg-info"><th>Item</th><th>Economy</th><th>Good</th><th>Premium</th></tr></thead>';
				table += '<tbody>';

				$.each(response.items, function(index, item) {
					var bestPriceFormatted = parseInt(item.best_price).toString();
					var economyFormatted = parseInt(item.economy).toString();
					var premiumFormatted = parseInt(item.premium).toString();

					table += '<tr><td>' + item.item_name + '</td><td>' + economyFormatted + '</td><td>' + bestPriceFormatted + '</td><td>' + premiumFormatted + '</td></tr>';
				});

				// table += '<tfoot>';
				table += '<tr class="bg-light">';
				table += '<td>Total</td>'; // For the empty cell in the first column
				table += '<td id="economy_total">' + response.economy_total + '</td>';
				table += '<td id="best_price_total">' + response.best_price_total + '</td>';
				table += '<td id="premium_total">' + response.premium_total + '</td>';
				table += '</tr>';
				// table += '</tfoot>';

				table += '<tr class="bg-light">';
				table += '<td colspan="3">Labour Charges</td>';
				table += '<td id="overall_total">' + parseInt(response.labour_charges) + '</td>';
				table += '</tr>';
				table += '</tfoot>';



				table += '<tfoot>';
				table += '<tr class="bg-light">';
				table += '<td colspan="3">Grand Total</td>';
				table += '<td id="overall_total">' + response.overall_total + '</td>';
				table += '</tr>';
				table += '</tfoot>';



				table += '</tbody>';
				table += '</table>';

				// Append the table to the iframe
				printFrame.contentDocument.write(table);

				// Print the iframe content
				printFrame.contentWindow.print();

				// Remove the iframe from the document
				document.body.removeChild(printFrame);
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
		var table = $('#Listquotations_table').DataTable({
			dom: 'lBifrtip',
			lengthMenu: [
				[5, 10, 25, 50, 100, -1],
				[5, 10, 25, 50, 100, "All"]
			],
			buttons: [{
					extend: 'excel',
					title: "ListQuotations",
					exportOptions: {
						columns: ":not(.not-export-column)"
					}
				},
				{
					extend: 'pdf',
					title: "ListQuotations",
					exportOptions: {
						columns: ":not(.not-export-column)"
					}
				}
			]
		});
	});
</script>
<?php include('includes/footer.php'); ?>