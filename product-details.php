<?php

session_start();
include('admin/functions/functions.php');
include('config/dbcon.php');
include_once("header.php");
$sql_product_details = "SELECT * FROM `products` where id=" . $_GET['product_id'];

$row_product_details = getQueryDataList($sql_product_details);



?>
<div class="content_wrapper bg_homebefore pt-0">
	<div class="container-fluid">
		<div class="row mt-2">
			<div class="col-sm-5 p-0 col-12">
				<?php
				if ((!empty($row_product_details[0]['chlide_model_id']))) {
				?>
					<a href="products.php?chlide_model_id=<?php echo $row_product_details[0]['chlide_model_id']; ?>"><i class="fa fa-arrow-left"> </i> Back</a>
				<?php
				} else {
				?>
					<a href="models-details.php?company_id=<?php echo $row_product_details[0]['company_id']; ?>&model_id=<?php echo $row_product_details[0]['model_id']; ?>"><i class="fa fa-arrow-left"></i> Back</a>

				<?php
				}
				?>


				<span class="float-right">
					<a href="javascript:void(0);" id="shareButton" onclick="shareProduct()">
						<i class="fa fa-share"></i>
					</a>
				</span>
			</div>
		</div>

		<div class="content-bar">
			<!-- Start row -->
			<div class="row">
				<!-- Start col -->
				<div class="col-lg-12 col-xl-3 p-1">

					<div class="card m-b-5">
						<div class="card-body">
							<?php
							if (!empty($row_product_details)) {

							?>
								<div class="product-img1">
									<img class="img-fluid" src="<?php echo 'admin/uploads/products/' . $row_product_details[0]['image']; ?>" />
								</div>
								<div class="product-content1">

									<table class="table table-stripped">
										<tr>
											<td>Category</td>
											<?php
											$sql_category_name = "SELECT * FROM `categories` where id=" . $row_product_details[0]['category_id'];

											$row_category_name  = getQueryData($sql_category_name);
											?>
											<td><?php echo $row_category_name['name']; ?></td>
										</tr>
										<tr>
											<td>Company</td>


											<?php
											$sql_Company_name = "SELECT * FROM `companies` where id=" . $row_product_details[0]['company_id'];

											$row_Company_name  = getQueryData($sql_Company_name);
											?>
											<td><?php echo $row_Company_name['name']; ?></td>
										</tr>

										<tr>
											<td>Car</td>

											<?php
											$sql_model_name = "SELECT * FROM `models` where id=" . $row_product_details[0]['model_id'];

											$row_model_name  = getQueryData($sql_model_name);
											?>
											<td><?php echo $row_model_name['name']; ?></td>

										</tr>
										<tr>
											<td>Product Name</td>
											<td><?php echo $row_product_details[0]['name']; ?></td>
										</tr>
										<tr>
											<td>Product Code</td>
											<td><?php echo $row_product_details[0]['code']; ?></td>
										</tr>
										<tr>
											<td>Packing Unit</td>
											<td><?php echo $row_product_details[0]['unit']; ?></td>
										</tr>
										<tr>
											<td>MRP</td>
											<td><?php echo number_format($row_product_details[0]['mrp'], 0, '', ''); ?></td>

										</tr>
									</table>
								<?php
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


<script>

	function shareProduct() {
		var imageSrc = '<?php echo 'admin/uploads/products/' . $row_product_details[0]['image'] . '?width=100&height=100'; ?>';
		var textContent = `
		Category: <?php echo $row_category_name['name']; ?>\n
		Company: <?php echo $row_Company_name['name']; ?>\n
		Car: <?php echo $row_model_name['name']; ?>\n
		Product Name: <?php echo $row_product_details[0]['name']; ?>\n
		Product Code: <?php echo $row_product_details[0]['code']; ?>\n
		Packing Unit: <?php echo $row_product_details[0]['unit']; ?>\n
		MRP: Rs.<?php echo number_format($row_product_details[0]['mrp'], 0, '', ''); ?>\n
	`;

		fetch(imageSrc)
			.then(response => response.blob())
			.then(blob => {
				var image = new Image();

				image.onload = function () {
					var canvas = document.createElement('canvas');
					var ctx = canvas.getContext('2d');

					canvas.width = image.width;
					canvas.height = image.height + 300;

					ctx.drawImage(image, 0, 0);

					ctx.font = '14px Arial';
					ctx.textAlign = 'left';
					ctx.fillStyle = 'black';

					var lines = textContent.split(/\r\n|\r|\n/);
					var lineHeight = 20;
					var y = image.height + lineHeight;
					lines.forEach(function (line) {
						ctx.fillText(line.trim(), 10, y);
						y += lineHeight;
					});

					var combinedImage = canvas.toDataURL();

					canvas.toBlob(function (blob) {
						navigator.share({
							title: 'Product Image',
							files: [new File([blob], 'product_image.png', { type: 'image/png' })]
						}).then(() => console.log('Shared successfully'))
							.catch((error) => console.error('Error sharing:', error));
					});
				};

				image.src = URL.createObjectURL(blob);
			})
			.catch(error => console.error('Error fetching image:', error));
	}


</script>






<?php include_once("footer.php") ?>