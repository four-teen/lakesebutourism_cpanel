<?php
session_start();
require_once __DIR__ . '/../../modules/auth/session_guard.php';
require_role(['Administrator']);
include_once __DIR__ . '/../../db.php';

// Load accounts
if(isset($_POST['loading_owner'])){
  ?>
  <div class="table-responsive">
    <table id="tblAccounts" class="table table-striped table-bordered w-100">
      <thead>
        <tr>
          <th>#</th>
          <th>Username</th>
          <th width="120">Actions</th>
        </tr>
      </thead>
      <tbody>
      <?php
      $select = "SELECT * FROM `tblowners` ORDER BY establishments ASC";
      $run = mysqli_query($conn,$select);
      $count = 0;
      while($row=mysqli_fetch_assoc($run)){
        echo '
        <tr>
          <td class="align-middle text-end" width="1%">'.++$count.'.</td>
          <td class="align-middle">'.htmlspecialchars(strtoupper($row['establishments'])).'</td>
          <td width="1%" class="text-nowrap">
            <div class="btn-group" role="group" aria-label="actions">
              <button type="button" class="btn btn-danger" onclick="edit_account()"><i class="bx bx-edit"></i></button>
              <button type="button" class="btn btn-warning" onclick="delete_account()"><i class="bx bx-trash"></i></button>
            </div>
          </td>
        </tr>';
      }
      ?>
      </tbody>
    </table>
  </div>
  <?php
  exit;
}

?>
