<?php
session_start();
include('functions/functions.php');
include('../config/dbcon.php');
include('functions/session.php');
include('functions/myfunctions.php');


$error = array();
if (isset($_POST["submit"])) {
    if (empty(trim($_POST["name"]))) {
        $error["name"] = "Category Name is Required";
    } else {
        if (!preg_match("/^[a-zA-Z ]*$/", trim($_POST["name"]))) {
            $error['name'] = "Only Letters and Space Allowed";
        }
    }

    if ($_POST["id"] == '') {
        $category_name = mysqli_real_escape_string($conn, trim($_POST['name']));
        $count = getDuplicate("categories", "name='" . $category_name . "'");
    }

    if ($count > 0) {
        $error['name'] = "This Category Is Already in Use";
    }

    if (count($error) == 0) {
        $id = $_POST["id"];
        if ($id != '') {
            $data['id'] = $_POST["id"];
            $data['name'] = $_POST["name"];
            $data['updated_at'] = date('Y-m-d H:i:s');
            $data['updated_by'] = $_SESSION['USER_ID'];

            $category_name = mysqli_real_escape_string($conn, trim($_POST['name']));

            $count = getDuplicate('categories', "name='" . $category_name . "' and id != " . $id);

            if ($count > 0) {
                $error['name'] = "This Category Is Already in Use";
            }
            if (count($error) == 0) {
                $brand_update_id = updateRecord('categories', $data, $id);
                if ($brand_update_id) {
                    redirecte("spare_category.php", "Category  Updated Successfully");
                }
            }
        } else {
            $data['name'] = $_POST["name"];
            $data['created_at'] = date('Y-m-d H:i:s');
            $data['created_by'] = $_SESSION['USER_ID'];
            $brand_id = addRecord('categories', $data);
            if ($brand_id == TRUE) {
                redirecte("spare_category.php", "Category Added Successfully");
            }
        }
    }
}

if (isset($_GET['update_id'])) {
    $sql_category_name = "SELECT * FROM `categories` where id=" . $_GET['update_id'];
    $row = getQueryData($sql_category_name);
    if (count($error) == 0) {
        $_POST = $row;
    }
}

if (isset($_GET["delete_id"])) {
    $sql_category_name = "DELETE FROM `categories` WHERE id=" . $_GET["delete_id"];
    $result_category_name = mysqli_query($conn, $sql_category_name);
    if ($result_category_name) {
        redirecte("spare_category.php", "Category Deleted Successfully");
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
                        <li class="breadcrumb-item"><a href="#!"> Spare Category Names</a></li>
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
                <form method="post">
                    <input type="hidden" name="id" value="<?php if (isset($_GET["update_id"])) echo $_GET["update_id"]; ?>">
                    <!-- Page-body start -->
                    <div class="page-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-block ">
                                        <div class="row justify-content-center">
                                            <div class="col-md-3">
                                                <label>Spare Category Name</label><span style="color: red;">*</span>
                                                <input type="text" name="name" class="form-control mx-auto" value="<?php if (isset($_POST['name'])) echo trim($_POST['name']); ?>">
                                                <span style=color:red;><?php if (isset($error["name"])) echo $error["name"]; ?></span>
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
                            <h5>Category Names</h5>
                        </div>
                        <div class="card-block p-b-0">
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover m-b-0" id="brand_table">
                                    <thead>
                                        <tr>
                                            <th>S.No</th>
                                            <th>Category Name</th>
                                            <th>Created Date</th>
                                            <th class="not-export-column">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $i = 0;
                                        $sql_category_name = "SELECT * FROM `categories` ORDER BY name";
                                        $result_category_name = mysqli_query($conn, $sql_category_name);
                                        if ($result_category_name) {
                                            while ($row_category_name = mysqli_fetch_assoc($result_category_name)) {


                                        ?>
                                                <tr>
                                                    <td><?php echo ++$i; ?></td>
                                                    <td><?php echo $row_category_name['name']; ?></td>
                                                    <td><?php echo date('d-m-Y', strtotime($row_category_name['created_at'])); ?></td>
                                                    <td>
                                                        <a class="btn btn-primary" href="spare_category.php?update_id=<?php echo $row_category_name["id"] ?>">Edit</a>
                                                        <span style="margin: 0 5px;"></span>

                                                        <a class="btn btn-danger" href="javascript:void(0);" onclick="confirmDelete(<?php echo $row_category_name['id']; ?>)">Delete</a>
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
                window.location.href = 'spare_category.php?delete_id=' + id;
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