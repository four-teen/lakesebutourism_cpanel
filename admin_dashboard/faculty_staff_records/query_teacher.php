<?php
// admin_dashboard/faculty_staff_records/query_teacher.php
session_start();
require_once __DIR__ . '/../../modules/auth/session_guard.php';
require_role(['Administrator']);

require_once __DIR__ . '/../../db.php';

// ---------- HTML fragment: faculty list ----------


if(isset($_POST['saving_details'])){

    // Sanitize inputs
    $designationid = mysqli_real_escape_string($conn, $_POST['designationid']);
    $gradelevelid = mysqli_real_escape_string($conn, $_POST['gradelevelid']);
    $sectionid = mysqli_real_escape_string($conn, $_POST['sectionid']);
    $ayid = mysqli_real_escape_string($conn, $_POST['ayid']);
    $the_teacher = mysqli_real_escape_string($conn, $_POST['the_teacher']);

    // Use ON DUPLICATE KEY UPDATE
    $insert = "INSERT INTO `tblassigned_designation` 
               (`ass_ayid`, `ass_teachersautoid`, `ass_designationid`, `ass_gradelevelid`, `ass_sectionid`) 
               VALUES ('$ayid', '$the_teacher', '$designationid', '$gradelevelid', '$sectionid')
               ON DUPLICATE KEY UPDATE 
               `ass_designationid` = VALUES(`ass_designationid`),
               `ass_gradelevelid` = VALUES(`ass_gradelevelid`),
               `ass_sectionid` = VALUES(`ass_sectionid`)";
    
    $runinsert = mysqli_query($conn, $insert);

    if($runinsert) {
        if(mysqli_affected_rows($conn) > 0) {
            echo "Success: Operation completed successfully!";
        } else {
            echo "Info: No changes were made.";
        }
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}

if(isset($_POST['loading_details'])){
  $check = $_POST['designationid'];

  if($check == 5 || $check == 15){
      echo
      ''; ?>
        <div class="row">
          <div class="col-lg-6">
              <label for="gradelevelid">Grade Level</label>
              <select id="gradelevelid" class="form-control">
                <?php 
                  $getgradelevel = "SELECT * FROM `tblgradelevel`";
                  $rungetgradelevel = mysqli_query($conn, $getgradelevel);
                  while($rowgradelevel = mysqli_fetch_assoc($rungetgradelevel)){
                    echo'<option value="'.$rowgradelevel['levelid'].'">'.$rowgradelevel['level_descrition'].'</option>';
                  }
                ?>
                
              </select>
          </div>
          <div class="col-lg-6">
              <label for="sectionid">Section</label>
              <select id="sectionid" class="form-control">
                <?php 
                  $getgradesections = "SELECT * FROM `tblsections`";
                  $rungetgradesections = mysqli_query($conn, $getgradesections);
                  while($rowgradesections = mysqli_fetch_assoc($rungetgradesections)){
                    echo'<option value="'.$rowgradesections['sectionsid'].'">'.$rowgradesections['section_desc'].'</option>';
                  }
                ?>
                
              </select>
          </div>

        </div>
      <?php echo '';
  }else{
      echo
      ''; ?>
      <div class="d-none">
        <div class="row">
          <div class="col-lg-6">
              <label for="gradelevelid">Grade Level</label>
              <select id="gradelevelid" class="form-control">
                <option value="0">None</option>
              </select>
          </div>
          <div class="col-lg-6">
              <label for="sectionid">Section</label>
              <select id="sectionid" class="form-control">
                <option value="0">None</option>
              </select>
          </div>

        </div>        
      </div>

      <?php echo '';
  }



  exit;
}


if (isset($_POST['loading_faculty_records'])) {
  // Serve HTML, not JSON
  header('Content-Type: text/html; charset=UTF-8');

  // Small helper to resolve image path (relative to teacher_profile.php)
  function teacher_img_src($row){
    $p = trim($row['teacher_image'] ?? '');
    if ($p === '') return '../../../assets/img/profile.png';
    // stored like 'uploads/teachers/file.jpg' => prefix for page location
    return '../../' . ltrim($p, '/');
  }
  ?>
  <div class="table-responsive">
    <table id="tblTeachers" class="table table-striped table-bordered w-100">
      <thead>
        <tr>
          <th style="width:1%">Photo</th>
          <th>ID</th>
          <th>Full Name</th>
          <th>Designation</th>
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
            echo '  <td class="align-middle text-center" width="1%"><img class="avatar" src="'.htmlspecialchars($img).'" onerror="this.src=\'../../assets/img/profile.png\'"></td>';
            echo '  <td class="align-middle" width="1%">'.htmlspecialchars($row['teachersid']).'</td>';
            echo '  <td class="align-middle"><b>'.htmlspecialchars($row['lastname']).'</b>, '.htmlspecialchars(ucwords(strtolower($row['firstname']))).' '.htmlspecialchars($row['middlename']).'</td>';
            echo '  
                  <td class="align-middle">
                  '; ?>
                  <?php 
                    $getdesignations = "SELECT * FROM `tblassigned_designation`
                    INNER JOIN tbldesignation on tbldesignation.designationid = tblassigned_designation.ass_designationid
                    INNER JOIN tblgradelevel on tblgradelevel.levelid=tblassigned_designation.ass_gradelevelid
                    LEFT JOIN tblsections on tblsections.sectionsid=tblassigned_designation.ass_sectionid
                    WHERE ass_teachersautoid='$row[teachersautoid]'";
                    $rungetdesig = mysqli_query($conn, $getdesignations);
                    $rowgetdesignation = mysqli_fetch_assoc($rungetdesig);
                    echo strtoupper($rowgetdesignation['designation_desc']) . 
     (($rowgetdesignation['level_descrition'] !== 'None' && !empty($rowgetdesignation['level_descrition'])) ? ' | ' . strtoupper($rowgetdesignation['level_descrition']) : '') .
     (($rowgetdesignation['section_desc'] !== 'None' && !empty($rowgetdesignation['section_desc'])) ? ' ' . strtoupper($rowgetdesignation['section_desc']) : '');
                  ?>
                  <?php echo'
                  </td>
                  ';
            echo '  <td width="1%" class="text-center text-nowrap align-middle">
                      <div class="btn-group py-2" role="group" aria-label="Basic mixed styles example">
                          <button title="More Information" class="btn btn-sm btn-info" data-id="'.$row['teachersautoid'].'" onclick="Teacherinfo(this)"><i class="bx bx-user"></i></button>
                          <button title="Manage class schedule" class="btn btn-sm btn-warning" data-id="'.$row['teachersautoid'].'" onclick="manageTeacher(this)"><i class="bx bx-command"></i></button>
                          <button title="Edit Profile" class="btn btn-sm btn-primary" data-id="'.$row['teachersautoid'].'" onclick="editTeacher(this)"><i class="bi bi-pencil"></i></button>
                          <button title="Delete Profile" class="btn btn-sm btn-danger" data-id="'.$row['teachersautoid'].'" onclick="delTeacher(this)"><i class="bi bi-trash"></i></button>
                      </div>
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
