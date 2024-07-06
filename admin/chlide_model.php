<?php
session_start();
include('functions/functions.php');
include('../config/dbcon.php');
include('functions/session.php');
include('functions/myfunctions.php');


$error = array();
if (isset($_POST["submit"])) {
    if (empty(trim($_POST["company_id"]))) {
        $error["company_id"] = "Company Name is Required";
    }
    if (empty(trim($_POST["model_id"]))) {
        $error["model_id"] = "Model Name is Required";
    }
    if (empty(trim($_POST["name"]))) {
        $error["name"] = "Child Model Name is Required";
    }

    // else {
    //     if (!preg_match("/^[a-zA-Z ]*$/", trim($_POST["name"]))) {
    //         $error['name'] = "Only Letters and Space Allowed";
    //     }
    // }


    if (empty($_POST['id'])) {
        if ($_POST["model_id"] != '' && !empty($_POST["name"])) {
            $child_model_name = mysqli_real_escape_string($conn, trim($_POST['name']));
            $count = getDuplicate('child_models', "name='" . $child_model_name . "' and model_id = " . $_POST["model_id"]);
        }
    }


    if ($count > 0) {
        $error['name'] = "Child Model Name Is Already in Use";
    }

    if (count($error) == 0) {
        $id = $_POST["id"];
        if ($id != '') {
            $data['id'] = $_POST["id"];
            $data['model_id'] = $_POST["model_id"];
            $data['name'] = $_POST["name"];
            $data['updated_at'] = date('Y-m-d H:i:s');
            $data['updated_by'] = $_SESSION['USER_ID'];

            $child_model_name = mysqli_real_escape_string($conn, trim($_POST['name']));
            $count = getDuplicate('child_models', "name='" . $child_model_name . "' and  id!= " . $_POST["id"]);

            if ($count > 0) {
                $error['name'] = "Child Model Name Is Already in Use";
            }

            if (count($error) == 0) {
                $model_update_id = updateRecord('child_models', $data, $id);
                if ($model_update_id) {
                    redirecte("chlide_model.php", "Child Model Name Updated Successfully");
                }
            }
        } else {
            //    print_r( $_POST["company_id"]);
            //    print_r($_POST["model_id"]);
            //    exit;
            $data['model_id'] = $_POST["model_id"];
            $data['name'] = $_POST["name"];
            $data['created_at'] = date('Y-m-d H:i:s');
            $data['created_by'] = $_SESSION['USER_ID'];
            $model_id = addRecord('child_models', $data);
            if ($model_id == TRUE) {
                redirecte("chlide_model.php", "Chlide Model Name Added Successfully");
            }
        }
    }
}

if (isset($_GET['update_id'])) {
    $sql_child_model = "SELECT *
    FROM companies AS c
    JOIN models AS m ON c.id = m.company_id
    JOIN child_models AS cm ON m.id = cm.model_id
    WHERE cm.id=" . $_GET['update_id'];

    $row = getQueryData($sql_child_model);
    if (count($error) == 0) {

        $_POST = $row;
    }
}

/*if (isset($_GET["deleteID"])) {
    $sql_child_model = "DELETE FROM `child_models` WHERE id=" . $_GET["deleteID"];

    $result_child_model = mysqli_query($conn, $sql_child_model);

    if ($result_child_model) {
        redirecte("chlide_model.php", " Chlide Model Name Deleted Successfully");
    }
}*/


if (isset($_GET['chlide_model_active']) && isset($_GET['status'])) {
    $data['status'] = $_GET['status'];
    $inactive_model_update_id = updateRecord('child_models', $data, $_GET['chlide_model_active']);
    if ($inactive_model_update_id && $_GET['status'] == 0) {
        redirecte("chlide_model.php", "Chlide Model Inactive Successfully");
    }

    if ($inactive_model_update_id && $_GET['status'] == 1) {
        redirecte("chlide_model.php", "Chlide Model Active Successfully");
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
                        <li class="breadcrumb-item"><a href="#!"> Child Model Names</a></li>
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
                                                <label><b>Model Name</b></label><span style="color: red;">*</span>
                                                <select name="model_id" id="model_id" class="form-control col-lg-12">
                                                    <option value="" selected>Select Model Name</option>
                                                    <?php
                                                    if (isset($_POST["company_id"])) {
                                                        $sql_model = "select * from models where company_id=" . $_POST['company_id'];
                                                        $result_model = mysqli_query($conn, $sql_model);
                                                        if ($result_model->num_rows > 0) {
                                                            while ($row_model = mysqli_fetch_assoc($result_model)) {
                                                    ?>
                                                                <option value="<?php echo $row_model['id'] ?>" <?php if (isset($_POST['model_id'])) if ($row_model['id'] == $_POST['model_id'])
                                                                                                                    echo "selected" ?>>
                                                                    <?php echo $row_model['name'] ?>
                                                                </option>
                                                    <?php }
                                                        }
                                                    } ?>
                                                </select>
                                                <span style=color:red;><?php if (isset($error["model_id"])) echo $error["model_id"]; ?></span>
                                            </div>


                                            <div class="col-md-3">
                                                <label> Child Model Name</label><span style="color: red;">*</span>
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
                            <h5>Child Model Names</h5>
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
                                            <th> Chlid Model Name</th>
                                            <th>Created Date</th>
                                            <th>Status</th>
                                            <th class="not-export-column">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $i = 0;
                                        $sql_child_model_name = "SELECT c.*,m.name as model_name,cm.name 
                                        as child_model_name,cm.created_at as model_name_created_at ,cm.id as child_model_id,
                                        c.name as company_name,m.name as model_name,b.id,b.name as brand_name,cm.status as child_model_status
                                        FROM companies AS c
                                        JOIN models AS m ON c.id = m.company_id
                                        JOIN brands AS b ON b.id = c.brand_id
                                        JOIN child_models AS cm ON m.id = cm.model_id";

                                        $result_chlid_model_name = mysqli_query($conn, $sql_child_model_name);
                                        if ($result_chlid_model_name) {
                                            while ($row = mysqli_fetch_assoc($result_chlid_model_name)) {



                                        ?>
                                                <tr>
                                                    <td><?php echo ++$i; ?></td>
                                                    <td><?php echo $row['brand_name']; ?></td>
                                                    <td><?php echo $row['company_name']; ?></td>
                                                    <td><?php echo $row['model_name']; ?></td>
                                                    <td><?php echo $row['child_model_name']; ?></td>
                                                    <td><?php echo date('d-m-Y', strtotime($row['model_name_created_at'])); ?></td>
                                                    <td><?php if ($row['child_model_status'] == 1) echo "Active";
                                                        else echo "InActive" ?></td>
                                                    <td>
                                                        <a class="btn btn-primary" href="chlide_model.php?update_id=<?php echo $row["child_model_id"] ?>">Edit</a>
                                                        <span style="margin: 0 5px;"></span>

                                                        <!-- <a class="btn btn-danger" href="javascript:void(0);" onclick="confirmDelete(<?php echo $row['child_model_id']; ?>)">Delete</a> -->
                                                        <a class="text-danger" id="flexSwitchCheckDefault1_<?php echo $row['child_model_id']; ?>" onclick="inactiveChlidModel('<?php echo $row['child_model_id']; ?>', <?php echo $row['child_model_status']; ?>)">
                                                            <i class="<?php echo $row['child_model_status'] == 1 ? 'fas fa-toggle-on' : 'fas fa-toggle-off'; ?> fa-2x" style="font-size: 1.5em;"></i>
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
                window.location.href = 'chlide_model.php?deleteID=' + id;
            }
        });
    }*/



    function inactiveChlidModel(id, currentStatus) {
        var status = currentStatus ? 0 : 1; // Toggle the status

        var text = status ? 'You want to Activate the Chlide Model' : 'You want to Inactivate the Chlide Model!';

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
                window.location.href = 'chlide_model.php?chlide_model_active=' + id + '&status=' + status;
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


    $(document).ready(function() {
        $("#company_id").on('change', function() {
            var company_id = $(this).val();
            if (company_id) {
                $.ajax({
                    type: 'POST',
                    url: 'backend_script.php',
                    data: {
                        'company_id': company_id
                    },
                    success: function(data) {
                        console.log(data);
                        $("#model_id").html(data);
                    }
                });
            }
        });


    });



    ;
</script>
<?php include('includes/footer.php'); ?>