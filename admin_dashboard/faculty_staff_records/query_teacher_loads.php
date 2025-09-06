<?php
// admin_dashboard/faculty_staff_records/query_teacher.php
session_start();
require_once __DIR__ . '/../../modules/auth/session_guard.php';
require_role(['Administrator']);

require_once __DIR__ . '/../../db.php';

// ---------- HTML fragment: faculty workload list ----------


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
					        	<td onclick="add_teacher(\''.$rowselect['classid'].'\')" style="cursor:pointer">
'; ?>
<?php 
$get_teachers = "SELECT * FROM `tblclass_schedules_teachers`
INNER JOIN tblteachers 
    ON tblteachers.teachersautoid=tblclass_schedules_teachers.cst_teachersid 
WHERE cst_classid='$rowselect[classid]'";

$runget_teachers = mysqli_query($conn, $get_teachers);

$names = [];
while($row_get_teachers = mysqli_fetch_assoc($runget_teachers)){
    $names[] = '<b>'.strtoupper($row_get_teachers['lastname']).'</b>, '.ucwords(strtolower($row_get_teachers['firstname']));
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

		      </tbody>
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
