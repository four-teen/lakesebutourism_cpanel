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



    <li class="nav-heading">Pages</li>
    <li class="nav-item">
      <a class="nav-link collapsed" href="../modules/auth/logout.php">
        <i class="bi bi-box-arrow-in-right text-danger"></i> <span>Logout</span>
      </a>
    </li>

  </ul>
</aside>
<!-- End Sidebar -->
