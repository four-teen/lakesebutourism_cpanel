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

                    <li><a class="dropdown-item" href="#"><i class='bx  bx-user'  ></i>  Add New</a></li>
                    <li><a class="dropdown-item" href="#">-</a></li>
                    <li><a class="dropdown-item" href="#">-</a></li>
                  </ul>
                </div>
                <div class="card-body">
                  <h5 class="card-title">Student List</h5>
                        <hr>
                        <!-- <div id="test">test</div> -->

<!-- ====================== -->

<div class="row g-3">
  <div class="col-12">
    <div class="d-flex justify-content-between align-items-center">
      <h6 class="mb-0">Enrollment Form <small class="text-muted">(AY: <?=htmlspecialchars($ay)?>)</small></h6>
    </div>
    <hr>
  </div>

  <!-- TOP STRIP: School Year / Grade / With LRN / Returning -->
  <div class="col-md-3">
    <label class="form-label">School Year</label>
    <input type="text" class="form-control" id="school_year" placeholder="2025-2026" value="<?=htmlspecialchars($ay)?>">
  </div>
  <div class="col-md-2">
    <label class="form-label">Grade level</label>
    <input type="text" class="form-control" id="grade_level" placeholder="7">
  </div>
  <div class="col-md-3">
    <label class="form-label d-block">With LRN?</label>
    <div class="form-check form-check-inline">
      <input class="form-check-input" type="radio" name="with_lrn" id="with_lrn_yes" value="1">
      <label class="form-check-label" for="with_lrn_yes">Yes</label>
    </div>
    <div class="form-check form-check-inline">
      <input class="form-check-input" type="radio" name="with_lrn" id="with_lrn_no" value="0" checked>
      <label class="form-check-label" for="with_lrn_no">No</label>
    </div>
  </div>
  <div class="col-md-4">
    <label class="form-label d-block">Returning (Balik-Aral)</label>
    <div class="form-check form-check-inline">
      <input class="form-check-input" type="radio" name="is_returning" id="ret_yes" value="1">
      <label class="form-check-label" for="ret_yes">Yes</label>
    </div>
    <div class="form-check form-check-inline">
      <input class="form-check-input" type="radio" name="is_returning" id="ret_no" value="0" checked>
      <label class="form-check-label" for="ret_no">No</label>
    </div>
  </div>

  <div class="col-12"><hr class="my-2"></div>

  <!-- LEARNER INFO HEADER -->
  <div class="col-12">
    <h6 class="text-uppercase text-muted">Learner Information</h6>
  </div>

  <div class="col-md-6">
    <label class="form-label">PSA Birth Certificate No. (if available)</label>
    <input type="text" class="form-control" id="psa_no" placeholder="">
  </div>
  <div class="col-md-6">
    <label class="form-label">Learner Reference No. (LRN)</label>
    <input type="text" class="form-control" id="learner_ref_no" placeholder="">
  </div>

  <div class="col-md-6">
    <label class="form-label">Last Name</label>
    <input type="text" class="form-control text-uppercase" id="last_name">
  </div>
  <div class="col-md-6">
    <label class="form-label">First Name</label>
    <input type="text" class="form-control text-uppercase" id="first_name">
  </div>
  <div class="col-md-6">
    <label class="form-label">Middle Name</label>
    <input type="text" class="form-control text-uppercase" id="middle_name">
  </div>
  <div class="col-md-6">
    <label class="form-label">Extension Name (e.g., Jr., III)</label>
    <input type="text" class="form-control text-uppercase" id="extension_name">
  </div>

  <div class="col-md-4">
    <label class="form-label">Birthdate (mm/dd/yyyy)</label>
    <input type="date" class="form-control" id="birthdate">
  </div>
  <div class="col-md-2">
    <label class="form-label">Age</label>
    <input type="number" min="1" class="form-control" id="age" readonly>
  </div>
  <div class="col-md-6">
    <label class="form-label d-block">Sex</label>
    <div class="form-check form-check-inline">
      <input class="form-check-input" type="radio" name="sex" id="sex_m" value="Male" checked>
      <label class="form-check-label" for="sex_m">Male</label>
    </div>
    <div class="form-check form-check-inline">
      <input class="form-check-input" type="radio" name="sex" id="sex_f" value="Female">
      <label class="form-check-label" for="sex_f">Female</label>
    </div>
  </div>

  <div class="col-md-6">
    <label class="form-label">Place of Birth (Municipality/City)</label>
    <input type="text" class="form-control text-uppercase" id="place_of_birth">
  </div>
  <div class="col-md-6">
    <label class="form-label">Mother Tongue</label>
    <input type="text" class="form-control text-uppercase" id="mother_tongue">
  </div>

  <div class="col-md-6">
    <label class="form-label d-block">Belonging to any Indigenous Peoples (IP) Community?</label>
    <div class="form-check form-check-inline">
      <input class="form-check-input" type="radio" name="ip_member" id="ip_yes" value="1">
      <label class="form-check-label" for="ip_yes">Yes</label>
    </div>
    <div class="form-check form-check-inline">
      <input class="form-check-input" type="radio" name="ip_member" id="ip_no" value="0" checked>
      <label class="form-check-label" for="ip_no">No</label>
    </div>
    <input type="text" class="form-control mt-2" id="ip_specify" placeholder="If Yes, please specify" style="display:none;">
  </div>

  <div class="col-md-6">
    <label class="form-label d-block">Is your family a beneficiary of 4Ps?</label>
    <div class="form-check form-check-inline">
      <input class="form-check-input" type="radio" name="four_ps" id="fourps_yes" value="1">
      <label class="form-check-label" for="fourps_yes">Yes</label>
    </div>
    <div class="form-check form-check-inline">
      <input class="form-check-input" type="radio" name="four_ps" id="fourps_no" value="0" checked>
      <label class="form-check-label" for="fourps_no">No</label>
    </div>
    <input type="text" class="form-control mt-2" id="four_ps_household_id" placeholder="If Yes, enter 4Ps Household ID" style="display:none;">
  </div>

  <div class="col-md-3">
    <label class="form-label">Photo</label>
    <img id="student_photo" class="d-block border mb-2" src="../../assets/img/placeholder-avatar.png" alt="photo">
    <input type="file" class="form-control" id="photo_file" accept="image/*">
    <small class="text-muted">JPG/PNG up to 3MB</small>
  </div>

  <div class="col-12">
    <hr>
    <button class="btn btn-primary" id="btn_save_learner">
      <i class="bx bx-save"></i> Save Learner
    </button>
  </div>
</div>


<!-- ======================== -->
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

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>
  <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script> -->
  <!-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> -->

  <!-- Vendor JS Files -->
  <!-- <script src="../assets/bootstrap.bundle.min.js"></script> -->
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

(function(){
  // Photo preview
  $('#photo_file').on('change', function (e) {
    const f = e.target.files[0];
    if(!f) return;
    const url = URL.createObjectURL(f);
    $('#student_photo').attr('src', url);
  });

  // Age auto-calc
  $('#birthdate').on('change', function(){
    const v = $(this).val();
    if(!v) return $('#age').val('');
    const b = new Date(v);
    const t = new Date();
    let age = t.getFullYear() - b.getFullYear();
    const m = t.getMonth() - b.getMonth();
    if (m < 0 || (m === 0 && t.getDate() < b.getDate())) age--;
    $('#age').val(age > 0 ? age : 0);
  });

  // IP toggle
  $('input[name="ip_member"]').on('change', function(){
    if ($('input[name="ip_member"]:checked').val() === '1') {
      $('#ip_specify').show().focus();
    } else {
      $('#ip_specify').hide().val('');
    }
  });

  // 4Ps toggle
  $('input[name="four_ps"]').on('change', function(){
    if ($('input[name="four_ps"]:checked').val() === '1') {
      $('#four_ps_household_id').show().focus();
    } else {
      $('#four_ps_household_id').hide().val('');
    }
  });

  // Save
  $('#btn_save_learner').on('click', async function(){
    const fd = new FormData();

    // upload photo first if provided
    const file = $('#photo_file')[0].files[0];
    let photo_url = '';
    if (file) {
      const up = new FormData();
      up.append('photo', file);
      up.append('upload_student_photo', '1');
      const upRes = await fetch('upload_student_photo.php', { method: 'POST', body: up });
      const upJson = await upRes.json();
      if(!upJson.success){
        Swal.fire('Upload failed', upJson.message || 'Please try again', 'error');
        return;
      }
      photo_url = upJson.url; // we will save only this URL
    }

    // gather fields
    fd.append('save_learner', '1');
    fd.append('school_year', $('#school_year').val().trim());
    fd.append('grade_level', $('#grade_level').val().trim());
    fd.append('with_lrn', $('input[name="with_lrn"]:checked').val());
    fd.append('is_returning', $('input[name="is_returning"]:checked').val());

    fd.append('psa_no', $('#psa_no').val().trim());
    fd.append('learner_ref_no', $('#learner_ref_no').val().trim());
    fd.append('last_name', $('#last_name').val().trim());
    fd.append('first_name', $('#first_name').val().trim());
    fd.append('middle_name', $('#middle_name').val().trim());
    fd.append('extension_name', $('#extension_name').val().trim());

    fd.append('birthdate', $('#birthdate').val());
    fd.append('age', $('#age').val());
    fd.append('sex', $('input[name="sex"]:checked').val());

    fd.append('place_of_birth', $('#place_of_birth').val().trim());
    fd.append('mother_tongue', $('#mother_tongue').val().trim());

    fd.append('ip_member', $('input[name="ip_member"]:checked').val());
    fd.append('ip_specify', $('#ip_specify').is(':visible') ? $('#ip_specify').val().trim() : '');

    fd.append('four_ps', $('input[name="four_ps"]:checked').val());
    fd.append('four_ps_household_id', $('#four_ps_household_id').is(':visible') ? $('#four_ps_household_id').val().trim() : '');

    fd.append('photo_url', photo_url);

    // simple client-side checks
    if (!$('#last_name').val().trim() || !$('#first_name').val().trim() || !$('#birthdate').val()) {
      Swal.fire('Missing fields', 'Please fill Last Name, First Name, and Birthdate.', 'warning');
      return;
    }

    $('#btn_save_learner').prop('disabled', true).text('Saving...');

    fetch('save_learner.php', { method: 'POST', body: fd })
      .then(r => r.json())
      .then(j => {
        if (j.success) {
          Swal.fire('Saved', 'Learner record was saved successfully.', 'success');
          // optionally reset form
          // location.reload();
        } else {
          Swal.fire('Error', j.message || 'Unable to save', 'error');
        }
      })
      .catch(() => Swal.fire('Error', 'Network or server error', 'error'))
      .finally(() => $('#btn_save_learner').prop('disabled', false).html('<i class="bx bx-save"></i> Save Learner'));
  });

})();

    function load_students() {
        $('#loader').show(); // Show the loader
        $('#content_area').hide(); // Hide the content while loading
 
        $.ajax({
            type: "POST",
            url: "query_curriculum.php",
            data: { 
            "loading_curr": '1' 
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

  </script>

</body>

</html>