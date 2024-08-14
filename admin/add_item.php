<?php
session_start();
include('functions/functions.php');
include('../config/dbcon.php');
include('functions/session.php');
include('functions/myfunctions.php');


$error = array();
if (isset($_POST["submit"])) {
    if (empty(trim($_POST["name"]))) {
        $error["name"] = "Item Name is required";
    }

    if ($_POST["id"] == '') {
        $item_name = mysqli_real_escape_string($conn, trim($_POST['name']));

        $count = getDuplicate("items", "name='" . $item_name . "'");
    }

    if ($count > 0) {
        $error['name'] = "Item Name Already Exists";
    }

    if (count($error) == 0) {

        $id = $_POST["id"];
        if ($id != '') {
            $data['id'] = $_POST["id"];
            $data['name'] = $_POST["name"];
            $data['created_at'] = date('Y-m-d H:i:s');
            $data['updated_at'] = date('Y-m-d H:i:s');

            $data['updated_by'] = $_SESSION['USER_ID'];

            $item_name = mysqli_real_escape_string($conn, trim($_POST['name']));

            $count = getDuplicate('items', "name='" . $item_name . "' and id != " . $id);

            if ($count > 0) {
                $error['name'] = "Item Name Already Exists";
            }
            if (count($error) == 0) {
                $item_update_id = updateRecord('items', $data, $id);
                if ($item_update_id) {
                    redirecte("add_item.php", "Item Name Updated Successfully");
                }
            }
        } else {
            $data['name'] = $_POST["name"];
            $data['created_at'] = date('Y-m-d H:i:s');
            $data['status'] = 1;
            $item_id = addRecord('items', $data);
            if ($item_id == TRUE) {
                redirecte("add_item.php", "Item Name Added Successfully");
            }
        }
    }
}



if (isset($_GET['id']) && isset($_GET['status'])) {
    $data['status'] = $_GET['status'];
    // $data['updated_at'] = date('Y-m-d H:i:s');
    // $data['updated_by'] = $_SESSION['USER_ID'];
    $inactive_item_id = updateRecord('items', $data, $_GET['id']);
    if ($data['status'] == 1) {
        if ($inactive_item_id) {
            redirecte("add_item.php", "active Item Successfully");
        }
    } else if ($data['status'] == 0) {
        if ($inactive_item_id) {
            redirecte("add_item.php", "Inactive Item Successfully");
        }
    }
}


if (isset($_GET['update_id'])) {
    $sql = "SELECT * FROM `items` where id=" . $_GET['update_id'];
    $row = getQueryData($sql);
    if (count($error) == 0) {
        $_POST = $row;
    }
}

if (isset($_GET["deleteID"])) {
    $sql = "DELETE FROM `items` WHERE id=" . $_GET["deleteID"];
    $result = mysqli_query($conn, $sql);
    if ($result) {
        redirecte("add_item.php", "Item Name Deleted Successfully");
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
                        <li class="breadcrumb-item"><a href="#!">Item Names</a></li>
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
                                                <label>Item Name</label><span style="color: red;">*</span>
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
                            <h5>Item Names</h5>
                        </div>
                        <div class="card-block p-b-0">
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover m-b-0" id="ItemNames_table">
                                    <thead>
                                        <tr>
                                            <th>S.No</th>
                                            <th>Item Name</th>
                                            <th>Status</th>
                                            <th class="not-export-column">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $i = 0;
                                        $sql_item_name = "SELECT * FROM `items` ORDER BY priority";
                                        $result = mysqli_query($conn, $sql_item_name);
                                        if ($result) {
                                            while ($row = mysqli_fetch_assoc($result)) {

                                        ?>
                                                <tr>
                                                    <td><?php echo ++$i; ?></td>
                                                    <td><?php echo $row['name']; ?></td>
                                                    <td><?php if ($row['status'] == 1) echo "Active";
                                                        else echo "InActive" ?></td>
                                                    <td>

                                                        <!-- <a class="text-danger" onclick="confirmInActive('<?php echo $row['id']; ?>')">
                                                            <i class="fas fa-toggle-on fa-2x" style="font-size: 2em; color:black;"></i>
                                                        </a> -->



                                                        <!-- <label class="fas fa-toggle-on fa-2x" for="flexSwitchCheckDefault1">
                                                            <input class="form-check-input" type="checkbox" name="flexSwitchCheckDefault1" id="flexSwitchCheckDefault1" <?php if ($row["status"] == 1) echo 'checked'; ?> onclick="inactiveitem('<?php echo $row['id']; ?>')">
                                                        </label> -->
                                                        <a class="text-danger" id="flexSwitchCheckDefault1_<?php echo $row['id']; ?>" onclick="inactiveitem('<?php echo $row['id']; ?>', <?php echo $row['status']; ?>)">
                                                            <i class="<?php echo $row['status'] == 1 ? 'fas fa-toggle-on' : 'fas fa-toggle-off'; ?> fa-2x" style="font-size: 1.5em;"></i>
                                                        </a>




                                                        <span style="margin: 0 5px;"></span>

                                                        <a class="btn btn-primary" href="add_item.php?update_id=<?php echo $row["id"] ?>">Edit</a>
                                                        <span style="margin: 0 5px;"></span>
                                                        <a class="btn btn-danger" href="javascript:void(0);" onclick="confirmDelete(<?php echo $row['id']; ?>)">Delete</a>

                                                    </td>
                                                    <!-- Inside the loop -->

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
    // function inactiveitem(id) {
    //     var status = document.getElementById('flexSwitchCheckDefault1').checked ? 1 : 0;

    //     // console.log(status);
    //     var text = status ? 'You want to Activate the item!' : 'You want to InActivate the item!';

    //     Swal.fire({
    //         title: 'Are you sure?',
    //         text: text,
    //         icon: 'warning',
    //         showCancelButton: true,
    //         confirmButtonColor: '#d33',
    //         cancelButtonColor: '#3085d6',
    //         confirmButtonText: 'Yes'
    //     }).then((result) => {
    //         if (result.isConfirmed) {
    //             window.location.href = 'add_item.php?id=' + id + '&status=' + status;
    //         }
    //     });
    // }


    function inactiveitem(id, currentStatus) {
			var status = currentStatus ? 0 : 1; // Toggle the status

			var text = status ? 'You want to Activate the item!' : 'You want to InActivate the item!';

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
                    window.location.href = 'add_item.php?id=' + id + '&status=' + status;
				}
			});
		}


    // function confirmInActive(id) {
    //     Swal.fire({
    //         title: 'Are you sure?',
    //         text: 'You Want to in Active the item!',
    //         icon: 'warning',
    //         showCancelButton: true,
    //         confirmButtonColor: '#d33',
    //         cancelButtonColor: '#3085d6',
    //         confirmButtonText: 'Yes'
    //     }).then((result) => {
    //         if (result.isConfirmed) {
    //             // If the user clicks Yes, redirect to the delete page with the id
    //             window.location.href = 'add_item.php?id=' + id;
    //         }
    //     });
    // }


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
                window.location.href = 'add_item.php?deleteID=' + id;
            }
        });
    }



    $(document).ready(function() {
        var table = $('#ItemNames_table').DataTable({
            dom: 'lBifrtip',
            lengthMenu: [
                [5, 10, 25, 50, 100, -1],
                [5, 10, 25, 50, 100, "All"]
            ],
            buttons: [{
                    extend: 'excel',
                    title: "ItemNames",
                    exportOptions: {
                        columns: ":not(.not-export-column)"
                    }
                },
                {
                    extend: 'pdf',
                    title: "ItemNames",
                    exportOptions: {
                        columns: ":not(.not-export-column)"
                    }
                }
            ]
        });
    });
</script>

<?php include('includes/footer.php'); ?>