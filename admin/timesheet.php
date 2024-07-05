<?php 
include('includes/header.php'); ?>
<?php include('includes/sidebar.php'); ?> 
                    <!-- [ navigation menu ] end -->
                    <div class="pcoded-content">
                        <!-- [ breadcrumb ] start -->
                        <div class="page-header">
                            <div class="page-block">
                                <div class="row align-items-center">
                                    <div class="col-md-12">
                                        <div class="page-header-title">
                                            <h5 class="m-b-10">Time Sheet</h5>
                                        </div>
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
                                            <div class="col-xl-12 col-md-6">
                                                <div class="card">
                                                    <div class="card-header bg-info">
                                                        <h5>Time Sheet</h5>                                 
                                                    </div>
                                                    <div class="card-block">
                                                       <div class="row">
														<div class="col-md-4 mb-2">
														   <label>Employee</label>
															<div>
																<select name="" id="" class="form-control">
																<option value="">Employee Name 1</option>
																	<option value="">Employee Name 1</option>
																</select>
														   </div>
														</div>							   
														 <div class="col-md-3 mb-2">
														   <label>Start Date</label>
															<div>
																<input type="date" class="form-control">
														   </div>
														</div>				   
														   <div class="col-md-3 mb-2">
														   <label>End Date</label>
															<div>
																<input type="date"  class="form-control">
														   </div>
														</div>			   
														<div class="col-md-2  mb-2">
															<label>Show Overtime</label>
														   <div class="form-radio">
                                                                <div class="radio radiofill radio-primary radio-inline">
                                                                    <label>
                                                                        <input type="radio" name="radio" checked="checked">
                                                                        <i class="helper"></i>Yes
                                                                    </label>
                                                                </div>
                                                                <div class="radio radiofill radio-primary radio-inline">
                                                                    <label>
                                                                        <input type="radio" name="radio" >
                                                                        <i class="helper"></i>No
                                                                    </label>
                                                                </div>
                                                        </div>
														   </div>   		   
														   <div class="offset-8 col-md-2  mb-2">
															   <a href="#" class="btn btn-success">View Timesheet</a> 
														   </div>
														   <div class="col-md-2  mb-2">
															   <a href="reports.php" class="btn btn-danger">Print Timesheet</a> 
														   </div>	   
                                                    </div>
                                                </div>
                                            </div>
                                        </div>	
											<div class="col-xl-12 col-md-6">
                                                <div class="card">          
                                                    <div class="card-block">
														<h5>Employee Name</h5>
                                                      <div class="table-responsive">
                                                    <table class="table table-bordered">
                                                        <thead>
                                                            <tr class="bg-info">
                                                                <th>Clock In</th>
                                                                <th>Clock Out</th>
                                                                <th>Reg Hours</th>
                                                                <th>PTO Hours</th>
																<th>Pay Code</th>
																<th>Job Code</th>
																<th>Task</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <th scope="row">13-02-2024 7:40:50 AM</th>
                                                                <td>13-02-2024 8:40:50 AM</td>
                                                                <td>0.00</td>
                                                                <td></td>
																<td></td>
																<td>Bank</td>
																<td></td>
                                                            </tr>
                                                           <tr>
                                                                <th scope="row">13-02-2024 7:40:50 AM</th>
                                                                <td>13-02-2024 8:40:50 AM</td>
                                                                <td>0.00</td>
                                                                <td></td>
																<td></td>
																<td>Bank</td>
																<td></td>
                                                            </tr>
                                                           <tr>
                                                                <th scope="row">13-02-2024 7:40:50 AM</th>
                                                                <td>13-02-2024 8:40:50 AM</td>
                                                                <td>0.00</td>
                                                                <td></td>
																<td></td>
																<td>Bank</td>
																<td></td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
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
    <?php include('includes/footer.php'); ?>
