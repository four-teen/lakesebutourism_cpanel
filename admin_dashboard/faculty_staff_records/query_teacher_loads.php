<?php
// admin_dashboard/faculty_staff_records/query_teacher.php
session_start();
require_once __DIR__ . '/../../modules/auth/session_guard.php';
require_role(['Administrator']);

require_once __DIR__ . '/../../db.php';

// ---------- HTML fragment: faculty workload list ----------

	
	if(isset($_POST['delete_class_schedule'])){
		$delete = "DELETE FROM `tblclass_schedules` WHERE classid='$_POST[classid]'";
		$rundelete = mysqli_query($conn, $delete);
	}

	if(isset($_POST['loading_class_schedules'])){
		echo
		''; ?>
		  <div class="table-responsive">
		    <table id="tblTeachers" class="table table-striped table-bordered w-100">
		      <thead>
		        <tr>
		          <th class="text-center">TIME</th>
		          <th>SUBJECT</th>
		          <th>TEACHER</th>
		          <th class="text-center" style="width:110px">Actions</th>
		        </tr>
		      </thead>
		      <tbody>
		        <?php
		        	$select = "SELECT * FROM `tblclass_schedules`
					INNER JOIN tblsubjects on tblsubjects.subjectid=tblclass_schedules.subjectid";
		        	$runselect = mysqli_query($conn, $select);
		        	while($rowselect = mysqli_fetch_assoc($runselect)){
		        		echo
		        		'
					        <tr>
					        	<td width="1%" class="text-nowrap">'.$rowselect['time_from'].'-'.$rowselect['time_to'].'</td>
					        	<td>'.$rowselect['subject_description'].'</td>
					        	<td></td>
					        	<td width="1%" class="text-nowrap">
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
		$insert = "INSERT INTO `tblclass_schedules` (`assignedid`, `time_from`, `time_to`, `subjectid`) VALUES ('$assigned_id', '$timefrom', '$timeto', '$subjectid')";
		$runinsert = mysqli_query($conn, $insert);

	}




?>
