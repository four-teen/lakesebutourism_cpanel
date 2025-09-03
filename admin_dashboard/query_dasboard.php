<?php
  session_start();
  require_once __DIR__ . '/../modules/auth/session_guard.php';
  require_role(['Administrator']); // only admins can access

  include_once __DIR__ . '/../db.php';

  if(isset($_POST['updating_ay'])){
    $ayids = $_POST['ayids'];

    $delete = "DELETE FROM `tblsettings`";
    $rundelete = mysqli_query($conn, $delete);

    $insert = "INSERT INTO `tblsettings` (`ayid`) VALUES ('$ayids')";
    $runinsert = mysqli_query($conn, $insert);
  }

  if(isset($_POST['loading_ay'])){
    $settings = "SELECT * FROM `tblsettings`
    INNER JOIN tblacademic_years on tblacademic_years.ayid=tblsettings.ayid LIMIT 1";
    $runsettings = mysqli_query($conn, $settings);
    $rowsettings = mysqli_fetch_assoc($runsettings);
    $ay = $rowsettings['ayfrom'].'-'.$rowsettings['ayto'];
    echo '<span class="text-danger">'.$ay.'</span>';
  }


?>