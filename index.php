<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>BTSLife Inc · School Registration & Management</title>
  <link rel="icon" href="assets/img/logo.png" />

  <!-- Bootstrap 5 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Font Awesome (icons) -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet" />
  <!-- SweetAlert2 -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <!-- jQuery (latest) -->
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

  <style>
    :root{
      --brand:#0d6efd;
      --brand-2:#7028e4;
      --brand-3:#e5b2ca;
    }
    body{ background: #f7f9fc; }
    .navbar{ box-shadow: 0 6px 16px rgba(13, 110, 253, .08); }
    .brand-badge{
      font-weight:700; letter-spacing:.5px; color:#0d6efd;
    }

    /* Hero */
    .hero{
      position: relative; overflow: hidden;
      border-radius: 1.25rem; /* rounded-2xl */
      background: radial-gradient(1200px 500px at 0% 0%, rgba(13,110,253,.12), transparent),
                  linear-gradient(135deg, rgba(13,110,253,.12), rgba(112,40,228,.10));
      backdrop-filter: blur(2px);
    }
    .hero .halo{
      position:absolute; inset:auto -20% -40% -20%; height:60%;
      background: radial-gradient(60% 60% at 50% 50%, rgba(13,110,253,.15), transparent 70%);
      filter: blur(45px);
    }
    .card-hover{
      transition: transform .2s ease, box-shadow .2s ease;
    }
    .card-hover:hover{
      transform: translateY(-4px);
      box-shadow: 0 10px 30px rgba(13,110,253,.12);
    }
    .chip{
      display:inline-flex; align-items:center; gap:.5rem;
      padding:.35rem .75rem; border-radius:999px; font-size:.87rem; font-weight:600;
      background:#eef4ff; color:#0d6efd;
    }
    .stat{ border-radius:1rem; background:white; box-shadow:0 10px 24px rgba(0,0,0,.04); }

    .footer{ color:#6c757d; }
  </style>
</head>
<body>
  <!-- Top Nav -->
  <nav class="navbar navbar-expand-lg bg-white sticky-top">
    <div class="container py-2">
      <a class="navbar-brand d-flex align-items-center gap-2" href="#">
        <img src="assets/img/logo.png" width="40" height="40" alt="BTSLife" onerror="this.style.display='none'">
        <span class="brand-badge">BTSLife Inc.</span>
      </a>

      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="mainNav">
        <ul class="navbar-nav ms-auto align-items-lg-center">
          <li class="nav-item px-lg-2"><a class="nav-link active" href="#home">Home</a></li>
          <li class="nav-item px-lg-2"><a class="nav-link" href="#features">Features</a></li>
          <li class="nav-item px-lg-2"><a class="nav-link" href="#stats">Stats</a></li>
          <li class="nav-item px-lg-2"><a class="nav-link" href="#contact">Contact</a></li>
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
          <span class="chip mb-3"><i class="fa-solid fa-shield"></i> Secure · Fast · Registrar-friendly</span>
          <h1 class="display-5 fw-bold mb-3">School Registration & Payments—made simple.</h1>
          <p class="lead text-secondary mb-4">
            Handle student registration, enrollment, and collections in one clean interface for Elementary & High School.
            Built with Bootstrap 5, jQuery (AJAX), and SweetAlert2.
          </p>
          <div class="d-flex gap-2 gap-lg-3">
            <a href="#features" class="btn btn-primary btn-lg rounded-pill px-4">
              <i class="fa-solid fa-rocket me-2"></i> Get Started
            </a>
            <a href="#demo" class="btn btn-outline-primary btn-lg rounded-pill px-4" id="btnQuickDemo">
              <i class="fa-regular fa-circle-play me-2"></i> Quick Demo
            </a>
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
                  <div class="fw-bold fs-4" id="statEnrolled">0</div>
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
              <small class="text-secondary">Live data via AJAX once endpoints are ready.</small>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Feature Cards -->
  <section id="features" class="container my-5">
    <div class="text-center mb-4">
      <h2 class="fw-bold">Core Modules</h2>
      <p class="text-secondary mb-0">Click a card to begin. These will route to module pages later.</p>
    </div>
    <div class="row g-4">
      <div class="col-md-6 col-lg-3">
        <a href="modules/students/index.php" class="text-decoration-none">
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
        <a href="modules/enrollment/index.php" class="text-decoration-none">
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
        <a href="modules/payments/index.php" class="text-decoration-none">
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
        <a href="modules/reports/index.php" class="text-decoration-none">
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
          <div class="display-6 fw-bold" id="statElem">0</div>
          <small class="text-secondary">Enrolled Students (S.Y. 2025–2026)</small>
        </div>
      </div>
      <div class="col-md-4">
        <div class="stat p-4">
          <div class="d-flex align-items-center mb-2"><i class="fa-solid fa-user-graduate me-2 text-primary"></i><strong>High School</strong></div>
          <div class="display-6 fw-bold" id="statHS">0</div>
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
        <h4 class="fw-bold mb-1">Need help or a new feature?</h4>
        <p class="text-secondary mb-0">Contact the Registrar/Cashier or send a ticket to the BTSLife IT Desk.</p>
      </div>
      <div class="col-md-4 text-md-end">
        <a href="#" class="btn btn-outline-primary rounded-pill"><i class="fa-regular fa-envelope me-2"></i> Open Support Ticket</a>
      </div>
    </div>
  </section>

  <footer class="py-4 border-top">
    <div class="container d-flex justify-content-between align-items-center footer">
      <small>© <span id="yr"></span> BTSLife Inc. All rights reserved.</small>
      <small>Built with Bootstrap 5 · jQuery · SweetAlert2</small>
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
              <label class="form-label">Username or Email</label>
              <input type="text" class="form-control" id="login_user" required>
            </div>
            <div class="mb-3">
              <label class="form-label">Password</label>
              <div class="input-group">
                <input type="password" class="form-control" id="login_pass" required>
                <button class="btn btn-outline-secondary" type="button" id="togglePass"><i class="fa-regular fa-eye"></i></button>
              </div>
            </div>
            <div class="form-check">
              <input class="form-check-input" type="checkbox" value="1" id="remember_me">
              <label class="form-check-label" for="remember_me">Remember me</label>
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
    $('#formLogin').on('submit', function(e){
      e.preventDefault();
      const user = $('#login_user').val().trim();
      const pass = $('#login_pass').val().trim();

      if(!user || !pass){
        return Swal.fire({ icon:'warning', title:'Missing fields', text:'Please enter your credentials.' });
      }

      // TODO: replace with real endpoint
      // $.post('modules/auth/login.php', { user, pass }, function(res){ ... }, 'json');

      // Temporary success state for UI testing
      Swal.fire({
        title: 'Login successful (UI demo)',
        text: 'Replace with real authentication and redirect to dashboard.',
        icon: 'success'
      }).then(()=>{
        // location.href = 'dashboard.php';
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
  </script>
</body>
</html>
