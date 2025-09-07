<?php
  session_start();
  require_once __DIR__ . '/../../modules/auth/session_guard.php';
  require_role(['Administrator']); // only admins can access

  include_once __DIR__ . '/../../db.php';


  $settings = "SELECT * FROM `tblsettings`
  INNER JOIN tblacademic_years on tblacademic_years.ayid=tblsettings.ayid LIMIT 1";
  $runsettings = mysqli_query($conn, $settings);
  $rowsettings = mysqli_fetch_assoc($runsettings);
  $_SESSION['ays'] = $rowsettings['ayfrom'].'-'.$rowsettings['ayfrom'];
  $_SESSION['ayid'] = $rowsettings['ayid'];

$ay = isset($_SESSION['ays']) ? $_SESSION['ays'] : '';

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Admin Dashboard</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <link href="../../assets/img/logo.png" rel="icon">
  <link href="../../assets/img/logo.png" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <link href="https://fonts.gstatic.com" rel="preconnect">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="../../assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="../../assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="../../assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
  <link href="../../assets/vendor/quill/quill.snow.css" rel="stylesheet">
  <link href="../../assets/vendor/quill/quill.bubble.css" rel="stylesheet">
  <link href="../../assets/vendor/remixicon/remixicon.css" rel="stylesheet">
  <link href="../../assets/vendor/simple-datatables/style.css" rel="stylesheet">
  <link href="../../assets/vendor/bootstrap/css/dataTables.bootstrap5.min.css" rel="stylesheet">

   <!-- Template Main CSS File -->
  <link href="../../assets/css/style.css" rel="stylesheet">
<style>
    .search-item.active {
      background-color: #0d6efd;
      color: white;
    }  #student_photo {
      border-radius: 10px;
      height: 150px;
      width: 150px;
      object-fit: cover;
    }
  /* Photo box */
  .photo-wrap { display:flex; align-items:flex-start; gap:1rem; }
  .photo-box {
    width: 200px; height: 200px;
    border-radius: 50%;
    background: #f2f5ff;
    border: 2px dashed #8db1ff;
    display: grid; place-items: center;
    cursor: pointer; position: relative;
  }
  .photo-box img {
    width: 100%; height: 100%; object-fit: cover; border-radius: 50%;
    display:none;
  }
  .photo-placeholder {
    color:#4b6bd6; text-align:center; font-weight:600; font-size:.9rem;
    display:flex; flex-direction:column; align-items:center; gap:.35rem;
  }
  .photo-placeholder svg { width:36px; height:36px; opacity:.8; }
  .field-note{ font-size:.85rem; color:#6c757d }

  /* Tighten label spacing */
  .form-label { margin-bottom: .35rem; }
</style>
</head>

<body onload="get_ay();load_students()">

<?php 
  include '../header.php';
 ?>
<!-- ======= Sidebar ======= -->
<aside id="sidebar" class="sidebar">
  <ul class="sidebar-nav" id="sidebar-nav">

    <!-- Dashboard -->
    <li class="nav-item">
      <a class="nav-link" href="../dashboard_admin.php">
        <i class="bi bi-grid"></i> <span>Dashboard</span>
      </a>
    </li>

<li class="nav-item active">
  <a class="nav-link" href="#" role="button">
    <i class="bx bx-user-circle text-primary"></i> <span>Student Management</span>
  </a>
  <ul id="students-nav" class="nav-content show" data-bs-parent="#sidebar-nav">
    <li class="nav-item active"><a class="active" href="student_management/student_records.php"><i class="bi bi-circle"></i><span>Enrolment & Student Records</span></a></li>
    <li><a href="#"><i class="bi bi-circle"></i><span>Attendance Tracking</span></a></li>
    <li><a href="#"><i class="bi bi-circle"></i><span>Grade and Reports</span></a></li>
    <li><a href="#"><i class="bi bi-circle"></i><span>Student Profiles</span></a></li>
  </ul>
</li>




  </ul>
</aside>
<!-- End Sidebar -->

  <main id="main" class="main">

    <div class="pagetitle">
      <h1>Dashboard</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="../dashboard_admin.php">Home</a></li>
          <li class="breadcrumb-item active">Student Management</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

    <section class="section dashboard">

      <!-- <div id="test">test</div> -->
      <div class="row">

        <!-- Left side columns -->
        <div class="col-lg-12">
          <div class="row g-3 align-items-stretch">
            <!-- Sales Card -->

            <!-- Reports -->
            <div class="col-12">
              <div class="card">

                <div class="filter">
                  <a class="icon" href="#" data-bs-toggle="dropdown">
                    <button class="btn btn-primary">Actions</button>
                  </a>
                  <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                    <li class="dropdown-header text-start">
                      <h6>Filter</h6>
                    </li>

                    <li><a onclick="show_student_modal()" class="dropdown-item" href="#"><i class='bx  bx-user'  ></i>  Add New</a></li>
                    <li><a class="dropdown-item" href="#">-</a></li>
                    <li><a class="dropdown-item" href="#">-</a></li>
                  </ul>
                </div>
                <div class="card-body">
                  <h5 class="card-title">Student List</h5>
                        <hr>
                        <div id="test">test</div>
                        <div id="main_data">
                          <div id="loader" class="text-center" style="display: none;">
                            <img src="../../loader.gif" alt="Loading..." width="10%">
                          </div>
                          <div id="content_area"></div>
                        </div>                  

                          
                </div>
              </div>
                                            
            </div>


          </div>
        </div><!-- End Reports -->


          </div>
        </div><!-- End Left side columns -->
      </div>


    </section>

  </main><!-- End #main -->

  <!-- ======= Footer ======= -->
  <footer id="footer" class="footer">
    <div class="copyright">
      &copy; Copyright <strong><span>BTESLife</span></strong>. All Rights Reserved
    </div>
    <div class="credits">
      Powered by <a href="#">bteslife</a>
    </div>
  </footer><!-- End Footer -->

     <div class="modal fade" id="modal_add_new_students" tabindex="-1" aria-labelledby="paymentModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
          <div class="modal-content shadow">
            <div class="modal-header bg-info text-white">
              <h5 class="modal-title" id="paymentModalLabel">New student registration</h5>
              <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
              <div class="row">
<div class="row g-4 align-items-start">
  <!-- LEFT: Photo -->
  <div class="col-md-3">
    <div class="photo-box" id="photo_click" title="Click to add image">
      <div id="photo_placeholder" class="photo-placeholder">
        <!-- camera icon (inline SVG so it never 404s) -->
        <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
          <path d="M20 5h-2.6l-1.1-1.7A2 2 0 0 0 14.6 2h-5.2a2 2 0 0 0-1.7.9L6.6 5H4a2 2 0 0 0-2 2v11a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2Zm-8 13a5 5 0 1 1 0-10 5 5 0 0 1 0 10Zm0-2.5a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5Z"/>
        </svg>
        <span>Add photo</span>
      </div>
      <img id="photo_preview" src="" alt="Student photo">
    </div>
    <input type="file" id="photo_file" accept="image/*" class="d-none">
    <div class="field-note mt-2 text-center">JPG/PNG up to 3MB</div>
  </div>

  <!-- RIGHT: Fields -->
<div class="col-md-9">
  <!-- radios -->
  <div class="row g-3">
    <div class="col-md-6">
      <label class="form-label">1. With LRN?</label><br>
      <div class="form-check form-check-inline">
        <input class="form-check-input" type="radio" name="with_lrn" value="1">
        <label class="form-check-label">Yes</label>
      </div>
      <div class="form-check form-check-inline">
        <input class="form-check-input" type="radio" name="with_lrn" value="0" checked>
        <label class="form-check-label">No</label>
      </div>
    </div>

    <div class="col-md-6">
      <label class="form-label">2. Returning (Balik-Aral)</label><br>
      <div class="form-check form-check-inline">
        <input class="form-check-input" type="radio" name="is_returning" value="1">
        <label class="form-check-label">Yes</label>
      </div>
      <div class="form-check form-check-inline">
        <input class="form-check-input" type="radio" name="is_returning" value="0" checked>
        <label class="form-check-label">No</label>
      </div>
    </div>
  </div>

  <!-- grade / birthdate -->
  <div class="row g-3 mt-1">
    <div class="col-md-6">
      <label class="form-label">Grade Level</label>
      <select id="grade_level" class="form-control">
        <?php 
          $get_grade_level = "SELECT * FROM `tblgradelevel` ORDER BY `levelid` ASC";
          $runget_gradelevel = mysqli_query($conn, $get_grade_level);
          while($rowgradelevel = mysqli_fetch_assoc($runget_gradelevel)){
            echo'<option value="'.$rowgradelevel['levelid'].'">'.$rowgradelevel['level_descrition'].'</option>';
          }
        ?>
        
      </select>

    </div>
    <div class="col-md-6">
      <label class="form-label">Birthdate (mm/dd/yyyy)</label>
      <input type="date" class="form-control" id="birthdate">
    </div>
  </div>

  <!-- sex -->
  <div class="row g-3 mt-1">
    <div class="col-md-6">
      <label class="form-label">Sex</label><br>
      <div class="form-check form-check-inline">
        <input class="form-check-input" type="radio" name="sex" value="Male" checked>
        <label class="form-check-label">Male</label>
      </div>
      <div class="form-check form-check-inline">
        <input class="form-check-input" type="radio" name="sex" value="Female">
        <label class="form-check-label">Female</label>
      </div>
    </div>
  </div>

  <!-- LRN + PSA (same row) -->
  <div class="row g-3 mt-1">
    <div class="col-md-6">
      <label class="form-label">Learner Reference No.</label>
      <input type="text" class="form-control" id="learner_ref_no">
    </div>
    <div class="col-md-6">
      <label class="form-label">PSA Birth Certificate No.</label>
      <input type="text" class="form-control" id="psa_birth_cert_no">
    </div>
  </div>

  <!-- Names in one line -->
  <div class="row g-3 mt-1">
    <div class="col-12 col-md-6 col-lg-4">
      <label class="form-label">Last Name</label>
      <input type="text" class="form-control text-uppercase" id="last_name">
    </div>
    <div class="col-12 col-md-6 col-lg-3">
      <label class="form-label">First Name</label>
      <input type="text" class="form-control text-uppercase" id="first_name">
    </div>
    <div class="col-12 col-md-6 col-lg-3">
      <label class="form-label">Middle Name</label>
      <input type="text" class="form-control text-uppercase" id="middle_name">
    </div>
    <div class="col-12 col-md-6 col-lg-2">
      <label class="form-label">Ext</label>
      <input type="text" class="form-control text-uppercase" id="extension_name">
    </div>
  </div>

  <div class="text-end mt-2">

  </div>
</div>

</div>


              </div>
              <div id="get_selected_final_print"></div>
            </div>
            <div class="modal-footer">
              <button class="btn btn-secondary" data-bs-dismiss="modal" onclick="close_payment_print();">Close</button>
              <button type="button" class="btn btn-primary" onclick="add_new_student();">
                <i class="bi bi-save"></i> Save
              </button>              
            </div>
          </div>
        </div>
      </div>



  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>
  <script src="../../assets/vendor/bootstrap/js/jquery-3.6.0.min.js"></script>  
  <script src="../../assets/vendor/bootstrap/js/bootstrap5.bundle.min.js"></script>


  <script src="../../assets/vendor/simple-datatables/simple-datatables.js"></script>
  <script src="../../assets/vendor/tinymce/tinymce.min.js"></script>
  <script src="../../assets/vendor/php-email-form/validate.js"></script>
  <script src="../../assets/vendor/bootstrap/js/boxicons.js"></script>  
  <!-- <script src="https://unpkg.com/boxicons@2.1.4/dist/boxicons.js"></script> -->
  <script src="../../assets/vendor/bootstrap/js/jquery.dataTables.min.js"></script>  
  <script src="../../assets/vendor/bootstrap/js/dataTables.bootstrap5.min.js"></script>

  <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
  <script src="../../assets/js/main.js"></script>
  <script src="../../assets/sweetalert2.js"></script>

  <script>
  // click to select
  $('#photo_click').on('click', function(){ $('#photo_file').trigger('click'); });

  // preview instantly; hide placeholder
  $('#photo_file').on('change', function(e){
    const f = e.target.files[0];
    if(!f) return;
    const url = URL.createObjectURL(f);
    $('#photo_placeholder').hide();
    $('#photo_preview').attr('src', url).show();
  });

function add_new_student(){
  var fd = new FormData();

  // file
  var file = $('#photo_file')[0].files[0];
  if (file) {
    fd.append('photo', file);
  }

  // flag for PHP
  fd.append('saving_student', '1');

  // normal fields
  fd.append('with_lrn', $('input[name="with_lrn"]:checked').val());
  fd.append('is_returning', $('input[name="is_returning"]:checked').val());
  fd.append('psa_birth_cert_no', $('#psa_birth_cert_no').val());
  fd.append('learner_ref_no', $('#learner_ref_no').val());
  fd.append('last_name', $('#last_name').val());
  fd.append('first_name', $('#first_name').val());
  fd.append('middle_name', $('#middle_name').val());
  fd.append('extension_name', $('#extension_name').val());
  fd.append('birthdate', $('#birthdate').val());
  fd.append('sex', $('input[name="sex"]:checked').val());
  fd.append('grade_level', $('#grade_level').val());

  $.ajax({
    type: "POST",
    url: "query_students.php",
    data: fd,
    processData: false,  // important
    contentType: false,  // important
    success: function (response) {
      console.log(response); // debug
      load_students();
      $('#modal_add_new_students').modal('hide');
    },
    error: function(xhr, status, error){
      alert("Error: " + error);
    }
  });
}

    function show_student_modal(){
      $('#modal_add_new_students').modal('show');
    }

    function load_students() {
        $('#loader').show(); // Show the loader
        $('#content_area').hide(); // Hide the content while loading
      
        $.ajax({
            type: "POST",
            url: "query_students.php",
            data: { 
            "loading_students": '1'
          },
            success: function(response) {
                setTimeout(() => {
                    $('#content_area').html(response);
                }, 300); // Small delay for smoother loading
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

    $(document).ready(function () {
      $('#students_table').DataTable({
        responsive: true,
        pageLength: 10
      });
    });
  </script>

</body>

</html>