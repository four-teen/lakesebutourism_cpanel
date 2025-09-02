<?php
session_start();
require_once __DIR__ . '/../modules/auth/session_guard.php';
require_role(['Administrator']); // only admins can access

include_once __DIR__ . '/../db.php';
include_once __DIR__ . '/../logger.php';
log_event("ADMIN DASHBOARD: accessed by {$_SESSION['USERNAME']} ({$_SESSION['TYPE']})");
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Dashboard: <?php echo $rowconfig['systemname'] ?></title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <link href="../assets/img/logo.png" rel="icon">
  <link href="../assets/img/logo.png" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <link href="https://fonts.gstatic.com" rel="preconnect">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="../assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="../assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="../assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
  <link href="../assets/vendor/quill/quill.snow.css" rel="stylesheet">
  <link href="../assets/vendor/quill/quill.bubble.css" rel="stylesheet">
  <link href="../assets/vendor/remixicon/remixicon.css" rel="stylesheet">
  <link href="../assets/vendor/simple-datatables/style.css" rel="stylesheet">
  <link href="../assets/vendor/bootstrap/css/dataTables.bootstrap5.min.css" rel="stylesheet">

    <!-- Bootstrap 5 CSS -->
    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"> -->

    <!-- DataTables CSS (Bootstrap 5 Integration) -->
    <!-- <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet"> -->


  <!-- Template Main CSS File -->
  <link href="../assets/css/style.css" rel="stylesheet">
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

<body onload="get_student_count();get_academic_year();load_students_reports();">



<?php 
  include 'header.php';
  include 'sidebar.php';
 ?>

  <main id="main" class="main">

    <div class="pagetitle">
      <h1>Dashboard</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index.php">Home</a></li>
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
            <div class="col-lg-4" style="cursor: pointer;" onclick="student_management();">
              <div class="card info-card sales-card h-100">

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
                  <h5 class="card-title">Manage Students <span>| <button class="btn btn-info btn-sm">F7</button></span></h5>

                  <div class="d-flex align-items-center">
                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                      <i class='bx bx-user'></i>
                    </div>
                    <div class="ps-3">
                      <h6 id="load_student_count"></h6>
                      <span class="text-success small pt-1 fw-bold"></span> <span class="text-muted small pt-2 ps-1">record(s)</span>

                    </div>
                  </div>
                </div>

              </div>
            </div><!-- End Sales Card -->

            <!-- MANAGE SETTINGS -->
            <div class="col-lg-4">
              <div class="card info-card sales-card h-100">
                <div class="filter">
                  <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                  <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                    <li class="dropdown-header text-start">
                      <h6>Manage</h6>
                    </li>
                    <li><a class="dropdown-item" href="#" onclick="manage_settings()"><i class='bx bx-calendar' ></i> Academic Year</a></li>
                    <li><a class="dropdown-item" href="#" onclick="manage_fees()"><i class='bx bx-dollar-circle' ></i> School Fees</a></li>
                  </ul>
                </div>
                    <a href="#" style="text-decoration: none; color: inherit;">
                      <div class="card-body">
                        <h5 class="card-title">Manage Settings <span>| All</span></h5>
                        <div class="d-flex align-items-center">
                          <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                            <i class='bx bxs-cog'></i>
                          </div>
                          <div class="ps-3">
                            <h6 id="load_settings"></h6>
                            <span class="text-danger small pt-1 fw-bold"></span> 
                            <span class="text-muted small pt-2 ps-1">Current Setting</span>
                          </div>
                        </div>
                      </div>
                    </a>
              </div>

            </div>
            <!-- LOAD BIO ATTENDANCE LOG -->
            <div class="col-lg-4" onclick="loading_fees_modals();">
              <div class="card info-card sales-card h-100">
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
                    <a href="#" style="text-decoration: none; color: inherit;">
                      <div class="card-body">
                        <h5 class="card-title">Manage Transaction <span>| <button class="btn btn-info btn-sm">F6</button></span></h5>
                        <div class="d-flex align-items-center">
                          <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                            <i class='bx bx-log-in-circle'></i>
                          </div>
                          <div class="ps-3">
                            <h6 id="get_bio_logs">
                              <?php 
                                $get_hist = "SELECT count(studentid) as trans_count FROM `tblpayments_history`";
                                $runget_hist = mysqli_query($conn, $get_hist);
                                if($runget_hist){
                                  $row_gethist = mysqli_fetch_assoc($runget_hist);
                                  echo $row_gethist['trans_count'];
                                }
                              ?>
                            </h6>
                            <span class="text-danger small pt-1 fw-bold"></span> 
                            <span class="text-muted small pt-2 ps-1">transactions</span>
                          </div>
                        </div>
                      </div>
                    </a>
              </div>

            </div>

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
                  <h5 class="card-title">Reports</h5>
                  <div id="main_data"></div>
                          <div id="loader_students_reports" class="text-center" style="display: none;">
                            <img src="../loader.gif" alt="Loading..." width="10%">
                          </div>

                        <div class="row">
                          <div class="col-lg-6">
                            <label for="get_acad_year">SELECT ACADEMIC YEAR</label>
                          <select class="form-control" id="get_acad_year" onchange="load_students_reports()">
                            <?php 
                              $get_ay = "SELECT * FROM `tblacademic_years` ORDER BY ay DESC";
                              $runget_ay = mysqli_query($conn, $get_ay);
                              while($roway = mysqli_fetch_assoc($runget_ay)){
                                echo'<option value="'.$roway['acayearid'].'">'.$roway['ay'].'</option>';
                              }
                            ?>
                          </select>
                          <hr>

                            <div id="loading_students_reports"></div>
                          </div>
                        </div>
                                            
                </div>


              </div>
            </div><!-- End Reports -->


          </div>
        </div><!-- End Left side columns -->

        <div class="modal fade" id="modal_class" tabindex="-1" aria-labelledby="modalLabel1" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
          <div class="modal-dialog modal-xl">
            <div class="modal-content shadow-lg">
              <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="modalLabel1">Add Student</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>

              <div class="modal-body">
                <form id="studentForm">        
                  <div class="row">

                    <div class="col-lg-4">
                      <label for="grade_lev">Student ID</label>
                      <input type="text" class="form-control" id="studentid" placeholder="Enter Student ID">
                    </div>           
                  </div>
                    <div class="row">
                      <div class="col-lg-4">
                        <label for="firstname">Firstname</label>
                        <input type="text" class="form-control" id="firstname">
                      </div>
                      <div class="col-lg-4">
                        <label for="middlename">Middle Name</label>
                        <input type="text" class="form-control" id="middlename">
                      </div>
                      <div class="col-lg-4">
                        <label for="lastname">Lastname</label>
                        <input type="text" class="form-control" id="lastname">
                      </div>
                    </div>
                    <div class="row">
                    <div class="col-lg-2">
                      <label for="grade_lev">Grade Level</label>
                      <select id="grade_lev" class="form-control">
                        <?php 
                          $grade_level = "SELECT * FROM `tblgradelevel` ORDER BY grade_level_desc ASC";
                          $rungrade_level = mysqli_query($conn, $grade_level);
                          while($rowgrade_level = mysqli_fetch_assoc($rungrade_level)){
                            echo
                            '
                              <option value="'.$rowgrade_level['gradeid'].'">'.$rowgrade_level['grade_level_desc'].'</option>
                            ';
                          }
                        ?>
                        
                      </select>
                    </div>
                    <div class="col-lg-2">
                      <label for="secid">Section</label>
                      <select id="secid" class="form-control">
                        <?php 
                          $grade_section = "SELECT * FROM `tblsectioning` ORDER BY sec_name ASC";
                          $rungrade_section = mysqli_query($conn, $grade_section);
                          while($rowgrade_section = mysqli_fetch_assoc($rungrade_section)){
                            echo
                            '
                              <option value="'.$rowgrade_section['secid'].'">'.$rowgrade_section['sec_name'].'</option>
                            ';
                          }
                        ?>
                        
                      </select>
                    </div>              
                    </div>
                  </form>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                <button onclick="save_student();" type="button" class="btn btn-primary btn-sm">Saving Students</button>
              </div>
              <div class="modal-body">
                <div class="row">
                    <div class="col-lg-12">
                        <div id="main_data">
                          <div id="loader_students" class="text-center" style="display: none;">
                            <img src="../loader.gif" alt="Loading..." width="10%">
                          </div>
                          <div id="loading_students">Loading students</div>
                        </div> 
                    </div>
                </div>
              </div>

            </div>
          </div>
        </div>


        <div class="modal fade" id="modal_academic_year" tabindex="-1" aria-labelledby="modalLabel1" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
          <div class="modal-dialog modal-lg">
            <div class="modal-content shadow-lg">
              <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="modalLabel1">Manage Fees</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>

              <div class="modal-body">
                <form id="paymentForm">        
                  <div class="row">
                    <div class="col-lg-12">
                      <label for="acad_year">Select Academic Year</label>
                      <select id="acad_year" class="form-control" onchange="loading_fees();">
                          <?php 
                            $select_ay = "SELECT * FROM `tblacademic_years` ORDER by ay DESC";
                            $runselect_ay = mysqli_query($conn, $select_ay);
                            while($row_ay = mysqli_fetch_assoc($runselect_ay)){
                              echo '<option value="'.$row_ay['acayearid'].'">'.$row_ay['ay'].'</option>';
                            }
                          ?>
                      </select>
                    </div>
                    <div class="col-lg-8">
                      <label for="fee_type">Fees Description</label>
                      <input type="text" class="form-control" id="fee_type">
                    </div>   
                    <div class="col-lg-4">
                      <label for="fee_amount">Fees Amount</label>
                      <input type="text" class="form-control" id="fee_amount">
                    </div>                 
                  </div>
                </form>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                <button onclick="saving_fee_amount();" type="button" class="btn btn-primary btn-sm">Saving Fees</button>
              </div>
              <div class="modal-body">
                <div class="row">
                    <div class="col-lg-12">
                        <div id="main_data">
                          <div id="loader_students_fee" class="text-center" style="display: none;">
                            <img src="../loader.gif" alt="Loading..." width="10%">
                          </div>
                          <div id="loading_students_fee">Loading fees</div>
                        </div> 
                    </div>
                </div>
              </div>

            </div>
          </div>
        </div>

        <div class="modal fade" id="modal_student_search" tabindex="-1" aria-labelledby="searchModalLabel" aria-hidden="true">
          <div class="modal-dialog modal-lg">
            <div class="modal-content shadow">
              <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="searchModalLabel">Search Student</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>

              <div class="modal-body">
                <input type="text" id="student_search_input" class="form-control" placeholder="Enter student name or ID"        autocomplete="off"
               autocorrect="off"
               spellcheck="false"
               aria-autocomplete="none">
                <div id="search_result" class="mt-3" style="max-height: 300px; overflow-y: auto;"></div>
              </div>

              <div class="modal-footer">
                <button class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
              </div>
            </div>
          </div>
        </div>


      <div class="modal fade" id="modal_payment" tabindex="-1" aria-labelledby="paymentModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
          <div class="modal-content shadow">
            <div class="modal-header bg-success text-white">
              <h5 class="modal-title" id="paymentModalLabel">Process Payment</h5>
              <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
              <div class="row">
                <!-- LEFT: Profile -->
                <div class="col-lg-4 border-end">
                  <div class="text-center" id="loading_student_info">
                    <!-- student image and info here -->
                  </div>
                </div>

                <!-- RIGHT: Payment Processing -->
                <div class="col-lg-8">
                  <input type="hidden" id="pay_studentid" readonly>
                  <div id="payment_content">
                    <!-- Payment form loads here via AJAX -->
                  </div>
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <button class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
            </div>
          </div>
        </div>
      </div>

      <div class="modal fade" id="modal_payment_preview" tabindex="-1" aria-labelledby="paymentModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
          <div class="modal-content shadow">
            <div class="modal-header bg-warning text-white">
              <h5 class="modal-title" id="paymentModalLabel">Payment Preview</h5>
              <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" onclick="modal_payment_preview_close()"></button>
            </div>
            <div class="modal-body">
              <div id="payment_previews"></div>
            </div>
            <div class="modal-footer">
              <button class="btn btn-secondary btn-sm" onclick="modal_payment_preview_close()" data-bs-dismiss="modal">Close</button>

            </div>
          </div>
        </div>
      </div>

      <div class="modal fade" id="modal_payment_browse" tabindex="-1" aria-labelledby="paymentModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
          <div class="modal-content shadow">
            <div class="modal-header bg-info text-white">
              <h5 class="modal-title" id="paymentModalLabel">Payment Preview</h5>
              <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
              <div id="payment_browse_details"></div>
            </div>
            <div class="modal-footer">
              <button class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
            </div>
          </div>
        </div>
      </div>

      <div class="modal fade" id="modal_manage_settings" tabindex="-1" aria-labelledby="paymentModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
          <div class="modal-content shadow">
            <div class="modal-header bg-info text-white">
              <h5 class="modal-title" id="paymentModalLabel">Manage Academic Year</h5>
              <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
              <div class="row">
                
              </div>
              <div id="get_aca_year_settings"></div>
            </div>
            <div class="modal-footer">
              <button class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
            </div>
          </div>
        </div>
      </div>    

      <div class="modal fade" id="modal_manage_history" tabindex="-1" aria-labelledby="paymentModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
          <div class="modal-content shadow">
            <div class="modal-header bg-info text-white">
              <h5 class="modal-title" id="paymentModalLabel">Manage Academic Year</h5>
              <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" onclick="close_payment_print();"></button>
            </div>
            <div class="modal-body">
              <div class="row">
                
              </div>
              <div id="get_selected_print"></div>
            </div>
            <div class="modal-footer">
              <button class="btn btn-secondary btn-sm" data-bs-dismiss="modal" onclick="close_payment_print();">Close</button>
            </div>
          </div>
        </div>
      </div>

      <div class="modal fade" id="modal_print_preview" tabindex="-1" aria-labelledby="paymentModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
          <div class="modal-content shadow">
            <div class="modal-header bg-info text-white">
              <h5 class="modal-title" id="paymentModalLabel">Ready to Print</h5>
              <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" onclick="close_payment_print();"></button>
            </div>
            <div class="modal-body">
              <div class="row">
                
              </div>
              <div id="get_selected_final_print"></div>
            </div>
            <div class="modal-footer">
              <button class="btn btn-secondary btn-sm" data-bs-dismiss="modal" onclick="close_payment_print();">Close</button>
            </div>
          </div>
        </div>
      </div>

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
  <script src="../assets/vendor/bootstrap/js/jquery-3.6.0.min.js"></script>  
  <script src="../assets/vendor/bootstrap/js/bootstrap5.bundle.min.js"></script>


  <script src="../assets/vendor/simple-datatables/simple-datatables.js"></script>
  <script src="../assets/vendor/tinymce/tinymce.min.js"></script>
  <script src="../assets/vendor/php-email-form/validate.js"></script>
  <script src="../assets/vendor/bootstrap/js/boxicons.js"></script>  
  <!-- <script src="https://unpkg.com/boxicons@2.1.4/dist/boxicons.js"></script> -->
  <script src="../assets/vendor/bootstrap/js/jquery.dataTables.min.js"></script>  
  <script src="../assets/vendor/bootstrap/js/dataTables.bootstrap5.min.js"></script>

  <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
  <script src="../assets/js/main.js"></script>
  <script src="../assets/sweetalert2.js"></script>

  <script>



  </script>

</body>

</html>