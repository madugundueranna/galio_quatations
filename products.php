<?php
session_start();
include('admin/functions/functions.php');
include('config/dbcon.php');
include_once("header.php");

?>
<div class="content_wrapper bg_homebefore pt-0">
	<div class="container-fluid">
		<div class="row mt-2">
			<div class="col-sm-5 p-0 col-12">

				<?php

				$sql_product_name = "SELECT * FROM products where chlide_model_id=" . $_GET['chlide_model_id'];
				$result_product_name = mysqli_query($conn, $sql_product_name);

				if (mysqli_num_rows($result_product_name) > 0) {
				?>
					<select name="product_name" id="product_name" class="form-control" onchange="updateRelatedCards()">
						<option value="all">Select Accessories Name</option>
						<?php
						while ($row = mysqli_fetch_assoc($result_product_name)) {
							echo '<option value="' . $row['id'] . '">' . $row['name'] . '</option>';
						}
						?>
					</select>
				<?php
				} else {
					echo '<p style="color: red;">Products is Not Available</p>';
				}

				?>
			</div>
		</div>
		<?php
		if (mysqli_num_rows($result_product_name) > 0) {
		?>
			<div class="sec-title">
				<div class="row d-flex align-items-center">
					<div class="col-md-12 p-0">
						<div class="heading_home">
							<h2>Search by Company <span class="float-right"><a href="products.php?chlide_model_id=<?php echo $_GET['chlide_model_id']; ?>">View All</a></span></h2>

						</div>
					</div>
				</div>
			</div>

		<?php
		}
		?>


		<div class="content-bar">
			<!-- Start row -->
			<div class="row" id="relatedCardsContainer">
				<!-- Start col -->

				<?php
				$sql_product_name = "SELECT * FROM `products` where chlide_model_id=" . $_GET['chlide_model_id'];

				$row_product_name = getQueryDataList($sql_product_name);
				if ($row_product_name) {
					foreach ($row_product_name as $value) {

				?>
						<div class="col-lg-12 col-xl-3 col-6 p-1">
							<a href="product-details.php?product_id=<?php echo $value['id']; ?>">
								<div class="card m-b-5">
									<div class="card-body">
										<div class="product-img">
											<img class="img-fluid" src="<?php echo 'admin/uploads/products/' . $value['image']; ?>" />
										</div>
										<div class="product-content">
											<!-- <h6>Creta</h6> -->
											<h6><?php echo $value['name'] ?></h6>
											<!-- <p>Body Graphics</p> -->
											<?php
											$sql_category_name = "SELECT * FROM `categories` where id=" . $value['category_id'];
											$row_category_name  = getQueryData($sql_category_name);
											?>
											<p><?php echo $row_category_name['name']; ?></p>
											<!-- <P>GLS-214 R</P> -->
											<p><?php echo $value['code']; ?></p>
											<p class="text-danger">Mrp: Rs. <?php echo $value['mrp']; ?></p>

										</div>
									</div>
								</div>
							</a>
						</div>

				<?php
					}
				}
				?>


			</div>
			<!-- End row -->


		</div>
		<!-- End Rightbar -->
	</div>


</div>


<script>
	function updateRelatedCards() {
		var selectedProductId = document.getElementById('product_name').value;
		var chlideModelId = '<?php echo $_GET['chlide_model_id']; ?>'; // Get the chlide_model_id

		// Make an AJAX request to fetch related cards based on the selected accessory name and chlide_model_id
		var xmlhttp = new XMLHttpRequest();
		xmlhttp.onreadystatechange = function() {
			if (this.readyState == 4 && this.status == 200) {
				// Update the content of the related cards container
				document.getElementById('relatedCardsContainer').innerHTML = this.responseText;
			}
		};
		xmlhttp.open("GET", "get_related_cards.php?chlide_model_id=" + chlideModelId + "&product_id=" + selectedProductId, true);
		xmlhttp.send();
	}
</script>

<?php include_once("footer.php") ?>