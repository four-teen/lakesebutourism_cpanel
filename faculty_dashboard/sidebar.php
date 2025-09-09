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
      </a>
      <ul id="students-nav" class="nav-content" data-bs-parent="#sidebar-nav">
        <li><a href="#"><i class="bi bi-circle"></i><span>My Workload</span></a></li>
        <li><a href="#"><i class="bi bi-circle"></i><span>Manage Grades</span></a></li>
        <li><a href="#"><i class="bi bi-circle"></i><span>Student Reports</span></a></li>
      </ul>
    </li>


    <li class="nav-heading"></li>
    <li class="nav-item">
      <a class="nav-link collapsed" href="../modules/auth/logout.php">
        <i class="bi bi-box-arrow-in-right text-danger"></i> <span>Logout</span>
      </a>
    </li>

  </ul>
</aside>
<!-- End Sidebar -->
