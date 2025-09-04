<?php
// admin_dashboard/faculty_staff_records/teacher_profile.php
session_start();
require_once __DIR__ . '/../../modules/auth/session_guard.php';
require_role(['Administrator']);

include_once __DIR__ . '/../../db.php';

// get AY (keep your logic)
$settings = "SELECT * FROM `tblsettings`
  INNER JOIN tblacademic_years on tblacademic_years.ayid=tblsettings.ayid LIMIT 1";
$runsettings = mysqli_query($conn, $settings);
$rowsettings = mysqli_fetch_assoc($runsettings);
$_SESSION['ays']  = $rowsettings['ayfrom'].'-'.$rowsettings['ayfrom'];
$_SESSION['ayid'] = $rowsettings['ayid'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Teachers Profile</title>

  <!-- Favicons -->
  <link href="../../assets/img/logo.png" rel="icon">
  <link href="../../assets/img/logo.png" rel="apple-touch-icon">

  <!-- Poppins -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">

  <!-- Vendor CSS -->
  <link href="../../assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="../../assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="../../assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
  <link href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap5.min.css" rel="stylesheet">

  <!-- Template Main CSS -->
  <link href="../../assets/css/style.css" rel="stylesheet">

  <style>
    *{ font-family:'Poppins',system-ui,-apple-system,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif; }
    .avatar{ width:46px;height:46px;border-radius:50%;object-fit:cover;border:1px solid #e7e7e7;background:#f7f7f7; }
    .avatar-lg{ width:120px;height:120px;border-radius:12px;object-fit:cover;border:1px solid #e7e7e7;background:#f7f7f7; }
    .table thead th{ font-weight:700; }
    .btn-icon{ padding:.35rem .55rem; }
  </style>
</head>

<body onload="load_fac_profile()">

<?php include '../header.php'; ?>

<!-- ======= Sidebar (short) ======= -->
<aside id="sidebar" class="sidebar">
  <ul class="sidebar-nav" id="sidebar-nav">
    <li class="nav-item">
      <a class="nav-link" href="../dashboard_admin.php">
        <i class="bi bi-grid"></i> <span>Dashboard</span>
      </a>
    </li>

    <li class="nav-item">
      <a class="nav-link collapsed" data-bs-toggle="collapse" href="#teachers-nav" role="button" aria-expanded="true" aria-controls="teachers-nav">
        <i class="bx bx-user text-primary"></i> <span>Teachers and Staff Records</span>
        <i class="bi bi-chevron-down ms-auto"></i>
      </a>
      <ul id="teachers-nav" class="nav-content collapse show" data-bs-parent="#sidebar-nav">
        <li><a class="active" href="#"><i class="bi bi-circle"></i><span>Faculty Profile</span></a></li>
        <li><a href="#"><i class="bi bi-circle"></i><span>Teaching Loads & Schedules</span></a></li>
        <li><a href="#"><i class="bi bi-circle"></i><span>Performance Monitoring</span></a></li>
      </ul>
    </li>
  </ul>
</aside>
<!-- End Sidebar -->

<main id="main" class="main">
  <div class="pagetitle">
    <h1>Manage Teachers Profile</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="../dashboard_admin.php">Home</a></li>
        <li class="breadcrumb-item active">Teachers</li>
      </ol>
    </nav>
  </div>

  <section class="section">
    <div class="card">
      <div class="card-body">
        <div class="d-flex flex-wrap justify-content-between align-items-center pt-3 pb-2">
          <div>
            <h5 class="card-title mb-0">Faculty List</h5>
            <small class="text-muted">Academic Year: <?php echo htmlspecialchars($_SESSION['ays']); ?></small>
          </div>
          <div>
            <button class="btn btn-success" id="btnAdd">
              <i class="bx bx-plus-circle me-1"></i> Add New Teacher
            </button>
          </div>
        </div>

                        <div id="main_data">
                          <div id="loader" class="text-center" style="display: none;">
                            <img src="../../loader.gif" alt="Loading..." width="10%">
                          </div>
                          <div id="content_area"></div>
                        </div>


      </div>
    </div>
  </section>
</main>

<!-- Footer -->
<footer id="footer" class="footer">
  <div class="copyright">
    &copy; Copyright <strong><span>BTESLife</span></strong>. All Rights Reserved
  </div>
  <div class="credits">Powered by <a href="#">bteslife</a></div>
</footer>

<a href="#" class="back-to-top d-flex align-items-center justify-content-center">
  <i class="bi bi-arrow-up-short"></i>
</a>

<!-- ADD/EDIT MODAL -->
<div class="modal fade" id="teacherModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <form id="formTeacher" class="modal-content" enctype="multipart/form-data">
      <div class="modal-header">
        <h5 class="modal-title" id="mTitle">Add Teacher</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body">
        <!-- hidden fields -->
        <input type="hidden" id="teachersautoid" name="teachersautoid">
        <!-- carry old image when editing (if user doesn't upload a new one) -->
        <input type="hidden" id="current_image" name="current_image">

        <div class="row g-3">
          <!-- preview -->
          <div class="col-12 text-center">
            <img id="previewImg" class="avatar-lg" src="../../assets/img/avatar-placeholder.png" alt="preview">
          </div>

          <!-- file input -->
          <div class="col-12">
            <label class="form-label">Upload Photo</label>
            <input type="file" class="form-control" id="teacher_image" name="teacher_image" accept="image/*">
            <div class="form-text">
              JPG/PNG up to 2MB. We will store only the relative path, e.g. <code>uploads/teachers/filename.jpg</code>
            </div>
          </div>

          <div class="col-md-6">
            <label class="form-label">Teacher ID</label>
            <input type="text" class="form-control" id="teachersid" name="teachersid" required>
          </div>
          <div class="row">
            <div class="col-md-4">
              <label class="form-label">Last Name</label>
              <input type="text" class="form-control text-uppercase" id="lastname" name="lastname" required>
            </div>
            <div class="col-md-4">
              <label class="form-label">First Name</label>
              <input type="text" class="form-control text-uppercase" id="firstname" name="firstname" required>
            </div>
            <div class="col-md-4">
              <label class="form-label">Middle Name</label>
              <input type="text" class="form-control text-uppercase" id="middlename" name="middlename">
            </div>            
          </div> 

        </div>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary" id="btnSave">Save</button>
      </div>
    </form>
  </div>
</div>


<!-- Vendor JS -->
<script src="../../assets/vendor/bootstrap/js/jquery-3.6.0.min.js"></script>
<script src="../../assets/vendor/bootstrap/js/bootstrap5.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap5.min.js"></script>
<script src="../../assets/sweetalert2.js"></script>

<script>

    function load_fac_profile() {
        $('#loader').show(); // Show the loader
        $('#content_area').hide(); // Hide the content while loading
        
        $.ajax({
            type: "POST",
            url: "query_teacher.php",
            data: { 
            "loading_faculty_records": '1'
          },
success: function(response) {
  $('#content_area').html(response);
  // initialize here if needed
  if ($.fn.DataTable.isDataTable('#tblTeachers')) {
    $('#tblTeachers').DataTable().destroy();
  }
  $('#tblTeachers').DataTable({ pageLength: 10, lengthChange: false, order: [[2,'asc'],[3,'asc']] });
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

 // ===== Helpers =====
  const placeholderImg = '../../assets/img/avatar-placeholder.png';

  // Uppercase typing
  $(document).on('input', '.text-uppercase', function(){ this.value = this.value.toUpperCase(); });

  // Image preview + basic client validation
  $('#teacher_image').on('change', function(){
    const file = this.files[0];
    if (!file){ $('#previewImg').attr('src', $('#current_image').val() || placeholderImg); return; }

    // simple validations
    const okTypes = ['image/jpeg','image/png','image/jpg','image/webp'];
    if (!okTypes.includes(file.type)) {
      Swal.fire({icon:'warning', title:'Invalid image type', text:'Use JPG/PNG/WEBP only.'});
      this.value = '';
      return;
    }
    if (file.size > 2*1024*1024) { // 2MB
      Swal.fire({icon:'warning', title:'File too large', text:'Max 2MB allowed.'});
      this.value = '';
      return;
    }

    const reader = new FileReader();
    reader.onload = e => $('#previewImg').attr('src', e.target.result);
    reader.readAsDataURL(file);
  });

  // ===== Open ADD modal =====
  function openAddTeacher(){
    $('#mTitle').text('Add Teacher');
    $('#formTeacher')[0].reset();
    $('#teachersautoid').val('');
    $('#current_image').val('');
    $('#previewImg').attr('src', placeholderImg);
    $('#teacher_image').val('');
    const m = new bootstrap.Modal('#teacherModal');
    m.show();
    setTimeout(()=> $('#teachersid').trigger('focus'), 200);
  }
  // bind if you have a button:
  $('#btnAdd').off('click').on('click', openAddTeacher);

  // ===== Open EDIT modal =====
  function editTeacher(btn){
    const id = $(btn).data('id');
    $.getJSON('query_teacher.php', { get_teacher:1, teachersautoid:id }, function(r){
      if (!r.ok) return Swal.fire({icon:'error', title:'Teacher not found'});
      const t = r.data;
      $('#mTitle').text('Edit Teacher');
      $('#teachersautoid').val(t.teachersautoid);
      $('#teachersid').val(t.teachersid);
      $('#lastname').val(t.lastname);
      $('#firstname').val(t.firstname);
      $('#middlename').val(t.middlename);
      $('#current_image').val(t.teacher_image || '');
      $('#previewImg').attr('src', t.teacher_image ? ('../../'+t.teacher_image) : placeholderImg);
      $('#teacher_image').val(''); // do not auto-fill file inputs
      const m = new bootstrap.Modal('#teacherModal');
      m.show();
      setTimeout(()=> $('#teachersid').trigger('focus'), 200);
    }).fail(()=> Swal.fire({icon:'error', title:'Server Error'}));
  }
  // keep this function on window so your table button can call it
  window.editTeacher = editTeacher;

  // ===== Save (Add/Edit) =====
  $('#formTeacher').off('submit').on('submit', function(e){
    e.preventDefault();

    // basic required checks (front-end)
    if (!$('#teachersid').val().trim() || !$('#lastname').val().trim() || !$('#firstname').val().trim()){
      return Swal.fire({icon:'warning', title:'Missing fields', text:'Teacher ID, Firstname, Lastname are required.'});
    }

    const fd = new FormData(this);
    fd.append('save_teacher', 1);

    $.ajax({
      url: 'query_teacher.php',
      type: 'POST',
      data: fd,
      processData: false,
      contentType: false,
      dataType: 'json',
      success: function(r){
        if (r.ok){
          Swal.fire({icon:'success', title:'Saved', timer:1000, showConfirmButton:false})
            .then(()=> location.reload());
        } else {
          Swal.fire({icon:'error', title:'Error', text:r.msg || 'Failed to save.'});
        }
      },
      error: function(){
        Swal.fire({icon:'error', title:'Server Error', text:'Please try again.'});
      }
    });
  });

function delTeacher(btn){
  const id = $(btn).data('id');
  Swal.fire({
    title:'Delete this teacher?',
    icon:'warning',
    showCancelButton:true,
    confirmButtonText:'Yes, delete it',
    confirmButtonColor:'#d33'
  }).then(res=>{
    if(!res.isConfirmed) return;
    $.post('query_teacher.php', { delete_teacher:1, teachersautoid:id }, function(r){
      if (r.ok){
        Swal.fire({icon:'success', title:'Deleted', timer:900, showConfirmButton:false});
        load_fac_profile(); // reload table via AJAX
      } else {
        Swal.fire({icon:'error', title:'Error', text:r.msg||'Failed to delete'});
      }
    }, 'json').fail(()=> Swal.fire({icon:'error', title:'Server Error'}));
  });
}
window.delTeacher = delTeacher; // expose globally for onclick

 
</script>
</body>
</html>
