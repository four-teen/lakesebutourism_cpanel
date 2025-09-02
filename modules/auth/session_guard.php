<?php
// modules/auth/session_guard.php

if (empty($_SESSION['LOGGED_IN'])) {
  header('Location: /index.php');
  exit;
}

function require_role($roles = []) {
  if (!$roles) return;
  $userRole = $_SESSION['TYPE'] ?? '';
  if (!in_array($userRole, $roles, true)) {
    header('Location: /no_access.php');
    exit;
  }
}
