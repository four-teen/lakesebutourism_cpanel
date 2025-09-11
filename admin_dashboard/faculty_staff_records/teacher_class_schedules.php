<?php
// admin_dashboard/faculty_staff_records/teacher_profile.php
session_start();
require_once __DIR__ . '/../../modules/auth/session_guard.php';
require_role(['Administrator']);

include_once __DIR__ . '/../../db.php';


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
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

  <!-- Template Main CSS -->
  <link href="../../assets/css/style.css" rel="stylesheet">

  <style>
@media print {
    /* Set A4 with 1-inch margins on all sides */
    @page { 
        size: A4 portrait; 
        margin: 1in; 
    }

    /* Neutral base */
    html, body { 
        margin: 0 !important; 
        padding: 0 !important; 
        background: #fff !important; 
    }

    /* Hide app chrome */
    header, #header, .header,
    aside, #sidebar, .sidebar,
    .pagetitle, nav, .breadcrumb,
    .no-print { 
        display: none !important; 
    }

    /* Neutralize layout containers so they don't push content */
    #main, .main, .section, .card, .card-body {
        position: static !important;
        margin: 0 !important;
        padding: 0 !important;
        border: 0 !important;
        box-shadow: none !important;
    }

    /* Show only the printable area */
    body * { 
        visibility: hidden; 
    }
    #print_area, #print_area * { 
        visibility: visible; 
    }

    /* Center printable area and remove extra padding/margin */
    #print_area {
        position: static !important; /* Change this from 'fixed' to 'static' */
        top: auto !important;
        left: auto !important;
        right: auto !important;
        width: 100% !important;
        margin: 0 !important;
        padding: 0 !important;
        border: 0 !important;
        box-shadow: none !important;
    }

    /* Remove default top gaps on first child */
    #print_area > *:first-child { 
        margin-top: 0 !important; 
        padding-top: 0 !important; 
    }

    /* Tables behave nicely across pages */
    thead { 
        display: table-header-group; 
    }
    tfoot { 
        display: table-footer-group; 
    }
    table { 
        width: 100%; 
        border-collapse: collapse; 
        page-break-inside: auto; 
    }
    tr { 
        page-break-inside: avoid; 
        page-break-after: auto; 
    }
}    
  
  </style>

</head>

<body onload="load_fac_workload();get_ay()">

<?php include 'header.php'; ?>

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
        <i class="bx bx-user text-primary"></i> <span>Teachers and Staff Records</span>

      </a>
      <ul id="teachers-nav" class="nav-content collapse show" data-bs-parent="#sidebar-nav">
        <li><a href="teacher_profile.php"><i class="bi bi-circle"></i><span>Faculty Profile</span></a></li>
        <li><a class="active" href="teacher_loads.php"><i class="bi bi-circle"></i><span>Teaching Loads & Schedules</span></a></li>
        <li><a href="#"><i class="bi bi-circle"></i><span>Performance Monitoring</span></a></li>
      </ul>
    </li>
  </ul>
</aside>
<!-- End Sidebar -->

<main id="main" class="main">
  <div class="pagetitle">
    <h1>Teahing Loads and Schedules</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="teacher_profile.php">Home</a></li>
        <li class="breadcrumb-item active">Teachers</li>
      </ol>
    </nav>
  </div>

<section class="section">

  <div class="card">
    <div class="card-body">

      <!-- Header / Toolbar (hides on print) -->
      <div class="py-3 no-print">
        <div class="d-flex flex-column flex-lg-row align-items-lg-center justify-content-between gap-3">

          <!-- Left: Selector -->
          <div class="w-100 w-lg-50">
            <label for="teacherid" class="form-label mb-1">SELECT TEACHER</label>
            <div class="d-flex gap-2">
              <select id="teacherid" class="js-example-basic-single form-control" name="state" onchange="load_fac_workload()">
                <?php 
                  $get_teacher = "SELECT * FROM `tblteachers` ORDER BY lastname ASC";
                  $rungetteacher = mysqli_query($conn, $get_teacher);
                  while($rowgetteacher = mysqli_fetch_assoc($rungetteacher)){
                    echo'<option value="'.$rowgetteacher['teachersautoid'].'">'.$rowgetteacher['lastname'].', '.$rowgetteacher['firstname'].' '.$rowgetteacher['middlename'].'</option>';
                  }
                ?>
              </select>
            </div>
            <small class="text-muted">Tip: Choose a teacher to load the latest workload details.</small>
          </div>

          <!-- Right: Actions -->
          <div class="w-100 w-lg-auto text-lg-end">
<div class="btn-group">
  <button type="button" class="btn btn-primary" onclick="print_content()">
    <i class='bx bx-printer'></i> Print Workload
  </button>
</div>
          </div>

        </div>
      </div>

      <hr class="no-print">

      <!-- Main printable content -->
      <div id="main_data" class="mt-3">
        <div class="print-area p-4">

          <!-- Loader centered nicely inside the frame -->
          <div id="loader" class="text-center py-5" style="display: none;">
            <img src="../../loader.gif" alt="Loading..." width="80">
            <div class="mt-2 text-muted">Fetching workload…</div>
          </div>

          <!-- Actual content -->
          <div id="content_area"></div>

        </div>
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




<!-- Vendor JS -->
<script src="../../assets/vendor/bootstrap/js/jquery-3.6.0.min.js"></script>
<script src="../../assets/vendor/bootstrap/js/bootstrap5.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="../../assets/sweetalert2.js"></script>

<script>

function print_content() {
    var teacherid = $('#teacherid').val();
    
    // Check if a teacher is selected
    if (!teacherid) {
        Swal.fire({
            icon: 'warning',
            title: 'No Teacher Selected',
            text: 'Please select a teacher first to view and print their workload.'
        });
        return;
    }

    // Use the existing function to load content
    load_fac_workload(function() {
        // This is a callback function that runs after the AJAX request is complete.
        // It ensures the content is fully loaded before we attempt to print.
        
        // Wait a little bit for the content to render fully.
        setTimeout(function() {
            window.print();
        }, 500); // Wait 0.5 seconds
    });
}
function load_fac_workload(callback = null) {
    $('#loader').show(); // Show the loader
    $('#content_area').hide(); // Hide the content while loading
    var teacherid = $('#teacherid').val();
    
    $.ajax({
        type: "POST",
        url: "query_teacher_loads.php",
        data: { 
            "loading_faculty_class_schedule": '1',
            "teacherid" : teacherid
        },
        success: function(response) {
            $('#content_area').html(response);
            
            if ($.fn.DataTable.isDataTable('#tblTeachers')) {
                $('#tblTeachers').DataTable().destroy();
            }
            $('#tblTeachers').DataTable({ pageLength: 10, lengthChange: false, order: [[2,'asc'],[3,'asc']] });
        },
        error: function(xhr, status, error) {
            $('#content_area').html('<p class="text-danger">Error loading data.</p>');
        },
        complete: function() {
            setTimeout(() => {
                $('#loader').hide(); // Hide the loader
                $('#content_area').show(); // Show the main content

                // Trigger the callback function if it exists
                if (typeof callback === 'function') {
                    callback();
                }
            }, 500); // Delay ensures a smooth transition
        }
    });
}

    function get_ay(){

       $.ajax({
          type: "POST",
          url: "../query_dasboard.php",
          data: {
            "loading_ay": "1"
          },
          success: function (response) {
              $('#curr_ay').html(response);
          }
        }); 
    }
 
    $(document).ready(function() {
        $('.js-example-basic-single').select2();
    });
</script>
</body>
</html>
