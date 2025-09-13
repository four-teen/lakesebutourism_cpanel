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
/* Keep the stretched link inside the body only */
.subject-card .card-body { position: relative; }
.subject-card .card-body .stretched-link {
  z-index: 1;
  pointer-events: none;  /* makes header items fully interactive */
}

.cta-badge{
  background:#f5f7ff;
  color:#4e54c8;
  border-radius:999px;
  padding:.35rem .8rem;
  font-weight:600;
  display:inline-flex;
  align-items:center;
  gap:4px;
  transition: transform .18s ease, background-color .18s ease, box-shadow .18s ease;
  will-change: transform;
  cursor: pointer;
}
.cta-badge:hover{
  transform: scale(1.08);
  background:#e8eaff;
  box-shadow: 0 .25rem .75rem rgba(0,0,0,.08);
}
.cta-badge:active{
  transform: scale(0.96);
}
/* Make the badge sit above and receive hover/click */
.subject-card .card-header .cta-badge {
  position: relative;
  z-index: 2;
  pointer-events: auto;
}

/* Card shell */
.subject-card{
  border: none;
  border-radius: 14px;
  overflow: hidden;
  transition: transform .15s ease, box-shadow .15s ease;
}
.subject-card:hover{
  transform: translateY(-3px);
  box-shadow: 0 .75rem 1.5rem rgba(0,0,0,.08);
}

/* Header + badge */
.subject-card .card-header{
  background: linear-gradient(135deg,#4e54c8,#8f94fb);
  color: #fff;
  font-weight: 600;
  border-bottom: none;
}
.badge-pill {
  background: #f5f7ff;
  color: #4e54c8;
  border-radius: 999px;
  padding: .35rem .8rem;
  font-weight: 600;
  display: inline-flex;
  align-items: center;
  gap: 4px;
  transition: transform 0.2s ease, background-color 0.2s ease;
}

.badge-pill:hover {
  transform: scale(1.08);        /* zoom in */
  background: #e8eaff;           /* optional: lighter background */
}

.badge-pill:active {
  transform: scale(0.95);        /* small shrink when clicked */
}

.subject-body {
  display: flex;
  gap: 16px;
  min-height: 120px;
}

/* left image */
.subject-thumb {
  width: 110px;
  height: 110px;
  border-radius: 12px;
  object-fit: cover;
  flex: 0 0 110px;
  background: #f6f7fb;
  position: relative;
  top:25px;
}

/* right column layout */
.subject-content {
  display: flex;
  flex-direction: column;
  justify-content: space-between; /* title up, metric down */
  flex: 1;
}

/* title */
.subject-title {
  margin: 0;
  font-weight: 700;
  color: #1f3b77;
  position: relative;
  top:15px;
}
.subject-title small {
  display: block;
  margin-top: .125rem;
  color: #6c7aa0;
  font-weight: 500;
}

/* metric pinned at bottom */
.subject-metric {
  margin-top: auto;  /* ensures this block stays at the bottom */
  position: relative;
  top:20px;
}
.big-metric {
  font-size: 2rem;
  line-height: 1.2;
  font-weight: 800;
  color: #0d6efd;
}
.metric-label {
  font-size: .85rem;
  color: #6c757d;
  margin-top: -2px;
}


/* Footer */
.subject-card .card-footer{
  background-color: rgba(78,84,200,.05);
  border-top: 1px solid rgba(78,84,200,.1);
}

/* Responsive */
@media (max-width: 575.98px){
  .subject-body{
    flex-direction: column;
    align-items: flex-start;
    min-height: 0;
  }
  .subject-thumb{
    width: 100%;
    height: 160px;
    margin: 0;                /* reset centering for stacked layout */
  }
  .subject-content{
    gap: .5rem;
  }
}

</style>
</head>

<body onload="get_ay();load_curr();">



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

<li class="nav-item">
  <a class="nav-link" href="#">
    <i class="bi bi-journal-text text-primary"></i> <span>Curriculum and Academic Management</span>
    <!-- <i class="bi bi-chevron-down ms-auto"></i> -->
  </a>
  <ul id="curriculum-nav" class="nav-content" data-bs-parent="#sidebar-nav">
    <li><a href="#"><i class="bi bi-circle"></i><span>Class Scheduling</span></a></li>
    <li><a href="student_curriculum.php"><i class="bi bi-circle"></i><span>Manage Curriculum</span></a></li>
    <li><a href="#"><i class="bi bi-circle"></i><span>Assessment and Examination Records</span></a></li>
  </ul>
</li>


  </ul>
</aside>
<!-- End Sidebar -->

  <main id="main" class="main">
    <div class="pagetitle">
      <h1>Manage Curriculum</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="../dashboard_admin.php">Home</a></li>
          <li class="breadcrumb-item active">Dashboard</li>
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

                        <div id="main_data">
                          <div id="loader" class="text-center" style="display: none;">
                            <img src="../../loader.gif" alt="Loading..." width="10%">
                          </div>
                          <div id="content_area"></div>
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



    function load_curr() {
        $('#loader').show(); // Show the loader
        $('#content_area').hide(); // Hide the content while loading
        var gradelevelid = $('#gradelevelid').val();

        $.ajax({
            type: "POST",
            url: "query_curriculum.php",
            data: { 
            "loading_curriculum_review": '1',
            "gradelevelid" : gradelevelid 
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