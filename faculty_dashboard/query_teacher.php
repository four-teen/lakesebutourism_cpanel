<?php
session_start();
require_once __DIR__ . '/../modules/auth/session_guard.php';
require_role(['Teacher']);
include_once __DIR__ . '/../db.php';


// ======================================================

if(isset($_POST['delete_the_student'])){
  $delete = "DELETE FROM `tblgrade_sect_students` WHERE gradesectID = '$_POST[gradesectID]'";
  $rundelete = mysqli_query($conn, $delete);
}

if (isset($_POST['loading_students_subjects'])) {
  echo
  ''; ?>
    <div class="table-responsive">
      <table id="modalStudentsTable" class="table table-striped table-bordered w-100">
        <thead>
          <tr>
            <th>#</th>
            <th>STUDENT NAME</th>
            <th>ACTION</th>
          </tr>          
        </thead>
        <tbody>
          <?php 
            $select = "SELECT * FROM `tblgrade_sect_students`
            INNER JOIN tblstudents on tblstudents.autoid = tblgrade_sect_students.studentID
            WHERE class_schedule_id='$_POST[cstid]'";
            $runselect = mysqli_query($conn, $select);
            $count = 0;
            while($rowselect = mysqli_fetch_assoc($runselect)){
              echo
              '
              <tr>
                <td class="align-middle text-end" width="1%">'.++$count.'.</td>
                <td class="align-middle">'.strtoupper($rowselect['last_name']).', '.strtoupper($rowselect['first_name']).' '.strtoupper($rowselect['middle_name']).'</td>
                <td class="align-middle"  width="1%">
                  <button onclick="remove_student(\''.$rowselect['gradesectID'].'\')" class="btn btn-danger btn-sm">Remove</button>
                </td>
              </tr>
              ';
            }

          ?>

        </tbody>
      </table>
    </div>
  <?php echo'';
}


if(isset($_POST['saving_subject_students'])){

  $settings = "SELECT * FROM `tblsettings`
  INNER JOIN tblacademic_years on tblacademic_years.ayid=tblsettings.ayid LIMIT 1";
  $runsettings = mysqli_query($conn, $settings);
  $rowsettings = mysqli_fetch_assoc($runsettings);
  $ayid = $rowsettings['ayid'];
  $studentsID = $_POST['studentsID'];
  $class_schedule_id = $_POST['class_schedule_id'];

  $insert = "INSERT INTO `tblgrade_sect_students` (`studentID`, `ayID`, `addedBy`, `addedDateTime`, `class_schedule_id`) VALUES ('$studentsID', '$ayid', '$_SESSION[TEA_ID]', CURRENT_TIMESTAMP, '$class_schedule_id')";
  $runinsert = mysqli_query($conn, $insert);  

  echo $insert; 

}


if (isset($_POST['loading_your_subject'])) {
  echo '<div class="row g-3">';

   $select = "SELECT * FROM `tblclass_schedules_teachers`
  INNER JOIN tblclass_schedules on tblclass_schedules.classid=tblclass_schedules_teachers.cst_classid
  INNER JOIN tblsubjects on tblsubjects.subjectid = tblclass_schedules.subjectid
  INNER JOIN tblgradelevel on tblgradelevel.levelid=tblclass_schedules.levelID
  INNER JOIN tblsections on tblsections.sectionsid=tblclass_schedules_teachers.cst_sectionid
  WHERE cst_teachersid='$_SESSION[TEA_ID]'";
  $runselect = mysqli_query($conn, $select);

  while ($rowselect = mysqli_fetch_assoc($runselect)) {
     
      // TODO: replace this with your actual count query
      // e.g. SELECT COUNT(*) FROM tblrecords WHERE classid = $classId
      // $recordsCount = 24;

      // human time (optional)
      // $lastUpdated = $updated ? date('M j, Y g:i A', strtotime($updated)) : '2 days ago';

      echo '
      <div class="col-md-6 col-lg-4">
        <div class="card subject-card h-100 shadow-sm" role="button">
          <div class="card-header d-flex justify-content-between align-items-center">
            <span>'.$rowselect['level_descrition'].' - '.strtoupper($rowselect['section_desc']).'</span>
              <span onclick="add_student_list(\''.$rowselect['cstid'].'\')" class="badge-pill cta-badge">
                <i class="bi bi-people-fill me-1"></i> Add Students
              </span>
          </div>

          <div class="card-body">
            <div class="subject-body">
              <img src="../assets/img/subject_logo.png" alt="Subject image" class="subject-thumb">

              <div class="flex-grow-1 subject-content">
                <div class="subject-header">
                  <h5 class="subject-title">
                    '.$rowselect['subject_description'].'
                    <small><i class="bi bi-calculator me-1"></i>Class Schedule: <span class="text-danger">'.$rowselect['time_from'].' - '.$rowselect['time_to'].'</span></small>
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
            <button class="btn btn-info btn-sm float-end">Manage Grades</button>
          </div>
        </div>
      </div>';
  }

  echo '</div>';
  exit;
}


if (isset($_POST['loading_ay'])) {
    header('Content-Type: application/json; charset=utf-8');

    $sql = "SELECT ayfrom, ayto 
            FROM tblsettings 
            INNER JOIN tblacademic_years USING(ayid) 
            LIMIT 1";
    $res = mysqli_query($conn, $sql);

    if ($res && $row = mysqli_fetch_assoc($res)) {
        echo json_encode([
            'ok' => true,
            'ay' => $row['ayfrom'] . '-' . $row['ayto']
        ]);
    } else {
        echo json_encode(['ok' => false, 'ay' => '']);
    }
    exit;
}
