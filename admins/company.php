<?php
session_start();
include('functions/functions.php');
include('../config/dbcon.php');
include('functions/session.php');
include('functions/myfunctions.php');


$error = array();
if (isset($_POST["submit"])) {

    if (empty(trim($_POST["name"]))) {
        $error["name"] = "Compnay Name is Required";
    } else {
        if (!preg_match("/^[a-zA-Z ]*$/", trim($_POST["name"]))) {
            $error['name'] = "Only Letters and Space Allowed";
        }
    }
    if (empty(trim($_POST["brand_id"]))) {
        $error["brand_id"] = "Brand Name is Required";
    }

    if ($_POST["id"] == '') {
        $company_name = mysqli_real_escape_string($conn, trim($_POST['name']));
        $count = getDuplicate("companies", "name='" . $company_name . "'");
    }

    if ($count > 0) {
        $error['name'] = "Company Name Is Already in Use";
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




    if (count($error) == 0) {

        $id = $_POST["id"];
        if ($id != '') {
            $data['id'] = $_POST["id"];
            $data['name'] = $_POST["name"];
            $data['brand_id'] = $_POST["brand_id"];
            $data['updated_at'] = date('Y-m-d H:i:s');
            $data['updated_by'] = $_SESSION['USER_ID'];
            $new_img = $_FILES['image']['name'];
            $old_img = $_POST['old_image'];

            $company_name = mysqli_real_escape_string($conn, trim($_POST['name']));
            $count = getDuplicate('companies', "name='" . $company_name . "' and id != " . $id);

            if ($count > 0) {
                $error['name'] = "Company Name Is Already in Use";
            }

            if ($new_img != '') {

                $image = $_FILES['image']['name'];
                $imageexptype = pathinfo($image, PATHINFO_EXTENSION);
                $date = date('m/d/Yh:i:sa');
                $rand = rand(10000, 99999);
                $encname = $date . $rand;
                $imagename = md5($encname) . '.' . $imageexptype;

                $imagepath = "uploads/companies/" . $imagename;

                move_uploaded_file($_FILES["image"]["tmp_name"], $imagepath);

                $data['image'] = $imagename;
            } else {
                $data['image'] = $old_img;
            }

            if (count($error) == 0) {
                $company_update_id = updateRecord('companies', $data, $id);
                if ($company_update_id == TRUE) {
                    redirecte("company.php", "Compnay Name Updated Successfully");
                }
            }
        } else {

            $image = $_FILES['image']['name'];
            $imageexptype = pathinfo($image, PATHINFO_EXTENSION);
            $date = date('m/d/Yh:i:sa');
            $rand = rand(10000, 99999);
            $encname = $date . $rand;
            $imagename = md5($encname) . '.' . $imageexptype;

            $imagepath = "uploads/companies/" . $imagename;



            $data['name'] = $_POST["name"];
            $data['created_by'] = $_SESSION['USER_ID'];
            $data['brand_id'] = $_POST["brand_id"];
            $data['created_at'] = date('Y-m-d H:i:s');
            $data['image'] = $imagename;

            if (move_uploaded_file($_FILES["image"]["tmp_name"], $imagepath)) {

                $company_id = addRecord('companies', $data);
                if ($company_id == TRUE) {
                    redirecte("company.php", "Compnay Name Added Successfully");
                }
            }
        }
    }
}



if (isset($_GET['update_id'])) {
    $sql = "SELECT * FROM `companies` where id=" . $_GET['update_id'];
    $row = getQueryData($sql);

    if (count($error) == 0) {

        // $_POST = $row;
        $_POST['old_image'] = $row['image'];
        $_POST['name'] = $row['name'];
        $_POST['brand_id'] = $row['brand_id'];

        // print_r($_POST);
        // exit;
    }
}



/*if (isset($_GET["deleteID"])) {

    $get_data_sql = "select * from companies where id=" . $_GET["deleteID"];
    $query_run = mysqli_query($conn, $get_data_sql);
    $value = mysqli_fetch_assoc($query_run);
    $query = "DELETE FROM `companies` WHERE id=" . $_GET["deleteID"];

    $query_run = mysqli_query($conn, $query);

    if ($query_run) {
        unlink("uploads/companies/" . $value['image']);
        redirecte("company.php", "Compnay Name Deleted Successfully");
        exit(0);
    }
}*/
if (isset($_GET['company_active']) && isset($_GET['status'])) {
    $data['status'] = $_GET['status'];
    $inactive_company_update_id = updateRecord('companies', $data, $_GET['company_active']);
    if ($inactive_company_update_id && $_GET['status']==0) {
        redirecte("company.php", "Company Inactive Successfully");
    }

    if ($inactive_company_update_id && $_GET['status']==1) {
        redirecte("company.php", "Company Active Successfully");
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
                        <li class="breadcrumb-item"><a href="#!">Compnay Names</a></li>
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
                                        <div class="row">
                                            <div class="col-md-3">

                                                <label><b>Brand Name</b></label><span style="color: red;">*</span>
                                                <select name="brand_id" id="brand_id" class="form-control col-lg-12">
                                                    <option value="" selected>Select Brand</option>
                                                    <?php
                                                    $sql_brand_name = "select * from `brands` ORDER BY name";
                                                    $result_brand_name = mysqli_query($conn, $sql_brand_name);
                                                    while ($row = mysqli_fetch_assoc($result_brand_name)) {
                                                    ?>
                                                        <option value="<?php echo $row['id'] ?>" <?php if (isset($_POST['brand_id'])) if ($row['id'] == $_POST['brand_id'])
                                                                                                        echo ' selected="selected"'; ?>>
                                                            <?php echo $row['name'] ?>
                                                        </option>
                                                    <?php
                                                    }
                                                    ?>
                                                </select>
                                                <span style=color:red;><?php if (isset($error["brand_id"])) echo $error["brand_id"]; ?></span>
                                            </div>


                                            <div class="col-md-3">
                                                <label>Compnay Name</label><span style="color: red;">*</span>
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
                                                        echo '<img src="uploads/companies/' . htmlspecialchars($_POST['old_image']) . '" width="100" />';
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
                        </div>
                </form>
                <div class="col-md-12">
                    <div class="card table-card">
                        <div class="card-header bg-info">
                            <h5>Compnay Names</h5>
                        </div>
                        <div class="card-block p-b-0">
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover m-b-0" id="Company_table">
                                    <thead>
                                        <tr>
                                            <th>S.No</th>
                                            <th>Brand</th>
                                            <th>Compnay Name</th>
                                            <th>Image</th>
                                            <th>Created Date</th>
                                            <th>Status</th>
                                            <th class="not-export-column">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $i = 0;
                                        $sql_company_name = "SELECT * FROM `companies`";
                                        $result_company_name = mysqli_query($conn, $sql_company_name);
                                        if ($result_company_name) {
                                            while ($row = mysqli_fetch_assoc($result_company_name)) {

                                                $brand_id = $row['brand_id'];
                                                $brand_name = "";
                                                $sql_brand_name = "SELECT b.id, b.name AS brand_name, c.* FROM companies c
                                               JOIN brands b ON b.id = c.brand_id WHERE c.brand_id = " . $brand_id;


                                                $result_brand_name = mysqli_query($conn, $sql_brand_name);
                                                if ($result_brand_name) {
                                                    while ($row_brand_name = mysqli_fetch_assoc($result_brand_name)) {
                                                        $brand_name = $row_brand_name['brand_name'];
                                                    }
                                                }


                                        ?>
                                                <tr>
                                                    <td><?php echo ++$i; ?></td>
                                                    <td><?php echo $brand_name; ?></td>
                                                    <td><?php echo $row['name']; ?></td>
                                                    <td><img width="100" src="<?= 'uploads/companies/' . $row['image']; ?>" /></td>
                                                    <td><?php echo date('d-m-Y', strtotime($row['created_at'])); ?></td>
                                                    <td><?php if ($row['status'] == 1) echo "Active";
                                                        else echo "InActive" ?></td>
                                            
                                                    <td>
                                                        <a class="btn btn-primary" href="company.php?update_id=<?php echo $row["id"] ?>">Edit</a>
                                                        <span style="margin: 0 5px;"></span>

                                                        <!-- <a class="btn btn-danger" href="javascript:void(0);" onclick="confirmDelete(<?php echo $row['id']; ?>)">Delete</a> -->

                                                        <a class="text-danger" id="flexSwitchCheckDefault1_<?php echo $row['id']; ?>" onclick="inactiveCompany('<?php echo $row['id']; ?>', <?php echo $row['status']; ?>)">
                                                            <i class="<?php echo $row['status'] == 1 ? 'fas fa-toggle-on' : 'fas fa-toggle-off'; ?> fa-2x" style="font-size: 1.5em;"></i>
                                                        </a>
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
    /*function confirmDelete(id) {
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
                window.location.href = 'company.php?deleteID=' + id;
            }
        });
    }*/


    function inactiveCompany(id, currentStatus) {
        var status = currentStatus ? 0 : 1; // Toggle the status

        var text = status ? 'You want to Activate the Company' : 'You want to Inactivate the Company!';

        Swal.fire({
            title: 'Are you sure?',
            text: text,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = 'company.php?company_active=' + id + '&status=' + status;
            }
        });
    }


    $(document).ready(function() {
        var table = $('#Company_table').DataTable({
            dom: 'lBifrtip',
            lengthMenu: [
                [5, 10, 25, 50, 100, -1],
                [5, 10, 25, 50, 100, "All"]
            ],
            buttons: [{
                    extend: 'excel',
                    title: "CompanyNames",
                    exportOptions: {
                        columns: ":not(.not-export-column)"
                    }
                },
                {
                    extend: 'pdf',
                    title: "CompanyNames",
                    exportOptions: {
                        columns: ":not(.not-export-column)"
                    }
                }
            ]
        });
    });
</script>
<?php include('includes/footer.php'); ?>