<?php
session_start();
require_once __DIR__ . '/../modules/auth/session_guard.php';
require_role(['Administrator']);
include_once __DIR__ . '/../db.php';

header('Content-Type: application/json');

// $ay = isset($_GET['ay']) ? (int)$_GET['ay'] : 0;  // ay = tblacademic_years.ayid
$ay = isset($_GET['ay']) && (int)$_GET['ay'] > 0 
        ? (int)$_GET['ay'] 
        : 2;   // default to AY=2

// labels = all grade levels (even with zero)
// $sql = "SELECT g.levelid, g.level_descrition,
//         COALESCE(SUM(CASE WHEN s.ay = ? THEN 1 ELSE 0 END),0) AS total
//         FROM tblgradelevel g
//         LEFT JOIN tblstudents s ON s.grade_level = g.levelid
//         GROUP BY g.levelid, g.level_descrition
//         ORDER BY g.levelid ASC";
$sql = "SELECT g.levelid, g.level_descrition,
               COUNT(s.autoid) AS total
        FROM tblgradelevel g
        LEFT JOIN tblstudents s 
          ON s.grade_level = g.levelid 
         AND s.ay = ?
        GROUP BY g.levelid, g.level_descrition
        ORDER BY g.levelid ASC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $ay);
$stmt->execute();
$res = $stmt->get_result();

$labels = [];
$data   = [];
while($r = $res->fetch_assoc()){
  $labels[] = $r['level_descrition'];
  $data[]   = (int)$r['total'];
}

// Also return AY options (for first load convenience)
$ays = [];
$qay = mysqli_query($conn, "SELECT ayid, CONCAT(ayfrom,'-',ayto) AS ay_label FROM tblacademic_years ORDER BY ayfrom DESC");
while($row = mysqli_fetch_assoc($qay)){
  $ays[] = $row;
}

echo json_encode([
  'labels' => $labels,
  'series' => $data,
  'ay_options' => $ays
]);
