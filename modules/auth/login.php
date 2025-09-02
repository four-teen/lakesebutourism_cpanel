<?php
header('Content-Type: application/json; charset=utf-8');
session_start();
require_once __DIR__ . '/../../db.php';
include_once __DIR__ . '/../../logger.php';

function fail($msg, $code = 400){
  log_event("LOGIN FAIL: $msg");
  http_response_code($code);
  echo json_encode(['ok'=>false, 'msg'=>$msg]);
  exit;
}

$user    = trim($_POST['user'] ?? '');
$pass    = trim($_POST['pass'] ?? '');
$type_id = (int)($_POST['type_id'] ?? 0);

log_event("LOGIN ATTEMPT: user='$user', type_id=$type_id");

if ($user === '' || $pass === '' || $type_id <= 0) {
  fail('Missing fields.');
}

$sql = "SELECT a.acc_id, a.acc_username, a.acc_password, a.acc_fullname, a.acc_email,
               a.acc_status, a.acc_type_id, t.type_name
        FROM tblaccounts a
        INNER JOIN tblaccount_type t ON t.type_id = a.acc_type_id
        WHERE (a.acc_username = ? OR a.acc_email = ?) LIMIT 1";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, 'ss', $user, $user);
mysqli_stmt_execute($stmt);
$res = mysqli_stmt_get_result($stmt);
$row = mysqli_fetch_assoc($res);

if (!$row) {
  fail('User not found.');
}
if ($row['acc_status'] !== 'Active') {
  fail('Account inactive.');
}
if ((int)$row['acc_type_id'] !== $type_id) {
  fail("Role mismatch. Expected={$row['acc_type_id']} Selected={$type_id}");
}
if (!password_verify($pass, $row['acc_password'])) {
  fail('Wrong password.');
}

$_SESSION['ACC_ID']    = (int)$row['acc_id'];
$_SESSION['USERNAME']  = $row['acc_username'];
$_SESSION['FULLNAME']  = $row['acc_fullname'];
$_SESSION['EMAIL']     = $row['acc_email'];
$_SESSION['TYPE_ID']   = (int)$row['acc_type_id'];
$_SESSION['TYPE']      = $row['type_name'];
$_SESSION['LOGGED_IN'] = true;

log_event("LOGIN SUCCESS: user='{$row['acc_username']}', role='{$row['type_name']}'");

// role-based redirect
$redirect = 'dashboard.php';
switch ($row['type_name']) {
  case 'Administrator': $redirect = 'admin_dashboard/dashboard_admin.php';   break;
  case 'Teacher':       $redirect = 'dashboard_teacher.php'; break;
  case 'Staff':         $redirect = 'dashboard_staff.php';   break;
}

echo json_encode(['ok'=>true, 'role'=>$row['type_name'], 'redirect'=>$redirect]);
