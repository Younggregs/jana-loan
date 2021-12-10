<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>TMC Admin Dashboard</title>
  <!-- plugins:css -->
  <link rel="stylesheet" href="../../vendors/ti-icons/css/themify-icons.css">
  <link rel="stylesheet" href="../../vendors/base/vendor.bundle.base.css">
  <!-- endinject -->
  <!-- plugin css for this page -->
  <!-- End plugin css for this page -->
  <!-- inject:css -->
  <link rel="stylesheet" href="../../css/style.css">
  <!-- endinject -->
  <link rel="shortcut icon" href="../../images/favicon.png" />
</head>

<body>
  <div class="container-scroller">
    <!-- partial:../../partials/_navbar.html -->
    <nav class="navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
      <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-center">
        <a class="navbar-brand brand-logo mr-5" href="../../dashboard.php"><img src="../../images/tmc-1.png" class="mr-2" alt="logo"/></a>
        <a class="navbar-brand brand-logo-mini" href="../../dashboard.php"><img src="../../images/tmc.png" alt="logo"/></a>
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
      <!-- partial:../../partials/_sidebar.html -->
      <nav class="sidebar sidebar-offcanvas" id="sidebar">
        <ul class="nav">
          <li class="nav-item">
            <a class="nav-link" href="../../dashboard.php">
              <i class="ti-shield menu-icon"></i>
              <span class="menu-title">Dashboard</span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="new_customer.php">
              <i class="ti-save menu-icon"></i>
              <span class="menu-title">Add Customer</span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="new_application.php">
              <i class="ti-plus menu-icon"></i>
              <span class="menu-title">Add Application</span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="customers.php">
              <i class="ti-user menu-icon"></i>
              <span class="menu-title">Customers</span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="pending.php">
              <i class="ti-view-list-alt menu-icon"></i>
              <span class="menu-title">Pending Loans</span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="paid_out.php">
              <i class="ti-money menu-icon"></i>
              <span class="menu-title">Paid-out Loans</span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="paid_back.php">
              <i class="ti-star menu-icon"></i>
              <span class="menu-title">Paid-back loans</span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="overdue.php">
              <i class="ti-eye menu-icon"></i>
              <span class="menu-title">Overdue Loans</span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="../../index.php">
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
                  <h4 class="font-weight-bold mb-0">TMC Add New Application</h4>
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">Application</h4>
                  <p class="card-description">
                    Add new application
                  </p>
                  <form class="forms-sample" method="POST" action="">

                  <?php

// import database connection variables
require_once __DIR__ . '../../../connection/db_config.php';

// Connecting to remote mysql database
$con = mysqli_connect(DB_SERVER, DB_USER, DB_PASSWORD, DB_DATABASE) or die("Unable to Connect");

$query = "SELECT * FROM customer";
$result = mysqli_query($con, $query);

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    
    $customer = $_POST['customer'];
    $loanAmount = $_POST["loan_amount"];
    $repaymentAmount = $_POST["repayment_amount"];
    $interest = $_POST["interest"];
    $repaymentDate = $_POST["repayment_date"];

    $response = array();

    if($customer == '' || $loanAmount == '' || $repaymentAmount == '' || $repaymentDate == '' || $interest == ''){
       ?> <p style="color: red;"> <?php echo 'All required fields must be filled'; ?> </p> <?php
    }else{
        
      $query = "INSERT INTO application (customer, loanAmount, repaymentAmount, repaymentDate, interest) 
      VALUES ('$customer', '$loanAmount', '$repaymentAmount', '$repaymentDate', '$interest')";
      if(mysqli_query($con, $query)){
        ?> <p style="color: green; fontWeight: bold;"> <?php echo 'Application recorded successfully'; ?> </p> <?php
      }else{
          ?> <p style="color: red;"> <?php echo 'Sorry something broke, refresh and try again'; ?> </p> <?php
      }
        
      mysqli_close($con);
    }
}
?>

                    <div class="form-group">
                      <label for="customer">Select Customer</label>
                      <select class="form-control" name="customer">
                        <?php 
                        if (mysqli_num_rows($result) > 0) {
                          while ($row = mysqli_fetch_array($result)) {
                            ?><option><?php echo $row["surname"].' '. $row["otherName"]. ' - '.$row["email"]; ?></option>
                        <?php
                          }
                        }
                        ?>
                      </select>
                    </div>
                    <div class="form-group">
                      <label for="loan_amount">Loan amount</label>
                      <input type="number" class="form-control" name="loan_amount" placeholder="Loan amount">
                    </div>
                    <div class="form-group">
                      <label for="interest">Interest %</label>
                      <input type="number" class="form-control" name="interest" placeholder="Repayment date">
                    </div>
                    <div class="form-group">
                      <label for="repayment_amount">Repayment amount</label>
                      <input type="number" class="form-control" name="repayment_amount" placeholder="Repayment amount">
                    </div>
                    <div class="form-group">
                      <label for="repayment_date">Repayment date</label>
                      <input type="date" min="<?= date('Y-m-d'); ?>" class="form-control" name="repayment_date" placeholder="Repayment date">
                    </div>
                    
                    <button type="submit" class="btn btn-primary mr-2">Submit</button>
                  </form>
                </div>
              </div>
            </div>
</div>
        </div>
        <!-- partial:../../partials/_footer.html -->
        <footer class="footer">
          <div class="d-sm-flex justify-content-center justify-content-sm-between">
            <span class="text-muted d-block text-center text-sm-left d-sm-inline-block">Copyright © Janada Kefas 2021</span> 
          </div>
        </footer>
        <!-- partial -->
      </div>
    </div>
  </div>
  <!-- plugins:js -->
  <script src="../../vendors/base/vendor.bundle.base.js"></script>
  <!-- endinject -->
  <!-- Plugin js for this page-->
  <!-- End plugin js for this page-->
  <!-- inject:js -->
  <script src="../../js/off-canvas.js"></script>
  <script src="../../js/hoverable-collapse.js"></script>
  <script src="../../js/template.js"></script>
  <script src="../../js/todolist.js"></script>
  <!-- endinject -->
  <!-- Custom js for this page-->
  <!-- End custom js for this page-->
</body>

</html>