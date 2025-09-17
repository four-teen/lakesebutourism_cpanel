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


?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Faculty Dashboard</title>
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
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

   <!-- Template Main CSS File -->
  <link href="../../assets/css/style.css" rel="stylesheet">
  <style>


  </style>
</head>

<body onload="get_ay();get_reports()">



<?php 
  include 'header.php';

 ?>
<!-- ======= Sidebar ======= -->
<aside id="sidebar" class="sidebar">
  <ul class="sidebar-nav" id="sidebar-nav">

    <!-- Dashboard -->
    <li class="nav-item">
      <a class="nav-link" href="dashboard_teacher.php">
        <i class="bi bi-grid"></i> <span>Dashboard</span>
      </a>
    </li>

    <!-- Student Management -->
    <li class="nav-item">
      <a class="nav-link collapsed" data-bs-toggle="collapse" href="#students-nav" role="button" aria-expanded="false" aria-controls="students-nav">
        <i class="bx bx-user-circle text-primary"></i> <span>Student Management</span>
      </a>
    <?php 
       $select = "SELECT * FROM `tblclass_schedules_teachers`
      INNER JOIN tblcurriculum on tblcurriculum.currid=tblclass_schedules_teachers.cst_classid
      INNER JOIN tblteachers on tblteachers.teachersautoid=tblclass_schedules_teachers.cst_teachersid
      INNER JOIN tblgradelevel on tblgradelevel.levelid=tblcurriculum.gradelevelid
      INNER JOIN tblsections on tblsections.sectionsid = tblclass_schedules_teachers.cst_sectionid
      INNER JOIN tblsubjects on tblsubjects.subjectid = tblcurriculum.subjectid
      INNER JOIN tblacademic_years on tblacademic_years.ayid=tblcurriculum.ayid
      WHERE cst_teachersid='$_SESSION[TEA_ID]'";
      $runselect = mysqli_query($conn, $select);

      while ($rowselect = mysqli_fetch_assoc($runselect)) {
        echo
        '
      <ul id="students-nav" class="nav-content" data-bs-parent="#sidebar-nav">
        <li onclick="get_reports(\''.$rowselect['cstid'].'\',\''.$rowselect['level_descrition'].'\',\''.$rowselect['section_desc'].'\',\''.$rowselect['subject_description'].'\')"><a href="#" class=" text-danger"><i class="bi bi-person-bounding-box fs-2 text-primary"></i><span>'.$rowselect['level_descrition'].' - '.strtoupper($rowselect['section_desc']).'<br><span class="text-primary">'.$rowselect['subject_description'].'</span></span></a></li>
      </ul>
        ';
      }
    ?>

    </li>


    <li class="nav-heading"></li>
    <li class="nav-item">
      <a class="nav-link collapsed" href="../modules/auth/logout.php">
        <i class="bi bi-box-arrow-in-right text-danger"></i> <span>Logout</span>
      </a>
    </li>

  </ul>
</aside>
<!-- End Sidebar -->

  <main id="main" class="main">

    <div class="pagetitle">
      <h1>Grades Report</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="student_grades_reports.php">Home</a></li>
          <li class="breadcrumb-item active">Dashboard</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

    <section class="section dashboard">

      <!-- <div id="test">test</div> -->
      <div class="row">

        <!-- Left side columns -->
        <div class="col-lg-12">
            <div class="col-12">
              <div class="card">

                <div class="filter">
                  <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                  <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                    <li class="dropdown-header text-start">
                      <h6>Filter</h6>
                    </li>

                    <li><a class="dropdown-item" href="#">Today</a></li>
                    <li><a class="dropdown-item" href="#">This Month</a></li>
                    <li><a class="dropdown-item" href="#">This Year</a></li>
                  </ul>
                </div>


                <div class="card-body">
                  <h5 class="card-title"></h5>

                        <div id="main_data">
                          <div id="loader" class="text-center" style="display: none;">
                            <img src="../loader.gif" alt="Loading..." width="10%">
                          </div>
                          <div id="content_area"></div>
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
      &copy; Copyright <strong><span><?php echo $_SESSION['footer'] ?></span></strong>. All Rights Reserved
    </div>
    <div class="credits">
      Powered by <a href="#">bteslife</a>
    </div>
  </footer><!-- End Footer -->

<!-- ======================= -->
  <div class="modal fade" id="addingStudentsModal" tabindex="-1" aria-labelledby="paymentModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
      <div class="modal-dialog modal-lg">
        <div class="modal-content shadow">
          <div class="modal-header bg-info text-white">
            <h5 class="modal-title" id="paymentModalLabel">Add Students</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
            <input type="hidden" id="class_schedule_id">
            <input type="hidden" id="get_level_id">
              <div class="row">
                <div id="get_student_list">loading students</div>

              </div>
              <div class="row">
                <div class="col-lg-12 py-2">
                  <button  onclick="saving_students()" class="btn btn-primary">Add to list</button>
                  <hr>
                    <div id="main_data2">
                      <div id="loader2" class="text-center" style="display: none;">
                        <img src="../loader.gif" alt="Loading..." width="10%">
                      </div>
                      <div id="content_area2"></div>
                    </div>

                </div>
              </div>
          </div>
          <div class="modal-footer">
            <button onclick="update_count()" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
  </div>


<!-- ======================= -->


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
  <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>


  <script>


    function get_reports() {
        $('#loader').show(); // Show the loader
        $('#content_area').hide(); // Hide the content while loading
        var cstid = '<?php echo $_GET['cstid'] ?>';
        var subject_description = '<?php echo $_GET['subject_description'] ?>';
        var level_descrition = '<?php echo $_GET['level_descrition'] ?>';
        var section_desc = '<?php echo $_GET['section_desc'] ?>';      
        
        $.ajax({
            type: "POST",
            url: "query_students.php",
            data: { 
            "loading_grade_reports": '1',
            "cstid" : cstid,
            "subject_description" : subject_description,
            "level_descrition" : level_descrition,
            "section_desc" : section_desc 
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