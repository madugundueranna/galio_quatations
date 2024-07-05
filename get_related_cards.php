<?php
include('config/dbcon.php');
include('admin/functions/functions.php');

if (isset($_GET['product_id'])) {
    $sql_product_id = mysqli_real_escape_string($conn, $_GET['product_id']);

    if ($sql_product_id === 'all') {

        $sql_product_name = "SELECT * FROM products where chlide_model_id=" . $_GET['chlide_model_id'];
    } elseif ($sql_product_id === 'ALL') {
        $sql_product_name = "SELECT * FROM products where model_id=" . $_GET['model_id'];
    } else {

        $sql_product_name = "SELECT * FROM products WHERE id = " . $sql_product_id;
    }

    $result_products = mysqli_query($conn, $sql_product_name);




    if (mysqli_num_rows($result_products) > 0) {
        while ($row_product = mysqli_fetch_assoc($result_products)) {


            $sql_category_name = "SELECT * FROM `categories` where id=" . $row_product['category_id'];
            $row_category_name  = getQueryData($sql_category_name);

            echo '<div class="col-lg-12 col-xl-3 col-6 p-1">';
            echo '<a href="product-details.php?product_id=' . $row_product['id'] . '">';
            echo '<div class="card m-b-5">';
            echo '<div class="card-body">';
            echo '<div class="product-img">';
            echo '<img class="img-fluid" src="admin/uploads/products/' . $row_product['image'] . '" />';
            echo '</div>';
            echo '<div class="product-content">';
            echo '<h6>' . $row_product['name'] . '</h6>';
            echo '<p>' . $row_category_name['name'] . '</p>';
            echo '<p>' . $row_product['code'] . '</p>';
            echo '<p class="text-danger">Mrp: Rs. ' . $row_product['mrp'] . '</p>';
            echo '</div>';
            echo '</div>';
            echo '</div>';
            echo '</a>';
            echo '</div>';
        }
    } else {
        echo '<p>No related cards found.</p>';
    }
} 