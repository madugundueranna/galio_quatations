<?php
include('../config/dbcon.php');

$draw = $_POST['draw'];
$start = $_POST['start'];
$length = $_POST['length'];
$columnIndex = $_POST['order'][0]['column'];
$columnName = $_POST['columns'][$columnIndex]['data'];
$columnSortOrder = $_POST['order'][0]['dir'];
$searchValue = mysqli_real_escape_string($conn, $_POST['search']['value']);

$searchQuery = "";

if (!empty($searchValue)) {
    $searchQuery = " AND (c.name LIKE '%" . $searchValue . "%' OR m.name LIKE '%" . $searchValue . "%' OR cm.name LIKE '%" .
        $searchValue . "%' OR p.name LIKE '%" . $searchValue . "%' OR p.code LIKE '%" . $searchValue . "%' OR p.unit LIKE '%" .
        $searchValue . "%' OR p.mrp LIKE '%" . $searchValue . "%' OR p.image LIKE '%" . $searchValue . "%' OR DATE_FORMAT(p.created_at, '%d-%m-%Y') LIKE '%" .
        $searchValue . "%')";
}



 $empQuery = "SELECT b.name AS brand_name ,c.name AS company_name, m.name AS model_name, cm.name AS child_model_name,
p.name AS product_name, p.code AS product_code, p.unit AS packing_unit,
p.mrp AS mrp_price, p.image AS product_image,
p.created_at AS product_created_at,
p.id as product_id,
cat.name as category_name FROM `products` p 
JOIN brands b on b.id=p.brand_id
JOIN companies c on c.id=p.company_id
JOIN models m on m.id=p.model_id
LEFT JOIN child_models cm on cm.id=p.chlide_model_id
JOIN categories cat on cat.id= p.category_id
WHERE 1 " . $searchQuery . " ORDER BY " . $columnName . " " . $columnSortOrder . " LIMIT " . $start . "," . $length;




$empRecords = mysqli_query($conn, $empQuery);

$data = array();
$startFrom = $start + 1;

while ($row = mysqli_fetch_assoc($empRecords)) {

    $editButton = "<a class='btn btn-primary' href='product.php?update_id=" . $row['product_id'] . "'>Edit</a>";
    $deleteButton = '<a class="btn btn-danger" href="javascript:void(0);" onclick="confirmDelete(' . $row['product_id'] . ')">Delete
    </a>';



    $formattedDate = date('d-m-Y', strtotime($row['product_created_at']));
    $action = $editButton . " " . $deleteButton;

    $data[] = array(
        "s_no" => $startFrom++,
        "brand_name" => $row['brand_name'],
        "company_name" => $row['company_name'],
        "model_name" => $row['model_name'],
        "child_model_name" => $row['child_model_name'],
        "category_name" => $row['category_name'],
        "product_name" => $row['product_name'],
        "product_code" => $row['product_code'],
        "packing_unit" => $row['packing_unit'],
        "mrp_price" => $row['mrp_price'],
        "product_image" => '<img  width="100" src="uploads/products/' . $row['product_image'] . '" alt="Product Image">',
        "product_created_at" => $formattedDate,
        "action" => $action,
    );
}






$response = array(
    "draw" => intval($draw),
    "iTotalRecords" => get_total_records(),
    "iTotalDisplayRecords" => get_filtered_records(),
    "aaData" => $data
);

echo json_encode($response);

function get_total_records()
{
    global $conn;
    $sel = mysqli_query($conn, "SELECT COUNT(*) as allcount FROM products");
    $records = mysqli_fetch_assoc($sel);
    return $records['allcount'];
}

function get_filtered_records()
{
    global $conn, $searchQuery;
    $sel = mysqli_query($conn, "SELECT COUNT(*) as allcount FROM products WHERE 1 " . $searchQuery);
    $records = mysqli_fetch_assoc($sel);
    return $records['allcount'];
}
