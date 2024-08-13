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

    if (empty(trim($_POST["brand_id"]))) {
        $error["brand_id"] = "Brand Name is required";
    }

    if (empty(trim($_POST["model_id"]))) {
        $error["model_id"] = "Model Name is required";
    }

    // if (empty($_POST["chlide_model_id"])) {
    //     $error["chlide_model_id"] = " Child Model Name is required";
    // }    

    if (empty(trim($_POST["category_id"]))) {
        $error["category_id"] = "Category Name is required";
    }


    if (empty(trim($_POST["product_name"]))) {
        $error["product_name"] = "Product Name is required";
    } else {
        if (!preg_match("/^[a-zA-Z ]*$/", trim($_POST["product_name"]))) {
            $error['product_name'] = "Only Letters and Space Allowed";
        }
    }


    if (empty(trim($_POST["mrp"]))) {
        $error["mrp"] = "MRP Price is required";
    } else {
        if (!preg_match("/^[0-9]+(?:\.[0-9]{1,2})?$/", trim($_POST["mrp"]))) {
            $error['mrp'] = "Only Numbers are Allowed";
        }
    }

    if (!empty($_POST["unit"])) {
        if (!is_numeric($_POST["unit"])) {
            $error["unit"] = "Packing Unit is allowed only numbers";
        }
    }

    if (empty(trim($_POST["code"]))) {
        $error["code"] = "Product Code is required";
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



    // if (!empty($_POST['product_image'])) {

    //     if (empty($_FILES["image"]['name'])) {
    //         $error["image"] = "Image is required";
    //     }

    //     $imageexptype = pathinfo($image, PATHINFO_EXTENSION);
    //     $image = $_FILES['image']['name'];

    //     $allowed_types = array('jpg', 'jpeg', 'png', 'gif');
    //     if (!in_array(strtolower($imageexptype), $allowed_types)) {
    //         $error["image"] = "Only Jpg, Jpeg, Png Files are Allowed.";
    //     }

    //     // Check if file size exceeds limit (5MB)
    //     $max_file_size = 5 * 1024 * 1024; // 5MB in bytes
    //     if ($_FILES["image"]["size"] > $max_file_size) {
    //         $error["image"] = "Image Size Exceeds the Limit. Please Upload an Image Smaller than 5MB.";
    //     }
    // }




    // print_r($_POST);
    // exit;


    // if (empty($_POST['id'])) {
    //     if ($_POST["brand_id"] != '' && $_POST["company_id"] != '' && $_POST["model_id"] != '' && $_POST["chlide_model_id"] == '') {
    //         $product_name = mysqli_real_escape_string($conn, trim($_POST['product_name']));
    //         $count = getDuplicate('products', "name='" . $product_name . "' and company_id = " . $_POST["company_id"] . " and brand_id = " . $_POST["brand_id"] . " and model_id = " . $_POST["model_id"] . " and chlide_model_id =0");
    //     } else if ($_POST["brand_id"] != '' && $_POST["company_id"] != '' && $_POST["model_id"] != '' && $_POST["chlide_model_id"] != '') {
    //         $product_name = mysqli_real_escape_string($conn, trim($_POST['product_name']));
    //         $count = getDuplicate('products', "name='" . $product_name . "' and company_id = " . $_POST["company_id"] . " and brand_id = " . $_POST["brand_id"] . " and chlide_model_id = " . $_POST["chlide_model_id"] . " and model_id = " . $_POST["model_id"]);
    //     }
    // }


    // if ($count > 0) {
    //     $error['product_name'] = "Product Name Is Already in Use";
    // }


    if (count($error) == 0) {
        $id = $_POST["id"];
        if ($id != '') {

            // if ($_POST["brand_id"] != '' && $_POST["company_id"] != '' && $_POST["model_id"] != '' && $_POST["chlide_model_id"] == '') {
            //     $product_name = mysqli_real_escape_string($conn, trim($_POST['product_name']));
            //     $count = getDuplicate('products', "name='" . $product_name . "' and company_id = " . $_POST["company_id"] . " and brand_id = " . $_POST["brand_id"] . " and model_id = " . $_POST["model_id"] . " and chlide_model_id = 0 and id != " . $_POST["id"]);
            // } else if ($_POST["brand_id"] != '' && $_POST["company_id"] != '' && $_POST["model_id"] != '' && $_POST["chlide_model_id"] != '') {
            //     $product_name = mysqli_real_escape_string($conn, trim($_POST['product_name']));
            //     $count = getDuplicate('products', "name='" . $product_name . "' and company_id = " . $_POST["company_id"] . " and brand_id = " . $_POST["brand_id"] . " and chlide_model_id = " . $_POST["chlide_model_id"] . " and model_id = " . $_POST["model_id"] . " and id != " . $_POST["id"]);
            // }

            // if ($count > 0) {
            //     $error['product_name'] = "Product Name Is Already in Use";
            // }

            $data['id'] = $_POST["id"];
            $old_img = $_POST['product_image'];

            $new_img = $_FILES['image']['name'];
            $data['company_id'] = $_POST["company_id"];
            $data['model_id'] = $_POST["model_id"];
            $data['chlide_model_id'] = $_POST["chlide_model_id"];
            $data['category_id'] = $_POST["category_id"];
            $data['name'] = $_POST["product_name"];
            $data['code'] = $_POST["code"];
            $data['unit'] = $_POST["unit"];
            $data['brand_id'] = $_POST["brand_id"];
            $data['updated_at'] = date('Y-m-d H:i:s');
            $data['updated_by'] = $_SESSION['USER_ID'];


            if ($new_img != '') {

                $imageexptype = pathinfo($new_img, PATHINFO_EXTENSION);

                $date = date('m/d/Yh:i:sa');

                $rand = rand(10000, 99999);

                $encname = $date . $rand;

                $imagename = md5($encname) . '.' . $imageexptype;

                $imagepath = "uploads/products/" . $imagename;
                move_uploaded_file($_FILES["image"]["tmp_name"], $imagepath);

                $data['image'] = $imagename;
            } else {
                $data['image'] = $old_img;
            }


            if (count($error) == 0) {
                $model_update_id = updateRecord('products', $data, $id);
                if ($model_update_id) {
                    redirecte("products.php", "Product Name Updated Successfully");
                }
            }
        } else {
            $image = $_FILES['image']['name'];
            $imageexptype = pathinfo($image, PATHINFO_EXTENSION);
            $date = date('m/d/Yh:i:sa');
            $rand = rand(10000, 99999);
            $encname = $date . $rand;
            $imagename = md5($encname) . '.' . $imageexptype;
            $imagepath = "uploads/products/" . $imagename;

            $data['company_id'] = $_POST["company_id"];
            $data['model_id'] = $_POST["model_id"];
            $data['brand_id'] = $_POST["brand_id"];
            $data['chlide_model_id'] = $_POST["chlide_model_id"];
            $data['category_id'] = $_POST["category_id"];
            $data['name'] = $_POST["product_name"];
            $data['code'] = $_POST["code"];
            $data['unit'] = $_POST["unit"];
            $data['image'] = $imagename;

            $data['mrp'] = $_POST["mrp"];
            $data['created_at'] = date('Y-m-d H:i:s');
            $data['created_by'] = $_SESSION['USER_ID'];
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $imagepath)) {
                $model_id = addRecord('products', $data);
                if ($model_id == TRUE) {
                    redirecte("products.php", "Product Added Successfully");
                }
            }
        }
    }
}

if (isset($_GET['update_id'])) {

    $sql_product = "SELECT  *,p.image as product_image,p.name as product_name FROM `products` p 
    JOIN companies c on c.id=p.company_id
    JOIN models m on m.id=p.model_id
    -- LEFT JOIN child_models cm on cm.id=p.chlide_model_id 
    JOIN categories ca on ca.id= p.category_id
    WHERE p.id=" . $_GET['update_id'];



    $row = getQueryData($sql_product);

    if (count($error) == 0) {

        $_POST = $row;
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
                        <li class="breadcrumb-item">Product Details</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <!-- [ breadcrumb ] end -->
    <div class="pcoded-inner-content">
        <!-- Main-body start -->
        <div class="content-page">
            <div class="content">

                <!-- Start Content-->
                <div class="container-fluid">

                    <!-- start page title -->

                    <div class="card-box tilebox-one">
                        <form action="" id="even" method="POST" enctype="multipart/form-data">
                            <input type="hidden" name="id" value="<?php if (isset($_GET["update_id"])) echo $_GET["update_id"]; ?>">
                            <div class="row">
                                <div class="col-md-12">
                                    <h5>Add Product Details</h5>
                                    <hr>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="exampleSelect1" style="padding-bottom:0; vertical-align:middle">Brand Name</label><span style="color: red;">*</span>
                                        <select name="brand_id" id="brand_id" class="form-control col-lg-9">
                                            <option value="" selected>Select Brand Name</option>
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
                                </div>



                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="exampleSelect1" style="padding-bottom:0; vertical-align:middle">Company Name</label><span style="color: red;">*</span>
                                        <select name="company_id" id="company_id" class="form-control col-lg-9">
                                            <option value="" selected>Select Company Name</option>
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
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="exampleSelect1" style="padding-bottom:0; vertical-align:middle">Model Name</label><span style="color: red;">*</span>
                                        <select name="model_id" id="model_id" class="form-control col-lg-9">
                                            <option value="" selected>Select Model Name</option>
                                            <?php
                                            if (isset($_POST["company_id"]) && !isset($error["company_id"])) {
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
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="exampleSelect1" style="padding-bottom:0; vertical-align:middle">Child Model Name</label>
                                        <select name="chlide_model_id" id="chlide_model_id" class="form-control col-lg-9">
                                            <option value="" selected>Select Child Model Name</option>
                                            <?php
                                            if (isset($_POST["model_id"]) && !isset($error["model_id"])) {
                                                $sql_chlide_model = "select * from child_models where model_id=" . $_POST['model_id'];
                                                $result_chlide_model = mysqli_query($conn, $sql_chlide_model);
                                                if ($result_chlide_model->num_rows > 0) {
                                                    while ($row_chlide_model = mysqli_fetch_assoc($result_chlide_model)) {
                                            ?>
                                                        <option value="<?php echo $row_chlide_model['id'] ?>" <?php if (isset($_POST['chlide_model_id'])) if ($row_chlide_model['id'] == $_POST['chlide_model_id'])
                                                                                                                    echo "selected" ?>>
                                                            <?php echo $row_chlide_model['name'] ?>
                                                        </option>
                                            <?php }
                                                }
                                            } ?>
                                        </select>
                                        <span style=color:red;><?php if (isset($error["chlide_model_id"])) echo $error["chlide_model_id"]; ?></span>
                                    </div>
                                </div>


                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="exampleSelect1" style="padding-bottom:0; vertical-align:middle">Category Name</label><span style="color: red;">*</span>
                                        <select name="category_id" id="category_id" class="form-control col-lg-9">
                                            <option value="" selected>Select Category Name</option>
                                            <?php
                                            $sql_company_name = "SELECT * FROM `categories` ORDER BY name";
                                            $result_company_name = mysqli_query($conn, $sql_company_name);
                                            while ($row = mysqli_fetch_assoc($result_company_name)) {
                                            ?>
                                                <option value="<?php echo $row['id'] ?>" <?php if (isset($_POST['category_id'])) if ($row['id'] == $_POST['category_id'])
                                                                                                echo ' selected="selected"'; ?>>
                                                    <?php echo $row['name'] ?>
                                                </option>
                                            <?php
                                            }
                                            ?>
                                        </select>
                                        <span style=color:red;><?php if (isset($error["category_id"])) echo $error["category_id"]; ?></span>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="exampleSelect1" style="padding-bottom:0; vertical-align:middle">Product Name</label><span style="color: red;">*</span>
                                        <input type="text" name="product_name" class="form-control" value="<?php if (isset($_POST['product_name'])) echo trim($_POST['product_name']); ?>">
                                        <span style=color:red;><?php if (isset($error["product_name"])) echo $error["product_name"]; ?></span>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="exampleSelect1" style="padding-bottom:0; vertical-align:middle">Upload Image</label><span style="color: red;">*</span>
                                        <input type="file" name="image" class="form-control">
                                        <input type="hidden" name="product_image" value="<?php if (isset($_POST['product_image'])) echo trim($_POST['product_image']); ?>">
                                        <?php

                                        if (!empty($_POST['product_image'])) {
                                            echo '<img src="uploads/products/' . htmlspecialchars($_POST['product_image']) . '" width="100" />';
                                        } else if (!empty($_POST['product_image'])) {
                                            echo '<img src="uploads/products/' . htmlspecialchars($_POST['product_image']) . '" width="100" />';
                                        }
                                        ?>
                                        <span style=color:red;><?php if (isset($error["image"])) echo $error["image"]; ?></span>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="exampleSelect1" style="padding-bottom:0; vertical-align:middle">Product Code</label><span style="color: red;">*</span>
                                        <input type="text" name="code" class="form-control " value="<?php if (isset($_POST['code'])) echo trim($_POST['code']); ?>">
                                        <span style=color:red;><?php if (isset($error["code"])) echo $error["code"]; ?></span>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="exampleSelect1" style="padding-bottom:0; vertical-align:middle">Packing Unit</label>
                                        <input type="text" name="unit" class="form-control " value="<?php if (isset($_POST['unit'])) echo trim($_POST['unit']); ?>">
                                        <span style=color:red;><?php if (isset($error["unit"])) echo $error["unit"]; ?></span>
                                    </div>
                                </div>



                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="exampleSelect1" style="padding-bottom:0; vertical-align:middle">MRP Price</label><span style="color: red;">*</span>
                                        <input type="text" name="mrp" class="form-control" value="<?php if (isset($_POST['mrp'])) echo trim($_POST['mrp']); ?>">
                                        <span style=color:red;><?php if (isset($error["mrp"])) echo $error["mrp"]; ?></span>
                                    </div>
                                </div>
                            </div>

                            <?php if (!empty($_GET['update_id'])) {
                            ?>
                                <div class="col-md-12">
                                    <div class="form-group text-right" style="margin-bottom:0;">
                                        <label for="exampleSelect1"></label>
                                        <button type="submit" name="submit" class="btn btn-info">Update</button>
                                    </div>
                                </div>
                            <?php
                            } else {
                            ?>
                                <div class="col-md-12">
                                    <div class="form-group text-right" style="margin-bottom:0;">
                                        <label for="exampleSelect1"></label>
                                        <button type="submit" name="submit" value="Submit" class="btn btn-info">Submit</button>
                                    </div>
                                </div>
                            <?php

                            } ?>
                    </div>
                </div>
                </form>
                <!-- end row -->

            </div>


            <!-- end row -->

        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
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

                        $("#model_id").html(data);
                    }
                });
            }
        });


        $("#model_id").on('change', function() {
            var model_id = $(this).val();
            if (model_id) {
                $.ajax({
                    type: 'POST',
                    url: 'backend_script.php',
                    data: {
                        'model_id': model_id
                    },
                    success: function(data) {
                        console.log(data);
                        $("#chlide_model_id").html(data);
                    },
                });
            }
        });



    });



    ;
</script>
<?php include('includes/footer.php'); ?>