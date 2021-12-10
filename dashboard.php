
<?php

// import database connection variables
require_once __DIR__ . '/connection/db_config.php';

// Connecting to remote mysql database
$con = mysqli_connect(DB_SERVER, DB_USER, DB_PASSWORD, DB_DATABASE) or die("Unable to Connect");

$customers_query = "SELECT email FROM customer";
$customers_result = mysqli_query($con, $customers_query);

$pending_query = "SELECT pending FROM application WHERE pending = 1";
$pending_result = mysqli_query($con, $pending_query);

$paid_out_query = "SELECT paid_out, loanAmount FROM application WHERE paid_out = 1";
$paid_out_result = mysqli_query($con, $paid_out_query);

$paid_out_sum = 0;
if (mysqli_num_rows($paid_out_result) > 0) {
  while ($row = mysqli_fetch_array($paid_out_result)) {
    $paid_out_sum += $row["loanAmount"];
  }
}


$paid_back_query = "SELECT paid_back, loanAmount FROM application WHERE paid_back = 1";
$paid_back_result = mysqli_query($con, $paid_back_query);

$paid_back_sum = 0;
if (mysqli_num_rows($paid_back_result) > 0) {
  while ($row = mysqli_fetch_array($paid_back_result)) {
    $paid_back_sum += $row["loanAmount"];
  }
}

$date = date('Y-m-d');
$overdue_query = "SELECT paid_out, loanAmount FROM application WHERE repaymentDate <= '$date' AND paid_back = 0";
$overdue_result = mysqli_query($con, $overdue_query);

$overdue_sum = 0;
if (mysqli_num_rows($overdue_result) > 0) {
  while ($row = mysqli_fetch_array($overdue_result)) {
    $overdue_sum += $row["loanAmount"];
  }
}

$query = "SELECT * FROM application LIMIT 7";
$result = mysqli_query($con, $query);
mysqli_close($con);

function evaluateStatus($row) {
  $status = 0;
  if($row["pending"] == 1){}
  if($row["paid_out"] == 1){
    $status = 1;
  }
  if($row["paid_out"] == 1 && $row["paid_back"] == 1){
    $status = 2;
  }

  return $status;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>TMC Admin Dashboard</title>
  <!-- plugins:css -->
  <link rel="stylesheet" href="vendors/ti-icons/css/themify-icons.css">
  <link rel="stylesheet" href="vendors/base/vendor.bundle.base.css">
  <!-- endinject -->
  <!-- plugin css for this page -->
  <!-- End plugin css for this page -->
  <!-- inject:css -->
  <link rel="stylesheet" href="css/style.css">
  <!-- endinject -->
  <link rel="shortcut icon" href="images/favicon.png" />
</head>
<body>
  <div class="container-scroller">
    <!-- partial:partials/_navbar.html -->
    <nav class="navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
      <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-center">
        <a class="navbar-brand brand-logo mr-5" href="dashboard.php"><img src="images/tmc-1.png" class="mr-2" alt="logo"/></a>
        <a class="navbar-brand brand-logo-mini" href="dashboard.php"><img src="images/tmc.png" alt="logo"/></a>
      </div>
      <div class="navbar-menu-wrapper d-flex align-items-center justify-content-end">
        <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
          <span class="ti-view-list"></span>
        </button>
        <ul class="navbar-nav mr-lg-2">
          <li class="nav-item nav-search d-none d-lg-block">
            <h4 style="color: #000;">TRANSCONTINENTAL MULTIPURPOSE COOPERATIVE SOCIETY</h2>
          </li>
        </ul>
        <ul class="navbar-nav navbar-nav-right"></ul>
        <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="offcanvas">
          <span class="ti-view-list"></span>
        </button>
      </div>
    </nav>
    <!-- partial -->
    <div class="container-fluid page-body-wrapper">
      <!-- partial:partials/_sidebar.html -->
      <nav class="sidebar sidebar-offcanvas" id="sidebar">
        <ul class="nav">
          <li class="nav-item">
            <a class="nav-link" href="dashboard.php">
              <i class="ti-shield menu-icon"></i>
              <span class="menu-title">Dashboard</span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="pages/forms/new_customer.php">
              <i class="ti-save menu-icon"></i>
              <span class="menu-title">Add Customer</span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="pages/forms/new_application.php">
              <i class="ti-plus menu-icon"></i>
              <span class="menu-title">Add Application</span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="pages/forms/customers.php">
              <i class="ti-user menu-icon"></i>
              <span class="menu-title">Customers</span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="pages/forms/pending.php">
              <i class="ti-view-list-alt menu-icon"></i>
              <span class="menu-title">Pending Loans</span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="pages/forms/paid_out.php">
              <i class="ti-money menu-icon"></i>
              <span class="menu-title">Paid-out Loans</span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="pages/forms/paid_back.php">
              <i class="ti-star menu-icon"></i>
              <span class="menu-title">Paid-back loans</span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="pages/forms/overdue.php">
              <i class="ti-eye menu-icon"></i>
              <span class="menu-title">Overdue Loans</span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="index.php">
              <i class="ti-close menu-icon"></i>
              <span class="menu-title">Logout</span>
            </a>
          </li>
        </ul>
      </nav>
      <!-- partial -->
      <div class="main-panel">
        <div class="content-wrapper">
          <div class="row">
            <div class="col-md-12 grid-margin">
              <div class="d-flex justify-content-between align-items-center">
                <div>
                  <h4 class="font-weight-bold mb-0">TMC Overview</h4>
                </div>
                <div>
                    <button type="button" class="btn btn-primary btn-icon-text btn-rounded">
                      <a style="color: #fff;text-decoration: none;" href="pages/forms/new_customer.php">
                        <i class="ti-clipboard btn-icon-prepend"></i>+ Add Customer
                      </a>
                    </button>
                </div>
                <div>
                    <button type="button" class="btn btn-primary btn-icon-text btn-rounded">
                    <a style="color: #fff;text-decoration: none;" href="pages/forms/new_application.php">  
                      <i class="ti-clipboard btn-icon-prepend"></i>+ New Loan Application
                    </a>
                    </button>
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            
            <div class="col-md-3 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <p class="card-title text-md-center text-xl-left">Customers</p>
                  <div class="d-flex flex-wrap justify-content-between justify-content-md-center justify-content-xl-between align-items-center">
                    <h3 class="mb-0 mb-md-2 mb-xl-0 order-md-1 order-xl-0"><?php echo mysqli_num_rows($customers_result); ?></h3>
                    <i class="ti-user icon-md text-muted mb-0 mb-md-3 mb-xl-0"></i>
                  </div> 
                </div>
              </div>
            </div>
            
            <div class="col-md-3 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <p class="card-title text-md-center text-xl-left">Paid Out Loans</p>
                  <div class="d-flex flex-wrap justify-content-between justify-content-md-center justify-content-xl-between align-items-center">
                    <h3 class="mb-0 mb-md-2 mb-xl-0 order-md-1 order-xl-0">₦<?php echo $paid_out_sum; ?></h3>
                    <i class="ti-agenda icon-md text-muted mb-0 mb-md-3 mb-xl-0"></i>
                  </div>  
                </div>
              </div>
            </div>
            <div class="col-md-3 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <p class="card-title text-md-center text-xl-left">Paid Back Loans</p>
                  <div class="d-flex flex-wrap justify-content-between justify-content-md-center justify-content-xl-between align-items-center">
                    <h3 class="mb-0 mb-md-2 mb-xl-0 order-md-1 order-xl-0">₦<?php echo $paid_back_sum; ?></h3>
                    <i class="ti-layers-alt icon-md text-muted mb-0 mb-md-3 mb-xl-0"></i>
                  </div> 
                </div>
              </div>
            </div>
            <div class="col-md-3 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <p class="card-title text-md-center text-xl-left">Overdue loans</p>
                  <div class="d-flex flex-wrap justify-content-between justify-content-md-center justify-content-xl-between align-items-center">
                    <h3 class="mb-0 mb-md-2 mb-xl-0 order-md-1 order-xl-0">₦<?php echo $overdue_sum; ?></h3>
                    <i class="ti-calendar icon-md text-muted mb-0 mb-md-3 mb-xl-0"></i>
                  </div> 
                 </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6 grid-margin stretch-card">
              <div class="card border-bottom-0">
                <div class="card-body pb-0">
                  <p class="card-title">Report</p>
                  <p class="text-muted font-weight-light">Report of Transcontinental Multipurpose Cooperative in numbers of customers, paid out, paid back and overdue loans.</p>
                  <div class="d-flex flex-wrap mb-5">
                    <div class="mr-5 mt-3">
                      <p class="text-muted">Pending</p>
                      <h3><?php echo mysqli_num_rows($pending_result); ?></h3>
                    </div>
                    <div class="mr-5 mt-3">
                      <p class="text-muted">Paid Out</p>
                      <h3><?php echo mysqli_num_rows($paid_out_result); ?></h3>
                    </div>
                    <div class="mr-5 mt-3">
                      <p class="text-muted">Paid Back</p>
                      <h3><?php echo mysqli_num_rows($paid_back_result); ?></h3>
                    </div>
                    <div class="mt-3">
                      <p class="text-muted">Overdue</p>
                      <h3><?php echo mysqli_num_rows($overdue_result); ?></h3>
                    </div> 
                  </div>
                </div>
                <canvas id="order-chart" class="w-100"></canvas>
              </div>
            </div>
            <div class="col-md-6 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <p class="card-title mb-0">Recents</p>
                  <div class="table-responsive">
                    <table class="table table-hover">
                      <thead>
                        <tr>
                          <th>Customer</th>
                          <th>Repayment Date</th>
                          <th>Amount</th>
                          <th>Status</th>
                        </tr>
                      </thead>
                      <tbody>
                         <?php 
                          $count = 1;
                          if (mysqli_num_rows($result) > 0) {
                            while ($row = mysqli_fetch_array($result)) {
                          ?> 
                            <tr>
                              <td><?php echo $row["customer"]; ?></td>
                              <td><?php echo $row["repaymentDate"]; ?></td>
                              
                                <?php 
                                  $status = evaluateStatus($row);
                                  switch ($status) {
                                    case 0:
                                      ?> 
                                      <td class="text-primary"> ₦<?php echo $row["loanAmount"]; ?> <i class="ti-arrow-down"></i></td>
                                      <td><label class="badge badge-info">Pending </label> </td> 
                                      <?php
                                      break;

                                    case 1:
                                      ?> 
                                      <td class="text-warning"> ₦<?php echo $row["loanAmount"]; ?> <i class="ti-arrow-up"></i></td>
                                      <td><label class="badge badge-warning">Paid out </label> </td> 
                                      <?php
                                      break;

                                    case 2:
                                      ?> 
                                      <td class="text-success"> ₦<?php echo $row["loanAmount"]; ?> <i class="ti-arrow-down"></i></td>
                                      <td><label class="badge badge-success">Pack back </label> </td> 
                                      <?php
                                      break;

                                    case 3:
                                      ?> 
                                      <td class="text-danger"> ₦<?php echo $row["loanAmount"]; ?> <i class="ti-arrow-down"></i></td>
                                      <td><label class="badge badge-danger">Overdue </label> </td> 
                                      <?php
                                      break;
                                    
                                    default:
                                      # code...
                                      break;
                                  }
                                ?>
                              
                            </tr>
                          <?php }} ?>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- content-wrapper ends -->
        <!-- partial:partials/_footer.html -->
        <footer class="footer">
          <div class="d-sm-flex justify-content-center justify-content-sm-between">
            <span class="text-muted d-block text-center text-sm-left d-sm-inline-block">Copyright © Janada Kefas 2021</span> 
          </div>
        </footer>
        <!-- partial -->
      </div>
      <!-- main-panel ends -->
    </div>
    <!-- page-body-wrapper ends -->
  </div>
  <!-- container-scroller -->

  <!-- plugins:js -->
  <script src="vendors/base/vendor.bundle.base.js"></script>
  <!-- endinject -->
  <!-- Plugin js for this page-->
  <script src="vendors/chart.js/Chart.min.js"></script>
  <!-- End plugin js for this page-->
  <!-- inject:js -->
  <script src="js/off-canvas.js"></script>
  <script src="js/hoverable-collapse.js"></script>
  <script src="js/template.js"></script>
  <script src="js/todolist.js"></script>
  <!-- endinject -->
  <!-- Custom js for this page-->
  <script src="js/dashboard.js"></script>
  <!-- End custom js for this page-->
</body>

</html>

