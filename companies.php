<?php
include('admin/functions/functions.php');
include('config/dbcon.php');
include_once("header.php");
$status = 1;
$error = array();
if (isset($_POST['search_button'])) {

  $searchTerm = mysqli_real_escape_string($conn, $_POST['search_term']);
  if (empty($searchTerm)) {
    $error['search_term'] = "Please Enter Company Name";
}

  $sql_company = "SELECT * FROM `companies` WHERE name LIKE '%$searchTerm%'";
  $result_company = getQueryDataList($sql_company);
} else if (isset($_GET['brand_id'])) {
  $sql_company = "SELECT * FROM `companies` WHERE status = " . $status . " AND name LIKE '%" . $searchTerm . "%' AND brand_id = " . $_GET['brand_id'];
  $result_company = getQueryDataList($sql_company);
} else {
  $sql_company = "SELECT * FROM `companies` WHERE status=" . $status . " AND name LIKE '%" . $searchTerm . "%' ";
  $result_company = getQueryDataList($sql_company);
}



?>
<div class="content_wrapper bg_homebefore pt-0">
  <div class="container-fluid">

    <?php
    // foreach ($result_company as $row_brand) {
    if (!empty($result_company[0]['brand_id'])) {
    ?>
      <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <div class="row mt-2">
          <div class="col-sm-5 p-0 col-10">
            <input type="text" class="form-control" placeholder="Search Company" name="search_term" value="<?php if (isset($_POST['search_term'])) echo trim($_POST['search_term']); ?>">
            <span style=color:red;><?php if (!empty($error['search_term']))  echo $error['search_term']; ?></span>
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
              <h2>Search by Company</h2>

            </div>
          </div>
        </div>
      </div>
    <?php
    } else {
    ?>

      <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <div class="row mt-2">
          <div class="col-sm-5 p-0 col-10">
            <input type="text" class="form-control" placeholder="Search Company" name="search_term">
          </div>
          <div class="col-sm-2 p-0 col-2">
            <button type="submit" class="btn btn-danger btn-block" name="search_button">
              <i class="fa fa-search"></i>
            </button>
          </div>
        </div>
      </form>
    <?php
    }
    // }
    ?>



    <div class="content-bar">
      <!-- Start row -->
      <div class="row">
        <!-- Start col -->

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
      <!-- End row -->





    </div>
    <!-- End Rightbar -->
  </div>
</div>
</div>
</div>
</div>
<?php include_once("footer.php") ?>