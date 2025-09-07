<?php
// admin_dashboard/faculty_staff_records/query_teacher.php
session_start();
require_once __DIR__ . '/../../modules/auth/session_guard.php';
require_role(['Administrator']);

require_once __DIR__ . '/../../db.php';

// ---------- HTML fragment: faculty workload list ----------

	if(isset($_POST['loading_faculty_class_schedule'])){

		$get_the_teacher = "SELECT * FROM `tblclass_schedules_teachers`
		INNER JOIN tblteachers on tblteachers.teachersautoid = tblclass_schedules_teachers.cst_teachersid
		INNER JOIN tblclass_schedules on tblclass_schedules.classid=tblclass_schedules_teachers.cst_classid
		INNER JOIN tblgradelevel on tblgradelevel.levelid=tblclass_schedules.levelID
		INNER JOIN tblsections on tblsections.sectionsid=tblclass_schedules.sectionsID
		LEFT JOIN tblassigned_designation on tblassigned_designation.ass_teachersautoid=tblteachers.teachersautoid
		LEFT JOIN tbldesignation on tbldesignation.designationid=tblassigned_designation.ass_designationid
		WHERE tblteachers.teachersautoid='$_POST[teacherid]'";
		$runget_the_teacher = mysqli_query($conn, $get_the_teacher);
		$row_get_the_teacher = mysqli_fetch_assoc($runget_the_teacher);


		echo 
		''; ?>
    <div class="container">
        <div class="header">
            <img src="../../barmm.png" alt="BARMM Logo" class="logo">
            <div class="school-info">
                <div class="republic">Republic of the Philippines</div>
                <div class="ministry">MINISTRY OF BASIC, HIGHER, AND TECHNICAL EDUCATION</div>
                <div class="division">Division of Maguindanao Del Sur 1</div>
                <div class="region">Bansamoro Autonomous Region in Muslim Mindanao</div>
                <div class="address">BULUAN TECHNICAL EDUCATION SCHOOL OF Life, Inc.<br>Poblacion, Buluan, Maguindanao Del Sur, BARMM</div>
            </div>
            <img src="../../bteslife.png" alt="DepEd Logo" class="logo">
        </div>

        <div class="title-section">
            <div class="main-title">INDIVIDUAL LOADS</div>
            <div class="sy">S.Y. 2025 – 2026</div>
        </div>

        <div class="info-block">
            <div><span class="label">NAME:</span> <span class="value"><?php echo $row_get_the_teacher['firstname']. ', '.$row_get_the_teacher['middlename'].'. '.$row_get_the_teacher['lastname'] ?></span></div>
            <div><span class="label">POSITION:</span> <span class="value"><?php echo strtoupper($row_get_the_teacher['level_descrition']) ?> – <?php echo strtoupper($row_get_the_teacher['section_desc']) ?> <?php echo strtoupper($row_get_the_teacher['designation_desc']) ?></span></div>
        </div>

        <table class="schedule-table">
            <thead>
                <tr>
                    <th>TIME</th>
                    <th>SUBJECT</th>
                    <th>YEAR LEVEL</th>
                </tr>
            </thead>
            <tbody>
            	<?php 
            		$get_schedule = "SELECT * FROM `tblclass_schedules_teachers`
					INNER JOIN tblclass_schedules on tblclass_schedules.classid=tblclass_schedules_teachers.cst_classid
					INNER JOIN tblsubjects on tblsubjects.subjectid = tblclass_schedules.subjectid
                    INNER JOIN tblgradelevel on tblgradelevel.levelid=tblclass_schedules.levelID
                    INNER JOIN tblsections on tblsections.sectionsid=tblclass_schedules.sectionsID
                    WHERE cst_teachersid='$_POST[teacherid]'";
            		$rungetselect = mysqli_query($conn, $get_schedule);
            		while($rowselect = mysqli_fetch_assoc($rungetselect)){
            			echo
            			'
			                <tr>
			                    <td>'.$rowselect['time_from'].'-'.$rowselect['time_to'].'</td>
			                    <td>'.$rowselect['subject_description'].'</td>
			                    <td>'.$rowselect['level_descrition'].' '.$rowselect['section_desc'].'</td>
			                </tr>
            			';
            		}
            	?>
            </tbody>
        </table>

        <div class="approved-by">
            <div>PREPARED BY:</div>
            <div class="signature-block">
                <!-- <img src="" alt="Signature Johanna" class="signature-image"> -->
                <div class="name">JOHANA M. DIMALILAY</div>
                <div class="title">ACADEMIC COORDINATOR</div>
            </div>
        </div>

        <div class="approved-by">
            <div>APPROVED BY:</div>
            <div class="signature-block">
                <!-- <img src="" alt="Signature Flora" class="signature-image"> -->
                <div class="name">FLORA UY SALENDAB</div>
                <div class="title">SCHOOL HEAD</div>
            </div>
        </div>
    </div>
		<?php echo'';
	}

	if(isset($_POST['remove_subject_teacher'])){
		$delete = "DELETE FROM `tblclass_schedules_teachers` WHERE cstid='$_POST[cstid]'";
		$rundelete = mysqli_query($conn, $delete);
	}

	if(isset($_POST['saving_subj_teachers'])){
		$teachersid = $_POST['teachersid'];
		$classid = $_POST['classid'];		
		$insert = "INSERT INTO `tblclass_schedules_teachers` (`cst_teachersid`, `cst_classid`) VALUES ('$teachersid', '$classid')";
		$runinsert = mysqli_query($conn, $insert);
		echo $insert;
	}
	
	if(isset($_POST['delete_class_schedule'])){
		$delete = "DELETE FROM `tblclass_schedules` WHERE classid='$_POST[classid]'";
		$rundelete = mysqli_query($conn, $delete);
	}

	if(isset($_POST['loading_class_schedules'])){
		$levelID = $_POST['levelID'];
		$sectionsID = $_POST['sectionsID'];
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
		        	$select = "SELECT * FROM `tblclass_schedules`
					LEFT JOIN tblsubjects on tblsubjects.subjectid=tblclass_schedules.subjectid
					WHERE levelid='$levelID' AND sectionsid='$sectionsID'";
		        	$runselect = mysqli_query($conn, $select);
		        	while($rowselect = mysqli_fetch_assoc($runselect)){
		        		echo
		        		'
					        <tr>
					        	<td width="1%" class="text-nowrap align-middle">'.date('h:i', strtotime($rowselect['time_from'])).'-'.date('h:i', strtotime($rowselect['time_to'])).'</td>
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
					        	<td title="click here to add teacher..." onclick="add_teacher(\''.$rowselect['classid'].'\')" style="cursor:pointer">
									'; ?>
									<?php 
									$get_teachers = "SELECT * FROM `tblclass_schedules_teachers`
									INNER JOIN tblteachers 
									    ON tblteachers.teachersautoid=tblclass_schedules_teachers.cst_teachersid 
									WHERE cst_classid='$rowselect[classid]'";

									$runget_teachers = mysqli_query($conn, $get_teachers);

									$names = [];
									while($row_get_teachers = mysqli_fetch_assoc($runget_teachers)){
									    $names[] = '<b>'.strtoupper($row_get_teachers['lastname']).'</b>, '.ucwords(strtolower($row_get_teachers['firstname'])).' <i title="Remove this teacher..." style="position: relative;top:2px" class="bx  bx-trash text-danger" id="delButoon" onclick="event.stopPropagation(); remove(\''.$row_get_teachers['cstid'].'\')"></i>  ';
									}

									echo implode(', ', $names);

									?>
									<?php echo'
					        	</td>
					        	<td width="1%" class="text-nowrap align-middle">
									<button onclick="delete_schedule(\''.$rowselect['classid'].'\')" class="btn btn-danger btn-sm">Remove</button>
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
