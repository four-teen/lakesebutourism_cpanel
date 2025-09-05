<?php
// admin_dashboard/faculty_staff_records/teacher_profile.php
session_start();
require_once __DIR__ . '/../../modules/auth/session_guard.php';
require_role(['Administrator']);

include_once __DIR__ . '/../../db.php';

// get AY (keep your logic)
$account = "SELECT * FROM `tblassigned_designation`
                    INNER JOIN tbldesignation on tbldesignation.designationid = tblassigned_designation.ass_designationid
                    INNER JOIN tblgradelevel on tblgradelevel.levelid=tblassigned_designation.ass_gradelevelid
                    LEFT JOIN tblsections on tblsections.sectionsid=tblassigned_designation.ass_sectionid
                    INNER JOIN tblteachers on tblteachers.teachersautoid=tblassigned_designation.ass_teachersautoid WHERE tblteachers.teachersautoid = '$_GET[teacherId]'";
$runaccount = mysqli_query($conn, $account);
$rowaccounts = mysqli_fetch_assoc($runaccount);
$assigned_id = $rowaccounts['assignedid'];


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>Teachers Profile</title>

    <link href="../../assets/img/logo.png" rel="icon">
    <link href="../../assets/img/logo.png" rel="apple-touch-icon">

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <link href="../../assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="../../assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link href="../../assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap5.min.css" rel="stylesheet">

    <link href="../../assets/css/style.css" rel="stylesheet">

    <style>
        /* This CSS ensures the modal is centered correctly */
        .modal.fade .modal-dialog {
            display: flex;
            align-items: center;
            min-height: calc(100vh - 1rem);
            margin: 0.5rem auto;
        }

        @media (min-width: 576px) {
            .modal.fade .modal-dialog {
                min-height: calc(100vh - 3.5rem);
            }
        }
        
        /* Your existing styles */
        *{ font-family:'Poppins',system-ui,-apple-system,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif; }
        .avatar{ width:46px;height:46px;border-radius:50%;object-fit:cover;border:1px solid #e7e7e7;background:#f7f7f7; }
        .avatar-lg{ width:120px;height:120px;border-radius:12px;object-fit:cover;border:1px solid #e7e7e7;background:#f7f7f7; }
        .table thead th{ font-weight:700; }
        .btn-icon{ padding:.35rem .55rem; }
    </style>
</head>

<body onload="load_class_schedules()">

<?php include '../header.php'; ?>

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
            </a>
            <ul id="teachers-nav" class="nav-content collapse show" data-bs-parent="#sidebar-nav">
                <li><a href="teacher_profile.php"><i class="bi bi-circle"></i><span>Faculty Profile</span></a></li>
                <li><a class="active" href="teacher_loads.php"><i class="bi bi-circle"></i><span>Teaching Loads & Schedules</span></a></li>
                <li><a href="#"><i class="bi bi-circle"></i><span>Performance Monitoring</span></a></li>
            </ul>
        </li>
    </ul>
</aside>
<main id="main" class="main">
    <div class="pagetitle">
        <h1>Manage Class Schedule</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="teacher_profile.php">Home</a></li>
                <li class="breadcrumb-item active">Teachers</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        <div class="card">
            <div class="card-body">
                <div class="d-flex flex-wrap justify-content-between align-items-center pt-3 pb-2">
                    <div>
                        <h5 class="card-title mb-0"><?php echo strtoupper($rowaccounts['designation_desc']) ?>: <b><?php echo $rowaccounts['lastname'] ?></b>, <?php echo $rowaccounts['firstname'] ?><br><span style="font-size:16px"><?php echo (($rowaccounts['level_descrition'] !== 'None' && !empty($rowaccounts['level_descrition'])) ? ' ' . strtoupper($rowaccounts['level_descrition']) : '') .
                                (($rowaccounts['section_desc'] !== 'None' && !empty($rowaccounts['section_desc'])) ? ' - ' . strtoupper($rowaccounts['section_desc']) : ''); ?></span></b></h5>
                    </div>
                </div>
                <div>
                  <!-- <div id="test">test</div> -->
<p>
  <button class="btn btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
    Add New Schedule
  </button>
</p>
<div class="collapse" id="collapseExample">
  <div class="card card-body py-2 px-2">
    <div class="row">
        <div class="col-lg-3">
            <label for="timefrom">Time From</label>
            <input type="time" class="form-control" id="timefrom" required>
        </div>
        <div class="col-lg-3">
            <label for="timeto">Time To</label>
            <input type="time" class="form-control" id="timeto" required>
        </div>
        <div class="col-lg-6">
            <label for="subjectid">Subject/Others</label>
            <select id="subjectid" class="form-control" required>
                <option value="" selected disabled>Select a subject</option>
                <?php
                    $getsubjects = "SELECT * FROM `tblsubjects`";
                    $rungetsubjects = mysqli_query($conn, $getsubjects);
                    if(mysqli_num_rows($rungetsubjects) > 0) {
                        while($rowsubjects = mysqli_fetch_assoc($rungetsubjects)){
                            echo '<option value="'.$rowsubjects['subjectid'].'">'.$rowsubjects['subject_description'].'</option>';
                        }
                    }
                ?>
                <option value="" disabled>----------------------------</option>
                <option value="10001">Break Time</option>
                <option value="10002">Lunch</option>
            </select>
        </div>
    </div>
    <div class="row">
      <div class="col-lg-12 py-2">
        <button onclick="saving_new_schedule()" class="btn btn-primary btn-sm float-end">Add ti list...</button>
      </div>
    </div>
  </div>
</div>                  
                    <hr>
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

<footer id="footer" class="footer">
    <div class="copyright">
        &copy; Copyright <strong><span>BTESLife</span></strong>. All Rights Reserved
    </div>
    <div class="credits">Powered by <a href="#">eoa * mgli</a></div>
</footer>

<a href="#" class="back-to-top d-flex align-items-center justify-content-center">
    <i class="bi bi-arrow-up-short"></i>
</a>



<script src="../../assets/vendor/bootstrap/js/jquery-3.6.0.min.js"></script>
<script src="../../assets/vendor/bootstrap/js/bootstrap5.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap5.min.js"></script>
<script src="../../assets/sweetalert2.js"></script>

<script>

    function delete_schedule(classid){
      Swal.fire({
        title: "Are you sure?",
        text: "You won't be able to revert this!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, delete it!"
      }).then((result) => {
        if (result.isConfirmed) {
         $.ajax({
            type: "POST",
            url: "query_teacher_loads.php",
            data: {
              "delete_class_schedule": "1",
              "classid" : classid
            },
            success: function () {
              load_class_schedules();
            }
          }); 

        }
      });
    }

    function saving_new_schedule(){
        var assigned_id = '<?php echo $assigned_id ?>';
        var timefrom = $('#timefrom').val();
        var timeto = $('#timeto').val();
        var subjectid = $('#subjectid').val();
        $.ajax({
            type: "POST",
            url: "query_teacher_loads.php",
            data: {
              "saving_class_schedule": "1",
              "assigned_id": assigned_id,
              "timefrom": timefrom,
              "timeto": timeto,
              "subjectid": subjectid
            },
            success: function (response) {
              // $('#test').html(response);
                load_class_schedules();
            }
        }); 

    }

    function load_class_schedules() {
        $('#loader').show(); // Show the loader
        $('#content_area').hide(); // Hide the content while loading
        
        $.ajax({
            type: "POST",
            url: "query_teacher_loads.php",
            data: { 
            "loading_class_schedules": '1'
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