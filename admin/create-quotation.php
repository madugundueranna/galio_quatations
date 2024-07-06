<?php
session_start();
include('functions/functions.php');
include('../config/dbcon.php');
include('functions/session.php');
include('functions/myfunctions.php');
include('includes/header.php');
$error = array();
$quotation_created_by = $_SESSION['USER_ID'];
if (isset($_POST["submit"])) {
	if (empty(trim($_POST["customer_name"]))) {
		$error["customer_name"] = "Customer Name is Required";
	} else {
		if (!preg_match("/^[a-zA-Z ]*$/", $_POST["customer_name"])) {
			$error["customer_name"] = "Only Letters and Space are Allowed";
		}
	}

	if (empty($_POST["phone_no"])) {
		$error["phone_no"] = "Phone Number is Required";
	} elseif (!preg_match('/^\d{10}$/', $_POST["phone_no"])) {
		$error["phone_no"] = "Phone Number Must be 10 Digits";
	}

	if (empty(trim($_POST["vehicle_model"]))) {
		$error["vehicle_model"] = "Vehicle Model is Required";
	}


	
	if (count($error) == 0) {

		$quotation_no = "";
		$old_string = "";

		if (isset($_SESSION['USER_ID'])) {
			$sql_quotation_no = "SELECT * FROM `employees` where id=" . $_SESSION['USER_ID'];
			$result_quotation_no = mysqli_query($conn, $sql_quotation_no);
			$branch_id = "";
			if ($row_quotatopn_no = mysqli_fetch_assoc($result_quotation_no)) {
				$branch_id = $row_quotatopn_no['branch_id'];
			}

			$sql_branch_name = "SELECT * FROM `branches` where id=" . $branch_id;
			$result_branch_name = mysqli_query($conn, $sql_branch_name);
			if ($row_branch_name = mysqli_fetch_assoc($result_branch_name)) {
				$branch_name = $row_branch_name['name'];
				$first_letter = strtoupper(substr($branch_name, 0, 1)); // Get the first letter and convert it to uppercase
				$result_string = $first_letter . '_';
				$old_string = $result_string;
			}
		}
		$sql_quotation_count_of_records = "SELECT *,count(*) FROM `quotations` where created_by=" . $_SESSION['USER_ID'];
		$result_quotation_count_of_record = mysqli_query($conn, $sql_quotation_count_of_records);
		if ($row_quotation_count_of_record = mysqli_fetch_assoc($result_quotation_count_of_record)) {
			$quotation_no = $old_string . sprintf('%d', $row_quotation_count_of_record['count(*)'] + 1);
		}


		$status = 1;
		$data['customer_name'] = $_POST["customer_name"];
		$data['phone_no'] = $_POST["phone_no"];
		$data['vehicle_model'] = $_POST["vehicle_model"];
		$data['quotation_no'] = $quotation_no;
		$data['total_quotation_value'] = $_POST["total-quotation-value"];
		$data['created_by'] = $_SESSION['USER_ID'];
		$data['total_economy'] = $_POST["economy_total"];
		$data['labour_charges'] = $_POST['labour_charges'];
		$data['grand_total'] = $_POST['grand_total'];
		$data['total_best_price'] = $_POST["best_total"];
		$data['total_premium'] = $_POST["premium_total"];
		$data['status'] = $status;
		$quotation_id = addRecord('quotations', $data);
		if ($quotation_id == TRUE) {
			$items = $_POST['name'];

			$data1['quotation_id'] = $quotation_id;
			for ($index = 0; $index < count($items); $index++) {
				$data1['economy'] = $_POST['economy_price'][$index];
				$data1['best_price'] = $_POST['best_price'][$index];
				$data1['premium'] = $_POST['premium_price'][$index];

				$name = $_POST['name'][$index];
				$sql_item_id = mysqli_query($conn, "SELECT id FROM items WHERE name='$name'");
				$num_item_id = mysqli_num_rows($sql_item_id);
				if ($num_item_id > 0) {
					$row_item_id = mysqli_fetch_assoc($sql_item_id);
					$data1['item_id'] = $row_item_id['id'];
					if (
						!empty($data1['economy']) || !empty($data1['best_price']) || !empty($data1['premium'])
					) {

						$item_id = addRecord('quotation_items', $data1);
					}
				}
			}
			redirecte("list-quotations.php", "Quotation is Created");
		} else {
			echo '<script>alert("Error inserting data: ' . $conn->error . '");</script>';
		}
	}
}

$status = 1;
$sql_items = "SELECT * FROM items WHERE status = $status ORDER BY priority,name";
$result_items = $conn->query($sql_items);
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
							<a href="index.php">
								<i class="feather icon-home"></i>
							</a>
						</li>
						<li class="breadcrumb-item"><a href="#!">Create Quotation</a></li>
					</ul>
				</div>
			</div>
		</div>
	</div>
	<!-- [ breadcrumb ] end -->
	<div class="pcoded-inner-content">
		<form action="" method="POST">
			<!-- Main-body start -->
			<div class="main-body">
				<div class="page-wrapper">
					<!-- Page-body start -->
					<div class="page-body">
						<div class="row">
							<div class="col-md-12">
								<div class="card">
									<div class="card-block ">
										<div class="row">
											<div class="col-md-3">
												<label>Customer Name</label>
												<input type="text" class="form-control" name="customer_name" id="" value="<?php if (isset($_POST["customer_name"])) echo $_POST["customer_name"] ?>">
												<span style="color:red;"><?php if (isset($error["customer_name"])) echo $error["customer_name"]; ?></span>
											</div>
											<div class="col-md-3 mt-2">
												<label>Ph No</label>
												<input type="number" class="form-control" name="phone_no" id="" value="<?php if (isset($_POST["phone_no"])) echo $_POST["phone_no"] ?>">
												<span style="color:red;"><?php if (isset($error["phone_no"])) echo $error["phone_no"]; ?></span>
											</div>
											<div class="col-md-3 mt-2">
												<label>Vehicle Type</label>
												<input type="text" class="form-control" name="vehicle_model" id="" value="<?php if (isset($_POST["vehicle_model"])) echo $_POST["vehicle_model"] ?>">
												<span style="color:red;"><?php if (isset($error["vehicle_model"])) echo $error["vehicle_model"]; ?></span>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="col-md-12">
								<div class="card">
									<div class="card-header bg-info">
										<h5>Create Quotation</h5>
									</div>
									<?php
									if ($result_items) {
									?>
										<div class="card-block p-0 m-0">
											<table class="table table-bordered table-responsive-md">
												<tr class="bg-light">
													<th>Item</th>
													<th>Economy</th>
													<th>Best Price</th>
													<th>Premium</th>
												</tr>
												<?php
												$economyTotal = 0;
												$bestTotal = 0;
												$premiumTotal = 0;

												$index = 0; // Initialize the index before the loop
												while ($row = $result_items->fetch_assoc()) {
												?>
													<tr>
														<td><?= $row['name']; ?></td>
														<input type="hidden" name="name[]" value="<?= $row['name']; ?>">

														<td><input type="number" class="form-control" name="economy_price[]" value="<?= isset($_POST['economy_price'][$index]) ? htmlspecialchars($_POST['economy_price'][$index]) : ''; ?>"></td>
														<td><input type="number" class="form-control" name="best_price[]" value="<?= isset($_POST['best_price'][$index]) ? htmlspecialchars($_POST['best_price'][$index]) : ''; ?>"></td>
														<td><input type="number" class="form-control" name="premium_price[]" value="<?= isset($_POST['premium_price'][$index]) ? htmlspecialchars($_POST['premium_price'][$index]) : ''; ?>"></td>
													</tr>
												<?php
													$index++; // Increment the index in each iteration
												}
												?>

												<tr class="bg-light">
													<td colspan="1" class="text-right">TOTAL</td>
													<td><input type="number" id="economy_total" name="economy_total" readonly value="<?= isset($_POST['economy_total']) ? htmlspecialchars($_POST['economy_total']) : ''; ?>"></td>
													<td><input type="number" id="best_total" name="best_total" readonly value="<?= isset($_POST['best_total']) ? htmlspecialchars($_POST['best_total']) : ''; ?>"></td>
													<td><input type="number" id="premium_total" name="premium_total" readonly value="<?= isset($_POST['premium_total']) ? htmlspecialchars($_POST['premium_total']) : ''; ?>"></td>
												</tr>


												<tr class="bg-light">
													<td colspan="1" class="text-right" style="<?= isset($_POST['total-quotation-value']) ? 'display: table-cell;' : 'display: none;'; ?>">
														TOTAL QUOTATION VALUE
													</td>
													<td colspan="3" style="<?= isset($_POST['total-quotation-value']) ? 'display: table-cell;' : 'display: none;'; ?>">
														<input type="number" id="total-quotation-value" name="total-quotation-value" readonly value="<?= isset($_POST['total-quotation-value']) ? htmlspecialchars($_POST['total-quotation-value']) : ''; ?>">
													</td>
												</tr>
												<tr class="bg-light">
													<td>Labour Charges</td>
													<td></td>
													<td></td>
													<td colspan="1"><input type="number" id="labour_charges" name="labour_charges" value="<?= isset($_POST['labour_charges']) ? htmlspecialchars($_POST['labour_charges']) : ''; ?>"></td>
												</tr>


												<tr class="bg-light">
													<td>Grand Total</td>
													<td></td>
													<td></td>
													<td colspan="1"><input type="number" id="grand_total" name="grand_total" value="<?= isset($_POST['grand_total']) ? htmlspecialchars($_POST['grand_total']) : ''; ?>"></td>
												</tr>

											</table>
										</div>
									<?php
									}
									?>
									<div class="card-footer text-right">
										<input type="submit" name="submit" value="Submit" class="btn btn-primary">
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<!-- Page-body end -->
		</form>
	</div>
</div>

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script>
	$(document).ready(function() {
		$('input[type="number"]').on('input', function() {
			updateTotals();
		});

		function updateTotals() {
			var totalEconomy = 0;
			var totalBestPrice = 0;
			var totalPremium = 0;
			$('input[name^="economy_price"]').each(function() {
				totalEconomy += parseFloat($(this).val()) || 0;
			});
			$('input[name^="best_price"]').each(function() {
				totalBestPrice += parseFloat($(this).val()) || 0;
			});
			$('input[name^="premium_price"]').each(function() {
				totalPremium += parseFloat($(this).val()) || 0;
			});
			$('#economy_total').val(Math.floor(totalEconomy.toFixed(2)));
			$('#best_total').val(Math.floor(totalBestPrice.toFixed(2)));
			$('#premium_total').val(Math.floor(totalPremium.toFixed(2)));
			var totalQuotationValue = totalEconomy + totalBestPrice + totalPremium;
			$('#total-quotation-value').val(Math.floor(totalQuotationValue.toFixed(2)));


			var labourCharges = parseFloat($('#labour_charges').val()) || 0;
			var grandTotal = labourCharges + totalEconomy + totalBestPrice + totalPremium;
			$('#grand_total').val(Math.floor(grandTotal.toFixed(2)));
		}
	});
	
</script>


<script>
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.querySelector('form');
        form.addEventListener('submit', function (event) {
            let allFieldsEmpty = true;
            const economyPrices = document.querySelectorAll('input[name="economy_price[]"]');
            const bestPrices = document.querySelectorAll('input[name="best_price[]"]');
            const premiumPrices = document.querySelectorAll('input[name="premium_price[]"]');

            for (let i = 0; i < economyPrices.length; i++) {
                if (economyPrices[i].value.trim() !== '' || bestPrices[i].value.trim() !== '' || premiumPrices[i].value.trim() !== '') {
                    allFieldsEmpty = false;
                    break;
                }
            }

            if (allFieldsEmpty) {
                alert('At least one field (Economy, Best Price, or Premium) must be filled for each item');
                event.preventDefault();
            }
        });
    });
</script>

<?php include_once("includes/footer.php") ?>