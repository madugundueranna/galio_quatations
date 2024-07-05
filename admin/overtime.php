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
                                            <h5 class="m-b-10">Over Time</h5>
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
                                            <div class="offset-2 col-xl-8 col-md-6">
                                                <div class="card">
                                                    <div class="card-header bg-info">
                                                        <h5>Over Time</h5>                                                     
                                                    </div>
                                                    <div class="card-block">
                                                       <div class="row">
														<div class="col-md-3 mb-2">
														   Calculate over time by 
														   </div>
														<div class="col-md-9  mb-2">
														   <div class="form-radio">
                                                                <div class="radio radiofill radio-primary radio-inline">
                                                                    <label>
                                                                        <input type="radio" name="radio" checked="checked">
                                                                        <i class="helper"></i>Week
                                                                    </label>
                                                                </div>
                                                                <div class="radio radiofill radio-primary radio-inline">
                                                                    <label>
                                                                        <input type="radio" name="radio" >
                                                                        <i class="helper"></i>Day
                                                                    </label>
                                                                </div>
                                                        </div>
														   </div>														   
														   <div class="col-md-3  mb-2">
														  Regular Hours per cycle
														   </div>
														<div class="col-md-2  mb-2 ">
														   <input type="text" class="form-control">
														   </div>													   
														   <div class="col-md-9 mt-2 offset-3">
															   <a href="timesheet.php" class="btn btn-primary">Submit</a></div>													
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
    <?php include('includes/footer.php'); ?>
