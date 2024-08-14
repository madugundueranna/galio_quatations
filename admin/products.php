<?php
session_start();
include('functions/functions.php');
include('../config/dbcon.php');
include('functions/session.php');
include('functions/myfunctions.php');

if (isset($_GET["deleteid"])) {

    $get_data_sql = "select * from products where id=" . $_GET["deleteid"];
    $query_run = mysqli_query($conn, $get_data_sql);
    $value = mysqli_fetch_assoc($query_run);
    $query = "DELETE FROM `products` WHERE id=" . $_GET["deleteid"];

    $query_run = mysqli_query($conn, $query);

    if ($query_run) {
        unlink("uploads/products/" . $value['image']);
        redirecte("products.php", "Product Name Deleted Successfully");
    }
}


include_once("includes/header.php");
include('includes/sidebar.php');
?>

<!-- [ navigation menu ] end -->
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
                        <li class="breadcrumb-item"><a href="">Product Details</a></li>
                    </ul>
                    <span class="fa-pull-right">
                        <a href="product.php" class="btn btn-danger btn-sm"><i>Add Product</i></a></span>
                </div>
            </div>
        </div>
    </div>
    <!-- [ breadcrumb ] end -->
    <div class="pcoded-inner-content">
        <!-- Main-body start -->
        <div class="main-body">
            <div class="page-wrapper">

                <!-- Page-body start -->
                <div class="page-body">
                    <form method="post">

                        <div class="row">
                            <div class="col-md-12">
                                <div class="card table-card">
                                    <div class="card-header bg-info">
                                        <h5>Product Details</h5>
                                    </div>
                                    <div class="card-block p-b-0">
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-hover m-b-0" id="productsTable">
                                                <thead>
                                                    <tr>
                                                        <th>S.No</th>
                                                        <th>Brand Name</th>
                                                        <th>Company Name</th>
                                                        <th>Model Name</th>
                                                        <th> Chlid Model Name</th>
                                                        <th>Category Name</th>
                                                        <th>Product Name</th>
                                                        <th>Product Code</th>
                                                        <th>Packing unit</th>
                                                        <th>MRP Price</th>
                                                        <th>Image</th>
                                                        <th>Created Date</th>
                                                        <th class="not-export-column">Actions</th>
                                                    </tr>
                                                </thead>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                </div>
            </div>
            </form>
        </div>
        <!-- Page-body end -->
    </div>





    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function() {
            $('#productsTable').DataTable({
                processing: true,
                serverSide: true,
                serverMethod: 'post',
                ajax: {
                    url: 'ajaxfile_products.php',
                    type: 'post',
                },
                dom: 'lBifrtip',
                order: [
                    [1, "desc"]
                ],
                'lengthMenu': [
                    [10, 25, 50, 100, -1],
                    [10, 25, 50, 100, "All"]
                ],

                'columns': [{

                        data: 's_no'
                    }, {
                        data: 'brand_name'
                    },

                    {
                        data: 'company_name'
                    },
                    {
                        data: 'model_name'
                    },
                    {
                        data: 'child_model_name'
                    },
                    {
                        data: 'category_name'
                    },
                    {
                        data: 'product_name'
                    },
                    {
                        data: 'product_code'
                    },
                    {
                        data: 'packing_unit'
                    },
                    {
                        data: 'mrp_price'
                    },
                    {
                        data: 'product_image'
                    },
                    {
                        data: 'product_created_at'
                    },
                    {
                        data: 'action'
                    },
                ],
                buttons: [{
                        extend: 'excel',
                        title: "Products",
                        exportOptions: {
                            columns: ":not(.not-export-column)"
                        }
                    },
                    {
                        extend: 'pdf',
                        title: "Products",
                        exportOptions: {
                            columns: ":not(.not-export-column)"
                        }
                    }
                ],

            });
        });



        function confirmDelete(product_id) {
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
                    window.location.href = 'products.php?deleteid=' + product_id;
                }
            });
        }
    </script>

    <?php include_once("includes/footer.php") ?>