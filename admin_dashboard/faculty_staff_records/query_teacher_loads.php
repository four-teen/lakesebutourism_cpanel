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
			<div id="print_area">
    <div id="print_area" class="p-5">
        <style>
            .header-info { text-align: center; }
            .header-info p { margin: 0; padding: 0; line-height: 1.2; font-size: 14px; }
            .header-logo { width: 100px; height: 100px; object-fit: cover; }
            .content-header h4 { font-size: 18px; margin-top: 20px; margin-bottom: 5px; font-weight: bold; }
            .info-item { margin-bottom: 5px; }
            .info-item .label { font-weight: bold; }
            .schedule-table { width: 100%; border-collapse: collapse; margin-top: 20px; }
            .schedule-table th, .schedule-table td { border: 1px solid #000; padding: 8px; text-align: left; font-size: 14px; }
            .schedule-table th { background-color: #f2f2f2; font-weight: bold; text-align: center; }
            .signatories { margin-top: 50px; }
            .signatory { width: 48%; display: inline-block; vertical-align: top; }
            .signature-line { border-bottom: 1px solid #000; margin-top: 50px; }
            .signatory-name { font-weight: bold; text-transform: uppercase; margin-top: 5px; }
            .signatory-role { font-size: 12px; }

    #print_area {
        padding: 1in 1.5in; /* 1in top/bottom, 1.5in left/right */
    }
        </style>
        
        <div style="display: flex; align-items: center; justify-content: center; gap: 20px; text-align: center;">
            <img src="../../assets/img/logo.png" class="header-logo" alt="Logo">
            <div class="header-info" style="flex: 1;">
                <p>Republic of the Philippines</p>
                <p>MINISTRY OF BASIC, HIGHER, AND TECHNICAL EDUCATION</p>
                <p>Division of Maguindanao Del Sur 1</p>
                <p>Bangsamoro Autonomous Region in Muslim Mindanao</p>
                <p style="font-weight: bold;">BULUAN TECHNICAL EDUCATION SCHOOL OF LIFE, Inc.</p>
                <p>Poblacion, Buluan, Maguindanao Del Sur, BARMM</p>
            </div>
            <img src="../../assets/img/logo.png" class="header-logo" alt="Logo">
        </div>
        <hr>

        <div class="content-header" style="text-align: center;">
            <h4>INDIVIDUAL LOADS</h4>
            <div style="margin-bottom: 20px;">S.Y. </div>
        </div>

        <div class="teacher-info" style="margin-bottom: 20px;">
            <div class="info-item"><span class="label">NAME:</span> </div>
            <div class="info-item"><span class="label">POSITION:</span> </div>
        </div>

        <table class="schedule-table">
            <thead>
                <tr>
                    <th width="15%">TIME</th>
                    <th width="50%">SUBJECT</th>
                    <th width="35%">YEAR LEVEL</th>
                </tr>
            </thead>
            <tbody>
            	<tr>
            		<td></td>
            		<td></td>
            		<td></td>
            	</tr>
            </tbody>
        </table>

        <div class="signatories">
            <div class="signatory" style="text-align: left;">
                <div style="margin-bottom: 10px;">PREPARED BY:</div>
                <div class="signature-line" style="height: 50px;"></div>
                <div class="signatory-name">JOHANA M. DIMALILAY</div>
                <div class="signatory-role">ACADEMIC COORDINATOR</div>
            </div>
            
            <div class="signatory" style="text-align: right;">
                <div style="margin-bottom: 10px;">APPROVED BY:</div>
                <div class="signature-line" style="height: 50px;"></div>
                <div class="signatory-name">FLORA UY SALENDAB</div>
                <div class="signatory-role">SCHOOL HEAD</div>
            </div>
        </div>
    </div>
				
			</div>

		<?php echo '';


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
