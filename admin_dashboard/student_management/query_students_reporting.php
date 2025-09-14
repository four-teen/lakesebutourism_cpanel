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

  // ================ code states here==================

if (isset($_POST['loading_student_grade_selected'])) {

  echo '<div class="row g-3">';

  // ——— Empty-state styles (scoped) ———
  echo '
  <style>
    .empty-state{
      background: linear-gradient(180deg,#f8fafc,#ffffff);
      border: 1px dashed #dfe3ea;
      border-radius: 16px;
      padding: 3rem 1.25rem;
    }
    .empty-state .icon-wrap{
      width: 84px; height: 84px; border-radius: 20px;
      display:flex; align-items:center; justify-content:center;
      background:#eef2ff; color:#4338ca; margin:0 auto 1rem;
    }
    .empty-state .icon-wrap svg{ width:46px; height:46px; }
    .empty-state h5{ margin: .5rem 0 .25rem; font-weight:700; }
    .empty-state p{ color:#6b7280; margin-bottom:1.25rem; }
    .empty-state .btn{ border-radius: 999px; padding:.55rem 1rem; }
  </style>';

  // ——— Your query (unchanged) ———
  $select = "SELECT * FROM `tblclass_schedules_teachers`
            INNER JOIN tblteachers on tblteachers.teachersautoid=tblclass_schedules_teachers.cst_teachersid
            INNER JOIN tblcurriculum on tblcurriculum.currid=tblclass_schedules_teachers.cst_classid
            INNER JOIN tblsubjects on tblsubjects.subjectid=tblcurriculum.subjectid
            INNER JOIN tblgradelevel on tblgradelevel.levelid=tblcurriculum.gradelevelid
            INNER JOIN tblsections on tblsections.sectionsid=tblcurriculum.sectID WHERE tblteachers.teachersautoid='$_POST[get_teacherID]'";

  $runselect = mysqli_query($conn, $select);

  // ——— If no records, show empty-state and exit ———
  if (!$runselect || mysqli_num_rows($runselect) === 0) {
    echo '
      <div class="col-12">
        <div class="empty-state text-center shadow-sm">
          <div class="icon-wrap">
            <!-- simple clipboard SVG -->
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                d="M9 3h6m-7 2a2 2 0 012-2h4a2 2 0 012 2h1a2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V7a2 2 0 012-2h1z" />
            </svg>
          </div>
          <h5>No classes found</h5>
          <p>No class schedule has been loaded for the selected teacher at this time.</p>
          <div class="d-flex justify-content-center gap-2 flex-wrap">
            <button type="button" class="btn btn-primary" onclick="location.reload()">Reload or Select another teacher</button>
          </div>
        </div>
      </div>
    ';
    echo '</div>'; // close .row
    exit;
  }

  // ——— Existing render loop (unchanged) ———
  while ($rowselect = mysqli_fetch_assoc($runselect)) {   
    $timed = $rowselect['timefrom'].' - '.$rowselect['timeto'];

    echo '
      <div class="col-md-6 col-lg-4 py-4">
        <div class="card subject-card h-100 shadow-sm" role="button">
          <div class="card-header d-flex justify-content-between align-items-center">
            <span>'.$rowselect['level_descrition'].' - '.strtoupper($rowselect['section_desc']).'</span>
          </div>

          <div class="card-body">
            <div class="subject-body">
              <img src="../../assets/img/subject_logo.png" alt="Subject image" class="subject-thumb">

              <div class="flex-grow-1 subject-content">
                <div class="subject-header">
                  <h5 class="subject-title">
                    '.$rowselect['subject_description'].'
                    <small><i class="bi bi-calculator me-1"></i>Class Schedule: <span class="text-danger">'.$rowselect['timefrom'].' - '.$rowselect['timeto'].'</span></small>
                  </h5>
                </div>

                <div class="subject-metric">
                  <div class="big-metric" id="rec-count-2">
                ';

                // ——— student count query (unchanged) ———
                $select_count = "SELECT * FROM `tblgrade_sect_students`
                  INNER JOIN tblstudents on tblstudents.autoid = tblgrade_sect_students.studentID
                  INNER JOIN tblclass_schedules_teachers on tblclass_schedules_teachers.cstid=tblgrade_sect_students.class_schedule_id
                  WHERE class_schedule_id='$rowselect[cstid]'";
                $runselect_count = mysqli_query($conn, $select_count);
                echo mysqli_num_rows($runselect_count);

                echo '
                  </div>
                  <div class="metric-label">student(s)</div>
                </div>
              </div>
            </div>
            <a href="javascript:void(0)" class="stretched-link" aria-hidden="true"></a>
          </div>

          <div class="card-footer text-muted">
                <div class="btn-group" role="group" aria-label="Basic mixed styles example">
                  <button onclick="show_grades(\''.$rowselect['cstid'].'\',\''.$rowselect['subject_description'].'\',\''.$rowselect['level_descrition'].'\',\''.$rowselect['section_desc'].'\')" type="button" class="btn btn-warning">Show Grades</button>
                  <button onclick="show_individual_grades(\''.$rowselect['cstid'].'\',\''.$rowselect['subject_description'].'\',\''.$rowselect['level_descrition'].'\',\''.$rowselect['section_desc'].'\', \''.$rowselect['cst_teachersid'].'\')"  type="button" class="btn btn-primary">Individual Grades</button>
                  
                </div>
          </div>
        </div>
      </div>';
  }

  echo '</div>';
  exit;
}
 

if (isset($_POST['loading_curriculum_review'])) {
  echo '<div class="row g-3">';

   $select = "SELECT * FROM `tblclass_schedules_teachers`
            INNER JOIN tblteachers on tblteachers.teachersautoid=tblclass_schedules_teachers.cst_teachersid
            INNER JOIN tblcurriculum on tblcurriculum.currid=tblclass_schedules_teachers.cst_classid
            INNER JOIN tblsubjects on tblsubjects.subjectid=tblcurriculum.subjectid
            INNER JOIN tblgradelevel on tblgradelevel.levelid=tblcurriculum.gradelevelid
            INNER JOIN tblsections on tblsections.sectionsid=tblcurriculum.sectID";
  $runselect = mysqli_query($conn, $select);

  while ($rowselect = mysqli_fetch_assoc($runselect)) {
     

    $timed = $rowselect['timefrom'].' - '.$rowselect['timeto'];

      echo '
      <div class="col-md-6 col-lg-4 py-4">
        <div class="card subject-card h-100 shadow-sm" role="button">
          <div class="card-header d-flex justify-content-between align-items-center">
            <span>'.$rowselect['level_descrition'].' - '.strtoupper($rowselect['section_desc']).'</span>
          </div>

          <div class="card-body">
            <div class="subject-body">
              <img src="../../assets/img/subject_logo.png" alt="Subject image" class="subject-thumb">

              <div class="flex-grow-1 subject-content">
                <div class="subject-header">
                  <h5 class="subject-title">
                    '.$rowselect['subject_description'].'
                    <small><i class="bi bi-calculator me-1"></i>Class Schedule: <span class="text-danger">'.$rowselect['timefrom'].' - '.$rowselect['timeto'].'</span></small>
                  </h5>
                </div>

                <div class="subject-metric">
                  <div class="big-metric" id="rec-count-2">
                  '; ?>
                  <?php 
                    $select_count = "SELECT * FROM `tblgrade_sect_students`
                    INNER JOIN tblstudents on tblstudents.autoid = tblgrade_sect_students.studentID
                    WHERE class_schedule_id='$rowselect[cstid]'";
                    $runselect_count = mysqli_query($conn, $select_count);
                    echo mysqli_num_rows($runselect_count);

                  ?>
                  <?php echo'
                  </div>
                  <div class="metric-label">student(s)</div>
                </div>
              </div>
            </div>
            <a href="javascript:void(0)" class="stretched-link" aria-hidden="true"></a>
          </div>

          <div class="card-footer text-muted">
            

                <div class="btn-group" role="group" aria-label="Basic mixed styles example">
                  <button type="button" class="btn btn-warning">TEACHER</button>
                  <button type="button" class="btn border border-warning">
                    '.$rowselect['lastname'].', '.$rowselect['firstname'].' <span><i>'.ucfirst(strtolower($rowselect['lastname'])).'</i></span>
                  </button>
                  
                </div>

              </div>
            </div>
          </div>';
      }

      echo '</div>';
      exit;
}



?>