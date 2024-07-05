<?php
session_start();
include('../config/dbcon.php');
include('functions/session.php');
include('functions/myfunctions.php');
include('includes/header.php');

if ($_SESSION['ROLE'] == 1) {
    $sql = "SELECT b.name branch_name, q.*
    FROM quotations q 
    JOIN employees e ON e.id = q.created_by
    JOIN branches b ON b.id = e.branch_id
    WHERE DATE(q.created_at) = '" . date('Y-m-d') . "'";
} elseif ($_SESSION['USER_ID'] != 1) {
    $sql = "SELECT b.name branch_name, q.*
    FROM quotations q 
    JOIN employees e ON e.id = q.created_by
    JOIN branches b ON b.id = e.branch_id
    WHERE DATE(q.created_at) = '" . date('Y-m-d') . "' AND q.created_by=" . $_SESSION['USER_ID'];
}
$result = mysqli_query($conn, $sql);
if ($_SESSION['USER_BRANCH_ID'] != '') {
    $where = " where id=" . $_SESSION['USER_BRANCH_ID'];
} else {
    $where = " where 1";
}
$branchs_sql = "select * from branches" . $where;
$branches = mysqli_query($conn, $branchs_sql);



?>
<?php include('includes/sidebar.php'); ?>
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
                        <li class="breadcrumb-item"><a href="#!">Dashboard</a></li>
                    </ul>
                    <span class="fa-pull-right">
                        <?php
                        if (isset($_SESSION['ROLE'])) {
                            $role = $_SESSION['ROLE'];
                            if ($role == 2) {
                        ?>
                                <a href="create-quotation.php" class="btn btn-danger btn-sm"><i>Create Quotation</i></a></span>
            <?php
                            }
                        } ?>
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
                    <div class="row">
                        <!-- web statistic card start -->
                        <?php
                        $colors = ['bg-c-blue', 'bg-c-green', 'bg-c-red', 'bg-c-yellow']; // Define colors
                        $colorIndex = 0; // Initialize color index
                        if ($branches) {
                            while ($branch = mysqli_fetch_assoc($branches)) {
                                if ($_SESSION['ROLE'] != 1) {
                                    $where_con = " and q.created_by=" . $_SESSION['USER_ID'] . " and b.id=" . $branch['id'];
                                } else {
                                    $where_con = " and b.id=" . $branch['id'];
                                }
                                $active_quoations_count_sql = "SELECT count(q.id) today_active_quoations_count FROM `quotations` q
                                join employees e on e.id=q.created_by
                                join branches b on b.id=e.branch_id
                                WHERE 1 " . $where_con . " and date(q.created_at)='" . date('Y-m-d') . "' and q.status=1";

                                $inactive_quoations_count_sql = "SELECT count(q.id) today_inactive_quoations_count FROM `quotations` q
                                join employees e on e.id=q.created_by
                                join branches b on b.id=e.branch_id
                                WHERE 1 " . $where_con . " and date(q.created_at)='" . date('Y-m-d') . "' and q.status=0";

                                $active_quoations_counts = mysqli_query($conn, $active_quoations_count_sql);
                                $active_quoations_count = mysqli_fetch_assoc($active_quoations_counts);
                                $inactive_quoations_counts = mysqli_query($conn, $inactive_quoations_count_sql);
                                $inactive_quoations_count = mysqli_fetch_assoc($inactive_quoations_counts)
                        ?>
                                <div class="col-xl-3 col-md-6">
                                    <div class="card o-hidden <?php echo $colors[$colorIndex]; ?> web-num-card">
                                        <div class="card-block text-white">
                                            <h3 class="m-t-15"><?php echo $branch['name']; ?></h3>
                                            <h5 class="m-b-15">Today Records: <?php echo $active_quoations_count['today_active_quoations_count']; ?></h5>
                                            <h5 class="m-b-15">Today Deleted Records: <?php echo $inactive_quoations_count['today_inactive_quoations_count']; ?></h5>
                                        </div>
                                    </div>
                                </div>
                        <?php
                                // Increment color index and reset if it exceeds the number of colors
                                $colorIndex = ($colorIndex + 1) % count($colors);
                            }
                        }
                        ?>

                        <div class="col-md-12">
                            <div class="card table-card">
                                <div class="card-header bg-info">
                                    <h5>Quotations</h5>
                                </div>
                                <div class="card-block p-b-0">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-hover m-b-0" id="dashboard_table">
                                            <thead>
                                                <tr>
                                                    <th>Branch</th>
                                                    <th>Q.No</th>
                                                    <th>Vehicle</th>
                                                    <th>Created date</th>
                                                    <th>Status</th>
                                                    <th class="not-export-column">Actions</th>
                                                </tr>
                                            </thead>


                                            <tbody>
                                                <?php
                                                if ($result) {
                                                    while ($row = mysqli_fetch_assoc($result)) {
                                                ?>
                                                        <tr>
                                                            <td><?php echo $row['branch_name']; ?></td>
                                                            <td><?php echo $row['quotation_no']; ?></td>
                                                            <td><?php echo $row['vehicle_model']; ?></td>
                                                            <td><?php echo date('d-m-Y', strtotime($row['created_at'])); ?></td>
                                                            <td><?php if ($row['status'] == 1) echo "Active";
                                                                else echo "In Active" ?></td>
                                                            <td>
                                                                <a data-toggle="modal" data-target="#large-Modal" onclick="GetDetails('<?php echo $row['id']; ?>')"><i class="feather icon-eye fa-2x" style="font-size: 1.5em;"></i></a>
                                                                <span style="margin: 0 5px;"></span>

                                                                <?php
                                                                if (isset($_SESSION['ROLE'])) {
                                                                    $role = $_SESSION['ROLE'];
                                                                    if ($role == 1) {
                                                                ?>
                                                                        <a href="javascript:void(0);" onclick="printQuotation('<?php echo $row['id']; ?>')">
                                                                            <i class="fas fa-print"></i>
                                                                        </a>
                                                                <?php }
                                                                } ?>
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
        <!-- Page-body end -->
    </div>
</div>
</div>
</div>
</div>
</div>
<div class="modal fade" id="large-Modal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="quotation_no"><span></span></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Customer Name: <span class="text-danger" id="customer_name"></span></p>
                <p>Vehicle: <span class="text-danger" id="vehicle_model"></span></p>
                <p>Branch: <span class="text-danger" id="branch_name"></span></p>
                <p>Date: <span class="text-danger" id="quotation_created_at"></span></p>

                <div class="table-responsive" id="table_name">

                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default waves-effect " data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function GetDetails(id) {
        $.ajax({
            type: "post",
            url: "ajax_list_quotations.php",
            data: {
                displayData: true,
                id: id
            },
            success: function(data) {
                var response = JSON.parse(data);

                console.log(response.overall_total);
                $("#quotation_no").text("Quotation No: " + response.quotation_no);
                $("#customer_name").text(response.customer_name); // Changed from .val() to .text()
                $("#vehicle_model").text(response.vehicle_model); // Corrected the field name
                $("#branch_name").text(response.branch_name);
                $("#quotation_created_at").text(formatDate(response.quotation_created_at));

                // Handle items
                var table = '<table class="table table-bordered table-hover m-b-0">';
                table += '<thead><tr class="bg-info"><th>Item</th><th>Economy</th><th>Good</th><th>Premium</th></tr></thead>';
                table += '<tbody>';

                $.each(response.items, function(index, item) {
                    var bestPriceFormatted = parseInt(item.best_price).toString();
                    var economyFormatted = parseInt(item.economy).toString();
                    var premiumFormatted = parseInt(item.premium).toString();

                    table += '<tr><td>' + item.item_name + '</td><td>' + economyFormatted + '</td><td>' + bestPriceFormatted +
                        '</td><td>' + premiumFormatted + '</td></tr>';
                });





                // table += '<tfoot>';
                table += '<tr class="bg-light">';
                table += '<td colspan="1">Total</td>'; // For the empty cell in the first column
                table += '<td id="economy_total">' + response.economy_total + '</td>';
                table += '<td id="best_price_total">' + response.best_price_total + '</td>';
                table += '<td id="premium_total">' + response.premium_total + '</td>';
                table += '</tr>';
                // table += '</tfoot>';


                table += '<tr class="bg-light">';
                table += '<td colspan="3">Labour Charges</td>';
                table += '<td id="overall_total">' + parseInt(response.labour_charges) + '</td>';
                table += '</tr>';
                table += '</tfoot>';



                table += '<tfoot>';
                table += '<tr class="bg-light">';
                table += '<td colspan="3">Grand Total</td>';
                table += '<td id="overall_total">' + response.overall_total + '</td>';
                table += '</tr>';
                table += '</tfoot>';
                table += '</tbody>';

                // Add a row for overall total


                table += '</table>';

                $("#table_name").html(table);

            }
        });
    }


    function printQuotation(id) {
        $.ajax({
            type: "post",
            url: "ajax_list_quotations.php",
            data: {
                displayData: true,
                id: id
            },
            success: function(data) {
                var response = JSON.parse(data);

                // Create a hidden iframe
                var printFrame = document.createElement('iframe');
                printFrame.style.visibility = 'hidden';
                document.body.appendChild(printFrame);

                // Display customer name and vehicle model
                printFrame.contentDocument.write('<p>Customer Name: ' + response.customer_name + '</p>');
                printFrame.contentDocument.write('<p>Branch Name: ' + response.branch_name + '</p>');
                printFrame.contentDocument.write('<p>Vehicle Model: ' + response.vehicle_model + '</p>');

                // Create the table as you already did
                var table = '<table class="table table-bordered table-hover m-b-0">';
                table += '<thead><tr class="bg-info"><th>Item</th><th>Economy</th><th>Good</th><th>Premium</th></tr></thead>';
                table += '<tbody>';

                $.each(response.items, function(index, item) {
                    var bestPriceFormatted = parseInt(item.best_price).toString();
                    var economyFormatted = parseInt(item.economy).toString();
                    var premiumFormatted = parseInt(item.premium).toString();

                    table += '<tr><td>' + item.item_name + '</td><td>' + economyFormatted + '</td><td>' + bestPriceFormatted + '</td><td>' + premiumFormatted + '</td></tr>';
                });

                // table += '<tfoot>';
                table += '<tr class="bg-light">';
                table += '<td>Total</td>'; // For the empty cell in the first column
                table += '<td id="economy_total">' + response.economy_total + '</td>';
                table += '<td id="best_price_total">' + response.best_price_total + '</td>';
                table += '<td id="premium_total">' + response.premium_total + '</td>';
                table += '</tr>';
                // table += '</tfoot>';

                table += '<tr class="bg-light">';
                table += '<td colspan="3">Labour Charges</td>';
                table += '<td id="overall_total">' + parseInt(response.labour_charges) + '</td>';
                table += '</tr>';
                table += '</tfoot>';



                table += '<tfoot>';
                table += '<tr class="bg-light">';
                table += '<td colspan="3">Grand Total</td>';
                table += '<td id="overall_total">' + response.overall_total + '</td>';
                table += '</tr>';
                table += '</tfoot>';



                table += '</tbody>';
                table += '</table>';

                // Append the table to the iframe
                printFrame.contentDocument.write(table);

                // Print the iframe content
                printFrame.contentWindow.print();

                // Remove the iframe from the document
                document.body.removeChild(printFrame);
            }
        });
    }


    const formatDate = dateString => {
        const date = new Date(dateString);
        const day = date.getDate().toString().padStart(2, '0');
        const month = (date.getMonth() + 1).toString().padStart(2, '0');
        const year = date.getFullYear();
        return `${day}-${month}-${year}`;
    };


    $(document).ready(function() {
        var table = $('#dashboard_table').DataTable({
            dom: 'lBifrtip',
            lengthMenu: [
                [5, 10, 25, 50, 100, -1],
                [5, 10, 25, 50, 100, "All"]
            ],
            buttons: [{
                    extend: 'excel',
                    title: "List_Of_Active_and_Inactive_Quotations",
                    exportOptions: {
                        columns: ":not(.not-export-column)"
                    }
                },
                {
                    extend: 'pdf',
                    title: "List_Of_Active_and_Inactive_Quotations",
                    exportOptions: {
                        columns: ":not(.not-export-column)"
                    }
                }
            ]
        });
    });
</script>




<?php include('includes/footer.php'); ?>