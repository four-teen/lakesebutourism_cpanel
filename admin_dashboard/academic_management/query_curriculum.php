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

  if(isset($_POST['delete_curr'])){
    $delete = "DELETE FROM `tblcurriculum` where currid='$_POST[currid]'";
    $rundelete = mysqli_query($conn, $delete);
  }

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
            <th>SUBJECT DESCRIPTION</th>
            <th>ACTION</th>
          </tr>
        </thead>
        <tbody>
          <?php 
            $select = "SELECT * FROM `tblcurriculum`
            INNER JOIN tblsubjects on tblsubjects.subjectid=tblcurriculum.subjectid
            INNER JOIN tblgradelevel on tblgradelevel.levelid=tblcurriculum.levelid
            INNER JOIN tblacademic_years on tblacademic_years.ayid=tblcurriculum.setid
            WHERE tblgradelevel.levelid='$_POST[gradelevelid]'";
            $runselect = mysqli_query($conn, $select);
            $count = 0;
            while($rowselect = mysqli_fetch_assoc($runselect)){
              echo
              '
              <tr>
                
              '?>
              <?php 
                if($rowselect['subjectid']=='10001'){
                  echo'
                  <td class="align-middle" width="1%"></td>
                  <td class="align-middle"><i><span class="text-danger">'.strtoupper($rowselect['subject_description']).'</span></i></td>
                  ';
                }else if($rowselect['subjectid']=='10002'){
                  echo'
                  <td class="align-middle" width="1%"></td>
                  <td class="align-middle"><i><span class="text-danger">'.strtoupper($rowselect['subject_description']).'</span></i></td>';
                }else{
                  echo'
                  <td class="align-middle" width="1%">'.++$count.'.</td>
                  <td class="align-middle">'.strtoupper($rowselect['subject_description']).'
                  </td>';
                }
              ?>
              <?php echo'
                
                <td class="align-middle text-center text-nowrap" width="1%">
                  <button onclick="removed_curr(\''.$rowselect['currid'].'\')" type="button" class="btn btn-danger"><i class="bx bx-trash"></i>
                  </button>
                </td>
              </tr>
              ';
            }

          ?>

        </tbody>
      </table>
    <?php echo'';
  }

?>