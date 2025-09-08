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

<body onload="load_account();get_ay()">

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
    &copy; Copyright <strong><span>BTESLife</span></strong>. All Rights Reserved
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
    <label>Faculty (for Teacher Accounts)</label>
    <select class="form-control" id="teacher_id">
      <option value="">-- Select Faculty --</option>
      <?php
        $fac = mysqli_query($conn,"SELECT teachersautoid, firstname, middlename, lastname FROM tblteachers ORDER BY lastname ASC");
        while($r = mysqli_fetch_assoc($fac)){
          echo '<option value="'.$r['teachersautoid'].'">'.strtoupper($r['lastname'].", ".$r['firstname']." ".$r['middlename']).'</option>';
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
              <option value="2">Teacher</option>
              <option value="3">Staff</option>
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

$('#btnAdd').click(function(){
  $('#modal_account').modal('show');
  $('#acc_id').val('');
  $('#teacher_id').val('');
  $('#acc_username').val('');
  $('#acc_password').val('');
  $('#acc_email').val('');
  $('#acc_type_id').val('2');
  $('#acc_status').val('Active');
});


  // Save (Insert or Update)
function save_account(){
  var acc_id       = $('#acc_id').val();
  var teacher_id   = $('#teacher_id').val();
  var acc_username = $('#acc_username').val();
  var acc_password = $('#acc_password').val();
  var acc_email    = $('#acc_email').val();
  var acc_type_id  = $('#acc_type_id').val();
  var acc_status   = $('#acc_status').val();

  $.ajax({
    type: "POST",
    url: "query_account.php",
    data: {
      "save_account": "1",
      "acc_id": acc_id,
      "teacher_id": teacher_id,
      "acc_username": acc_username,
      "acc_password": acc_password,
      "acc_email": acc_email,
      "acc_type_id": acc_type_id,
      "acc_status": acc_status
    },
    success: function(){
      $('#modal_account').modal('hide');
      load_account();
    }
  });
}

  // Edit
function edit_account(acc_id){
  $.ajax({
    type: "POST",
    url: "query_account.php",
    data: { "get_account":"1", "acc_id":acc_id },
    success: function(response){
      var data = JSON.parse(response);
      $('#acc_id').val(data.acc_id);
      $('#teacher_id').val(data.teacher_id ? data.teacher_id : '');
      $('#acc_username').val(data.acc_username || '');
      $('#acc_password').val('');
      $('#acc_email').val(data.acc_email || '');
      $('#acc_type_id').val(data.acc_type_id || '2');
      $('#acc_status').val(data.acc_status || 'Active');
      $('#modal_account').modal('show');
    }
  });
}

  // Delete
  function delete_account(acc_id){
    Swal.fire({
      title: "Are you sure?",
      text: "This account will be deleted.",
      icon: "warning",
      showCancelButton: true,
      confirmButtonColor: "#3085d6",
      cancelButtonColor: "#d33",
      confirmButtonText: "Yes, delete it!"
    }).then((result)=>{
      if(result.isConfirmed){
        $.ajax({
          type: "POST",
          url: "query_account.php",
          data: { "delete_account":"1", "acc_id":acc_id },
          success: function(){
            load_account();
          }
        });
      }
    });
  }

  // Show modal
  $('#btnAdd').click(function(){
    $('#modal_account').modal('show');
    $('#acc_id').val('');
    $('#acc_username,#acc_password,#acc_fullname,#acc_email').val('');
    $('#acc_type_id').val('2');
    $('#acc_status').val('Active');
  });


    function load_account() {
        $('#loader').show(); // Show the loader
        $('#content_area').hide(); // Hide the content while loading
        
        $.ajax({
            type: "POST",
            url: "query_account.php",
            data: { 
            "loading_account": '1'
          },
          success: function(response) {
            $('#content_area').html(response);
            // initialize here if needed
            if ($.fn.DataTable.isDataTable('#tblAccounts')) {
              $('#tblAccounts').DataTable().destroy();
            }
            $('#tblAccounts').DataTable({ pageLength: 10, lengthChange: false, order: [[2,'asc'],[3,'asc']] });
          },
            error: function(xhr, status, error) {
                $('#content_area').html('<p class="text-danger">Error loading data.</p>');
            },
            complete: function() {
                setTimeout(() => {
                    $('#loader').hide(); // Hide the loader
                    $('#content_area').show(); // Show the main content
                }, 500); // Delay ensures a smooth transition
            }
        });
    }

 
</script>
</body>
</html>
