<?php
include('../config/dbcon.php');

if (isset($_POST["displayData"])) {
    $id = $_POST['id'];
    $sql_quotation_branch = "SELECT q.*, b.name branch_name,q.labour_charges  FROM `quotations` AS q 
        JOIN `employees` AS e ON q.created_by = e.id
        JOIN `branches` AS b ON b.id = e.branch_id
        WHERE q.id=" . $id;

    $result_branch = mysqli_query($conn, $sql_quotation_branch);

    $res = array();

    if ($result_branch) {
        while ($row = mysqli_fetch_assoc($result_branch)) {
            $res['branch_name'] = $row['branch_name'];
            $res['customer_name'] = $row['customer_name'];
            $res['vehicle_model'] = $row['vehicle_model'];
            $res['quotation_created_at'] = date('Y-m-d', strtotime($row['created_at']));
            $res['quotation_no'] = $row['quotation_no'];
            $res['labour_charges']=$row['labour_charges'];
        }
    }


        $sql_quotation = "SELECT i.name as item_name, qi.*,q.labour_charges
        FROM `quotation_items` qi
        JOIN `quotations` q ON q.id = qi.`quotation_id`
        JOIN `items` i ON i.id = qi.`item_id`
        WHERE q.id = $id
        ORDER BY i.priority,i.name";
    
    
    $result = mysqli_query($conn, $sql_quotation);

    if ($result) {
        $items = array();
        $best_price_total = 0;
        $premium_total = 0;
        $economy_total = 0;

        while ($row = mysqli_fetch_assoc($result)) {
            $item = array(
                'item_name' => $row["item_name"],
                'best_price' => $row["best_price"],
                'premium' => $row["premium"],
                'economy' => $row["economy"],
                
            );

            $best_price_total += $row["best_price"];
            $premium_total += $row["premium"];
            $economy_total += $row["economy"];
            $labour_charges += $row["labour_charges"];

            $items[] = $item;
        }

        $res['items'] = $items;
        $res['best_price_total'] = $best_price_total;
        $res['economy_total'] = $economy_total;
        $res['premium_total'] = $premium_total;
        // $res['labour_charges']=$labour_charges;

        $res['overall_total'] = $economy_total + $best_price_total + $premium_total+$res['labour_charges'];
    }

    // Convert the PHP array to JSON
    echo json_encode($res);
}
