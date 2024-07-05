<?php
session_start();
include('admin/functions/functions.php');
include('config/dbcon.php');

include_once("header.php");
?>
<div class="content_wrapper bg_homebefore pt-0">
	<div class="container-fluid">

		<div class="sec-title">
			<div class="row d-flex align-items-center">
				<div class="col-md-12 p-0">
					<div class="heading_home">
						<?php
						if (isset($_GET['company_id'])) {
							$sql_company_name = "SELECT * FROM `companies` WHERE id=" . $_GET['company_id'];
							$row_company_name  = getQueryData($sql_company_name);
							if ($row_company_name) {
						?>
								<h2><?php echo $row_company_name['name']; ?> - Models</h2>
						<?php
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
							<div class="table-responsive">
								<?php
								$sql_model_name = "SELECT * FROM `models` WHERE company_id=" . $_GET['company_id'];
								$row_model_name = getQueryDataList($sql_model_name);
								if (!empty($row_model_name)) {
									foreach ($row_model_name as $value) {
										$model_id = $value['id'];
										$model_name = $value['name'];

										// Count of child models
										$sql_count_of_child_models = "SELECT COUNT(*) AS record_count FROM `child_models` WHERE model_id = $model_id";
										$count_of_child_models = getQueryDataList($sql_count_of_child_models);
										$count_of_child_models = $count_of_child_models[0]['record_count'];

										// Count of products
										if (empty($count_of_child_models)) {
											$sql_count_of_products = "SELECT COUNT(*) AS record_count FROM `products` WHERE model_id = $model_id";
											$count_of_products = getQueryDataList($sql_count_of_products);
											$count_of_child_models = $count_of_products[0]['record_count'];
										}
								?>

										<table class="table table-bordered">
											<tr>
												<td>
													<a href="models-details.php?company_id=<?php echo $value['company_id']; ?>&amp;model_id=<?php echo $model_id; ?>">
														<?php echo $model_name; ?>
														<span class="badge badge-primary float-right"><?php echo $count_of_child_models; ?></span>
													</a>
												</td>
											</tr>
										</table>
								<?php
									}
								}
								?>
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
</div>
</div>
</div>
<?php include_once("footer.php") ?>