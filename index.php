<?php
include('admin/functions/functions.php');
include('config/dbcon.php');
$status = 1;
if (isset($_POST['search_button'])) {
    $searchTerm = mysqli_real_escape_string($conn, $_POST['search_term']);
    $sql_brand_name = "SELECT * FROM `brands` WHERE status = " . $status . " AND name LIKE '%" . $searchTerm . "%'";
    $result_brand_name = getQueryDataList($sql_brand_name);
    $sql_company = "SELECT * FROM `companies` WHERE status=" . $status . " AND name LIKE '%" . $searchTerm . "%' ";
    $result_company = getQueryDataList($sql_company);
} else {
    $sql_brand_name = "SELECT * FROM `brands` where status=" . $status;
    $result_brand_name = getQueryDataList($sql_brand_name);
    $active_brand_ids = [];
    foreach ($result_brand_name as $row_brand) {
        $active_brand_ids[] = $row_brand['id'];
    }
    $active_brand_ids_str = implode(",", $active_brand_ids);
    if (!empty($active_brand_ids)) {
        $sql_company = "SELECT * FROM `companies` WHERE status=" . $status . " AND brand_id IN (" . $active_brand_ids_str . ")";
        $result_company = getQueryDataList($sql_company);
    }
}

?>
<?php include_once("header.php") ?>
<div class="content_wrapper bg_homebefore pt-0">
    <div class="container-fluid">

        <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <div class="row mt-2">
                <div class="col-sm-5 p-0 col-10">
                    <input type="text" class="form-control" placeholder="Search Company or Brand" name="search_term">
                </div>
                <div class="col-sm-2 p-0 col-2">
                    <button type="submit" class="btn btn-danger btn-block" name="search_button">
                        <i class="fa fa-search"></i>
                    </button>
                </div>
            </div>
        </form>

        <!-- Display search results for companies -->
        <div class="sec-title">
            <div class="row d-flex align-items-center">
                <div class="col-md-12 p-0">
                    <div class="heading_home">
                        <h2>Search by Company <span class="float-right"><a href="companies.php">View All</a></span></h2>
                    </div>
                </div>
            </div>
        </div>
        <div class="content-bar">
            <div class="row">
                <?php
                if ($result_company) {
                    foreach ($result_company as $row_company) {
                ?>
                        <div class="col-lg-12 col-xl-3 col-3 p-1">
                            <a href="models.php?company_id=<?php echo $row_company['id']; ?>">
                                <div class="card m-b-5" style="height: 100%;">
                                    <div class="card-body" style="height: 200px; overflow: hidden;">
                                        <img class="img-fluid" style="height: 100%;" src="<?php echo 'admin/uploads/companies/' . $row_company['image']; ?>" />
                                    </div>
                                </div>
                            </a>
                        </div>
                <?php
                    }
                } else {
                    echo '<p style="color: red;">companies is Not Available</p>';
                }


                ?>
            </div>
        </div>

        <!-- Display search results for brands -->
        <div class="sec-title">
            <div class="row d-flex align-items-center">
                <div class="col-md-12 p-0">
                    <div class="heading_home">
                        <h2>Search by Brand <span class="float-right"><a href="brands.php">View All</a></span></h2>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
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
            } else {
                echo '<p style="color: red;">Brands is Not Available</p>';
            }
            ?>

            <br>
            <br>
            <br>
            <br>
        </div>
    </div>
</div>
</div>
</div>
</div>
<?php include_once("footer.php") ?>