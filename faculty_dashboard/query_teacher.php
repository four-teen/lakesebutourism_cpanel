<?php
session_start();
require_once __DIR__ . '/../modules/auth/session_guard.php';
require_role(['Teacher']);
include_once __DIR__ . '/../db.php';


// ======================================================


if(isset($_POST['loading_subject_class_list'])){
  echo '';
  ?>

  <style>
    /* —— Visual polish (safe, scoped) —— */
    #classListWrap .card { border-radius: 16px; border: 1px solid #e9ecef; }
    #classListWrap .card-header { background: linear-gradient(180deg,#f8fafc,#f1f5f9); border-bottom: 1px solid #e9ecef; }
    #classListWrap .meta-badge{ background:#eef2ff; color:#4338ca; border-radius:999px; padding:.35rem .7rem; font-weight:600; }
    #classListWrap .subtle{ color:#6b7280; }
    #classListWrap .lrn { font-family: ui-monospace, SFMono-Regular, Menlo, Consolas, "Liberation Mono", monospace; letter-spacing:.3px; }
    #classListWrap .badge-m { background:#e0f2fe; color:#0369a1; }
    #classListWrap .badge-f { background:#fde7f3; color:#9d174d; }
    #classListWrap .table thead th{ white-space:nowrap; }
    #classListWrap .table td, 
    #classListWrap .table th{ vertical-align: middle; }
    /* Sticky header on wide tables when scrolling */
    #classListWrap .table thead th{ position: sticky; top: 0; z-index: 1; background:#ffffff; }
    /* Print-friendly tweaks */
    @media print {
      #classListWrap .card, #classListWrap .card-body{ box-shadow:none !important; }
      #classListWrap .dt-buttons, #classListWrap .dataTables_filter, #classListWrap .dataTables_length, #classListWrap .dataTables_info, #classListWrap .dataTables_paginate { display:none !important; }
      #classListWrap .table thead th{ position: static; }
      @page { size: A4 portrait; margin: 15mm; }
    }
  </style>

  <div id="classListWrap" class="table-responsive">
    <div class="card shadow-sm">
      <div class="card-header d-flex flex-wrap align-items-center justify-content-between gap-2">
        <div>
          <div class="fw-bold fs-5">Subject:
            <span class="meta-badge"><?php echo htmlspecialchars($_POST['subject_description']); ?></span>
          </div>
          <div class="subtle small">Class Schedule: <span class="fw-semibold"><?php echo htmlspecialchars($_POST['timed']); ?></span></div>
        </div>
        <div class="d-flex align-items-center gap-2 flex-wrap">
          <span class="badge bg-light text-dark border"><i class="fa fa-users me-1"></i><span id="ttlStu">0</span> Students</span>
          <span class="badge bg-light text-dark border"><i class="fa fa-mars me-1"></i><span id="ttlM">0</span> Male</span>
          <span class="badge bg-light text-dark border"><i class="fa fa-venus me-1"></i><span id="ttlF">0</span> Female</span>
<button type="button" class="btn btn-outline-secondary btn-sm" onclick="printClassList()">
  <i class="fa fa-print me-1"></i>Print
</button>
        </div>
      </div>

      <div class="card-body">
        <table id="modalStudentsTable" class="table table-striped table-bordered w-100">
          <thead>
            <tr>
              <th class="text-end" style="width:60px">#</th>
              <th class="text-end">LRN</th>
              <th class="text-start">Student Name</th>
              <th class="text-center" style="width:120px">Gender</th>
            </tr>
          </thead>
          <tbody>
            <?php 
              $select = "SELECT *
                FROM tblgrade_sect_students AS gss
                INNER JOIN tblstudents AS s 
                  ON s.autoid = gss.studentID WHERE class_schedule_id='$_POST[cstid]'";
              $runselect = mysqli_query($conn, $select);
              $count = 0;
              $males = 0; $females = 0;

              while($rowselect = mysqli_fetch_assoc($runselect)){
                $sex = strtoupper(trim($rowselect['sex']));
                if($sex === 'MALE' || $sex === 'M'){ $males++; } 
                elseif($sex === 'FEMALE' || $sex === 'F'){ $females++; }

                echo '
                <tr>
                  <td class="text-end">'.(++$count).'.</td>
                  <td class="text-end">'.htmlspecialchars(strtoupper($rowselect['learner_ref_no'])).'</td>
                  <td class="text-start">
                    <div class="fw-semibold">'.htmlspecialchars(strtoupper($rowselect['last_name'])).', '.htmlspecialchars(strtoupper($rowselect['first_name'])).' <i>'.htmlspecialchars(ucfirst(strtolower($rowselect['middle_name']))).'</i></div>
                    <div class="small subtle">ID: '.htmlspecialchars($rowselect['studentID'] ?? '').'</div>
                  </td>
                  <td class="text-center">';
                    if($sex === 'MALE' || $sex === 'M'){
                      echo '<span class="badge badge-m px-3 py-2"><i class="fa fa-mars me-1"></i>Male</span>';
                    } elseif($sex === 'FEMALE' || $sex === 'F'){
                      echo '<span class="badge badge-f px-3 py-2"><i class="fa fa-venus me-1"></i>Female</span>';
                    } else {
                      echo '<span class="badge bg-secondary-subtle text-dark px-3 py-2">N/A</span>';
                    }
                echo '</td>
                </tr>';
              }
            ?>
          </tbody>
          <tfoot>
            <tr>
              <th class="text-end">#</th>
              <th class="text-end">LRN</th>
              <th class="text-start">Student Name</th>
              <th class="text-center">Gender</th>
            </tr>
          </tfoot>
        </table>

        <div class="mt-3 small text-muted">
          <i class="fa fa-info-circle me-1"></i>
          Showing the official class list for <strong><?php echo htmlspecialchars($_POST['subject_description']); ?></strong>.
        </div>
      </div>
    </div>
  </div>

  <script>
    (function(){
      // If DataTables is available, enhance the table UX. Otherwise, gracefully fall back.
      var $tbl = $('#modalStudentsTable');
      if ($.fn.DataTable) {
        var dt = $tbl.DataTable({
          order: [[2, 'asc']],          // sort by name
          pageLength: 25,
          lengthMenu: [[10,25,50,100,-1],[10,25,50,100,'All']],
          responsive: true,
          autoWidth: false,
          deferRender: true,
          dom: "<'row align-items-center mb-2'<'col-md-6'l><'col-md-6 text-md-end'Bf>>" +
               "rt" +
               "<'row mt-2'<'col-md-6'i><'col-md-6'p>>",
          buttons: [
            { extend: 'copy', className: 'btn btn-light btn-sm', title: 'Class List - <?php echo addslashes($_POST['subject_description']); ?>' },
            { extend: 'excel', className: 'btn btn-light btn-sm', title: 'Class List - <?php echo addslashes($_POST['subject_description']); ?>' },
            { extend: 'print', className: 'btn btn-light btn-sm', title: 'Class List - <?php echo addslashes($_POST['subject_description']); ?>' }
          ],
          columnDefs: [
            { targets: 0, className: 'text-end' },
            { targets: 1, className: 'text-end' },
            { targets: 2, className: 'text-start' },
            { targets: 3, className: 'text-center' }
          ]
        });

        // Totals (from PHP counters echoed below) + live count with search
        var phpTotals = {
          total: <?php echo (int)$count; ?>,
          male:  <?php echo (int)$males; ?>,
          female:<?php echo (int)$females; ?>
        };
        function updateBadges(){
          // If filtered, recompute via visible rows; else use PHP totals
          var info = dt.page.info();
          var rows = dt.rows({search:'applied'}).data();
          var m=0,f=0;
          for (var i=0;i<rows.length;i++){
            var gCell = $(dt.row(i, {search:'applied'}).node()).find('td:eq(3)').text().trim().toUpperCase();
            if (gCell.includes('MALE')) m++;
            else if (gCell.includes('FEMALE')) f++;
          }
          $('#ttlStu').text(info.recordsDisplay);
          if (dt.search()) {
            $('#ttlM').text(m);
            $('#ttlF').text(f);
          } else {
            $('#ttlM').text(phpTotals.male);
            $('#ttlF').text(phpTotals.female);
          }
        }
        dt.on('draw.dt search.dt', updateBadges);
        updateBadges();
      } else {
        // Fallback: set totals via PHP counts
        $('#ttlStu').text(<?php echo (int)$count; ?>);
        $('#ttlM').text(<?php echo (int)$males; ?>);
        $('#ttlF').text(<?php echo (int)$females; ?>);
      }
    })();

  function printClassList() {
    // 1) Source node (what we want to print)
    const src = document.getElementById('classListWrap');
    if (!src) return;

    // 2) Remove any previous clone
    const old = document.getElementById('__print_clone');
    if (old) old.remove();

    // 3) Deep clone current UI state (including DataTables-rendered DOM)
    const clone = src.cloneNode(true);
    clone.id = '__print_clone';

    // 4) Optional: remove runtime-only controls inside the clone
    //    (like DT buttons/filter/pager if they exist in the cloned area)
    clone.querySelectorAll('.dt-buttons, .dataTables_filter, .dataTables_length, .dataTables_info, .dataTables_paginate')
         .forEach(el => el.remove());

    // 5) Inject clone at end of body
    document.body.appendChild(clone);

    // 6) Add printing class, trigger print, then cleanup
    document.body.classList.add('printing');

    const cleanup = () => {
      document.body.classList.remove('printing');
      const c = document.getElementById('__print_clone');
      if (c) c.remove();
      window.removeEventListener('afterprint', cleanup);
      // Safari fallback: small delay before removing if needed
      setTimeout(() => { /* no-op */ }, 0);
    };

    // Make sure we always clean after print
    window.addEventListener('afterprint', cleanup);

    // Trigger the print dialog
    window.print();
  }
    
  </script>

  <?php
  echo '';
}


if(isset($_POST['get_student_list'])){

  echo
  ''; ?>
      <div class="col-lg-12">
        <label for="studentsID">Select Students</label>
        <select id="studentsID" class="js-example-basic-single form-control" name="state">
          <?php 
            $level = $_POST['get_level_id'] ?? ''; // kunin yung value ng input
            $get_students = "SELECT * FROM `tblstudents` WHERE grade_level='$_POST[get_level_id]'";
            $runget_students = mysqli_query($conn, $get_students);
            while($row_students = mysqli_fetch_assoc($runget_students)){

              $check_added = "SELECT * FROM `tblgrade_sect_students` WHERE studentID='$row_students[autoid]' AND ayID='$_SESSION[ayid]'";
              $runcheck_added = mysqli_query($conn, $check_added);
              $rowcheck_stud = mysqli_fetch_assoc($runcheck_added);

              if($rowcheck_stud['studentID']==$row_students['autoid'] && $rowcheck_stud['ayID']==$row_students['ay']){
                 // echo'<option value="'.$row_students['autoid'].'">'.strtoupper($row_students['last_name']).', '.strtoupper($row_students['first_name']).' '.strtoupper($row_students['middle_name']).' ('.strtoupper($row_students['grade_level']).')X';
              }else{
                 echo'<option value="'.$row_students['autoid'].'">'.strtoupper($row_students['last_name']).', '.strtoupper($row_students['first_name']).' '.strtoupper($row_students['middle_name']).' ('.strtoupper($row_students['grade_level']).')</option>';                
              }

 
            }
          ?>
          
        </select>
      </div>
  <?php echo'';
}

if(isset($_POST['delete_the_student'])){
  $delete = "DELETE FROM `tblgrade_sect_students` WHERE gradesectID = '$_POST[gradesectID]'";
  $rundelete = mysqli_query($conn, $delete);
}

if (isset($_POST['loading_students_subjects'])) {
  echo
  ''; ?>
    <div class="table-responsive">
      <table id="modalStudentsTable" class="table table-striped table-bordered w-100">
        <thead>
          <tr>
            <th>#</th>
            <th>STUDENT NAME</th>
            <th>ACTION</th>
          </tr>          
        </thead>
        <tbody>
          <?php 
            $select = "SELECT * FROM `tblgrade_sect_students`
            INNER JOIN tblstudents on tblstudents.autoid = tblgrade_sect_students.studentID
            WHERE class_schedule_id='$_POST[cstid]'";
            $runselect = mysqli_query($conn, $select);
            $count = 0;
            while($rowselect = mysqli_fetch_assoc($runselect)){
              echo
              '
              <tr>
                <td class="align-middle text-end" width="1%">'.++$count.'.</td>
                <td class="align-middle">'.strtoupper($rowselect['last_name']).', '.strtoupper($rowselect['first_name']).' '.strtoupper($rowselect['middle_name']).'</td>
                <td class="align-middle"  width="1%">
                  <button onclick="remove_student(\''.$rowselect['gradesectID'].'\')" class="btn btn-danger btn-sm">Remove</button>
                </td>
              </tr>
              ';
            }

          ?>

        </tbody>
      </table>
    </div>
  <?php echo'';
}


if(isset($_POST['saving_subject_students'])){

  $settings = "SELECT * FROM `tblsettings`
  INNER JOIN tblacademic_years on tblacademic_years.ayid=tblsettings.ayid LIMIT 1";
  $runsettings = mysqli_query($conn, $settings);
  $rowsettings = mysqli_fetch_assoc($runsettings);
  $ayid = $rowsettings['ayid'];
  $studentsID = $_POST['studentsID'];
  $class_schedule_id = $_POST['class_schedule_id'];

  $insert = "INSERT INTO `tblgrade_sect_students` (`studentID`, `ayID`, `addedBy`, `addedDateTime`, `class_schedule_id`) VALUES ('$studentsID', '$ayid', '$_SESSION[TEA_ID]', CURRENT_TIMESTAMP, '$class_schedule_id')";
  $runinsert = mysqli_query($conn, $insert);  

}


if (isset($_POST['loading_your_subject'])) {
  echo '<div class="row g-3">';

   $select = "SELECT * FROM `tblclass_schedules_teachers`
  INNER JOIN tblcurriculum on tblcurriculum.currid=tblclass_schedules_teachers.cst_classid
  INNER JOIN tblteachers on tblteachers.teachersautoid=tblclass_schedules_teachers.cst_teachersid
  INNER JOIN tblgradelevel on tblgradelevel.levelid=tblcurriculum.gradelevelid
  INNER JOIN tblsections on tblsections.sectionsid = tblclass_schedules_teachers.cst_sectionid
  INNER JOIN tblsubjects on tblsubjects.subjectid = tblcurriculum.subjectid
  INNER JOIN tblacademic_years on tblacademic_years.ayid=tblcurriculum.ayid
  WHERE cst_teachersid='$_SESSION[TEA_ID]'";
  $runselect = mysqli_query($conn, $select);

  while ($rowselect = mysqli_fetch_assoc($runselect)) {
     

    $timed = $rowselect['timefrom'].' - '.$rowselect['timeto'];

      echo '
      <div class="col-md-6 col-lg-4">
        <div class="card subject-card h-100 shadow-sm" role="button">
          <div class="card-header d-flex justify-content-between align-items-center">
            <span>'.$rowselect['level_descrition'].' - '.strtoupper($rowselect['section_desc']).'</span>
              <span onclick="add_student_list(\''.$rowselect['cstid'].'\',\''.$rowselect['levelid'].'\')" class="badge-pill cta-badge">
                <i class="bi bi-people-fill me-1"></i> Add Students
              </span>
          </div>

          <div class="card-body">
            <div class="subject-body">
              <img src="../assets/img/subject_logo.png" alt="Subject image" class="subject-thumb">

              <div class="flex-grow-1 subject-content">
                <div class="subject-header">
                  <h5 class="subject-title">
                    '.$rowselect['subject_description'].'
                    <small><i class="bi bi-calculator me-1"></i>Class Schedule: <span class="text-danger">'.$rowselect['timefrom'].' - '.$rowselect['timeto'].'</span></small>
                  </h5>
                </div>

                <div class="subject-metric">
                  <div class="big-metric" id="rec-count-2">
                  '; ?>
                  <?php 
                    $select_count = "SELECT * FROM `tblgrade_sect_students`
                    INNER JOIN tblstudents on tblstudents.autoid = tblgrade_sect_students.studentID
                    WHERE class_schedule_id='$rowselect[cstid]'";
                    $runselect_count = mysqli_query($conn, $select_count);
                    echo mysqli_num_rows($runselect_count);

                  ?>
                  <?php echo'
                  </div>
                  <div class="metric-label">student(s)</div>
                </div>
              </div>
            </div>
            <a href="javascript:void(0)" class="stretched-link" aria-hidden="true"></a>
          </div>

          <div class="card-footer text-muted">
            
            <div class="btn-group" role="group" aria-label="Basic mixed styles example">
              <button type="button" class="btn btn-danger">-</button>
              <button onclick="manage_class_list(\''.$rowselect['subject_description'].'\',\''.$timed.'\', \''.$rowselect['cstid'].'\')" type="button" class="btn btn-warning">Class List</button>
              <button onclick="manage_graded(\''.$rowselect['subject_description'].'\',\''.$timed.'\', \''.$rowselect['cstid'].'\')" type="button" class="btn btn-success">Manage Grades</button>
            </div>

          </div>
        </div>
      </div>';
  }

  echo '</div>';
  exit;
}


if (isset($_POST['loading_ay'])) {
    header('Content-Type: application/json; charset=utf-8');

    $sql = "SELECT ayfrom, ayto 
            FROM tblsettings 
            INNER JOIN tblacademic_years USING(ayid) 
            LIMIT 1";
    $res = mysqli_query($conn, $sql);

    if ($res && $row = mysqli_fetch_assoc($res)) {
        echo json_encode([
            'ok' => true,
            'ay' => $row['ayfrom'] . '-' . $row['ayto']
        ]);
    } else {
        echo json_encode(['ok' => false, 'ay' => '']);
    }
    exit;
}
