<?php
  session_start();
  require_once __DIR__ . '/../modules/auth/session_guard.php';
  require_role(['Administrator']); // only admins can access

  include_once __DIR__ . '/../db.php';
  include_once __DIR__ . '/../logger.php';
  log_event("ADMIN DASHBOARD: accessed by {$_SESSION['USERNAME']} ({$_SESSION['TYPE']})");


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

  <title><?php echo $_SESSION['title'] ?>Admin Dashboard</title>
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

<body onload="get_ay();">



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
            <div class="col-lg-3" style="cursor: pointer;" onclick="enrolment_student_records();">
              <div class="card info-card sales-card h-100">
                <div class="card-body">
                  <h5 class="card-title">Manage Users <span>| All</span></h5>

                  <div class="d-flex align-items-center">
                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                      <i class='bx bx-user'></i>
                    </div>
                    <div class="ps-3">
                      <h6 id="load_student_count">
                        <?php 
                          $get_student_count = "SELECT count(autoid) as stud_count FROM `tblstudents`";
                          $runget_count = mysqli_query($conn, $get_student_count);
                          if($runget_count){
                            $rowcount = mysqli_fetch_assoc($runget_count);
                            echo $rowcount['stud_count'];
                          }

                        ?>
                      </h6>
                      <span class="text-success small pt-1 fw-bold"></span> <span class="text-muted small pt-2 ps-1">users record</span>

                    </div>
                  </div>
                </div>

              </div>
            </div><!-- End Sales Card -->

            <div class="col-lg-3" onclick="loading_user_accounts();">
              <div class="card info-card sales-card h-100">
                    <a href="#" style="text-decoration: none; color: inherit;">
                      <div class="card-body">
                        <h5 class="card-title">Manage Accounts <span>| All</span></h5>
                        <div class="d-flex align-items-center">
                          <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                            <i class='bx  bx-key'  ></i> 
                          </div>
                          <div class="ps-3">
                            <h6 id="get_bio_logs">
                              <?php 
                                $getaccount = "SELECT count(acc_id) as acc_count FROM `tblaccounts`";
                                $rungetacount = mysqli_query($conn, $getaccount);
                                if($rungetacount){
                                  $rowacount = mysqli_fetch_assoc($rungetacount);
                                  echo $rowacount['acc_count'];
                                }
                              ?>
                            </h6>
                            <span class="text-danger small pt-1 fw-bold"></span> 
                            <span class="text-muted small pt-2 ps-1">User Account</span>
                          </div>
                        </div>
                      </div>
                    </a>
              </div>
            </div>

            <!-- MANAGE SETTINGS -->
            <div class="col-lg-3">
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
                        <h5 class="card-title">FAQ <span>| Management</span></h5>
                        <div class="d-flex align-items-center">
                          <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                            <i class='bx bxs-cog'></i>
                          </div>
                          <div class="ps-3">
                            <h6 id="load_settings">0</h6>
                            <span class="text-danger small pt-1 fw-bold"></span> 
                            <span class="text-muted small pt-2 ps-1">FAQ records</span>
                          </div>
                        </div>
                      </div>
                    </a>
              </div>

            </div>

            <div class="col-lg-3" onclick="loading_fees_modals();">
              <div class="card info-card sales-card h-100">
                    <a href="#" style="text-decoration: none; color: inherit;">
                      <div class="card-body">
                        <h5 class="card-title">Total Transactions <span></span></h5>
                        <div class="d-flex align-items-center">
                          <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                            <i class='bx bx-log-in-circle'></i>
                          </div>
                          <div class="ps-3">
                            <h6 id="get_bio_logs">0
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
<div class="d-flex justify-content-end mb-2">
  <label class="me-2">AY</label>
  <select id="ayFilter" class="form-select form-select-sm" style="min-width:180px"></select>
</div>
                  <div id="main_data"></div>
                      
 <div id="enrolmentChart"></div>


                </div>


              </div>
            </div><!-- End Reports -->


          </div>
        </div><!-- End Left side columns -->



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


      <div class="modal fade" id="modal_manage_settings" tabindex="-1" aria-labelledby="paymentModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
          <div class="modal-content shadow">
            <div class="modal-header bg-info text-white">
              <h5 class="modal-title" id="paymentModalLabel">Manage Academic Year</h5>
              <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
              <div class="row">
                <div class="col-lg-12">
                  <label for="ayids">Select Academic Year</label>
                  <select id="ayids" class="form-control">
                    <?php 
                      $settings_ay = "SELECT * FROM `tblacademic_years`";
                      $runsettings_ay = mysqli_query($conn, $settings_ay);
                      while($rowsettings_ay=mysqli_fetch_assoc($runsettings_ay)){
                        echo'<option value="'.$rowsettings_ay['ayid'].'">'.$rowsettings_ay['ayfrom'].'-'.$rowsettings_ay['ayto'].'</option>';
                      }
                    ?>
                    
                  </select>
                  
                </div>
              </div>
              <div class="row">
                <div class="col-lg-12 py-2">
                  <button onclick="update_ay()" class="btn btn-primary btn-sm">Update Academic Year</button>
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <button class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
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
  <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

  <script>

    function loading_user_accounts(){
      window.location = 'user_account/user_account.php';
    }


let enrolChart = null;

// 1) populate AY dropdown and draw chart
function initEnrolmentChart() {
  // try to get current ayid from PHP session (already set in your page)
  // const currentAy = <?= json_encode(isset($_SESSION['ayid']) ? (int)$_SESSION['ayid'] : 0) ?>;
  const currentAy = 2;

  fetchChartData(currentAy, true);
}

function fetchChartData(ay, init=false){
   $.getJSON('chart_students_by_grade.php', { ay }, function(res){
    // fill AY filter once
    if(init){
      const $sel = $('#ayFilter').empty();
      res.ay_options.forEach(o=>{
        const opt = $('<option>').val(o.ayid).text(o.ay_label);
        $sel.append(opt);
      });
      if(ay){ $('#ayFilter').val(ay); }
      // change handler
      $('#ayFilter').off('change').on('change', function(){
        fetchChartData($(this).val(), false);
      });
    }

    const options = {
      chart: { type: 'line', height: 320, toolbar: { show: false } },
      stroke: { curve: 'smooth', width: 2 },
      dataLabels: { 
        enabled: true,
        style: {
          colors: ['#FFA500']
        }
       },
      series: [{ name: 'Students', data: res.series }],
      xaxis: { categories: res.labels, title: { text: 'Grade Level' } },
      yaxis: { title: { text: 'No. of Students' }, forceNiceScale: true },
      tooltip: { y: { formatter: (v)=> v + ' student' + (v==1?'':'s') } },
      markers: { 
        size: 3 
      }
    };

    if(enrolChart){
      enrolChart.updateOptions({ xaxis: options.xaxis, yaxis: options.yaxis });
      enrolChart.updateSeries(options.series);
    }else{
      enrolChart = new ApexCharts(document.querySelector('#enrolmentChart'), options);
      enrolChart.render();
    }
  });
}

// call on page load
$(initEnrolmentChart);



    function enrolment_student_records(){
      window.location = 'student_management/student_records.php';
    }

    function update_ay(){
      var ayids = $('#ayids').val();
      Swal.fire({
        title: "Updating Academic Year?",
        text: "This will change the records appears in the dashboard!",
        icon: "info",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, proceed it!"
      }).then((result) => {
        if (result.isConfirmed) {
             $.ajax({
                type: "POST",
                url: "query_dasboard.php",
                data: {
                  "updating_ay": "1",
                  "ayids" : ayids
                },
                success: function () {
                    get_ay();
                    $('#modal_manage_settings').modal('hide');
                }
              }); 
        }
      });

     
    }

    function get_ay(){
       $.ajax({
          type: "POST",
          url: "query_dasboard.php",
          data: {
            "loading_ay": "1"
          },
          success: function (response) {
              $('#curr_ay').html(response);
          }
        }); 
       
    }

    function manage_ay(){
      $('#modal_manage_settings').modal('show');
    }


  </script>

</body>

</html>