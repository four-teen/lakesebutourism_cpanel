<?php 
    // Keep your original PHP block
    session_start();
    ob_start();

    include 'db.php';
    // include_once 'logger.php';

    // // Log page load + session info
    // log_event("Index page loaded. Session ID: " . session_id());


?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>BTESLife Inc · MIS</title>
  <link rel="icon" href="assets/img/logo.png" />

  <!-- Bootstrap 5 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Font Awesome (icons) -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet" />

  <link rel="stylesheet" href="css/mycss.css">

  <style>

  </style>
</head>
<body>
  <!-- Top Nav -->
  <nav class="navbar navbar-expand-lg bg-white sticky-top">
    <div class="container py-2">
      <a class="navbar-brand d-flex align-items-center gap-2" href="#">
        <img src="assets/img/logo.png" width="40" height="40" alt="BTESLife" onerror="this.style.display='none'">
        <span class="brand-badge">BTESLife Inc. MIS</span>
      </a>

      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="mainNav">
        <ul class="navbar-nav ms-auto align-items-lg-center">
<!--           <li class="nav-item px-lg-2"><a class="nav-link active" href="#home">Home</a></li>
          <li class="nav-item px-lg-2"><a class="nav-link" href="#features">Features</a></li>
          <li class="nav-item px-lg-2"><a class="nav-link" href="#stats">Stats</a></li>
          <li class="nav-item px-lg-2"><a class="nav-link" href="#contact">Contact</a></li> -->
          <li class="nav-item ps-lg-3 mt-2 mt-lg-0">
            <button class="btn btn-primary rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#loginModal">
              <i class="fa-solid fa-right-to-bracket me-2"></i> Secure Login
            </button>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <!-- Hero -->
  <section id="home" class="container my-4 my-lg-5">
    <div class="hero p-4 p-lg-5">
      <div class="halo"></div>
      <div class="row align-items-center g-4">
        <div class="col-lg-7">
          <span class="chip mb-3"><i class="fa-solid fa-shield"></i> Secure · Fast · Friendly</span>
          <h1 class="display-5 fw-bold mb-3">School Registration & Payments - made simple.</h1>
          <p class="lead text-secondary mb-4">
            Handle student registration, enrollment, and collections in one clean interface for Elementary & High School.
          </p>
<div>
            <button class="btn btn-warning rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#loginModal">
              <i class="fa-solid fa-right-to-bracket me-2"></i> Secure Login
            </button>  
</div>
        </div>
        <div class="col-lg-5">
          <div class="bg-white rounded-4 p-4 shadow-sm">
            <div class="d-flex align-items-center mb-3">
              <i class="fa-solid fa-chart-line fs-3 text-primary me-2"></i>
              <h5 class="mb-0">Today at a glance</h5>
            </div>
            <div class="row text-center g-3">
              <div class="col-4">
                <div class="p-3 rounded-3 border bg-light-subtle">
                  <div class="fw-bold fs-4" id="statNew">0</div>
                  <small class="text-secondary">New Reg</small>
                </div>
              </div>
              <div class="col-4">
                <div class="p-3 rounded-3 border bg-light-subtle">
                  <div class="fw-bold fs-4" id="statEnrolled">
                    <?php 
                      $enrolled_count = "SELECT COUNT(autoid) as student_count FROM `tblstudents`";
                      $runenrolled = mysqli_query($conn, $enrolled_count);
                      if($runenrolled){
                        $row_student_count = mysqli_fetch_assoc($runenrolled);
                        echo $row_student_count['student_count'];
                      }
                    ?>
                  </div>
                  <small class="text-secondary">Enrolled</small>
                </div>
              </div>
              <div class="col-4">
                <div class="p-3 rounded-3 border bg-light-subtle">
                  <div class="fw-bold fs-4" id="statPaid">₱0</div>
                  <small class="text-secondary">Collections</small>
                </div>
              </div>
            </div>
            <div class="text-center mt-3">
              <small class="text-secondary"></small>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Feature Cards -->
  <section id="features" class="container my-5">
    <div class="text-center mb-4">
      <h2 class="fw-bold"></h2>
      <p class="text-secondary mb-0"></p>
    </div>
    <div class="row g-4">
      <div class="col-md-6 col-lg-3">
        <a href="#" class="text-decoration-none">
          <div class="card card-hover h-100">
            <div class="card-body p-4">
              <i class="fa-solid fa-user-plus fa-2x text-primary"></i>
              <h5 class="mt-3 mb-1 text-dark">Registration</h5>
              <p class="text-secondary mb-0">Create student profiles, upload requirements, auto-ID.</p>
            </div>
          </div>
        </a>
      </div>
      <div class="col-md-6 col-lg-3">
        <a href="#" class="text-decoration-none">
          <div class="card card-hover h-100">
            <div class="card-body p-4">
              <i class="fa-solid fa-list-check fa-2x text-primary"></i>
              <h5 class="mt-3 mb-1 text-dark">Enrollment</h5>
              <p class="text-secondary mb-0">Assign grade/section, auto-load subjects per level.</p>
            </div>
          </div>
        </a>
      </div>
      <div class="col-md-6 col-lg-3">
        <a href="#" class="text-decoration-none">
          <div class="card card-hover h-100">
            <div class="card-body p-4">
              <i class="fa-solid fa-peso-sign fa-2x text-primary"></i>
              <h5 class="mt-3 mb-1 text-dark">Payments</h5>
              <p class="text-secondary mb-0">Post payments, OR no., partials, balances, reports.</p>
            </div>
          </div>
        </a>
      </div>
      <div class="col-md-6 col-lg-3">
        <a href="#" class="text-decoration-none">
          <div class="card card-hover h-100">
            <div class="card-body p-4">
              <i class="fa-solid fa-chart-pie fa-2x text-primary"></i>
              <h5 class="mt-3 mb-1 text-dark">Reports</h5>
              <p class="text-secondary mb-0">Enrollment & collection dashboards and exports.</p>
            </div>
          </div>
        </a>
      </div>
    </div>
  </section>

  <!-- Quick Stats (placeholders) -->
  <section id="stats" class="container my-5">
    <div class="row g-3">
      <div class="col-md-4">
        <div class="stat p-4">
          <div class="d-flex align-items-center mb-2"><i class="fa-solid fa-children me-2 text-primary"></i><strong>Elementary</strong></div>
          <div class="display-6 fw-bold" id="statElem">
            <?php 
               $elem_count = "SELECT COUNT(autoid) as student_count FROM `tblstudents`
              WHERE grade_level < 7";
              $runelem = mysqli_query($conn, $elem_count);
              if($runelem){
                $row_student_count = mysqli_fetch_assoc($runelem);
                echo $row_student_count['student_count'];
              }
            ?>
          </div>
          <small class="text-secondary">Enrolled Students (S.Y. 2025–2026)</small>
        </div>
      </div>
      <div class="col-md-4">
        <div class="stat p-4">
          <div class="d-flex align-items-center mb-2"><i class="fa-solid fa-user-graduate me-2 text-primary"></i><strong>High School</strong></div>
          <div class="display-6 fw-bold" id="statHS">
            <?php 
              $highschool_count = "SELECT COUNT(autoid) as student_count FROM `tblstudents`
              WHERE grade_level >= 7";
              $runhighschool = mysqli_query($conn, $highschool_count);
              if($runhighschool){
                $row_student_count = mysqli_fetch_assoc($runhighschool);
                echo $row_student_count['student_count'];
              }
            ?>
          </div>
          <small class="text-secondary">Enrolled Students (S.Y. 2025–2026)</small>
        </div>
      </div>
      <div class="col-md-4">
        <div class="stat p-4">
          <div class="d-flex align-items-center mb-2"><i class="fa-solid fa-sack-dollar me-2 text-primary"></i><strong>Total Collections</strong></div>
          <div class="display-6 fw-bold" id="statCollections">₱0</div>
          <small class="text-secondary">Since June 1, 2025</small>
        </div>
      </div>
    </div>
  </section>

  <!-- Contact / Footer -->
  <section id="contact" class="container my-5">
    <div class="row align-items-center g-3">
      <div class="col-md-8">
        <h4 class="fw-bold mb-1">Need help?</h4>
        <p class="text-secondary mb-0">Contact the Administrator or contact BTESLife IT Desk.</p>
      </div>
      <div class="col-md-4 text-md-end">
      </div>
    </div>
  </section>

  <footer class="py-4 border-top">
    <div class="container d-flex justify-content-between align-items-center footer">
      <small>© <span id="yr"></span> BTESLife Inc. All rights reserved.</small>
      <small>Developed by EOA | MGIS</small>
    </div>
  </footer>

  <!-- Login Modal -->
  <div class="modal fade" id="loginModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content border-0 shadow">
        <div class="modal-header">
          <h5 class="modal-title"><i class="fa-solid fa-lock me-2 text-primary"></i>Secure Login</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form id="formLogin" autocomplete="off">
          <div class="modal-body">
            <div class="mb-3">
              <label class="form-label">Username</label>
              <input type="text" class="form-control" id="login_user" required autocomplete="off">
            </div>
            <div class="mb-3">
              <label class="form-label">Password</label>
              <div class="input-group">
                <input type="password" class="form-control" id="login_pass" required>
                <button class="btn btn-outline-secondary" type="button" id="togglePass"><i class="fa-regular fa-eye"></i></button>
              </div>
            </div>
            <div class="mb-3">
              <label class="form-label">User Type</label>
              <select id="login_user_type" class="form-select" required>
                <?php 
                  $getype = "SELECT * FROM `tblaccount_type` ORDER BY type_name";
                  $rungetype = mysqli_query($conn, $getype);
                  while($rowtype = mysqli_fetch_assoc($rungetype)){
                    echo '<option value="'.$rowtype['type_id'].'">'.$rowtype['type_name'].'</option>';
                  }
                ?>
              </select>
            </div>

          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Login</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <!-- SweetAlert2 -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <!-- jQuery (latest) -->
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

  <script>
    // YEAR
    document.getElementById('yr').textContent = new Date().getFullYear();

    // Toggle password visibility
    $('#togglePass').on('click', function(){
      const inp = $('#login_pass');
      const type = inp.attr('type') === 'password' ? 'text' : 'password';
      inp.attr('type', type);
      $(this).find('i').toggleClass('fa-eye fa-eye-slash');
    });

    // Quick Demo button
    $('#btnQuickDemo').on('click', function(e){
      e.preventDefault();
      Swal.fire({
        title: 'Heads up! Demo only',
        text: 'Buttons and stats are wired for real data once endpoints are connected (AJAX/PHP).',
        icon: 'info',
        confirmButtonText: 'Got it'
      });
    });

    // Login (AJAX-ready placeholder)
    $('#formLogin').off('submit').on('submit', function(e){
      e.preventDefault();
      const user    = $('#login_user').val().trim();
      const pass    = $('#login_pass').val().trim();
      const type_id = $('#login_user_type').val();

      if(!user || !pass || !type_id){
        return Swal.fire({ icon:'warning', title:'Missing fields', text:'Please complete all fields.' });
      }

      $.post('modules/auth/login.php', { user, pass, type_id }, function(r){
        if (r.ok) {
          Swal.fire({ icon:'success', title:'Welcome!', text:`Logged in as ${r.role}`, timer: 900, showConfirmButton: false })
          .then(()=> window.location.href = r.redirect);
        } else {
          Swal.fire({ icon:'error', title:'Login failed', text: r.msg || 'Invalid credentials.' });
        }
      }, 'json').fail(function(xhr){
        Swal.fire({ icon:'error', title:'Server error', text: xhr.responseJSON?.msg || 'Please try again.' });
      });
    });

    // Example: load dashboard stats (AJAX placeholder)
    function loadDashboardStats(){
      // $.getJSON('modules/reports/get_dashboard_stats.php', function(d){
      //   $('#statNew').text(d.new_reg || 0);
      //   $('#statEnrolled').text(d.enrolled || 0);
      //   $('#statPaid').text('₱'+(d.collections || 0).toLocaleString());
      //   $('#statElem').text(d.elem || 0);
      //   $('#statHS').text(d.hs || 0);
      //   $('#statCollections').text('₱'+(d.total_collections || 0).toLocaleString());
      // });
    }
    loadDashboardStats();
    $('#loginModal').on('shown.bs.modal', function () {
      $('#login_user').trigger('focus');
    });    
  </script>
</body>
</html>
