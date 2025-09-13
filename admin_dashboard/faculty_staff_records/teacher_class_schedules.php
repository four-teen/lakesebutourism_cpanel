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
/* ==== PRINT VISIBILITY (clone approach) – FINAL (no blank first page) ==== */
@media print {
  @page {
    size: A4 portrait;
    margin: 0.5in;
  }

  /* Hide ONLY body-level siblings so the clone starts at page 1 */
  body.printing > * {
    display: none !important;
  }
  body.printing > #__print_clone {
    display: block !important;
  }

  /* Clean container for the print clone */
  #__print_clone {
    position: static !important;
    width: 100% !important;
    margin: 0 !important;
    padding: 0 !important;
    border: 0 !important;
    box-shadow: none !important;
  }

  /* Keep proper table semantics inside the clone */
  #__print_clone table {
    display: table !important;
    border-collapse: collapse;
    width: 100%;
    page-break-inside: auto;
  }
  #__print_clone thead { display: table-header-group !important; }
  #__print_clone tfoot { display: table-footer-group !important; }
  #__print_clone tbody { display: table-row-group !important; }
  #__print_clone tr {
    display: table-row !important;
    page-break-inside: avoid;
    page-break-after: auto;
  }
  #__print_clone th,
  #__print_clone td { display: table-cell !important; }
}  /* <-- closes the print-only block */


/* ==== LAYOUT for printed sheet (applies to original #printed AND its clone) ==== */

#printed .sheet,
#__print_clone .sheet {
  max-width: 820px;
  margin: 0 auto;
  padding: 10px 20px;
}

#printed .header-grid,
#__print_clone .header-grid {
  display: grid;
  grid-template-columns: 80px 1fr 80px;
  align-items: center;
  gap: 10px;
  margin-bottom: 10px;
}
#printed .hdr-logo,
#__print_clone .hdr-logo {
  width: 80px;
  height: 80px;
  border-radius: 50%;
  object-fit: cover;
}
#printed .header-top,
#__print_clone .header-top {
  text-align: center;
  line-height: 1.2;
}
#printed .header-top div,
#__print_clone .header-top div {
  margin: 2px 0;
}
#printed .hr,
#__print_clone .hr {
  height: 2px;
  background: #000;
  margin: 8px 0 15px;
}

#printed .title,
#__print_clone .title {
  text-align: center;
  margin: 15px 0 10px;
}
#printed .title h2,
#__print_clone .title h2 {
  margin: 0;
  font-weight: 700;
  letter-spacing: 0.5px;
  font-size: 18px;
  text-decoration: underline;
}
#printed .title .sy,
#__print_clone .title .sy {
  font-size: 14px;
  margin-top: 2px;
  font-weight: bold;
}

#printed .meta,
#__print_clone .meta {
  margin: 15px 0 12px;
  font-size: 14px;
}
#printed .meta div,
#__print_clone .meta div {
  margin-bottom: 5px;
}
#printed .meta b a,
#__print_clone .meta b a {
  color: #000;
  text-decoration: none;
  border-bottom: 1px solid #000;
  padding-bottom: 1px;
  font-weight: bold;
  text-transform: uppercase;
}

#printed table,
#__print_clone table {
  width: 100%;
  border-collapse: collapse;
  font-size: 14px;
  margin: 15px 0;
}
#printed thead th,
#__print_clone thead th {
  border: 1px solid #000;
  padding: 8px 10px;
  text-align: center;
  font-weight: 700;
  background: #f0f0f0;
}
#printed tbody td,
#__print_clone tbody td {
  border: 1px solid #000;
  padding: 8px 10px;
  vertical-align: middle;
}
#printed tbody td:nth-child(1),
#__print_clone tbody td:nth-child(1) {
  width: 25%;
  text-align: center;
}
#printed tbody td:nth-child(2),
#__print_clone tbody td:nth-child(2) {
  width: 45%;
}
#printed tbody td:nth-child(3),
#__print_clone tbody td:nth-child(3) {
  width: 30%;
  text-align: center;
}

#printed .signatures,
#__print_clone .signatures {
  margin-top: 30px;
  display: flex;
  justify-content: space-between;
}
#printed .sig-block,
#__print_clone .sig-block {
  text-align: left;
  width: 45%;
}
#printed .sig-label,
#__print_clone .sig-label {
  font-size: 14px;
  margin-bottom: 40px;
  text-transform: uppercase;
  font-weight: bold;
}
#printed .sig-name,
#__print_clone .sig-name {
  font-weight: 700;
  text-transform: uppercase;
  margin-top: 5px;
  border-top: 1px solid #000;
  padding-top: 5px;
  display: inline-block;
  width: 100%;
}
#printed .sig-title,
#__print_clone .sig-title {
  margin-top: 2px;
  font-size: 13px;
}

/* Print-only tune-ups (safe to keep) */
@media print {
  #printed .sheet,
  #__print_clone .sheet {
    padding: 0 10mm;
  }
  #printed img,
  #__print_clone img {
    -webkit-print-color-adjust: exact;
    print-color-adjust: exact;
  }
}

}
</style>

</head>

<body onload="load_fac_workload();get_ay()" id="body">

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
  <button type="button" class="btn btn-primary" onclick="printpage()">
    <i class='bx bx-printer'></i> Print Workload
  </button>

</div>
          </div>

        </div>
      </div>

      <hr class="no-print">

    <div class="tab-content p-0">
      <div id="loadingdetails">
        <div class="d-flex flex-column justify-content-center align-items-center" style="height: 150px;">
          <h5 class="mb-3">Loading Interview Data</h5>
          <div class="progress w-100" style="height: 10px;">
            <div id="loadingProgressBar" class="progress-bar progress-bar-striped progress-bar-animated" 
                 role="progressbar" style="width: 0%"></div>
          </div>
          <small id="loadingStatusText" class="text-muted mt-2">Initializing...</small>
        </div>
      </div>           
    </div>


      <!-- Main printable content -->
<!--       <div id="main_data" class="mt-3">
        <div class="print-area p-4">
          <div id="loader" class="text-center py-5" style="display: none;">
            <img src="../../loader.gif" alt="Loading..." width="80">
            <div class="mt-2 text-muted">Fetching workload…</div>
          </div>
          <div id="content_area"></div>
        </div>
      </div> -->

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

function printpage(){
  const printed = document.getElementById('printed');
  if(!printed){
    window.print(); // fallback
    return;
  }
  // 1) clone
  const clone = printed.cloneNode(true);
  clone.id = '__print_clone';

  // 2) append as direct child of <body>
  document.body.appendChild(clone);

  // 3) add flag class para sa print CSS
  document.body.classList.add('printing');

  // 4) trigger print
  window.print();

  // 5) cleanup after print (some browsers call this AFTER dialog closes)
  document.body.classList.remove('printing');
  clone.remove();
}

// function printpage(){
  
//   var body = document.getElementById('body').innerHTML;
//   var printed = document.getElementById('printed').innerHTML;
//   document.getElementById('body').innerHTML = printed;
//   window.print();
//   document.getElementById('body').innerHTML = body;
// }

function load_fac_workload() {

    var teacherid = $('#teacherid').val();
    // Initialize loading state
    $('#loadingdetails').html(`
        <div class="d-flex flex-column justify-content-center align-items-center" style="height: 150px;">
            <h5 class="mb-3">Loading Interview Data</h5>
            <div class="progress w-100" style="height: 10px;">
                <div id="loadingProgressBar" class="progress-bar progress-bar-striped progress-bar-animated" 
                     role="progressbar" style="width: 0%"></div>
            </div>
            <small id="loadingStatusText" class="text-muted mt-2">Initializing...</small>
        </div>
    `);
    
    // Progress simulation variables
    let progress = 0;
    const progressInterval = setInterval(() => {
        progress = Math.min(progress + Math.random() * 10, 90);
        $('#loadingProgressBar').css('width', progress + '%');
        $('#loadingStatusText').text(getLoadingMessage(progress));
    }, 500);
    
    // Set up timeout for request timeout
    var requestTimeout = setTimeout(function() {
        clearInterval(progressInterval);
        showTimeoutError();
    }, 15000); // Total timeout after 15 seconds
    
    $.ajax({
        type: "POST",
        url: "query_teacher_loads.php",
        data: {
            "loading_faculty_class_schedule": '1',
            "teacherid" : teacherid
        },
        timeout: 10000, // 10 second AJAX timeout
        beforeSend: function() {
            $('#loadingStatusText').text("Connecting to server...");
        },
        xhr: function() {
            var xhr = new window.XMLHttpRequest();
            xhr.addEventListener("progress", function(evt) {
                if (evt.lengthComputable) {
                    var percentComplete = evt.loaded / evt.total * 100;
                    $('#loadingProgressBar').css('width', percentComplete + '%');
                    $('#loadingStatusText').text(getLoadingMessage(percentComplete));
                }
            });
            return xhr;
        },
        success: function(response) {
            clearInterval(progressInterval);
            clearTimeout(requestTimeout);
            
            // Complete the progress bar
            $('#loadingProgressBar').css('width', '100%').removeClass('progress-bar-animated progress-bar-striped');
            $('#loadingStatusText').text("Loading complete!");
            
            // Small delay to show completion
            setTimeout(() => {
                if(response.trim() === '') {
                    showNoData();
                } else {
                    $('#loadingdetails').html(response);
                }
            }, 500);
        },
        error: function(xhr, status, error) {
            clearInterval(progressInterval);
            clearTimeout(requestTimeout);
            showError(status);
        }
    });  
}

function showNoData() {
    $('#loadingdetails').html(`
        <div class="d-flex flex-column justify-content-center align-items-center" style="height: 150px;">
            <div class="progress w-100 mb-3" style="height: 10px;">
                <div class="progress-bar bg-info" role="progressbar" style="width: 100%"></div>
            </div>
            <i class="bi bi-info-circle-fill text-info fs-1"></i>
            <h5 class="mt-2">No Data Available</h5>
            <p class="text-muted">No interview records found.</p>
        </div>
    `);
}
// function print_content() {
//     var teacherid = $('#teacherid').val();
    
//     // Check if a teacher is selected
//     if (!teacherid) {
//         Swal.fire({
//             icon: 'warning',
//             title: 'No Teacher Selected',
//             text: 'Please select a teacher first to view and print their workload.'
//         });
//         return;
//     }

//     // Use the existing function to load content
//     load_fac_workload(function() {
//         // This is a callback function that runs after the AJAX request is complete.
//         // It ensures the content is fully loaded before we attempt to print.
        
//         // Wait a little bit for the content to render fully.
//         setTimeout(function() {
//             window.print();
//         }, 500); // Wait 0.5 seconds
//     });
// }
// function load_fac_workload(callback = null) {
//     $('#loader').show(); // Show the loader
//     $('#content_area').hide(); // Hide the content while loading
//     var teacherid = $('#teacherid').val();
    
//     $.ajax({
//         type: "POST",
//         url: "query_teacher_loads.php",
//         data: { 
//             "loading_faculty_class_schedule": '1',
//             "teacherid" : teacherid
//         },
//         success: function(response) {
//             $('#content_area').html(response);
            
//             if ($.fn.DataTable.isDataTable('#tblTeachers')) {
//                 $('#tblTeachers').DataTable().destroy();
//             }
//             $('#tblTeachers').DataTable({ pageLength: 10, lengthChange: false, order: [[2,'asc'],[3,'asc']] });
//         },
//         error: function(xhr, status, error) {
//             $('#content_area').html('<p class="text-danger">Error loading data.</p>');
//         },
//         complete: function() {
//             setTimeout(() => {
//                 $('#loader').hide(); // Hide the loader
//                 $('#content_area').show(); // Show the main content

//                 // Trigger the callback function if it exists
//                 if (typeof callback === 'function') {
//                     callback();
//                 }
//             }, 500); // Delay ensures a smooth transition
//         }
//     });
// }

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
