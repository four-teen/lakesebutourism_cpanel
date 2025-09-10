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
        * { font-family:'Poppins',system-ui,-apple-system,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif; }
        .avatar { width:46px;height:46px;border-radius:50%;object-fit:cover;border:1px solid #e7e7e7;background:#f7f7f7; }
        .avatar-lg { width:120px;height:120px;border-radius:12px;object-fit:cover;border:1px solid #e7e7e7;background:#f7f7f7; }
        .table thead th { font-weight:700; }
        .btn-icon { padding:.35rem .55rem; }

        .select2-container--default .select2-selection--single {
            height: 45px;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 45px; /* Apply line-height specifically to the text element */
        }

        /* --- Updated CSS for transparency --- */
        #header,
        .header,
        .header-content,
        .card-body {
            background-color: transparent !important;
            box-shadow: none !important;
            border: none !important;
        }

        .container {
            background-color: transparent !important;
            box-shadow: none !important;
            box-sizing: border-box;
            font-size: 11pt;
            width: 8.5in;
            padding: 0.5in;
        }

        .main {
            background-color: #f0f0f0; /* Set a background color for the body/main content */
        }
        /* --- End of Updated CSS --- */

.header {
    display: flex;
    justify-content: space-between;
    align-items: center; /* This will vertically center all items */
    margin-bottom: 20px;
    background-color: transparent !important;
    padding: 10px 0; /* Add top and bottom padding for vertical spacing */
}

.header .logo {
    width: 100px; /* Adjust size as needed */
    height: auto;
    background-color: transparent !important;
}

.header .school-info {
    text-align: center;
    flex-grow: 1; /* Allows it to take all available space */
    margin: 0 20px; /* Add horizontal margin for spacing */
}
        .header .school-info .republic {
            font-size: 10pt;
            margin-bottom: 2px;
        }
        .header .school-info .ministry {
            font-weight: bold;
            font-size: 10.5pt;
            margin-bottom: 2px;
        }
        .header .school-info .division,
        .header .school-info .region,
        .header .school-info .address {
            font-size: 9.5pt;
            line-height: 1.3;
        }
        .title-section {
            text-align: center;
            margin-top: 30px;
            margin-bottom: 20px;
        }
        .title-section .main-title {
            font-size: 14pt;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .title-section .sy {
            font-size: 12pt;
        }
        .info-block {
            margin-top: 30px;
            margin-bottom: 30px;
        }
        .info-block div {
            margin-bottom: 5px;
        }
        .info-block .label {
            font-weight: normal;
        }
        .info-block .value {
            font-weight: bold;
            text-decoration: underline;
        }
        .schedule-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            margin-bottom: 40px;
        }
        .schedule-table th,
        .schedule-table td {
            border: 1px solid black;
            padding: 8px;

            font-size: 10.5pt;
        }
        .schedule-table th {
            background-color: #f2f2f2;
            font-weight: bold;
            text-transform: uppercase;
        }
        .prepared-by,
        .approved-by {
            margin-top: 40px;
        }
        .signature-block {
            margin-top: 20px;
            text-align: left;
            padding-left: 50px;
        }
        .signature-line {
            height: 1px;
            background-color: black;
            width: 250px;
            margin-top: 5px;
            margin-bottom: 5px;
        }
        .name {
            font-weight: bold;
            text-transform: uppercase;
            margin-top: 20px;
        }
        .title {
            font-size: 10pt;
            margin-top: 2px;
        }
        .signature-image {
            width: 150px;
            height: auto;
            display: block;
            margin-bottom: -20px;
            margin-left: -25px;
        }
        .approved-by .signature-block {
            padding-left: 0;
            margin-top: 60px;
        }
        .approved-by .signature-block .name {
            margin-top: 5px;
        }  
@media print {
    /* ... your existing print styles ... */
    
    .printable-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start; /* Align items to the top to prevent overlap */
        width: 100%; /* Force the header to use the full available width */
        box-sizing: border-box; /* Ensure padding is included in the width */
    }

    .printable-header .school-info {
        flex-grow: 1;
        text-align: center;
        /* Add margin to prevent the school info from touching the logos */
        margin: 0 20px; 
    }
}
/*  @page {
    size: A4;
    margin: 1in;
}*/
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
        <div class="py-3">
            <div class="row">
              <div class="col-lg-6">
                <label for="teacherid">SELECT TEACHER</label>
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
           <div class="col-lg-6 text-lg-end">
                <button class="btn btn-primary" onclick="print_content()">
                    <i class='bx bx-printer'></i> <span class="icon-text">Print Workload</span>
                </button>
            </div>

        </div>
        <hr>
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
        alert("Please select a teacher first.");
        return;
    }

    // Show a loader or a message to the user
    Swal.fire({
        title: 'Generating document...',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading()
        }
    });

    $.ajax({
        type: "POST",
        url: "query_teacher_loads.php",
        data: { 
            "loading_faculty_class_schedule": '1',
            "teacherid" : teacherid
        },
success: function(response) {
    // Open a new window
    var printWindow = window.open('', '_blank', 'height=800,width=800');
    
    // Write the HTML from the response into the new window
    printWindow.document.write('<html><head><title>Teacher\'s Workload</title>');
    
    // Copy all necessary CSS styles to the new window
    var head = $('head').clone();
    printWindow.document.write(head.html());
    
    // Add new print-specific CSS for A4 size and margins
printWindow.document.write('<style>');
printWindow.document.write('@page { size: A4; margin: 1in; }');
printWindow.document.write('body { margin: 0; padding: 0; }');
printWindow.document.write('.container { margin: 0 auto !important; }'); // This is the new line
printWindow.document.write('</style>');
    
    printWindow.document.write('</head><body>');
    printWindow.document.write(response);
    printWindow.document.write('</body></html>');
    
    // Close the document to ensure all content is loaded
    printWindow.document.close();
    
    // Wait for images to load, then print
    setTimeout(function() {
        printWindow.focus();
        printWindow.print();
        printWindow.close();
        Swal.close();
    }, 1000); // Wait 1 second to ensure everything is rendered
},
        error: function(xhr, status, error) {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Error loading document!'
            });
        }
    });
}
    function load_fac_workload() {
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
              // initialize here if needed
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
