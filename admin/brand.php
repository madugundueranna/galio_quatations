<?php
session_start();
include('functions/functions.php');
include('../config/dbcon.php');
include('functions/session.php');
include('functions/myfunctions.php');


$error = array();
if (isset($_POST["submit"])) {
    if (empty(trim($_POST["name"]))) {
        $error["name"] = "Brand Name is Required";
    } else {
        if (!preg_match("/^[a-zA-Z ]*$/", trim($_POST["name"]))) {
            $error['name'] = "Only Letters and Space Allowed";
        }
    }
    if ($_POST["id"] == '') {
        $brand_name = mysqli_real_escape_string($conn, trim($_POST['name']));
        $count = getDuplicate("brands", "name='" . $brand_name . "'");
    }

    if ($count > 0) {
        $error['name'] = "Brand Name Is Already in Use";
    }



    if (empty($_POST['id'])) {

        if (empty($_FILES["image"]['name'])) {

            $error["image"] = "Image is Required";
        } else {
            $image = $_FILES['image']['name'];
            $imageexptype = pathinfo($image, PATHINFO_EXTENSION);

            $allowed_types = array('jpg', 'jpeg', 'png', 'gif');
            if (!in_array(strtolower($imageexptype), $allowed_types)) {
                $error["image"] = "Only Jpg, Jpeg, Png Files are Allowed.";
            }
        }
    }

    if (!empty($_POST['id'])) {
        $image = $_FILES['image']['name'];
        $imageexptype = pathinfo($image, PATHINFO_EXTENSION);

        $allowed_types = array('jpg', 'jpeg', 'png', 'gif');
        if (!in_array(strtolower($imageexptype), $allowed_types) && !empty($_FILES["image"]['name'])) {
            $error["image"] = "Only Jpg, Jpeg, Png Files are Allowed.";
        }
    }


    // image validation


    if (count($error) == 0) {

        $id = $_POST["id"];
        if ($id != '') {
            $data['id'] = $_POST["id"];
            $data['name'] = $_POST["name"];
            $data['updated_at'] = date('Y-m-d H:i:s');
            $data['updated_by'] = $_SESSION['USER_ID'];
            // $data['image'] = $imagename;
            $new_img = $_FILES['image']['name'];
            $old_img = $_POST['old_image'];
            $brand_name = mysqli_real_escape_string($conn, trim($_POST['name']));

            $count = getDuplicate('brands', "name='" . $brand_name . "' and id != " . $id);

            if ($count > 0) {
                $error['name'] = "Brand Name Is Already in Use";
            }

            if ($new_img != '') {
                $image = $_FILES['image']['name'];
                $imageexptype = pathinfo($image, PATHINFO_EXTENSION);
                $date = date('m/d/Yh:i:sa');
                $rand = rand(10000, 99999);
                $encname = $date . $rand;
                $imagename = md5($encname) . '.' . $imageexptype;
                $imagepath = "uploads/brands/" . $imagename;
                move_uploaded_file($_FILES["image"]["tmp_name"], $imagepath);
                $data['image'] = $imagename;
            } else {
                $data['image'] = $old_img;
            }

            if (count($error) == 0) {
                $brand_update_id = updateRecord('brands', $data, $id);
                if ($brand_update_id) {
                    redirecte("brand.php", "Brand Name Updated Successfully");
                }
            }
        } else {
            $image = $_FILES['image']['name'];
            $imageexptype = pathinfo($image, PATHINFO_EXTENSION);
            $date = date('m/d/Yh:i:sa');
            $rand = rand(10000, 99999);
            $encname = $date . $rand;
            $imagename = md5($encname) . '.' . $imageexptype;
            $imagepath = "uploads/brands/" . $imagename;


            $data['name'] = $_POST["name"];
            $data['created_at'] = date('Y-m-d H:i:s');
            $data['created_by'] = $_SESSION['USER_ID'];
            $data['image'] = $imagename;





            if (move_uploaded_file($_FILES["image"]["tmp_name"], $imagepath)) {

                $brand_id = addRecord('brands', $data);
                if ($brand_id == TRUE) {
                    redirecte("brand.php", "Brand Name Added Successfully");
                }
            }
        }
    }
}

if (isset($_GET['update_id'])) {
    $sql_brand_name = "SELECT * FROM `brands` where id=" . $_GET['update_id'];
    $row = getQueryData($sql_brand_name);
    if (count($error) == 0) {
        $_POST['old_image'] = $row['image'];
        $_POST['name'] = $row['name'];
    }
}


if (isset($_GET["delete_id"])) {

    $get_sql_brand = "select * from brands where id=" . $_GET["delete_id"];
    $query_run = mysqli_query($conn, $get_sql_brand);

    $value = mysqli_fetch_assoc($query_run);

    $sql_brand_name = "DELETE FROM `brands` WHERE id=" . $_GET["delete_id"];
    $query_run = mysqli_query($conn, $sql_brand_name);

    if ($query_run) {
        unlink("uploads/brands/" . $value['image']);
        redirecte("brand.php", "Brand Name Deleted Successfully");
    }
}

include('includes/header.php');
include('includes/sidebar.php');
?>
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
                        <li class="breadcrumb-item"><a href="#!">Brand Names</a></li>
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
                <form method="post" enctype="multipart/form-data">
                    <input type="hidden" name="id" value="<?php if (isset($_GET["update_id"])) echo $_GET["update_id"]; ?>">
                    <!-- Page-body start -->
                    <div class="page-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-block ">
                                        <div class="row ">
                                            <div class="col-md-3">
                                                <label>Brand Name</label><span style="color: red;">*</span>
                                                <input type="text" name="name" class="form-control mx-auto" value="<?php if (isset($_POST['name'])) echo trim($_POST['name']); ?>">
                                                <span style=color:red;><?php if (isset($error["name"])) echo $error["name"]; ?></span>
                                            </div>


                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="exampleSelect1" style="padding-bottom:0; vertical-align:middle">Upload Image</label><span style="color: red;">*</span>
                                                    <input type="file" name="image" class="form-control">
                                                    <input type="hidden" name="old_image" value="<?php if (isset($_POST['old_image'])) echo trim($_POST['old_image']); ?>">
                                                    <?php
                                                    if (!empty($_POST['old_image'])) {
                                                        echo '<img src="uploads/brands/' . htmlspecialchars($_POST['old_image']) . '" width="100" />';
                                                    }
                                                    ?>
                                                    <span style=color:red;><?php if (isset($error["image"])) echo $error["image"]; ?></span>
                                                </div>
                                            </div>

                                          

                                            <?php if (!empty($_GET['update_id'])) {
                                            ?>
                                                <div class="col-md-3 pt-4">
                                                    <input type="submit" name="submit" value="Update" class="btn btn-danger d-block">
                                                </div>
                                            <?php
                                            } else {
                                            ?>
                                                <div class="col-md-3 pt-4">
                                                    <input type="submit" name="submit" value="Submit" class="btn btn-danger d-block">
                                                </div>
                                            <?php

                                            } ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                </form>
                <div class="col-md-12">
                    <div class="card table-card">
                        <div class="card-header bg-info">
                            <h5>Brand Names</h5>
                        </div>
                        <div class="card-block p-b-0">
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover m-b-0" id="brand_table">
                                    <thead>
                                        <tr>
                                            <th>S.No</th>
                                            <th>Brand Name</th>
                                            <th>image</th>
                                            <th>Created Date</th>
                                            <th class="not-export-column">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $i = 0;
                                        $sql_brand_name = "SELECT * FROM `brands`";
                                        $result_brand_name = mysqli_query($conn, $sql_brand_name);
                                        if ($result_brand_name) {
                                            while ($row = mysqli_fetch_assoc($result_brand_name)) {


                                        ?>
                                                <tr>
                                                    <td><?php echo ++$i; ?></td>
                                                    <td><?php echo $row['name']; ?></td>
                                                    <td><img width="100" src="<?= 'uploads/brands/' . $row['image']; ?>" /></td>
                                                    <td><?php echo date('d-m-Y', strtotime($row['created_at'])); ?></td>
                                                    <td>
                                                        <a class="btn btn-primary" href="brand.php?update_id=<?php echo $row["id"] ?>">Edit</a>
                                                        <span style="margin: 0 5px;"></span>

                                                        <a class="btn btn-danger" href="javascript:void(0);" onclick="confirmDelete(<?php echo $row['id']; ?>)">Delete</a>
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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function confirmDelete(id) {
        Swal.fire({
            title: 'Are you sure?',
            text: 'You want to delete the record!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = 'brand.php?delete_id=' + id;
            }
        });
    }

    $(document).ready(function() {
        var table = $('#brand_table').DataTable({
            dom: 'lBifrtip',
            lengthMenu: [
                [5, 10, 25, 50, 100, -1],
                [5, 10, 25, 50, 100, "All"]
            ],
            buttons: [{
                    extend: 'excel',
                    title: "BrandDetails",
                    exportOptions: {
                        columns: ":not(.not-export-column)"
                    }
                },
                {
                    extend: 'pdf',
                    title: "BrandDetails",
                    exportOptions: {
                        columns: ":not(.not-export-column)"
                    }
                }
            ]
        });
    });
</script>
<?php include('includes/footer.php'); ?>