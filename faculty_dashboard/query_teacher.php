<?php
session_start();
require_once __DIR__ . '/../modules/auth/session_guard.php';
require_role(['Teacher']);
include_once __DIR__ . '/../db.php';


// ======================================================


if (isset($_POST['loading_your_subject'])) {
  echo '<div class="row g-3">';

   $select = "SELECT * FROM `tblclass_schedules_teachers`
  INNER JOIN tblclass_schedules on tblclass_schedules.classid=tblclass_schedules_teachers.cst_classid
  INNER JOIN tblsubjects on tblsubjects.subjectid = tblclass_schedules.subjectid
  WHERE cst_teachersid='$_SESSION[TEA_ID]'";
  $runselect = mysqli_query($conn, $select);

  while ($rowselect = mysqli_fetch_assoc($runselect)) {
     
      // TODO: replace this with your actual count query
      // e.g. SELECT COUNT(*) FROM tblrecords WHERE classid = $classId
      $recordsCount = 24;

      // human time (optional)
      // $lastUpdated = $updated ? date('M j, Y g:i A', strtotime($updated)) : '2 days ago';

      echo '
      <div class="col-md-6 col-lg-4">
        <div class="card subject-card h-100 shadow-sm" role="button">
          <div class="card-header d-flex justify-content-between align-items-center">
            <span>Grade Level - section name</span>
              <span class="badge-pill cta-badge">
                <i class="bi bi-people-fill me-1"></i> Add Students
              </span>
          </div>

          <div class="card-body">
            <div class="subject-body">
              <img src="../assets/img/logo.png" alt="Subject image" class="subject-thumb">

              <div class="flex-grow-1 subject-content">
                <div class="subject-header">
                  <h5 class="subject-title">
                    '.$rowselect['subject_description'].'
                    <small><i class="bi bi-calculator me-1"></i>Class ID: '.$rowselect['cstid'].'</small>
                  </h5>
                </div>

                <div class="subject-metric">
                  <div class="big-metric" id="rec-count-2">0</div>
                  <div class="metric-label">records</div>
                </div>
              </div>
            </div>
            <a href="javascript:void(0)" class="stretched-link" aria-hidden="true"></a>
          </div>

          <div class="card-footer text-muted text-center">
            Last updated: 01/24/25
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
