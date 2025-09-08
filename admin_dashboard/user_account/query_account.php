<?php
session_start();
require_once __DIR__ . '/../../modules/auth/session_guard.php';
require_role(['Administrator']);
include_once __DIR__ . '/../../db.php';

// Load accounts
if(isset($_POST['loading_account'])){
  ?>
  <div class="table-responsive">
    <table id="tblAccounts" class="table table-striped table-bordered w-100">
      <thead>
        <tr>
          <th>#</th>
          <th>Username</th>
          <th>Faculty</th>
          <th>Email</th>
          <th>Type</th>
          <th>Status</th>
          <th>Created</th>
          <th width="120">Actions</th>
        </tr>
      </thead>
      <tbody>
      <?php
      $select = "SELECT a.*, at.type_name,
                 CONCAT(t.lastname, ', ', t.firstname, ' ', t.middlename) AS faculty_name
                 FROM tblaccounts a
                 INNER JOIN tblaccount_type at ON at.type_id = a.acc_type_id
                 LEFT JOIN tblteachers t ON t.teachersautoid = a.teacher_id
                 ORDER BY a.created_at DESC";
      $run = mysqli_query($conn,$select);
      $count = 0;
      while($row=mysqli_fetch_assoc($run)){
        $who = $row['faculty_name'] ? strtoupper($row['faculty_name']) : ($row['acc_fullname'] ?: '—');
        echo '
        <tr>
          <td class="align-middle text-end" width="1%">'.++$count.'.</td>
          <td class="align-middle">'.htmlspecialchars(strtoupper($row['acc_username'])).'</td>
          <td class="align-middle">'.htmlspecialchars($who).'</td>
          <td class="align-middle">'.htmlspecialchars($row['acc_email']).'</td>
          <td class="align-middle">'.htmlspecialchars($row['type_name']).'</td>
          <td class="align-middle">'.htmlspecialchars($row['acc_status']).'</td>
          <td class="align-middle">'.htmlspecialchars($row['created_at']).'</td>
          <td width="1%" class="text-nowrap">
            <div class="btn-group" role="group" aria-label="actions">
              <button type="button" class="btn btn-danger" onclick="edit_account(\''.$row['acc_id'].'\')"><i class="bx bx-edit"></i></button>
              <button type="button" class="btn btn-warning" onclick="delete_account(\''.$row['acc_id'].'\')"><i class="bx bx-trash"></i></button>
            </div>
          </td>
        </tr>';
      }
      ?>
      </tbody>
    </table>
  </div>
  <?php
  exit;
}


// Save (Insert/Update)
if(isset($_POST['save_account'])){
  $acc_id       = $_POST['acc_id'];
  $teacher_id   = ($_POST['teacher_id'] !== '' ? $_POST['teacher_id'] : NULL);
  $acc_username = trim($_POST['acc_username']);
  $acc_password = $_POST['acc_password'];
  $acc_email    = trim($_POST['acc_email']);
  $acc_type_id  = (int)$_POST['acc_type_id'];
  $acc_status   = $_POST['acc_status'];

  // duplicate username (exclude self)
  $qUser = "SELECT COUNT(*) c FROM tblaccounts WHERE acc_username='$acc_username'".
           ($acc_id ? " AND acc_id <> '$acc_id'" : "");
  $cUser = mysqli_fetch_assoc(mysqli_query($conn,$qUser))['c'];
  if($cUser > 0){ http_response_code(409); echo 'DUP_USERNAME'; exit; }

  // duplicate faculty link (exclude self)
  if($teacher_id){
    $qTeach = "SELECT COUNT(*) c FROM tblaccounts WHERE teacher_id='$teacher_id'".
              ($acc_id ? " AND acc_id <> '$acc_id'" : "");
    $cTeach = mysqli_fetch_assoc(mysqli_query($conn,$qTeach))['c'];
    if($cTeach > 0){ http_response_code(409); echo 'DUP_TEACHER'; exit; }
  }

  // derive fullname from tblteachers if teacher_id is set
  $derived_fullname = "(
    SELECT CONCAT(lastname, ', ', firstname, ' ', middlename)
    FROM tblteachers WHERE teachersautoid = ".($teacher_id ? "'$teacher_id'" : "NULL")."
  )";

  if($acc_id == ""){ // INSERT
    $hashpass = password_hash($acc_password, PASSWORD_BCRYPT);
    $insert = "INSERT INTO tblaccounts
               (teacher_id, acc_username, acc_password, acc_fullname, acc_email, acc_type_id, acc_status)
               VALUES (".($teacher_id ? "'$teacher_id'" : "NULL").",
                       '$acc_username',
                       '$hashpass',
                       ".($teacher_id ? $derived_fullname : "NULL").",
                       '$acc_email',
                       '$acc_type_id',
                       '$acc_status')";
    mysqli_query($conn,$insert);
  }else{ // UPDATE
    if($acc_password != ""){
      $hashpass = password_hash($acc_password, PASSWORD_BCRYPT);
      $update = "UPDATE tblaccounts SET
                 teacher_id=".($teacher_id ? "'$teacher_id'" : "NULL").",
                 acc_username='$acc_username',
                 acc_password='$hashpass',
                 acc_fullname=".($teacher_id ? $derived_fullname : "acc_fullname").",
                 acc_email='$acc_email',
                 acc_type_id='$acc_type_id',
                 acc_status='$acc_status'
                 WHERE acc_id='$acc_id'";
    }else{
      $update = "UPDATE tblaccounts SET
                 teacher_id=".($teacher_id ? "'$teacher_id'" : "NULL").",
                 acc_username='$acc_username',
                 acc_fullname=".($teacher_id ? $derived_fullname : "acc_fullname").",
                 acc_email='$acc_email',
                 acc_type_id='$acc_type_id',
                 acc_status='$acc_status'
                 WHERE acc_id='$acc_id'";
    }
    mysqli_query($conn,$update);
  }
  exit;
}


if(isset($_POST['get_account'])){
  $acc_id = $_POST['acc_id'];
  $sel = "SELECT a.acc_id, a.teacher_id, a.acc_username, a.acc_email, a.acc_type_id, a.acc_status
          FROM tblaccounts a
          WHERE a.acc_id='$acc_id' LIMIT 1";
  $run = mysqli_query($conn,$sel);
  $row = mysqli_fetch_assoc($run);
  echo json_encode($row ?: []);
  exit;
}

// Delete
if(isset($_POST['delete_account'])){
  $acc_id = $_POST['acc_id'];
  $delete = "DELETE FROM tblaccounts WHERE acc_id='$acc_id'";
  mysqli_query($conn,$delete);
}
?>
