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


  if(isset($_POST['loading_curriculum_review'])){
      echo '<div class="row g-3">';

       $select = "SELECT DISTINCT tblsections.section_desc,tblgradelevel.level_descrition, tblgradelevel.levelid,tblsections.sectionsid FROM `tblcurriculum` 
      INNER JOIN tblgradelevel on tblgradelevel.levelid=tblcurriculum.gradelevelid
      INNER JOIN tblsections on tblsections.sectionsid=tblcurriculum.sectID";
        $runselect = mysqli_query($conn, $select);

      while ($rowselect = mysqli_fetch_assoc($runselect)) {
       
          echo '
          <div class="col-md-6 col-lg-4">
            <div class="card subject-card h-100 shadow-sm" role="button">
              <div class="card-header d-flex justify-content-between align-items-center">
                <span>'.$rowselect['level_descrition'].'</span>
                  <span class="badge-pill cta-badge">
                    <i class="bi bi-backpack4"></i> Show Entries
                  </span>
              </div>

              <div class="card-body">
                <div class="subject-body">
                  <img src="../../assets/img/subject_logo.png" alt="Subject image" class="subject-thumb">

                  <div class="flex-grow-1 subject-content">
                    <div class="subject-header">
                      <h5 class="subject-title">
                        '.strtoupper($rowselect['section_desc']).'
                        
                      </h5>
                    </div>

                    <div class="subject-metric">
                      <div class="big-metric" id="rec-count-2">
                      '; ?>
                      <?php 
                        $select_count = "SELECT * FROM `tblcurriculum` WHERE gradelevelid='$rowselect[levelid]' AND sectID='$rowselect[sectionsid]' AND (subjectid != '10001' AND subjectid != '10002')";
                        $runselect_count = mysqli_query($conn, $select_count);
                        echo mysqli_num_rows($runselect_count);
                      ?>
                      <?php echo'
                      </div>
                      <div class="metric-label">subject(s)</div>
                    </div>
                  </div>
                </div>
                <a href="javascript:void(0)" class="stretched-link" aria-hidden="true"></a>
              </div>

              <div class="card-footer text-muted">
                
                 
              '; ?>
              <?php 
                $get_adviser = "SELECT * FROM `tblassigned_designation`
                INNER JOIN tblteachers on tblteachers.teachersautoid=tblassigned_designation.ass_teachersautoid
                INNER JOIN tbldesignation on tbldesignation.designationid=tblassigned_designation.ass_designationid
                WHERE ass_gradelevelid='$rowselect[levelid]' and ass_sectionid='$rowselect[sectionsid]'";
                $rungetadvise = mysqli_query($conn, $get_adviser);
                $rowadviser = mysqli_fetch_assoc($rungetadvise);

                echo'ADVISER: '.$rowadviser['firstname'].', '.$rowadviser['middlename'].' '.$rowadviser['lastname'];
              ?>
              <?php echo'

              </div>
            </div>
          </div>';
      }

      echo '</div>';
      exit;

  }

  if(isset($_POST['delete_curr'])){
    $delete = "DELETE FROM `tblcurriculum` where currid='$_POST[currid]'";
    $rundelete = mysqli_query($conn, $delete);
  }

  if(isset($_POST['edit_curr'])){
    $usubjectids = $_POST['usubjectids']; 
    $utimefrom = $_POST['utimefrom'];
    $utimeto = $_POST['utimeto'];
    $curriculum_ids = $_POST['curriculum_ids'];


    $update = "UPDATE `tblcurriculum` SET `timefrom`='$utimefrom', `timeto`='$utimeto', `subjectid`='$usubjectids' where currid='$curriculum_ids'";
    $runupdate = mysqli_query($conn, $update);

  }

  if(isset($_POST['saving_curr'])){
    $gradelevelid = $_POST['gradelevelid'];
    $subjectids = $_POST['subjectids']; 
    $timefrom = $_POST['timefrom'];
    $timeto = $_POST['timeto'];
    $sectionids = $_POST['sectionids'];    

    $insert = "INSERT INTO `tblcurriculum` (`gradelevelid`, `timefrom`, `timeto`, `subjectid`, `ayid`, `sectID`) VALUES ('$gradelevelid', '$timefrom', '$timeto', '$subjectids', '$ayid', '$sectionids')";
    $runinsert = mysqli_query($conn, $insert);
    // echo $insert;
  }

  if(isset($_POST['loading_curr'])){
    echo
    '';?>
      <table class="table table-sm table-striped table-hover">
        <thead>
          <tr>
            <th>#</th>
            <th>TIME</th>
            <th></th>
            <th>SUBJECT DESCRIPTION</th>
            <th>ACTION</th>
          </tr>
        </thead>
        <tbody>
          <?php 
            $select = "SELECT * FROM `tblcurriculum`
            INNER JOIN tblsubjects on tblsubjects.subjectid=tblcurriculum.subjectid
            WHERE gradelevelid='$_POST[gradelevelid]' AND sectID='$_POST[sectionids]'";
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
                  <td colspan="4" class="align-middle"><i><span class="text-danger">('.strtoupper(date('h:i', strtotime($rowselect['timefrom']))).' - '.strtoupper(date('h:i', strtotime($rowselect['timeto']))).') '.strtoupper($rowselect['subject_description']).'</span></i></td>
                  ';
                }else if($rowselect['subjectid']=='10002'){
                  echo'
                  <td colspan="4"  class="align-middle"><i><span class="text-danger">('.strtoupper(date('h:i', strtotime($rowselect['timefrom']))).' - '.strtoupper(date('h:i', strtotime($rowselect['timeto']))).') '.strtoupper($rowselect['subject_description']).'</span></i></td>';
                }else{
                  echo'
                  <td class="align-middle" width="1%">'.++$count.'.</td>
                  <td class="align-middle text-nowrap" width="1%">'.strtoupper(date('h:i', strtotime($rowselect['timefrom']))).' - '.strtoupper(date('h:i', strtotime($rowselect['timeto']))).'</td>
                  <td class="align-middle" width="1%"></td>
                  <td class="align-middle">'.strtoupper($rowselect['subject_description']).'
                  </td>';
                }
              ?>
              <?php echo'
                
                <td class="align-middle text-center text-nowrap" width="1%">
                  <button onclick="edit_curr(\''.$rowselect['currid'].'\')" type="button" class="btn btn-warning btn-sm"><i class="bx bx-edit"></i>
                  </button>
                  <button onclick="removed_curr(\''.$rowselect['currid'].'\')" type="button" class="btn btn-danger btn-sm"><i class="bx bx-trash"></i>
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