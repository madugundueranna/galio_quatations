<?php
session_start();
include('../config/dbcon.php');
$msg = "";
if (isset($_POST['submit'])) {
    $user_name = mysqli_real_escape_string($conn, $_POST['user_name']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $role = mysqli_real_escape_string($conn, $_POST['role']);
    $encpass = md5($password);
    $sql = mysqli_query($conn, "select * from users where `user_name`='$user_name' and `password`='$encpass'");
    $num = mysqli_num_rows($sql);
    if ($num > 0) {
        $row = mysqli_fetch_assoc($sql);

        if ($row['id'] != 1) {
            // $branch_sql = mysqli_query($conn, "select * from employees where id=" . $row['id']);
            $branch_sql = mysqli_query($conn, "SELECT * FROM employees WHERE id=" . $row['id'] . " AND status=1");
            $branch_num = mysqli_num_rows($branch_sql);
            $branch_row = mysqli_fetch_assoc($branch_sql);
            // print_r($branch_row['id']);
            // exit;
        }

        $_SESSION['USER_ID'] = $row['id'];
        if ($row['id'] != 1) {
            if (!empty($branch_row['branch_id'])) {
                $_SESSION['id'] =$branch_row['id'];
                
                $_SESSION['USER_BRANCH_ID'] = !empty($branch_row['branch_id']);
                $_SESSION['USER_NAME'] = $row['user_name'];
                $_SESSION['ROLE'] = $row['role'];
                header("location:dashboard.php");
                exit();
            } else {
                $msg = "Please Contact Admin";
            }
        } else {
            $_SESSION['USER_BRANCH_ID'] = '';
        }
        $_SESSION['USER_NAME'] = $row['user_name'];
        $_SESSION['ROLE'] = $row['role'];
        if ($row['id'] == 1) {

            header("location:dashboard.php");
        } else {
            $msg = "Please Contact Admin";
        }
    } else {
        $msg = "Please Enter Valid Details !";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Abhee Car Shopee</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <!-- Favicon icon -->
    <script type="text/javascript" src="files/bower_components/jquery/js/jquery.min.js"></script>
    <script type="text/javascript" src="files/assets/js/pace.min.js"></script>
    <!-- Google font-->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700,800" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,500,600,700" rel="stylesheet">
    <!-- Required Fremwork -->
    <link rel="stylesheet" type="text/css" href="files/bower_components/bootstrap/css/bootstrap.min.css">
    <!-- waves.css -->
    <link rel="stylesheet" href="files/assets/pages/waves/css/waves.min.css" type="text/css" media="all">
    <!-- feather icon -->
    <link rel="stylesheet" type="text/css" href="files/assets/icon/feather/css/feather.css">
    <!-- font-awesome-n -->
    <link rel="stylesheet" type="text/css" href="files/assets/css/font-awesome-n.min.css">
    <!-- Style.css -->
    <link rel="stylesheet" type="text/css" href="files/assets/css/style.css">
    <link rel="stylesheet" type="text/css" href="files/assets/css/pages.css">
    <style>
        .error {
            color: red;
        }
    </style>
</head>

<body themebg-pattern="theme1">
    <section class="login-block">
        <!-- Container-fluid starts -->
        <div class="container">
            <div class="row">
                <div class="col-sm-12">
                    <!-- Authentication card start -->
                    <form class="md-float-material form-material" method="post">
                        <div class="text-center mt-5">
                            <img src="files/assets/images/logo.png" alt="logo.png">
                        </div>
                        <div class="auth-box card">
                            <div class="card-block">
                                <div class="row m-b-20">
                                    <div class="col-md-12">
                                        <h3 class="text-center">Sign In</h3>
                                    </div>
                                </div>
                                <div class="form-group form-primary">
                                    <!-- <input type="text" name="user_name" class="form-control" value="<?php echo isset($_COOKIE['remember_username']) ? $_COOKIE['remember_username'] : ''; ?>"> -->
                                    <input type="text" name="user_name" class="form-control" value="<?php if (isset($_POST['user_name'])) echo trim($_POST['user_name']); ?>">

                                    <span class="form-bar"></span>
                                    <label class="float-label">User Name</label>
                                </div>
                                <div class="form-group form-primary">
                                    <!-- <input type="password" name="password" class="form-control" value="<?php echo isset($_COOKIE['remember_password']) ? $_COOKIE['remember_password'] : ''; ?>"> -->
                                    <input type="password" name="password" class="form-control" value="<?php if (isset($_POST['password'])) echo trim($_POST['password']); ?>">
                                    <span class="form-bar"></span>
                                    <label class="float-label">Password</label>
                                </div>
                                <div class="error" style="text-align: center;">
                                    <?php echo $msg ?>
                                </div>
                                <div class="row m-t-30">
                                    <div class="col-md-12">
                                        <!-- <a type="button" href="dashboard.php" class="btn btn-primary btn-md btn-block waves-effect waves-light text-center m-b-20">Sign in</a> -->
                                        <input type="submit" name="submit" value="Sign in" class="btn btn-primary btn-md btn-block waves-effect waves-light text-center m-b-20">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                    <!-- end of form -->
                </div>
                <!-- end of col-sm-12 -->
            </div>
            <!-- end of row -->
        </div>
        <!-- end of container-fluid -->
    </section>
    <script type="text/javascript" src="files/bower_components/jquery-ui/js/jquery-ui.min.js"></script>
    <script type="text/javascript" src="files/bower_components/popper.js/js/popper.min.js"></script>
    <script type="text/javascript" src="files/bower_components/bootstrap/js/bootstrap.min.js"></script>
    <!-- waves js -->
    <script src="files/assets/pages/waves/js/waves.min.js"></script>
    <!-- jquery slimscroll js -->
    <script type="text/javascript" src="files/bower_components/jquery-slimscroll/js/jquery.slimscroll.js"></script>
    <script type="text/javascript" src="files/assets/js/common-pages.js"></script>
</body>

</html>