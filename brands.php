<?php

include('admin/functions/functions.php');
include('config/dbcon.php');
include_once("header.php");

if (isset($_POST['search_button'])) {
    $searchTerm = mysqli_real_escape_string($conn, $_POST['search_term']);

    $sql_brand_name = "SELECT * FROM `brands`  WHERE name LIKE '%$searchTerm%'";


    $result_brand_name = getQueryDataList($sql_brand_name);
} else {
    $sql_brand_name = "SELECT * FROM `brands` ";
    $result_brand_name = getQueryDataList($sql_brand_name);
}
?>
<div class="content_wrapper bg_homebefore pt-0">
    <div class="container-fluid">

        <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <div class="row mt-2">
                <div class="col-sm-5 p-0 col-10">
                    <input type="text" class="form-control" placeholder="Search Brand" name="search_term">
                </div>
                <div class="col-sm-2 p-0 col-2">
                    <button type="submit" class="btn btn-danger btn-block" name="search_button">
                        <i class="fa fa-search"></i>
                    </button>
                </div>
            </div>
        </form>


        <div class="sec-title">
            <div class="row d-flex align-items-center">
                <div class="col-md-12 p-0">
                    <div class="heading_home">
                        <h2>Search by Brands</h2>

                    </div>
                </div>
            </div>
        </div>
        <div class="content-bar">
            <!-- Start row -->
            <div class="row">
                <!-- Start col -->
                <?php

                if ($result_brand_name) {
                    foreach ($result_brand_name as $row_brand) {
                      
                ?>
                        <div class="col-lg-12 col-xl-3 col-3 p-1">
                            <a href="companies.php?brand_id=<?php echo $row_brand['id']; ?>">
                                <div class="card m-b-5" style="height: 100%;">
                                    <div class="card-body" style="height: 200px; overflow: hidden;">
                                        <img class="img-fluid" style="height: 100%;" src="<?php echo 'admin/uploads/brands/' . $row_brand['image']; ?>" />
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
</div>
</div>
</div>
<?php include_once("footer.php") ?>