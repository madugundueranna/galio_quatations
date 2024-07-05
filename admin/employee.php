<?php
session_start();
include('functions/functions.php');
include('../config/dbcon.php');
include('functions/api_functions.php');
include('functions/session.php');
include('functions/myfunctions.php');
include('includes/header.php');
$error = array();
$emp_created_by = $_SESSION['USER_ID'];
$emp_updated_by = $_SESSION['USER_ID'];
if (isset($_POST["submit"])) {
	if (empty(trim($_POST["name"]))) {
		$error["name"] = "Employee name is required";
	} else {
		if (!preg_match("/^[a-zA-Z ]*$/", $_POST["name"])) {
			$error["name"] = "only letters and space characters";
		}
	}
	if (empty(trim($_POST["phone"]))) {
		$error["phone"] = "Phone number is required";
	} elseif (!preg_match('/^\d{10}$/', $_POST["phone"])) {
		$error["phone"] = "Phone number must be 10 digits";
	}

	if ($_POST['id'] != '') {
		$id = $_POST['id'];
		$sql_user = "SELECT phone FROM `employees` WHERE phone='" . $_POST['phone'] . "' AND id!= " . $id;
	} else {
		$sql_user = "SELECT phone FROM `employees` WHERE phone='" . $_POST['phone'] . "'";
	}
	$result = mysqli_query($conn, $sql_user);
	if (mysqli_num_rows($result) > 0) {
		$error["phone"] = "Phone Number Already Exists";
	}

	if (empty(trim($_POST["password"]))) {
		$error["password"] = "Password is required";
	}
	if (empty(trim($_POST["branch_id"]))) {
		$error["branch_id"] = "Branch is required";
	}



	if (count($error) == 0) {
		$role = 2;
		$id = $_POST["id"];
		if ($id != '') {
			$data['id'] = $_POST["id"];
			$data['name'] = $_POST["name"];
			$data['phone'] = $_POST["phone"];
			$data['branch_id'] = $_POST["branch_id"];
			$data['updated_by'] = $_SESSION['USER_ID'];
			$data['updated_at'] = date('Y-m-d H:i:s');
			$employees_update_id = updateRecord('employees', $data, $id);
			$data1['user_name'] = $_POST["phone"];
			$password = $_POST["password"];
			$data1['password'] = md5($password);
			$user_update_id = updateRecord('employees', $data, $id);
			if ($user_update_id) {
				redirecte("employees.php", "Employee Updated successfully");
			}
		} else {
			$data['user_name'] = $_POST["phone"];
			$password = $_POST["password"];
			$data['password'] = md5($password);
			$data['role'] = 2;
			$user_id = addRecord('users', $data);
			$data1['id'] = $user_id;
			$data1['name'] = $_POST["name"];
			$data1['phone'] = $_POST["phone"];
			$data1['branch_id'] = $_POST["branch_id"];
			$data1['created_by'] = $_SESSION['USER_ID'];
			if ($user_id == TRUE) {
				$employe_id = addRecord('employees', $data1);
				if ($user_id  == TRUE) {
					$sql_select_employee = "SELECT * FROM `employees` WHERE `id` = '$user_id'";
					$result_employee = $conn->query($sql_select_employee);
					if ($result_employee) {
						$row_employee = $result_employee->fetch_assoc();
						$phone = $row_employee['phone'];
						$followup_msg = "Dear " . $row_employee['name'] . ", You have registered successfully. Your login credentials are: User ID: " . $row_employee['phone'] . " and Password: " . $password . "";
						sendWhatsAppMessage($phone, $followup_msg);
					}
					redirecte("employees.php", "Employee added successfully");
				}
			}
		}
	}
}
if (isset($_GET['id'])) {
	$sql = "SELECT e.*,b.name branch_name FROM  `employees` AS e 
	JOIN  branches AS b ON b.id=e.branch_id
	WHERE e.id=" . $_GET['id'];
	$row = getQueryData($sql);
	if (count($error) == 0) {
		$_POST = $row;
	}
}
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
							<a href="index.html">
								<i class="feather icon-home"></i>
							</a>
						</li>
						<li class="breadcrumb-item"><a href="employee.php">Add Employee</a></li>
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
				<!-- Page-body start -->
				<div class="page-body">
					<div class="row">
						<div class="col-md-3"></div>
						<div class="col-md-6">
							<div class="card">
								<div class="card-header">
									<h5>Add Employee</h5>
								</div>
								<div class="card-block">
									<form class="form-material" method="post">
										<div class="form-group form-default">
											<input type="text" name="name" class="form-control" value="<?php if (isset($_POST["name"])) echo $_POST["name"] ?>">
											<span style=color:red;><?php if (isset($error["name"])) echo $error["name"]; ?></span>
											<label class="float-label">Employee Name</label>
										</div>
										<div class="form-group form-default">
											<input type="text" name="phone" class="form-control" value="<?php if (isset($_POST["phone"])) echo $_POST["phone"] ?>">
											<span style=color:red;><?php if (isset($error["phone"])) echo $error["phone"]; ?></span>

											<label class="float-label">Phone No</label>
										</div>
										<div class="form-group form-default">
											<input type="password" name="password" class="form-control" value="<?php if (isset($_POST["password"])) echo $_POST["password"] ?>">
											<span style=color:red;><?php if (isset($error["password"])) echo $error["password"]; ?></span>
											<label class="float-label">Password</label>
										</div>
										<select class="form-control" id="branch_id" name="branch_id">
											<option value="" selected> Select Branch</option>
											<?php
											$sql_branch = "SELECT * FROM `branches`";
											$result_branch = mysqli_query($conn, $sql_branch);
											while ($row = mysqli_fetch_assoc($result_branch)) {
											?>
												<option value="<?php echo $row['id'] ?>" <?php if (isset($_POST['branch_id'])) if ($row['id'] == $_POST['branch_id']) echo "selected" ?>>
													<?php echo $row['name'] ?></option>
											<?php
											}
											?>
										</select>
										<span style="color:red;"><?php if (isset($error["branch_id"])) echo $error["branch_id"]; ?></span>
										<div class="form-group form-default">
											<input type="hidden" name="id" id="id" value="<?php if (isset($_POST["id"])) echo $_POST["id"] ?>">
											<button class="btn btn-danger" type="submit" value="Register" name="submit">Register</button>
										</div>
									</form>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- Page-body end -->
	</div>

	<?php include('includes/footer.php'); ?>>