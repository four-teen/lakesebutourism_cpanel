<?php
  session_start();
  require_once __DIR__ . '/../../modules/auth/session_guard.php';
  require_role(['Administrator']); // only admins can access

  include_once __DIR__ . '/../../db.php';


  $settings = "SELECT * FROM `tblsettings`
  INNER JOIN tblacademic_years on tblacademic_years.ayid=tblsettings.ayid LIMIT 1";
  $runsettings = mysqli_query($conn, $settings);
  $rowsettings = mysqli_fetch_assoc($runsettings);
  $ayid = $rowsettings['ayid'];


  // ===================================================================

  if(isset($_POST['saving_curr'])){
    $gradelevelid = $_POST['gradelevelid'];
    $subjectids = $_POST['subjectids'];    
    $insert = "INSERT INTO `tblcurriculum` (`levelid`, `subjectid`, `setid`) VALUES ('$gradelevelid', '$subjectids', '$ayid')";
    $runinsert = mysqli_query($conn, $insert);
    echo $insert;
  }

  if(isset($_POST['loading_curr'])){
    echo
    '';?>
      <table class="table table-sm table-striped table-hover">
        <thead>
          <tr>
            <th>#</th>
            <th></th>
            <th></th>
          </tr>
        </thead>
        <tbody>
          <?php 
            $select = "SELECT * FROM `tblcurriculum`";
            $runselect = mysqli_query($conn, $select);
            while($rowselect = mysqli_fetch_assoc($runselect)){
              echo
              '
              <tr>
                <td></td>
                <td></td>
                <td></td>
              </tr>
              ';
            }

          ?>

        </tbody>
      </table>
    <?php echo'';
  }

?>