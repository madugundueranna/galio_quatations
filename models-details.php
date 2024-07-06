<?php
session_start();
include('admin/functions/functions.php');
include('config/dbcon.php');
include_once("header.php"); ?>
<div class="content_wrapper bg_homebefore pt-0">
	<div class="container-fluid">

		<div class="sec-title">
			<div class="row d-flex align-items-center">
				<div class="col-md-12 p-0">
					<div class="heading_home">

						<?php
						$status = 1;
						$sql_chlide_model_name = "SELECT * FROM `child_models` where model_id=" . $_GET['model_id'];
						$row_chlide_model_name = getQueryDataList($sql_chlide_model_name);
						$sql_model_name = "SELECT * FROM `models` WHERE company_id=" . $_GET['company_id'] . " and  status=" . $status;
						$row_model_name = getQueryDataList($sql_model_name);

						if ($row_chlide_model_name[0]['id']) {

							if (isset($_GET['company_id'])) {
								$sql_company_name = "SELECT * FROM `companies` WHERE id=" . $_GET['company_id'];
								$row_company_name  = getQueryData($sql_company_name);

								if ($row_company_name) {
						?>
									<!-- <h2><?php echo $row_company_name['name']; ?> - Models - Models</h2> -->
									<h2><?php echo $row_company_name['name']; ?> - <?php echo $row_model_name[0]['name']; ?> - Models</h2>
						<?php
								}
							}
						}
						?>
					</div>
				</div>
			</div>
		</div>

		<div class="content-bar">
			<!-- Start row -->
			<div class="row ">
				<!-- Start col -->
				<div class="col-lg-12 p-0">
					<div class="card p-2">
						<div class="card-body">
							<div class="table-responsive ">
								<?php
								// and status=$status";
								$status = 1;
								$sql_chlide_model_name = "SELECT * FROM `child_models` where model_id=" . $_GET['model_id'] . " and status=$status";

								$row_chlide_model_name = getQueryDataList($sql_chlide_model_name);

								if ($row_chlide_model_name) {
									foreach ($row_chlide_model_name as $value) {

								?>
										<table class="table table-bordered">
											<tr>
												<td>
													<a href="products.php?chlide_model_id=<?php echo $value['id']; ?>">
														<?php
														echo $value['name'];
														$sql_count_of_product_names = "SELECT COUNT(*) AS record_count
														FROM `products`
														WHERE chlide_model_id =" . $value['id'];
														$count_of_product_names_array = getQueryDataList($sql_count_of_product_names);
														$count_of_product_names = $count_of_product_names_array[0]['record_count'];
														?><span class="badge badge-primary float-right"><?php echo $count_of_product_names; ?></span>
													</a>
												</td>
											</tr>
									<?php
									}
								} else {

									echo '<p style="color: red;">Chlide Models are Not Available</p>';
								}

									?>

										</table>
							</div>
						</div>
					</div>


				</div>

			</div>
			<!-- End row -->
		</div>
		<!-- End Rightbar -->
	</div>
</div>



<?php include_once("footer.php") ?>