<?php
// admin_dashboard/faculty_staff_records/teacher_profile.php
session_start();
require_once __DIR__ . '/../../modules/auth/session_guard.php';
require_role(['Administrator']);

include_once __DIR__ . '/../../db.php';

// get AY (keep your logic)
$settings = "SELECT * FROM `tblsettings`
  INNER JOIN tblacademic_years on tblacademic_years.ayid=tblsettings.ayid LIMIT 1";
$runsettings = mysqli_query($conn, $settings);
$rowsettings = mysqli_fetch_assoc($runsettings);
$_SESSION['ays']  = $rowsettings['ayfrom'].'-'.$rowsettings['ayfrom'];
$_SESSION['ayid'] = $rowsettings['ayid'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Teachers Profile</title>

  <!-- Favicons -->
  <link href="../../assets/img/logo.png" rel="icon">
  <link href="../../assets/img/logo.png" rel="apple-touch-icon">

  <!-- Poppins -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">

  <!-- Vendor CSS -->
  <link href="../../assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="../../assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="../../assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
  <link href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap5.min.css" rel="stylesheet">

  <!-- Template Main CSS -->
  <link href="../../assets/css/style.css" rel="stylesheet">

  <style>
    *{ font-family:'Poppins',system-ui,-apple-system,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif; }
    .avatar{ width:46px;height:46px;border-radius:50%;object-fit:cover;border:1px solid #e7e7e7;background:#f7f7f7; }
    .avatar-lg{ width:120px;height:120px;border-radius:12px;object-fit:cover;border:1px solid #e7e7e7;background:#f7f7f7; }
    .table thead th{ font-weight:700; }
    .btn-icon{ padding:.35rem .55rem; }
  </style>
</head>

<body onload="loading_owners()">

<?php include '../header.php'; ?>

<!-- ======= Sidebar (short) ======= -->
<aside id="sidebar" class="sidebar">
  <ul class="sidebar-nav" id="sidebar-nav">
    <li class="nav-item">
      <a class="nav-link" href="../dashboard_admin.php">
        <i class="bi bi-grid"></i> <span>Dashboard</span>
      </a>
    </li>

    <li class="nav-item">
      <a class="nav-link collapsed" data-bs-toggle="collapse" href="#teachers-nav" role="button" aria-expanded="true" aria-controls="teachers-nav">
        <i class="bx bx-user text-primary"></i> <span>User Accounts</span>
      </a>
      <ul id="teachers-nav" class="nav-content collapse show" data-bs-parent="#sidebar-nav">
        <li><a class="active" href="#"><i class="bi bi-circle"></i><span>Account</span></a></li>
      </ul>
    </li>
  </ul>
</aside>
<!-- End Sidebar -->

<main id="main" class="main">
  <div class="pagetitle">
    <h1>Manage User Account</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="../dashboard_admin.php">Home</a></li>
        <li class="breadcrumb-item active">Teachers</li>
      </ol>
    </nav>
  </div>

    <section class="section">
      <div class="card">
        <div class="card-body">
          <div class="d-flex flex-wrap justify-content-between align-items-center pt-3 pb-2">
            <div>
              <h5 class="card-title mb-0">Account List</h5>
             
            </div>
            <div>
              <button class="btn btn-success" id="btnAdd">
                <i class="bx bx-plus-circle me-1"></i> Add New Account
              </button>
            </div>
          </div>
            <div id="main_data">
              <div id="loader" class="text-center" style="display: none;">
                <img src="../../loader.gif" alt="Loading..." width="10%">
              </div>
              <div id="content_area"></div>
            </div>
        </div>
      </div>
    </section>

</main>

<!-- Footer -->
<footer id="footer" class="footer">
  <div class="copyright">
    &copy; Copyright <strong><span><?php echo $_SESSION['footer'] ?></span></strong>. All Rights Reserved
  </div>
  <div class="credits">Powered by <a href="#">eoa * mgli</a></div>
</footer>

<a href="#" class="back-to-top d-flex align-items-center justify-content-center">
  <i class="bi bi-arrow-up-short"></i>
</a>

<!-- Add/Edit Modal -->
<div class="modal fade" id="modal_account" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title">Manage Account</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <input type="hidden" id="acc_id">

<div class="row mb-2">
  <div class="col-md-12">
    <label>Users (for Owners Accounts)</label>
<select class="form-control" id="owner_id">
  <option value="">-- Select Establishments --</option>
  <?php
    $fac = mysqli_query($conn,"SELECT * FROM `tblowners` order by establishments ASC");
    while($r = mysqli_fetch_assoc($fac)){
      echo '<option value="'.$r['usersautoid'].'">'.strtoupper($r['establishments']).'</option>';
    }
  ?>
</select>
  </div>
</div>

        <div class="row mb-2">
          <div class="col-md-6">
            <label>Username</label>
            <input type="text" class="form-control" id="acc_username">
          </div>
          <div class="col-md-6">
            <label>Password</label>
            <input type="password" class="form-control" id="acc_password">
          </div>
        </div>
        <div class="row mb-2">
          <div class="col-md-12">
            <label>Email</label>
            <input type="email" class="form-control" id="acc_email">
          </div>
        </div>
        <div class="row mb-2">
          <div class="col-md-6">
            <label>Account Type</label>
            <select class="form-control" id="acc_type_id">
              <option value="1">Administrator</option>
              <option value="2">Establishment Owner</option>
            </select>
          </div>
          <div class="col-md-6">
            <label>Status</label>
            <select class="form-control" id="acc_status">
              <option value="Active">Active</option>
              <option value="Inactive">Inactive</option>
            </select>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button class="btn btn-primary" onclick="save_account()">Save</button>
      </div>
    </div>
  </div>
</div>

<!-- Vendor JS -->
<script src="../../assets/vendor/bootstrap/js/jquery-3.6.0.min.js"></script>
<script src="../../assets/vendor/bootstrap/js/bootstrap5.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap5.min.js"></script>
<script src="../../assets/sweetalert2.js"></script>

<script>

function loading_owners() {
    $('#loader').show(); 
    $('#content_area').hide();
    
    $.ajax({
        type: "POST",
        url: "query_owners.php",
        data: { "loading_owner": '1' },
        success: function(response) {
            // Inject the table HTML into the content area
            $('#content_area').html(response);
            
            // Now, initialize the DataTable
            // You can simplify this since we know it's a fresh table
            $('#tblAccounts').DataTable({ 
                pageLength: 10, 
                lengthChange: false, 
                // Only use these if the columns are in the correct order
                // order: [[2,'asc'],[3,'asc']] 
            });
        },
        error: function(xhr, status, error) {
            // Display an error message if the AJAX call fails
            $('#content_area').html('<p class="text-danger">Error loading data.</p>');
        },
        complete: function() {
            // This runs whether the request succeeded or failed
            setTimeout(() => {
                $('#loader').hide();
                $('#content_area').show();
            }, 500); 
        }
    });
}

 
</script>
</body>
</html>
