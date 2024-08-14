<?php
include('../config/dbcon.php');
if (!empty($_POST['company_id'])) {
    $company_id = $_POST['company_id'];
    $query = "select * from models where company_id=$company_id";
    $result = mysqli_query($conn, $query);

    if ($result->num_rows > 0) {
        echo '<option value="">Select Model</option>';
        while ($row = mysqli_fetch_assoc($result)) {
            echo '<option value="' . $row['id'] . '">' . $row['name'] . '</option>';
        }
    }
}



if (!empty($_POST['model_id'])) {
    $model_id = $_POST['model_id'];
    $query = "select * from child_models where model_id=$model_id";
    $result = mysqli_query($conn, $query);

    if ($result->num_rows > 0) {
        echo '<option value="">Select Model</option>';
        while ($row = mysqli_fetch_assoc($result)) {
            echo '<option value="' . $row['id'] . '">' . $row['name'] . '</option>';
        }
    }
}
