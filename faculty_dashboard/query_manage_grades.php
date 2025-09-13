<?php
session_start();
require_once __DIR__ . '/../modules/auth/session_guard.php';
require_role(['Teacher']);
include_once __DIR__ . '/../db.php';


// ======================================================

// ======================================================
// Save one grade cell (on blur/change)
// ======================================================
// Save one grade cell (on blur/change)
if (isset($_POST['save_grade'])) {
  header('Content-Type: application/json; charset=UTF-8');

  // Required fields
  $grade_sectID = isset($_POST['grade_sectID']) ? trim($_POST['grade_sectID']) : '';
  $quarter      = isset($_POST['quarter']) ? (int)$_POST['quarter'] : 0;
  $grade_value  = isset($_POST['grade_value']) ? trim($_POST['grade_value']) : '';

  if ($grade_sectID === '' || !in_array($quarter, [1,2,3,4], true)) {
    echo json_encode(['ok'=>false,'msg'=>'Invalid parameters.']); exit;
  }

  // Because columns are NOT NULL, blank should be saved as 0
  // Otherwise validate 0–100 (2 decimals)
  if ($grade_value === '') {
    $val_sql = "0";
  } else {
    if (!preg_match('/^\d{1,3}(\.\d{1,2})?$/', $grade_value)) {
      echo json_encode(['ok'=>false,'msg'=>'Invalid grade format.']); exit;
    }
    $n = floatval($grade_value);
    if ($n < 0 || $n > 100) {
      echo json_encode(['ok'=>false,'msg'=>'Grade must be 0–100.']); exit;
    }
    $val_sql = "'".mysqli_real_escape_string($conn, $grade_value)."'";
  }

  // map quarter -> column
  $col = ['','grade_first','grade_second','grade_third','grade_fourth'][$quarter];
  $gsid_esc = mysqli_real_escape_string($conn, $grade_sectID);

  // Ensure a full row exists (all quarters = 0), then update just the edited quarter.
  // Requires UNIQUE KEY on tblgrades(grade_sectID)
  $sql = "
    INSERT INTO tblgrades (grade_sectID, grade_first, grade_second, grade_third, grade_fourth)
    VALUES ('$gsid_esc', 0, 0, 0, 0)
    ON DUPLICATE KEY UPDATE $col = $val_sql
  ";
  $ok = mysqli_query($conn, $sql);
  if (!$ok) {
    echo json_encode(['ok'=>false,'msg'=>'DB error: '.mysqli_error($conn)]); exit;
  }

  // Recompute simple average, ignoring zeros (treat zeros as 'blank' for display)
  $q = "
    SELECT grade_first, grade_second, grade_third, grade_fourth
    FROM tblgrades
    WHERE grade_sectID = '$gsid_esc'
    LIMIT 1
  ";
  $rs = mysqli_query($conn, $q);
  $final_avg = '';
  if ($rs && $row = mysqli_fetch_assoc($rs)) {
    $vals = [];
    foreach (['grade_first','grade_second','grade_third','grade_fourth'] as $c) {
      $v = (float)$row[$c];
      if ($v > 0) { // ignore zero
        $vals[] = $v;
      }
    }
    if (count($vals)) {
      $final_avg = number_format(array_sum($vals)/count($vals), 2);
    }
  }

  echo json_encode(['ok'=>true,'final_avg'=>$final_avg]); exit;
}



if(isset($_POST['loading_students_subjects_grades'])){
  echo ''; ?>
    <div class="table-responsive">
      <table id="modalStudentsTable" class="table table-striped table-bordered w-100">
        <thead>
          <tr>
            <th class="align-middle">#</th>
            <th class="align-middle">STUDENT NAME</th>
            <th class="align-middle text-center">1st Grading</th>
            <th class="align-middle text-center">2nd Grading</th>
            <th class="align-middle text-center">3rd Grading</th>
            <th class="align-middle text-center">4th Grading</th>
            <th class="align-middle text-center">Final Grade</th>
          </tr>          
        </thead>
        <tbody>
          <?php 
            $select = "SELECT gss.*, s.last_name, s.first_name, s.middle_name,
                  g.grade_first, g.grade_second, g.grade_third, g.grade_fourth
           FROM tblgrade_sect_students AS gss
           INNER JOIN tblstudents AS s 
             ON s.autoid = gss.studentID
           LEFT JOIN tblgrades AS g 
             ON g.grade_sectID = gss.gradesectID WHERE class_schedule_id='$_POST[cstid]'";
            $runselect = mysqli_query($conn, $select);
            $count = 0;
            while($rowselect = mysqli_fetch_assoc($runselect)){
              $gsid = $rowselect['grade_sectID'] ?? ($rowselect['gradesectID'] ?? '');

              // default values blank (pwedeng mapuno ng LEFT JOIN later kung gusto mo)
              $g1 = $rowselect['grade_first']  ?? '';
              $g2 = $rowselect['grade_second'] ?? '';
              $g3 = $rowselect['grade_third']  ?? '';
              $g4 = $rowselect['grade_fourth'] ?? '';

              // compute simple average
              $vals = [];
              foreach ([$g1,$g2,$g3,$g4] as $v) {
                if ($v !== '' && $v !== null) $vals[] = (float)$v;
              }
              $avg = count($vals) ? number_format(array_sum($vals)/4, 2) : '';

              echo '
              <tr>
                <td class="align-middle text-end" width="1%">'.++$count.'.</td>
                <td class="align-middle">'.strtoupper($rowselect['last_name']).', '.strtoupper($rowselect['first_name']).' '.strtoupper($rowselect['middle_name']).'</td>

                <td class="align-middle text-center" width="1%">
                  <input type="text" class="form-control text-center grade-input"
                         data-gsid="'.htmlspecialchars($gsid).'"
                         data-quarter="1"
                         value="'.htmlspecialchars($g1).'">
                </td>
                <td class="align-middle text-center" width="1%">
                  <input type="text" class="form-control text-center grade-input"
                         data-gsid="'.htmlspecialchars($gsid).'"
                         data-quarter="2"
                         value="'.htmlspecialchars($g2).'">
                </td>
                <td class="align-middle text-center" width="1%">
                  <input type="text" class="form-control text-center grade-input"
                         data-gsid="'.htmlspecialchars($gsid).'"
                         data-quarter="3"
                         value="'.htmlspecialchars($g3).'">
                </td>
                <td class="align-middle text-center" width="1%">
                  <input type="text" class="form-control text-center grade-input"
                         data-gsid="'.htmlspecialchars($gsid).'"
                         data-quarter="4"
                         value="'.htmlspecialchars($g4).'">
                </td>

                <td class="align-middle text-center" width="1%">
                  <span class="final-grade">'.$avg.'</span>
                </td>
              </tr>
              ';
            }
          ?>
        </tbody>
      </table>
    </div>
  <?php echo '';
}

