<?php
session_start();

?>
<div class="pcoded-main-container">
    <div class="pcoded-wrapper">
        <!-- [ navigation menu ] start -->
        <nav class="pcoded-navbar">
            <div class="nav-list">
                <div class="pcoded-inner-navbar main-menu">
                    <div class="pcoded-navigation-label">Navigation</div>
                    <ul class="pcoded-item pcoded-left-item">
                        <li>
                            <a href="dashboard.php" class="waves-effect waves-dark">
                                <span class="pcoded-micon"><i class="feather icon-home"></i></span>
                                <span class="pcoded-mtext">Dashboard</span>
                            </a>
                        </li>
                        <?php


                        if (isset($_SESSION['ROLE'])) {
                            $role = $_SESSION['ROLE'];
                            if ($role == 1) {
                        ?>
                                <li>
                                    <a href="employees.php" class="waves-effect waves-dark">
                                        <span class="pcoded-micon"><i class="feather icon-menu"></i></span>
                                        <span class="pcoded-mtext">Employee</span>

                                    </a>
                                </li>
                        <?php
                            }
                        } ?>


                        <li class="">
                            <a href="list-quotations.php" class="waves-effect waves-dark">
                                <span class="pcoded-micon"><i class="feather icon-menu"></i></span>
                                <span class="pcoded-mtext">List Quotations</span>
                            </a>
                        </li>

                        <li class="">
                            <a href="inactive-quotations.php" class="waves-effect waves-dark">
                                <span class="pcoded-micon"><i class="feather icon-menu"></i></span>
                                <span class="pcoded-mtext">Deleted Quotations</span>
                            </a>
                        </li>

                        <?php
                        if (isset($_SESSION['ROLE'])) {
                            $role = $_SESSION['ROLE'];
                            if ($role == 2) {
                        ?>
                                <li class="">
                                    <a href="create-quotation.php" class="waves-effect waves-dark">
                                        <span class="pcoded-micon"><i class="feather icon-menu"></i></span>
                                        <span class="pcoded-mtext">Add Quotation</span>
                                    </a>
                                </li>
                        <?php
                            }
                        }
                        ?>


                        <?php
                        if (isset($_SESSION['ROLE'])) {
                            $role = $_SESSION['ROLE'];
                            if ($role == 1) {
                        ?>
                                <li class="">
                                    <a href="add_item.php" class="waves-effect waves-dark">
                                        <span class="pcoded-micon"><i class="feather icon-menu"></i></span>
                                        <span class="pcoded-mtext">Add Item</span>
                                    </a>
                                </li>
                        <?php
                            }
                        }
                        ?>


                        <?php
                        if (isset($_SESSION['ROLE'])) {
                            $role = $_SESSION['ROLE'];
                            if ($role == 1) {
                        ?>
                                <li class="">
                                    <a href="brand.php" class="waves-effect waves-dark">
                                        <span class="pcoded-micon"><i class="feather icon-menu"></i></span>
                                        <span class="pcoded-mtext">Add Brand</span>
                                    </a>
                                </li>
                        <?php
                            }
                        }
                        ?>



                        <?php
                        if (isset($_SESSION['ROLE'])) {
                            $role = $_SESSION['ROLE'];
                            if ($role == 1) {
                        ?>
                                <li class="">
                                    <a href="company.php" class="waves-effect waves-dark">
                                        <span class="pcoded-micon"><i class="feather icon-menu"></i></span>
                                        <span class="pcoded-mtext">Add Company</span>
                                    </a>
                                </li>
                        <?php
                            }
                        }
                        ?>


                        <?php
                        if (isset($_SESSION['ROLE'])) {
                            $role = $_SESSION['ROLE'];
                            if ($role == 1) {
                        ?>
                                <li class="">
                                    <a href="model.php" class="waves-effect waves-dark">
                                        <span class="pcoded-micon"><i class="feather icon-menu"></i></span>
                                        <span class="pcoded-mtext">Add Model</span>
                                    </a>
                                </li>
                        <?php
                            }
                        }
                        ?>



                        <?php
                        if (isset($_SESSION['ROLE'])) {
                            $role = $_SESSION['ROLE'];
                            if ($role == 1) {
                        ?>
                                <li class="">
                                    <a href="chlide_model.php" class="waves-effect waves-dark">
                                        <span class="pcoded-micon"><i class="feather icon-menu"></i></span>
                                        <span class="pcoded-mtext">Add Child Model</span>
                                    </a>
                                </li>
                        <?php
                            }
                        }
                        ?>



                        <?php
                        if (isset($_SESSION['ROLE'])) {
                            $role = $_SESSION['ROLE'];
                            if ($role == 1) {
                        ?>
                                <li class="">
                                    <a href="spare_category.php" class="waves-effect waves-dark">
                                        <span class="pcoded-micon"><i class="feather icon-menu"></i></span>
                                        <span class="pcoded-mtext">Add Spare Category</span>
                                    </a>
                                </li>
                        <?php
                            }
                        }
                        ?>
                        

                        <?php
                        if (isset($_SESSION['ROLE'])) {
                            $role = $_SESSION['ROLE'];
                            if ($role == 1) {
                        ?>
                                <li class="">
                                    <a href="products.php" class="waves-effect waves-dark">
                                        <span class="pcoded-micon"><i class="feather icon-menu"></i></span>
                                        <span class="pcoded-mtext">Product List</span>
                                    </a>
                                </li>
                        <?php
                            }
                        }
                        ?>


                        <li class="">
                            <a href="logout.php" class="waves-effect waves-dark">
                                <span class="pcoded-micon"><i class="feather icon-menu"></i></span>
                                <span class="pcoded-mtext">Logout</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>