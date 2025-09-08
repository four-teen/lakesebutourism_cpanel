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

// fetch one student (for edit)
if(isset($_POST['get_student'])){
  header('Content-Type: application/json');
  $id = (int)($_POST['id'] ?? 0);
  if($id <= 0){ echo json_encode(['success'=>false,'message'=>'Invalid ID']); exit; }

  $q = mysqli_query($conn, "SELECT * FROM tblstudents WHERE autoid=$id LIMIT 1");
  if($q && mysqli_num_rows($q)){
    $data = mysqli_fetch_assoc($q);
    echo json_encode(['success'=>true,'data'=>$data]); exit;
  }
  echo json_encode(['success'=>false,'message'=>'Record not found']); exit;
}

// update student
if(isset($_POST['updating_student'])){
  header('Content-Type: application/json');

  $id = (int)($_POST['id'] ?? 0);
  if($id<=0){ echo json_encode(['success'=>false,'message'=>'Invalid ID']); exit; }

  // pull current to keep/cleanup photo if needed
  $currQ = mysqli_query($conn, "SELECT photo_url FROM tblstudents WHERE autoid=$id LIMIT 1");
  if(!$currQ || !mysqli_num_rows($currQ)){ echo json_encode(['success'=>false,'message'=>'Record not found']); exit; }
  $curr = mysqli_fetch_assoc($currQ);
  $photo_url = $curr['photo_url'];

  // fields
  $with_lrn = mysqli_real_escape_string($conn, $_POST['with_lrn'] ?? '0');
  $is_returning = mysqli_real_escape_string($conn, $_POST['is_returning'] ?? '0');
  $psa_birth_cert_no = mysqli_real_escape_string($conn, $_POST['psa_birth_cert_no'] ?? '');
  $learner_ref_no   = mysqli_real_escape_string($conn, $_POST['learner_ref_no'] ?? '');
  $last_name        = mysqli_real_escape_string($conn, $_POST['last_name'] ?? '');
  $first_name       = mysqli_real_escape_string($conn, $_POST['first_name'] ?? '');
  $middle_name      = mysqli_real_escape_string($conn, $_POST['middle_name'] ?? '');
  $extension_name   = mysqli_real_escape_string($conn, $_POST['extension_name'] ?? '');
  $birthdate        = mysqli_real_escape_string($conn, $_POST['birthdate'] ?? '');
  $sex              = mysqli_real_escape_string($conn, $_POST['sex'] ?? 'Male');
  $grade_level      = mysqli_real_escape_string($conn, $_POST['grade_level'] ?? '');

  // optional photo upload (same validation as your add)
  if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
    $tmp = $_FILES['photo']['tmp_name'];

    $mime = '';
    if (function_exists('finfo_open')) {
      $fi = finfo_open(FILEINFO_MIME_TYPE);
      if ($fi) { $mime = finfo_file($fi,$tmp); finfo_close($fi); }
    }
    if (!$mime && function_exists('mime_content_type')) { $mime = @mime_content_type($tmp); }
    if (!$mime && function_exists('exif_imagetype')) {
      $type = @exif_imagetype($tmp);
      $map  = [IMAGETYPE_JPEG=>'image/jpeg', IMAGETYPE_PNG=>'image/png'];
      $mime = $map[$type] ?? '';
    }
    if (!$mime && isset($_FILES['photo']['type'])) { $mime = $_FILES['photo']['type']; }

    $allowed = ['image/jpeg'=>'jpg','image/jpg'=>'jpg','image/png'=>'png'];
    if (!isset($allowed[$mime])) { echo json_encode(['success'=>false,'message'=>'Only JPG/PNG images are allowed.']); exit; }
    if ($_FILES['photo']['size'] > 3 * 1024 * 1024) { echo json_encode(['success'=>false,'message'=>'Image too large (max 3MB).']); exit; }

    $folder   = __DIR__ . '/../../uploads/students/';
    if (!is_dir($folder)) { @mkdir($folder, 0775, true); }

    $ext      = $allowed[$mime];
    $basename = date('YmdHis') . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
    $dest_abs = $folder . $basename;

    if (!move_uploaded_file($tmp, $dest_abs)) {
      echo json_encode(['success'=>false,'message'=>'Failed to save uploaded image.']); exit;
    }

    // remove old file if it exists and is under our uploads/students
    if(!empty($photo_url)){
      $root = realpath(__DIR__ . '/../../');
      $old  = realpath($root . $photo_url);
      if($old && strpos($old, realpath($root.'/uploads/students'))===0 && is_file($old)){
        @unlink($old);
      }
    }

    $photo_url = '/bteslife/uploads/students/' . $basename;
  }

  $sql = "UPDATE tblstudents SET
            with_lrn='$with_lrn',
            is_returning='$is_returning',
            psa_birth_cert_no='$psa_birth_cert_no',
            learner_ref_no='$learner_ref_no',
            last_name='$last_name',
            first_name='$first_name',
            middle_name='$middle_name',
            extension_name='$extension_name',
            birthdate='$birthdate',
            sex='$sex',
            grade_level='$grade_level',
            photo_url ".(isset($_FILES['photo']) && $_FILES['photo']['error']===UPLOAD_ERR_OK ? "='$photo_url'" : "=photo_url")."
          WHERE autoid=$id LIMIT 1";
  $ok = mysqli_query($conn, $sql);
  echo json_encode(['success'=> (bool)$ok]); exit;
}

// delete student
if(isset($_POST['delete_student'])){
  header('Content-Type: application/json');
  $id = (int)($_POST['id'] ?? 0);
  if($id<=0){ echo json_encode(['success'=>false,'message'=>'Invalid ID']); exit; }

  // fetch photo to clean up
  $q = mysqli_query($conn, "SELECT photo_url FROM tblstudents WHERE autoid=$id LIMIT 1");
  if(!$q || !mysqli_num_rows($q)){ echo json_encode(['success'=>false,'message'=>'Record not found']); exit; }
  $row = mysqli_fetch_assoc($q);

  $ok = mysqli_query($conn, "DELETE FROM tblstudents WHERE autoid=$id LIMIT 1");
  if($ok){
    // remove file if local
    if(!empty($row['photo_url'])){
      $root = realpath(__DIR__ . '/../../');
      $old  = realpath($root . $row['photo_url']);
      if($old && strpos($old, realpath($root.'/uploads/students'))===0 && is_file($old)){
        @unlink($old);
      }
    }
    echo json_encode(['success'=>true]); exit;
  }
  echo json_encode(['success'=>false,'message'=>'Delete failed']); exit;
}

if(isset($_POST['saving_student'])){
  $with_lrn = $_POST['with_lrn'];
  $is_returning = $_POST['is_returning'];
  $psa_birth_cert_no = $_POST['psa_birth_cert_no'];
  $learner_ref_no = $_POST['learner_ref_no'];
  $last_name = $_POST['last_name'];
  $first_name = $_POST['first_name'];
  $middle_name = $_POST['middle_name'];
  $extension_name = $_POST['extension_name'];
  $birthdate = $_POST['birthdate'];
  $sex = $_POST['sex'];
  $grade_level = $_POST['grade_level'];

  // --- handle file upload (optional) ---
  $photo_url = null;
  if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
    $tmp = $_FILES['photo']['tmp_name'];

    // detect mime (robust version)
    $mime = '';
    if (function_exists('finfo_open')) {
      $fi = finfo_open(FILEINFO_MIME_TYPE);
      if ($fi) { $mime = finfo_file($fi, $tmp); finfo_close($fi); }
    }
    if (!$mime && function_exists('mime_content_type')) {
      $mime = @mime_content_type($tmp);
    }
    if (!$mime && function_exists('exif_imagetype')) {
      $type = @exif_imagetype($tmp);
      $map  = [IMAGETYPE_JPEG=>'image/jpeg', IMAGETYPE_PNG=>'image/png'];
      $mime = $map[$type] ?? '';
    }
    if (!$mime && isset($_FILES['photo']['type'])) {
      $mime = $_FILES['photo']['type'];
    }

    $allowed = ['image/jpeg'=>'jpg','image/jpg'=>'jpg','image/png'=>'png'];
    if (!isset($allowed[$mime])) {
      header('Content-Type: application/json');
      echo json_encode(['success'=>false,'message'=>'Only JPG/PNG images are allowed.']); exit;
    }

    if ($_FILES['photo']['size'] > 3 * 1024 * 1024) {
      header('Content-Type: application/json');
      echo json_encode(['success'=>false,'message'=>'Image too large (max 3MB).']); exit;
    }

    // ✅ Save under /uploads/teachers/
    $folder   = __DIR__ . '/../../uploads/students/';
    if (!is_dir($folder)) { @mkdir($folder, 0775, true); }

    $ext      = $allowed[$mime];
    $basename = date('YmdHis') . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
    $dest_abs = $folder . $basename;

    if (!move_uploaded_file($tmp, $dest_abs)) {
      header('Content-Type: application/json');
      echo json_encode(['success'=>false,'message'=>'Failed to save uploaded image.']); exit;
    }

    // Build public URL (relative to web root)
    // This points to /bteslife/uploads/teachers/<file>
    $photo_url = '/bteslife/uploads/students/' . $basename;
  }


  // --- save student record ---

  // ensure academic year is recorded
  $settings = "SELECT * FROM `tblsettings`
  INNER JOIN tblacademic_years on tblacademic_years.ayid=tblsettings.ayid LIMIT 1";
  $runsettings = mysqli_query($conn, $settings);
  $rowsettings = mysqli_fetch_assoc($runsettings);
  $curr_ay = $rowsettings['ayid'];

  $insert = "INSERT INTO `tblstudents`
    (`with_lrn`, `is_returning`, `psa_birth_cert_no`, `learner_ref_no`,
     `last_name`, `first_name`, `middle_name`, `extension_name`,
     `birthdate`, `sex`, `grade_level`, `photo_url`, `ay`)
    VALUES
    ('$with_lrn','$is_returning','$psa_birth_cert_no','$learner_ref_no',
     '$last_name','$first_name','$middle_name','$extension_name',
     '$birthdate','$sex','$grade_level','$photo_url', '$curr_ay')";
  
  $runinsert = mysqli_query($conn, $insert);
  echo $insert; // for your debugging
}

if (isset($_POST['loading_students'])) {

  // get AY (optional display)
  $school_year = isset($_SESSION['ays']) ? $_SESSION['ays'] : '';

  // pull from tblstudents (latest first) with photo_url
  $sql = "SELECT tblstudents.*, tblgradelevel.level_descrition
          FROM tblstudents
          INNER JOIN tblgradelevel on tblgradelevel.levelid=tblstudents.grade_level
          ORDER BY created_at DESC";
  $res = mysqli_query($conn, $sql);

  // small helper to compute age
  function compute_age($bdate) {
    if (!$bdate) return '';
    $b = new DateTime($bdate);
    $t = new DateTime('today');
    return $b->diff($t)->y;
  }
  ?>
  
  <table id="students_table" class="table table-striped table-bordered" style="width:100%">
    <thead class="table-info">
      <tr>
        <th>#</th>
        <th>Photo</th>
        <th>Full Name</th>
        <th>Grade Level</th>
        <th>Sex</th>
        <th>Age</th>
        <th>LRN</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php 
      $i = 1;
      while ($row = mysqli_fetch_assoc($res)) {
        $full = strtoupper(
    trim(
        ($row['last_name'] ?? '') . ', ' .
        ($row['first_name'] ?? '') . ' ' .
        ($row['middle_name'] ?? '') . ' ' .
        ($row['extension_name'] ?? '')
    )
        );
        $age = compute_age($row['birthdate'] ?? null);
        // use saved photo if available, otherwise fallback
        $photo = !empty($row['photo_url']) ? $row['photo_url'] : "../../assets/img/profile.png";
        ?>
        <tr>
          <td class="align-middle text-end" width="1%"><?= $i++ ?>.</td>
          <td class="align-middle" width="1%">
            <img src="<?= htmlspecialchars($photo) ?>" 
                 width="50" height="50" 
                 style="object-fit:cover;border-radius:50%;">
          </td>
          <td class="align-middle"><?= htmlspecialchars($full) ?></td>
          <td class="align-middle"><?= htmlspecialchars($row['level_descrition']) ?></td>
          <td class="align-middle"><?= htmlspecialchars($row['sex']) ?></td>
          <td class="align-middle"><?= htmlspecialchars($age) ?></td>
          <td class="align-middle"><?= htmlspecialchars($row['learner_ref_no']) ?></td>
          <td width="1%" class="align-middle text-nowrap">

<div class="btn-group" role="group" aria-label="Basic mixed styles example">
  <button type="button" class="btn btn-danger btn-view" data-id="<?= (int)$row['autoid'] ?>"><i class="bi bi-eye"></i></button>
  <button title="Edit this record" type="button" class="btn btn-warning btn-edit" data-id="<?= (int)$row['autoid'] ?>"><i class="bi bi-pencil"></i></button>
  <button title="Delete this record" type="button" class="btn btn-success btn-delete" data-id="<?= (int)$row['autoid'] ?>"><i class="bi bi-trash"></i></button>
</div>

          </td>
        </tr>
      <?php } ?>
    </tbody>
  </table>

  <script>
    $(function(){
      $('#students_table').DataTable({
        responsive: true,
        pageLength: 10
      });
    });
  </script>
  <?php
}




?>