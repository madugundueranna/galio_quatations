<?php
session_start();
include('functions/functions.php');
include('../config/dbcon.php');
include('functions/session.php');
include('functions/myfunctions.php');


$error = array();
if (isset($_POST["submit"])) {

    if (empty(trim($_POST["company_id"]))) {
        $error["company_id"] = "Company Name is required";
    }

    if (empty(trim($_POST["name"]))) {
        $error["name"] = "Model Name is required";
    }


    if (empty($_POST['id'])) {
        if ($_POST["company_id"] != '' && !empty($_POST["name"])) {
            $Model_name = mysqli_real_escape_string($conn, trim($_POST['name']));
            $count = getDuplicate('models', "name='" . $Model_name . "' and company_id = " . $_POST["company_id"]);
        }
    }

    if ($count > 0) {
        $error['name'] = "Model Name Is Already in Use";
    }

    if (count($error) == 0) {

        $id = $_POST["id"];
        if ($id != '') {
            $data['id'] = $_POST["id"];
            $data['name'] = $_POST["name"];
            $data['updated_at'] = date('Y-m-d H:i:s');
            $data['updated_by'] = $_SESSION['USER_ID'];


            $Model_name = mysqli_real_escape_string($conn, trim($_POST['name']));
            $count = getDuplicate('models', "name='" . $Model_name . "' and  id!= " . $_POST["id"]);

            if ($count > 0) {
                $error['name'] = "Model Name Is Already in Use";
            }

            if (count($error) == 0) {
                $model_update_id = updateRecord('models', $data, $id);
                if ($model_update_id) {
                    redirecte("model.php", "Model Name Updated Successfully");
                }
            }
        } else {
            $data['name'] = $_POST["name"];
            $data['company_id'] = $_POST["company_id"];
            $data['created_at'] = date('Y-m-d H:i:s');
            $data['created_by'] = $_SESSION['USER_ID'];
            $model_id = addRecord('models', $data);
            if ($model_id == TRUE) {
                redirecte("model.php", "Model Name Added Successfully");
            }
        }
    }
}

if (isset($_GET['update_id'])) {
    $sql_model_name = "SELECT * FROM `models` where id=" . $_GET['update_id'];
    $row = getQueryData($sql_model_name);
    if (count($error) == 0) {
        $_POST = $row;
    }
}

if (isset($_GET["deleteID"])) {
    $sql_model_name = "DELETE FROM `models` WHERE id=" . $_GET["deleteID"];
    $result = mysqli_query($conn, $sql_model_name);
    if ($result) {
        redirecte("model.php", "Model Name Deleted Successfully");
    }
}


if (isset($_GET['model_active']) && isset($_GET['status'])) {
    $data['status'] = $_GET['status'];
    $inactive_model_update_id = updateRecord('models', $data, $_GET['model_active']);
    if ($inactive_model_update_id && $_GET['status'] == 0) {
        redirecte("model.php", "Model Inactive Successfully");
    }

    if ($inactive_model_update_id && $_GET['status'] == 1) {
        redirecte("model.php", "Model Active Successfully");
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
                        <li class="breadcrumb-item"><a href="#!">Model Names</a></li>
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
                                        <div class="row">

                                            <div class="col-md-3">
                                                <label><b>Company Name</b></label><span style="color: red;">*</span>
                                                <select name="company_id" id="company_id" class="form-control col-lg-12">
                                                    <option value="" selected>Select Company</option>
                                                    <?php
                                                    $sql_company_name = "select * from `companies` ORDER BY name";
                                                    $result_company_name = mysqli_query($conn, $sql_company_name);
                                                    while ($row = mysqli_fetch_assoc($result_company_name)) {

                                                    ?>
                                                        <option value="<?php echo $row['id'] ?>" <?php if (isset($_POST['company_id'])) if ($row['id'] == $_POST['company_id'])
                                                                                                        echo ' selected="selected"'; ?>>
                                                            <?php echo $row['name'] ?>
                                                        </option>
                                                    <?php
                                                    }
                                                    ?>
                                                </select>
                                                <span style=color:red;><?php if (isset($error["company_id"])) echo $error["company_id"]; ?></span>

                                            </div>
                                            <div class="col-md-3">
                                                <label>Model Name</label><span style="color: red;">*</span>
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
                            <h5>Model Names</h5>
                        </div>
                        <div class="card-block p-b-0">
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover m-b-0" id="Model_table">
                                    <thead>
                                        <tr>
                                            <th>S.No</th>
                                            <th>Brand</th>
                                            <th>Company Name</th>
                                            <th>Model Name</th>
                                            <th>Created Date</th>
                                            <th>Status</th>
                                            <th class="not-export-column">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $i = 0;
                                        $sql_model_name = "SELECT m.status as model_status, m.id as model_id, m.name as model_name, c.name as company_name, m.created_at,b.id,b.name as brand_name
                                        FROM companies AS c
                                        JOIN models AS m ON c.id = m.company_id
                                        JOIN brands AS b  ON b.id=c.brand_id
                                        ORDER BY m.name;
                                        ";
                                        $result_model_name = mysqli_query($conn, $sql_model_name);
                                        if ($result_model_name) {
                                            while ($row = mysqli_fetch_assoc($result_model_name)) {

                                        ?>
                                                <tr>
                                                    <td><?php echo ++$i; ?></td>
                                                    <td><?php echo  $row['brand_name']; ?></td>
                                                    <td><?php echo $row['company_name']; ?></td>
                                                    <td><?php echo $row['model_name']; ?></td>
                                                    <td><?php echo date('d-m-Y', strtotime($row['created_at'])); ?></td>
                                                    <td><?php if ($row['model_status'] == 1) echo "Active";
                                                        else echo "InActive" ?></td>
                                                    <td>
                                                        <a class="btn btn-primary" href="model.php?update_id=<?php echo $row["model_id"] ?>">Edit</a>
                                                        <span style="margin: 0 5px;"></span>

                                                        <!-- <a class="btn btn-danger" href="javascript:void(0);" onclick="confirmDelete(<?php echo $row['model_id']; ?>)">Delete</a> -->
                                                        <a class="text-danger" id="flexSwitchCheckDefault1_<?php echo $row['model_id']; ?>" onclick="inactiveModel('<?php echo $row['model_id']; ?>', <?php echo $row['model_status']; ?>)">
                                                            <i class="<?php echo $row['model_status'] == 1 ? 'fas fa-toggle-on' : 'fas fa-toggle-off'; ?> fa-2x" style="font-size: 1.5em;"></i>
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
                window.location.href = 'model.php?deleteID=' + id;
            }
        });
    }*/


    function inactiveModel(id, currentStatus) {
        var status = currentStatus ? 0 : 1; // Toggle the status

        var text = status ? 'You want to Activate the Model' : 'You want to Inactivate the Model!';

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
                window.location.href = 'model.php?model_active=' + id + '&status=' + status;
            }
        });
    }

    $(document).ready(function() {
        var table = $('#Model_table').DataTable({
            dom: 'lBifrtip',
            lengthMenu: [
                [5, 10, 25, 50, 100, -1],
                [5, 10, 25, 50, 100, "All"]
            ],
            buttons: [{
                    extend: 'excel',
                    title: "ModelDetails",
                    exportOptions: {
                        columns: ":not(.not-export-column)"
                    }
                },
                {
                    extend: 'pdf',
                    title: "ModelDetails",
                    exportOptions: {
                        columns: ":not(.not-export-column)"
                    }
                }
            ]
        });
    });
</script>
<?php include('includes/footer.php'); ?>