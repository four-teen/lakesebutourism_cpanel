<!-- ======= Sidebar ======= -->
<aside id="sidebar" class="sidebar">
  <ul class="sidebar-nav" id="sidebar-nav">

    <!-- Dashboard -->
    <li class="nav-item">
      <a class="nav-link" href="dashboard_admin.php">
        <i class="bi bi-grid"></i> <span>Dashboard</span>
      </a>
    </li>

    <!-- Student Management -->
    <li class="nav-item">
      <a class="nav-link collapsed" data-bs-toggle="collapse" href="#students-nav" role="button" aria-expanded="false" aria-controls="students-nav">
        <i class="bx bx-user-circle text-primary"></i> <span>Student Management</span>
        <i class="bi bi-chevron-down ms-auto"></i>
      </a>
      <ul id="students-nav" class="nav-content collapse" data-bs-parent="#sidebar-nav">
        <li><a href="student_management/student_records.php"><i class="bi bi-circle"></i><span>Enrolment & Student Records</span></a></li>
        <li><a href="#"><i class="bi bi-circle"></i><span>Attendance Tracking</span></a></li>
        <li><a href="#"><i class="bi bi-circle"></i><span>Grade and Reports</span></a></li>
        <li><a href="#"><i class="bi bi-circle"></i><span>Student Profiles</span></a></li>
      </ul>
    </li>

    <!-- Teachers & Staff -->
    <li class="nav-item">
      <a class="nav-link collapsed" data-bs-toggle="collapse" href="#teachers-nav" role="button" aria-expanded="false" aria-controls="teachers-nav">
        <i class="bx bx-user text-primary"></i> <span>Teachers and Staff Records</span>
        <i class="bi bi-chevron-down ms-auto"></i>
      </a>
      <ul id="teachers-nav" class="nav-content collapse" data-bs-parent="#sidebar-nav">
        <li><a href="faculty_staff_records/teacher_profile.php"><i class="bi bi-circle"></i><span>Faculty Profile</span></a></li>
        <li><a href="#"><i class="bi bi-circle"></i><span>Teaching Loads and Schedules</span></a></li>
        <li><a href="#"><i class="bi bi-circle"></i><span>Performance Monitoring</span></a></li>
      </ul>
    </li>

    <!-- Curriculum & Academic -->
    <li class="nav-item">
      <a class="nav-link collapsed" data-bs-toggle="collapse" href="#curriculum-nav" role="button" aria-expanded="false" aria-controls="curriculum-nav">
        <i class="bi bi-journal-text text-primary"></i> <span>Curriculum and Academic Management</span>
        <i class="bi bi-chevron-down ms-auto"></i>
      </a>
      <ul id="curriculum-nav" class="nav-content collapse" data-bs-parent="#sidebar-nav">
        <li><a href="academic_management/student_scheduling.php"><i class="bi bi-circle"></i><span>Class Scheduling/Program</span></a></li>
        <li><a href="academic_management/student_curriculum.php"><i class="bi bi-circle"></i><span>Manage Curriculum</span></a></li>
        <li><a href="#"><i class="bi bi-circle"></i><span>Assessment and Examination Records</span></a></li>
      </ul>
    </li>

    <!-- Administrative Reports -->
    <li class="nav-item">
      <a class="nav-link collapsed" data-bs-toggle="collapse" href="#adminreports-nav" role="button" aria-expanded="false" aria-controls="adminreports-nav">
        <i class="bx bx-alarm text-primary"></i> <span>Administrative Reports</span>
        <i class="bi bi-chevron-down ms-auto"></i>
      </a>
      <ul id="adminreports-nav" class="nav-content collapse" data-bs-parent="#sidebar-nav">
        <li><a href="#"><i class="bi bi-circle"></i><span>Finance and Accounting</span></a></li>
        <li><a href="#"><i class="bi bi-circle"></i><span>Inventory and Assets Mngt</span></a></li>
      </ul>
    </li>

    <!-- Reports & Analytics -->
    <li class="nav-item">
      <a class="nav-link collapsed" data-bs-toggle="collapse" href="#reports-nav" role="button" aria-expanded="false" aria-controls="reports-nav">
        <i class="bi bi-graph-up text-primary"></i> <span>Reports and Analytics</span>
        <i class="bi bi-chevron-down ms-auto"></i>
      </a>
      <ul id="reports-nav" class="nav-content collapse" data-bs-parent="#sidebar-nav">
        <li><a href="#"><i class="bi bi-circle"></i><span>Student Performance</span></a></li>
        <li><a href="#"><i class="bi bi-circle"></i><span>Attendance Summary</span></a></li>
      </ul>
    </li>

    <li class="nav-heading">Settings</li>
    <li class="nav-item">
      <a class="nav-link collapsed" href="#" onclick="manage_ay()">
        <i class="bx bx-calendar-alt text-warning"></i> <span>Academic Year</span>
      </a>
    </li>

    <li class="nav-heading">Pages</li>
    <li class="nav-item">
      <a class="nav-link collapsed" href="../modules/auth/logout.php">
        <i class="bi bi-box-arrow-in-right text-danger"></i> <span>Logout</span>
      </a>
    </li>

  </ul>
</aside>
<!-- End Sidebar -->
