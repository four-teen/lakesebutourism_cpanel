<?php
  session_start();
  require_once __DIR__ . '/../modules/auth/session_guard.php';
  require_role(['Owner']); // only admins can access

  include_once __DIR__ . '/../db.php';
  // include_once __DIR__ . '/../logger.php';
  // log_event("ADMIN DASHBOARD: accessed by {$_SESSION['USERNAME']} ({$_SESSION['TYPE']})");


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
            <div class="col-lg-3" style="cursor: pointer;" onclick="manage_the_owners();">
              <div class="card info-card sales-card h-100">
                <div class="card-body">
                  <h5 class="card-title">Manage Users <span>| All</span></h5>

                  <div class="d-flex align-items-center">
                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                      <i class='bx bx-user'></i>
                    </div>
                    <div class="ps-3">
                      <h6 id="load_owner_count">
                        <?php 
                          $get_owner_count = "SELECT count(usersautoid) as owner_count FROM `tblowners`";
                          $runget_count = mysqli_query($conn, $get_owner_count);
                          if($runget_count){
                            $rowcount = mysqli_fetch_assoc($runget_count);
                            echo $rowcount['owner_count'];
                          }

                        ?>
                      </h6>
                      <span class="text-success small pt-1 fw-bold"></span> <span class="text-muted small pt-2 ps-1">owner record</span>

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

                <div class="card-body">
                  <h5 class="card-title">Reports</h5>
                  <div id="main_data"></div>
                      
 
<!-- VISITOR RECORDING FORM -->
<div class="container-fluid">
  <form id="visitorForm" class="needs-validation" novalidate>
    <!-- Header -->
    <div class="row align-items-center mb-3">
      <div class="col">
        <h4 class="mb-0">New Visitor / Check-In</h4>
        <small class="text-muted">Lake Sebu Establishments IMS</small>
      </div>
      <div class="col-auto">
        <span class="badge badge-pill badge-primary px-3 py-2">Step 1–4</span>
      </div>
    </div>

    <!-- Step 1: Booking Details -->
    <div class="card shadow-sm mb-3">
      <div class="card-header bg-primary text-white">
        <strong>1) Booking Details</strong>
      </div>
      <div class="card-body">
        <div class="form-row">
          <div class="form-group col-md-4">
            <label for="establishment_id">Establishment</label>
            <select class="form-control" id="establishment_id" name="establishment_id" required>
              <option value="">Select establishment…</option>
              <!-- fill from DB -->
            </select>
            <div class="invalid-feedback">Please select an establishment.</div>
          </div>
          <div class="form-group col-md-4">
            <label for="purpose">Purpose of Visit</label>
            <select class="form-control" id="purpose" name="purpose" required>
              <option value="">Select…</option>
              <option>Leisure</option><option>Business</option>
              <option>Event</option><option>LGU/Official</option>
              <option>Others</option>
            </select>
            <div class="invalid-feedback">Required.</div>
          </div>
          <div class="form-group col-md-4">
            <label for="source">Booking Source</label>
            <select class="form-control" id="source" name="source">
              <option>Walk-in</option><option>Phone</option>
              <option>Facebook</option><option>OTA</option>
              <option>Referral</option>
            </select>
          </div>
        </div>

        <div class="form-row">
          <div class="form-group col-md-3">
            <label for="check_in">Check-in</label>
            <input type="datetime-local" class="form-control" id="check_in" name="check_in" required>
            <div class="invalid-feedback">Required.</div>
          </div>
          <div class="form-group col-md-3">
            <label for="check_out">Check-out</label>
            <input type="datetime-local" class="form-control" id="check_out" name="check_out" required>
            <div class="invalid-feedback">Required.</div>
          </div>
          <div class="form-group col-md-2">
            <label for="adults">Adults</label>
            <input type="number" min="1" class="form-control" id="adults" name="adults" value="1" required>
          </div>
          <div class="form-group col-md-2">
            <label for="children">Children</label>
            <input type="number" min="0" class="form-control" id="children" name="children" value="0">
          </div>
          <div class="form-group col-md-2">
            <label>Nights</label>
            <input type="text" class="form-control" id="nights" readonly value="0">
          </div>
        </div>

        <div class="form-row">
          <div class="form-group col-md-6">
            <label for="origin_city">Origin (City/Province)</label>
            <input type="text" class="form-control" id="origin_city" name="origin_city" placeholder="e.g., GenSan, South Cotabato">
          </div>
          <div class="form-group col-md-3">
            <label for="origin_country">Country</label>
            <input type="text" class="form-control" id="origin_country" name="origin_country" value="Philippines">
          </div>
          <div class="form-group col-md-3">
            <label for="vehicle">Vehicle Plate (optional)</label>
            <input type="text" class="form-control" id="vehicle" name="vehicle">
          </div>
        </div>
      </div>
    </div>

    <!-- Step 2: Guest Details -->
    <div class="card shadow-sm mb-3">
      <div class="card-header bg-success text-white">
        <strong>2) Primary Guest</strong>
      </div>
      <div class="card-body">
        <div class="form-row">
          <div class="form-group col-md-3">
            <label for="first_name">First Name</label>
            <input type="text" class="form-control" id="first_name" name="first_name" required>
            <div class="invalid-feedback">Required.</div>
          </div>
          <div class="form-group col-md-3">
            <label for="last_name">Last Name</label>
            <input type="text" class="form-control" id="last_name" name="last_name" required>
            <div class="invalid-feedback">Required.</div>
          </div>
          <div class="form-group col-md-2">
            <label for="sex">Sex</label>
            <select class="form-control" id="sex" name="sex">
              <option value="">Select…</option><option>M</option><option>F</option><option>X</option>
            </select>
          </div>
          <div class="form-group col-md-2">
            <label for="birthdate">Birthdate</label>
            <input type="date" class="form-control" id="birthdate" name="birthdate">
          </div>
          <div class="form-group col-md-2">
            <label for="nationality">Nationality</label>
            <input type="text" class="form-control" id="nationality" name="nationality" value="Filipino">
          </div>
        </div>

        <div class="form-row">
          <div class="form-group col-md-4">
            <label for="id_type">ID Type</label>
            <select class="form-control" id="id_type" name="id_type">
              <option value="">Select…</option>
              <option>Passport</option><option>Driver’s License</option>
              <option>UMID</option><option>National ID</option>
            </select>
          </div>
          <div class="form-group col-md-4">
            <label for="id_number">ID Number</label>
            <input type="text" class="form-control" id="id_number" name="id_number">
          </div>
          <div class="form-group col-md-4">
            <label for="email">Email (optional)</label>
            <input type="email" class="form-control" id="email" name="email">
          </div>
        </div>

        <div class="form-row">
          <div class="form-group col-md-4">
            <label for="phone">Mobile</label>
            <input type="text" class="form-control" id="phone" name="phone" required>
            <div class="invalid-feedback">Mobile is required.</div>
          </div>
          <div class="form-group col-md-8">
            <label for="address_text">Address</label>
            <input type="text" class="form-control" id="address_text" name="address_text" placeholder="House/Street, Barangay, City/Province">
          </div>
        </div>
      </div>
    </div>

    <!-- Step 3: Room Assignment -->
    <div class="card shadow-sm mb-3">
      <div class="card-header bg-info text-white">
        <strong>3) Room Assignment</strong>
      </div>
      <div class="card-body">
        <div class="form-row">
          <div class="form-group col-md-4">
            <label for="room_id">Room</label>
            <select class="form-control" id="room_id" name="room_id" required>
              <option value="">Select available room…</option>
              <!-- populate by AJAX based on establishment & dates -->
            </select>
            <div class="invalid-feedback">Select a room.</div>
          </div>
          <div class="form-group col-md-3">
            <label for="room_type">Room Type</label>
            <input type="text" class="form-control" id="room_type" name="room_type" readonly>
          </div>
          <div class="form-group col-md-2">
            <label for="capacity">Capacity</label>
            <input type="text" class="form-control" id="capacity" name="capacity" readonly>
          </div>
          <div class="form-group col-md-3">
            <label for="rate_per_night">Rate / Night (₱)</label>
            <input type="number" min="0" step="0.01" class="form-control" id="rate_per_night" name="rate_per_night" required>
          </div>
        </div>

        <div class="form-row">
          <div class="form-group col-md-3">
            <label for="tax">Tax/Fees (₱)</label>
            <input type="number" min="0" step="0.01" class="form-control" id="tax" name="tax" value="0">
          </div>
          <div class="form-group col-md-3">
            <label for="discount">Discount (₱)</label>
            <input type="number" min="0" step="0.01" class="form-control" id="discount" name="discount" value="0">
          </div>
          <div class="form-group col-md-3">
            <label>Guest-Nights</label>
            <input type="text" class="form-control" id="guest_nights" readonly value="0">
          </div>
          <div class="form-group col-md-3">
            <label>Est. Bill (₱)</label>
            <input type="text" class="form-control font-weight-bold" id="estimated_bill" readonly value="0.00">
          </div>
        </div>
      </div>
    </div>

    <!-- Step 4: Payment & Consent -->
    <div class="card shadow-sm mb-4">
      <div class="card-header bg-warning">
        <strong>4) Payment & Consent</strong>
      </div>
      <div class="card-body">
        <div class="form-row">
          <div class="form-group col-md-3">
            <label for="payment_method">Payment Method</label>
            <select class="form-control" id="payment_method" name="payment_method">
              <option>Cash</option><option>GCash</option><option>Card</option><option>Bank</option>
            </select>
          </div>
          <div class="form-group col-md-3">
            <label for="payment_ref">Reference No. (if any)</label>
            <input type="text" class="form-control" id="payment_ref" name="payment_ref">
          </div>
          <div class="form-group col-md-3">
            <label for="amount_paid">Amount Paid (₱)</label>
            <input type="number" min="0" step="0.01" class="form-control" id="amount_paid" name="amount_paid" value="0">
          </div>
          <div class="form-group col-md-3">
            <label>Balance (₱)</label>
            <input type="text" class="form-control" id="balance" readonly value="0.00">
          </div>
        </div>

        <div class="custom-control custom-checkbox">
          <input type="checkbox" class="custom-control-input" id="privacy_consent" name="privacy_consent" required checked>
          <label class="custom-control-label" for="privacy_consent">
            I agree to the Privacy Notice and consent to the collection and processing of my data.
          </label>
          <div class="invalid-feedback d-block">Consent is required to proceed.</div>
        </div>
      </div>
      <div class="card-footer text-right">
        <button type="reset" class="btn btn-outline-secondary">Clear</button>
        <button type="submit" class="btn btn-primary">Save & Check-In</button>
      </div>
    </div>
  </form>
</div>
 


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
      &copy; Copyright <strong><span><?php echo $_SESSION['title'] ?></span></strong>. All Rights Reserved
    </div>
    <div class="credits">
      Powered by <a href="#"><?php echo $_SESSION['footer'] ?></a>
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


    function manage_the_owners(){
      window.location = 'owner_management/manage_owner.php';
    }



  </script>

</body>

</html>