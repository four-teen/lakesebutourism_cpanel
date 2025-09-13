<?php
// admin_dashboard/faculty_staff_records/query_teacher.php
session_start();
require_once __DIR__ . '/../../modules/auth/session_guard.php';
require_role(['Administrator']);

require_once __DIR__ . '/../../db.php';

// ---------- HTML fragment: faculty workload list ----------

if (isset($_POST['loading_faculty_class_schedule'])) {
  header('Content-Type: text/html; charset=UTF-8');

  // ========= Query =========
  $get_the_teacher = "
    SELECT 
tblteachers.teachersautoid,
tblclass_schedules_teachers.cstid,
tblteachers.firstname,tblteachers.lastname,tblteachers.middlename,
tblcurriculum.timefrom,tblcurriculum.timeto,
tblacademic_years.ayfrom,tblacademic_years.ayto,
tblsubjects.subject_description,
tbldesignation.designation_desc,
tblgradelevel.level_descrition,
tblsections.section_desc
FROM `tblclass_schedules_teachers`
INNER JOIN tblteachers on tblteachers.teachersautoid=tblclass_schedules_teachers.cst_teachersid
INNER JOIN tblcurriculum on tblcurriculum.currid=tblclass_schedules_teachers.cst_classid
INNER JOIN tblsubjects on tblsubjects.subjectid=tblcurriculum.subjectid
INNER JOIN tblacademic_years on tblacademic_years.ayid=tblcurriculum.ayid
INNER JOIN tblassigned_designation on tblassigned_designation.ass_teachersautoid=tblclass_schedules_teachers.cst_teachersid
INNER JOIN tbldesignation on tbldesignation.designationid=tblassigned_designation.ass_designationid
INNER JOIN tblgradelevel on tblgradelevel.levelid=tblassigned_designation.ass_gradelevelid
INNER JOIN tblsections on tblsections.sectionsid = tblassigned_designation.ass_sectionid
    WHERE tblteachers.teachersautoid = '{$_POST['teacherid']}'
  ";
  $runget_the_teacher = mysqli_query($conn, $get_the_teacher);
  $rowgetrecords = mysqli_fetch_assoc($runget_the_teacher);


  // Logos (edit paths)
  $left_logo  = '../../assets/img/barmm.png';
  $right_logo = '../../assets/img/logo.png';

  // Signatories (edit as needed)
  $prepared_by_name  = 'JOHANA M. DIMALILAY';
  $prepared_by_title = 'ACADEMIC COORDINATOR';
  $approved_by_name  = 'FLORA UY SALENDAB';
  $approved_by_title = 'SCHOOL HEAD';
  ?>

<section id="printed">
    <div class="sheet">
      <!-- Header with logos -->
      <div class="header-grid">
        <img class="hdr-logo" src="<?php echo $left_logo ?>" alt="Left Logo">
        <div class="header-top">
          <div><b>Republic of the Philippines</b></div>
          <div><b>MINISTRY OF BASIC, HIGHER, AND TECHNICAL EDUCATION</b></div>
          <div>Division of Maguindanao Del Sur 1</div>
          <div><b>Bangsamoro Autonomous Region in Muslim Mindanao</b></div>
          <div><b>BULUAN TECHNICAL EDUCATION SCHOOL OF Life, Inc.</b></div>
          <div>Poblacion, Buluan, Maguindanao Del Sur, BARMM</div>
        </div>
        <img class="hdr-logo" src="<?php echo $right_logo ?>" alt="Right Logo">
      </div>
      <div class="hr"></div>

      <!-- Title -->
      <div class="title">
        <h2>INDIVIDUAL LOADS</h2>
        <div class="sy">S.Y. <?php echo $rowgetrecords['ayfrom'].'-'.$rowgetrecords['ayto'] ?></div>
      </div>

      <!-- Name / Position -->
      <div class="meta">
        <div>NAME: <b><a><?php echo $rowgetrecords['firstname']. '., '.$rowgetrecords['middlename'].' '.$rowgetrecords['lastname'] ?></a></b></div>
        <div>POSITION: <b><a><?php echo $rowgetrecords['designation_desc'].' '.$rowgetrecords['level_descrition']. ' '.$rowgetrecords['section_desc'] ?></a></b></div>
      </div>

      <!-- Table -->
      <table>
        <thead>
          <tr>
            <th>TIME</th>
            <th>SUBJECT</th>
            <th>YEAR LEVEL</th>
          </tr>
        </thead>
        <tbody>
          <?php 
            $get_subject = "SELECT * FROM `tblclass_schedules_teachers` 
            INNER JOIN tblteachers on tblteachers.teachersautoid=tblclass_schedules_teachers.cst_teachersid
            INNER JOIN tblcurriculum on tblcurriculum.currid=tblclass_schedules_teachers.cst_classid
            INNER JOIN tblsubjects on tblsubjects.subjectid = tblcurriculum.subjectid
            inner JOIN tblgradelevel on tblgradelevel.levelid = tblcurriculum.gradelevelid
            INNER JOIN tblsections on tblsections.sectionsid=tblcurriculum.sectID
            WHERE tblteachers.teachersautoid = '{$_POST['teacherid']}'";
            $runget_subjects = mysqli_query($conn, $get_subject);
            while($rowget_subject = mysqli_fetch_assoc($runget_subjects)){
              echo
              '
              <tr>
                <td>'.$rowget_subject['timefrom'].' - '.$rowget_subject['timeto'].'</td>
                <td>'.$rowget_subject['subject_description'].'</td>
                <td>'.$rowget_subject['level_descrition'].' - '.$rowget_subject['section_desc'].'</td>
              </tr>
              ';
            }
          ?>

        </tbody>
      </table>

      <!-- Signatures -->
      <div class="signatures">
        <div class="sig-block">
          <div class="sig-label">PREPARED BY:</div>
          <div><b>JOHANA M. DIMALILAY</b></div>
          <div class="sig-title">ACADEMIC COORDINATOR</div>
        </div>
      </div>

      <div class="signatures">
        <div class="sig-block">
          <div class="sig-label">APPROVED BY:</div>
          <div><b>FLORA UY SALENDAB</b></div>
          <div class="sig-title">SCHOOL HEAD</div>
        </div>

      </div>
    </div>
  </section>
  <?php
  exit;
}


	if(isset($_POST['remove_subject_teacher'])){
		$delete = "DELETE FROM `tblclass_schedules_teachers` WHERE cstid='$_POST[cstid]'";
		$rundelete = mysqli_query($conn, $delete);
	}

	if(isset($_POST['saving_subj_teachers'])){
		$teachersid = $_POST['teachersid'];
		$classid = $_POST['classid'];	
		$sectionsID = $_POST['sectionsID'];	
		$insert = "INSERT INTO `tblclass_schedules_teachers` (`cst_teachersid`, `cst_classid`, `cst_sectionid`) VALUES ('$teachersid', '$classid', '$sectionsID')";
		$runinsert = mysqli_query($conn, $insert);
		// echo $insert;
	}
	
	if(isset($_POST['delete_class_schedule'])){
		$delete = "DELETE FROM `tblclass_schedules` WHERE classid='$_POST[classid]'";
		$rundelete = mysqli_query($conn, $delete);
	}

	if(isset($_POST['loading_class_schedules'])){
		$levelID = $_POST['levelID'];
		$sectionsID = $_POST['sectionsID'];
		// echo $levelID. '-' .$sectionsID; 
		echo
		''; ?>
		  <div class="table-responsive">
		  	 <!-- id="tblTeachers" -->
		    <table class="table table-striped table-hover table-bordered w-100">
		      <thead>
		        <tr>
		          <th class="text-center">TIME</th>
		          <th>SUBJECT</th>
		          <th>TEACHERs</th>
		          <th class="text-center" style="width:110px">Actions</th>
		        </tr>
		      </thead>
		      <tbody>
		        <?php

		        	$select = "SELECT * FROM `tblcurriculum`
		        	INNER JOIN tblsubjects on tblsubjects.subjectid=tblcurriculum.subjectid
					WHERE gradelevelid='$levelID' AND sectID='$sectionsID'";		        	

		        	// $select = "SELECT * FROM `tblclass_schedules`
					// LEFT JOIN tblsubjects on tblsubjects.subjectid=tblclass_schedules.subjectid
					// WHERE levelid='$levelID'";

		        	// $select = "SELECT * FROM `tblclass_schedules`
					// LEFT JOIN tblsubjects on tblsubjects.subjectid=tblclass_schedules.subjectid
					// WHERE levelid='$levelID' AND sectionsid='$sectionsID'";

		        	$runselect = mysqli_query($conn, $select);
		        	while($rowselect = mysqli_fetch_assoc($runselect)){
		        		echo
		        		'
					        <tr>
					        	<td width="1%" class="text-nowrap align-middle">'.date('h:i', strtotime($rowselect['timefrom'])).'-'.date('h:i', strtotime($rowselect['timeto'])).'</td>
					    '; ?>
					    <?php 
					    	if($rowselect['subjectid']=='10001'){
								echo'<td class="text-danger align-middle"><i>'.$rowselect['subject_description'].'</i></td>';
					    	}elseif($rowselect['subjectid']=='10002'){
								echo'<td class="text-danger align-middle"><i>'.$rowselect['subject_description'].'</i></td>';
					    	}else{
					    		echo'<td class="align-middle">'.$rowselect['subject_description'].'</td>';
					    	}
					    ?>
					    <?php echo'
					        	<td title="click here to add teacher..." onclick="add_teacher(\''.$rowselect['currid'].'\')" style="cursor:pointer">
									'; ?>
									<?php 
									$get_teachers = "SELECT * FROM `tblclass_schedules_teachers`
							        INNER JOIN tblteachers ON tblteachers.teachersautoid=tblclass_schedules_teachers.cst_teachersid
									WHERE cst_classid='$rowselect[currid]' AND cst_sectionid='$sectionsID'";

									$runget_teachers = mysqli_query($conn, $get_teachers);
									if(mysqli_num_rows($runget_teachers)<=0){
										echo '<i><span class="text-secondary" style="font-size:12px">No teacher assigned</span></i>';
									}else{
										$names = [];
										while($row_get_teachers = mysqli_fetch_assoc($runget_teachers)){
										    $names[] = '<b>'.strtoupper($row_get_teachers['lastname']).'</b>, '.ucwords(strtolower($row_get_teachers['firstname'])).' <i title="Remove this teacher..." style="position: relative;top:2px" class="bx  bx-trash text-danger" id="delButoon" onclick="event.stopPropagation(); remove(\''.$row_get_teachers['cstid'].'\')"></i>  ';
										}

										echo implode(', ', $names);										
									}



									?>
									<?php echo'
					        	</td>
					        	<td width="1%" class="text-nowrap align-middle">
									<button onclick="delete_schedule(\''.$rowselect['currid'].'\')" class="btn btn-danger btn-sm">Remove</button>
					        	</td>
					        </tr>
		        		';
		        	}
		        ?>

		      </tbody><div style="position: relative;"></div>
		    </table>
		  </div>
		<?php echo '';
	}


	if(isset($_POST['saving_class_schedule'])){
		$assigned_id = $_POST['assigned_id'];
		$timefrom = $_POST['timefrom'];
		$timeto = $_POST['timeto'];
		$subjectid = $_POST['subjectid'];	
		$levelID = $_POST['levelID'];
		$sectionsID = $_POST['sectionsID'];			
		$insert = "INSERT INTO `tblclass_schedules` (`assignedid`, `time_from`, `time_to`, `subjectid`, `levelID`, `sectionsID`) VALUES ('$assigned_id', '$timefrom', '$timeto', '$subjectid', '$levelID', '$sectionsID')";
		$runinsert = mysqli_query($conn, $insert);

	}




?>
