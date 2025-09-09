
<?php
// Always start the session here so every page using the guard is safe
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

// Compute app base (e.g., /bteslife) safely
$base = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\');
$index = ($base === '' || $base === '/') ? '/index.php' : $base . '/index.php';
$no_access = ($base === '' || $base === '/') ? '/no_access.php' : $base . '/no_access.php';

if (empty($_SESSION['LOGGED_IN'])) {
  header("Location: $index");
  exit;
}

function require_role($roles = []) {
  if (!$roles) return;
  $userRole = $_SESSION['TYPE'] ?? '';
  if (!in_array($userRole, $roles, true)) {
    // reuse computed base-safe no_access
    $base = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\');
    $no_access = ($base === '' || $base === '/') ? '/no_access.php' : $base . '/no_access.php';
    header("Location: $no_access");
    exit;
  }
}
