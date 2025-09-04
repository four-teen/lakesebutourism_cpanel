<?php
// admin_dashboard/faculty_staff_records/query_teacher.php
session_start();
require_once __DIR__ . '/../../modules/auth/session_guard.php';
require_role(['Administrator']);

require_once __DIR__ . '/../../db.php';

// ---------- HTML fragment: faculty list ----------
if (isset($_POST['loading_faculty_records'])) {
  // Serve HTML, not JSON
  header('Content-Type: text/html; charset=UTF-8');

  // Small helper to resolve image path (relative to teacher_profile.php)
  function teacher_img_src($row){
    $p = trim($row['teacher_image'] ?? '');
    if ($p === '') return '../../assets/img/avatar-placeholder.png';
    // stored like 'uploads/teachers/file.jpg' => prefix for page location
    return '../../' . ltrim($p, '/');
  }
  ?>
  <div class="table-responsive">
    <table id="tblTeachers" class="table table-striped table-bordered w-100">
      <thead>
        <tr>
          <th style="width:60px">Photo</th>
          <th>Teacher ID</th>
          <th>Last Name</th>
          <th>First Name</th>
          <th>Middle</th>
          <th style="width:110px">Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php
          $q = "SELECT teachersautoid, teachersid, firstname, middlename, lastname, teacher_image
                FROM tblteachers ORDER BY lastname, firstname";
          $rs = mysqli_query($conn, $q);
          while ($row = mysqli_fetch_assoc($rs)) {
            $img = teacher_img_src($row);
            echo '<tr>';
            echo '  <td><img class="avatar" src="'.htmlspecialchars($img).'" onerror="this.src=\'../../assets/img/avatar-placeholder.png\'"></td>';
            echo '  <td>'.htmlspecialchars($row['teachersid']).'</td>';
            echo '  <td>'.htmlspecialchars($row['lastname']).'</td>';
            echo '  <td>'.htmlspecialchars($row['firstname']).'</td>';
            echo '  <td>'.htmlspecialchars($row['middlename']).'</td>';
            echo '  <td class="text-center">
                      <button class="btn btn-sm btn-primary btn-icon me-1" data-id="'.$row['teachersautoid'].'" onclick="editTeacher(this)"><i class="bi bi-pencil"></i></button>
                      <button class="btn btn-sm btn-danger btn-icon" data-id="'.$row['teachersautoid'].'" onclick="delTeacher(this)"><i class="bi bi-trash"></i></button>
                    </td>';
            echo '</tr>';
          }
        ?>
      </tbody>
    </table>
  </div>
  <script>
    // Initialize DataTable after fragment inject
    $('#tblTeachers').DataTable({
      pageLength: 10,
      lengthChange: false,
      order: [[2,'asc'],[3,'asc']]
    });
  </script>
  <?php
  exit; // IMPORTANT: stop here so we don't print JSON
}

// ---------- JSON endpoints below ----------
header('Content-Type: application/json');

function j($ok, $msg = '', $extra = []) {
  echo json_encode(array_merge(['ok'=>$ok, 'msg'=>$msg], $extra));
  exit;
}

// GET teacher
if (isset($_GET['get_teacher'])) {
  $id = intval($_GET['teachersautoid'] ?? 0);
  if ($id <= 0) j(false, 'Invalid ID');

  $q = "SELECT teachersautoid, teachersid, firstname, middlename, lastname, teacher_image
        FROM tblteachers WHERE teachersautoid=$id LIMIT 1";
  $rs = mysqli_query($conn, $q);
  if ($rs && mysqli_num_rows($rs) === 1) {
    j(true, '', ['data' => mysqli_fetch_assoc($rs)]);
  }
  j(false, 'Teacher not found');
}

// SAVE teacher (add/edit)
if (isset($_POST['save_teacher'])) {
  $id   = intval($_POST['teachersautoid'] ?? 0);
  $tid  = mysqli_real_escape_string($conn, trim($_POST['teachersid'] ?? ''));
  $fn   = mysqli_real_escape_string($conn, trim($_POST['firstname'] ?? ''));
  $ln   = mysqli_real_escape_string($conn, trim($_POST['lastname'] ?? ''));
  $mn   = mysqli_real_escape_string($conn, trim($_POST['middlename'] ?? ''));
  $old  = mysqli_real_escape_string($conn, trim($_POST['current_image'] ?? ''));

  if ($tid === '' || $fn === '' || $ln === '') j(false, 'Teacher ID, Firstname, and Lastname are required.');

  // handle image upload
  $imgPath = $old;
  if (!empty($_FILES['teacher_image']['name'])) {
    $folder = __DIR__ . '/../../uploads/teachers/';
    if (!is_dir($folder)) mkdir($folder, 0777, true);

    $ext      = pathinfo($_FILES['teacher_image']['name'], PATHINFO_EXTENSION);
    $filename = time().'_'.preg_replace('/[^A-Za-z0-9_\-]/','', $tid).'.'.$ext;
    $target   = $folder.$filename;

    if (move_uploaded_file($_FILES['teacher_image']['tmp_name'], $target)) {
      $imgPath = 'uploads/teachers/'.$filename; // relative path for DB
    } else {
      j(false, 'Failed to upload image.');
    }
  }

  if ($id > 0) {
    $sql = "UPDATE tblteachers SET
              teachersid='$tid',
              firstname='$fn',
              middlename='$mn',
              lastname='$ln',
              teacher_image='$imgPath'
            WHERE teachersautoid=$id";
    if (mysqli_query($conn, $sql)) j(true);
    j(false, 'Update failed: '.mysqli_error($conn));
  } else {
    $chk = mysqli_query($conn, "SELECT 1 FROM tblteachers WHERE teachersid='$tid' LIMIT 1");
    if ($chk && mysqli_num_rows($chk) > 0) j(false, 'Teacher ID already exists.');
    $sql = "INSERT INTO tblteachers (teachersid, firstname, middlename, lastname, teacher_image)
            VALUES ('$tid','$fn','$mn','$ln','$imgPath')";
    if (mysqli_query($conn, $sql)) j(true);
    j(false, 'Insert failed: '.mysqli_error($conn));
  }
}

// DELETE teacher
if (isset($_POST['delete_teacher'])) {
  $id = intval($_POST['teachersautoid'] ?? 0);
  if ($id <= 0) j(false, 'Invalid ID');

  if (mysqli_query($conn, "DELETE FROM tblteachers WHERE teachersautoid=$id LIMIT 1")) j(true);
  j(false, 'Delete failed: '.mysqli_error($conn));
}

j(false, 'No action');
