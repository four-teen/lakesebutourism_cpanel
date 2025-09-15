<?php 
    // Keep your original PHP block
    session_start();
    ob_start();

    include 'db.php';
    $get_config = "SELECT * FROM `tblconfig`";
    $runget_config = mysqli_query($conn, $get_config);
    $row_config = mysqli_fetch_assoc($runget_config);
    $_SESSION['title'] = $row_config['system_name'];
    $_SESSION['footer'] = $row_config['system_footer'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />

  <title><?php echo $_SESSION['title'] ?> · Console</title>

  <!-- Favicons (transparent lotus) -->
  <link rel="icon" href="assets/img/logo.png" type="image/svg+xml">
  <meta name="theme-color" content="#0d6efd" />

  <!-- Bootstrap 5 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Font Awesome (existing icons) -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet" />
  <!-- Boxicons (for lotus / tourism icons) -->
  <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet" />

  <link rel="stylesheet" href="css/mycss.css">

  <style>
    :root{
      --ls-primary:#0d6efd;         /* Primary brand */
      --ls-accent:#00a19a;          /* Teal accent */
      --ls-ink:#1e2a35;
      --ls-muted:#6c7a89;
    }
    body{ color:var(--ls-ink); }

    /* Glassy navbar */
    .navbar-glass{
      background: rgba(255,255,255,.9);
      -webkit-backdrop-filter: blur(8px);
      backdrop-filter: blur(8px);
      border-bottom: 1px solid rgba(0,0,0,.06);
    }

    .brand-badge{
      font-weight:700;
      letter-spacing:.2px;
    }
    .brand-sub{
      font-size:.78rem;
      color:var(--ls-muted);
    }

    /* Hero with Lake background */
    .hero{
      position: relative;
      border-radius: 1rem;
      overflow: hidden;
      background: #f3f7fb;
      min-height: 420px;
    }
    .hero::before{
      content:"";
      position:absolute; inset:0;
      background:url('https://images.unsplash.com/photo-1469474968028-56623f02e42e?q=80&w=2400&auto=format&fit=crop') center/cover no-repeat;
      filter: saturate(1.05) contrast(0.95) brightness(0.9);
    }
    .hero::after{
      content:"";
      position:absolute; inset:0;
      background:linear-gradient(120deg, rgba(0,0,0,.45), rgba(0,0,0,.25), rgba(0,0,0,.35));
    }
    .hero .content{
      position:relative; z-index:2; color:#fff;
    }
    .chip{
      display:inline-flex; align-items:center; gap:.5rem;
      background: rgba(255,255,255,.15);
      border:1px solid rgba(255,255,255,.25);
      color:#fff; padding:.35rem .75rem; border-radius:50rem;
      backdrop-filter: blur(4px);
    }

    /* Card hover */
    .card-hover{ transition:transform .25s ease, box-shadow .25s ease; }
    .card-hover:hover{ transform:translateY(-4px); box-shadow: 0 10px 26px rgba(2,36,89,.08); }

    /* Stat tiles */
    .stat{
      border-radius: 1rem;
      background: linear-gradient(180deg,#ffffff,#f7fbff);
      border:1px solid #eef2f7;
    }

    /* Tourism color accents */
    .text-teal{ color: var(--ls-accent)!important; }
    .bg-teal{ background-color: var(--ls-accent)!important; color:#fff!important; }
  </style>
</head>

<body>
<!-- REPLACE your <nav> ... </nav> with this -->
<nav class="navbar navbar-expand-lg navbar-glass sticky-top">
  <div class="container py-2">
<a class="navbar-brand d-flex align-items-center" href="#home">
  <img src="assets/img/logo.png" alt="Lake Sebu Tourism" width="36" height="36" class="me-2">
  <div>
    <strong>Lake Sebu</strong><br>
    <small class="text-muted">South Cotabato • PH</small>
  </div>
</a>

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="mainNav">
      <ul class="navbar-nav ms-auto align-items-lg-center">
        <li class="nav-item px-lg-2"><a class="nav-link" href="#" id="openPublic">Public Site</a></li>
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
<!-- REPLACE the entire Hero <section id="home"> ... </section> with this -->
<section id="home" class="container my-4 my-lg-5">
  <div class="hero p-4 p-lg-5">
    <div class="row align-items-center g-4 content">
      <div class="col-lg-7">
        <span class="chip mb-3">
          <i class='bx bxs-shield'></i> Secure • Tourism CMS • Business Onboarding
        </span>
        <h1 class="display-6 fw-bold mb-3">Manage Lake Sebu’s Public Site — content, tours, stays, and events.</h1>
        <p class="lead mb-4">
          Admins and accredited businesses log in here to update the <strong>main Lake Sebu tourism page</strong> — highlights, rates, itineraries, and announcements.
        </p>
        <div class="d-flex gap-2 flex-wrap">
          <button class="btn btn-warning rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#loginModal">
            <i class="fa-solid fa-right-to-bracket me-2"></i> Secure Login
          </button>
          <a href="/" class="btn btn-outline-light rounded-pill px-3" id="visitPublic">
            <i class='bx bx-globe me-1'></i> Visit Public Site
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
                <small class="text-secondary">New Signups</small>
              </div>
            </div>
            <div class="col-4">
              <div class="p-3 rounded-3 border bg-light-subtle">
                <div class="fw-bold fs-4" id="statEnrolled">
0
                </div>
                <small class="text-secondary">Active Accounts</small>
              </div>
            </div>
            <div class="col-4">
              <div class="p-3 rounded-3 border bg-light-subtle">
                <div class="fw-bold fs-4" id="statPaid">₱0</div>
                <small class="text-secondary">Payments</small>
              </div>
            </div>
          </div>
          <div class="text-center mt-3">
            <small class="text-secondary">*Placeholders — will map to tourism metrics later.</small>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>


<!-- REPLACE the whole Features <section id="features"> ... -->
<section id="features" class="container my-5">
  <div class="text-center mb-4">
    <h2 class="fw-bold">What you can manage</h2>
    <p class="text-secondary mb-0">Admins and businesses update sections shown on the public Lake Sebu site.</p>
  </div>
  <div class="row g-4">
    <div class="col-md-6 col-lg-3">
      <a href="#" class="text-decoration-none">
        <div class="card card-hover h-100">
          <div class="card-body p-4">
            <i class="bx bxs-edit-alt bx-md text-primary"></i>
            <h5 class="mt-3 mb-1 text-dark">Homepage Content</h5>
            <p class="text-secondary mb-0">Hero, highlights, culture, responsible tourism.</p>
          </div>
        </div>
      </a>
    </div>
    <div class="col-md-6 col-lg-3">
      <a href="#" class="text-decoration-none">
        <div class="card card-hover h-100">
          <div class="card-body p-4">
            <i class="bx bxs-briefcase bx-md text-primary"></i>
            <h5 class="mt-3 mb-1 text-dark">Business Listings</h5>
            <p class="text-secondary mb-0">Onboard resorts, tours, cafés; manage profiles.</p>
          </div>
        </div>
      </a>
    </div>
    <div class="col-md-6 col-lg-3">
      <a href="#" class="text-decoration-none">
        <div class="card card-hover h-100">
          <div class="card-body p-4">
            <i class="bx bxs-purchase-tag bx-md text-primary"></i>
            <h5 class="mt-3 mb-1 text-dark">Tours & Rates</h5>
            <p class="text-secondary mb-0">Prices, inclusions, schedules, availability.</p>
          </div>
        </div>
      </a>
    </div>
    <div class="col-md-6 col-lg-3">
      <a href="#" class="text-decoration-none">
        <div class="card card-hover h-100">
          <div class="card-body p-4">
            <i class="bx bxs-megaphone bx-md text-primary"></i>
            <h5 class="mt-3 mb-1 text-dark">Events & News</h5>
            <p class="text-secondary mb-0">Helobung Festival, advisories, announcements.</p>
          </div>
        </div>
      </a>
    </div>
  </div>
</section>


  <!-- Quick Stats (placeholders) -->
<!-- REPLACE the section title + labels in #stats (keep PHP queries as-is) -->
<section id="stats" class="container my-5">
  <div class="row g-3">
    <div class="col-md-4">
      <div class="stat p-4">
        <div class="d-flex align-items-center mb-2">
          <i class="bx bx-building-house me-2 text-teal"></i><strong>Resorts & Stays</strong>
        </div>
        <div class="display-6 fw-bold" id="statElem">
0
        </div>
        <small class="text-secondary">Registered (placeholder mapping)</small>
      </div>
    </div>
    <div class="col-md-4">
      <div class="stat p-4">
        <div class="d-flex align-items-center mb-2">
          <i class="bx bx-directions me-2 text-teal"></i><strong>Tours & Operators</strong>
        </div>
        <div class="display-6 fw-bold" id="statHS">
0
        </div>
        <small class="text-secondary">Accredited (placeholder mapping)</small>
      </div>
    </div>
    <div class="col-md-4">
      <div class="stat p-4">
        <div class="d-flex align-items-center mb-2">
          <i class="bx bx-wallet me-2 text-teal"></i><strong>Total Collections</strong>
        </div>
        <div class="display-6 fw-bold" id="statCollections">₱0</div>
        <small class="text-secondary">From bookings (placeholder)</small>
      </div>
    </div>
  </div>
</section>


  <!-- Contact / Footer -->
  <section id="contact" class="container my-5">
    <div class="row align-items-center g-3">
      <div class="col-md-8">
        <h4 class="fw-bold mb-1">Need help?</h4>
        <p class="text-secondary mb-0">Contact the Administrator or contact TOURISM IT Desk.</p>
      </div>
      <div class="col-md-4 text-md-end">
      </div>
    </div>
  </section>

  <footer class="py-4 border-top">
    <div class="container d-flex justify-content-between align-items-center footer">
      <small>© <span id="yr"></span> <?php echo $_SESSION['footer'] ?>. All rights reserved.</small>
      <small>Developed by <b>SKSU-College of Computer Studies</b></small>
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
