<?php
  session_start();
  require '../config/config.php';
  require '../config/common.php';

  if(empty($_SESSION['user_id']) && empty($_SESSION['logged_in'])) {
    header('Location: login.php');
  }
  if($_SESSION['role'] != 1) {
    header('Location: login.php');
  }

  if(!empty($_POST['search'])) {
    setcookie('search',$_POST['search'], time() + (86400 * 30), "/");
  }else {
    if(empty($_GET['pageno'])) {
      unset($_COOKIE['search']);
      setcookie('search', null, -1, '/');
    }
  }

  if(!empty($_GET['pageno'])) {
    $pageno = $_GET['pageno'];
  }else {
    $pageno = 1;
  }
  $numOfRecs = 5;
  $offset = ($pageno - 1) * $numOfRecs;

  if(empty($_POST['search']) && empty($_COOKIE['search'])) {
    $stmt = $pdo->prepare("SELECT * FROM users ORDER BY id DESC");
    $stmt->execute();
    $rawResult = $stmt->fetchAll();
    $total_pages = ceil(count($rawResult) / $numOfRecs);

    $stmt = $pdo->prepare("SELECT * FROM users ORDER BY id DESC LIMIT $offset,$numOfRecs");
    $stmt->execute();
    $result = $stmt->fetchAll();
  }else {
    $searchKey = !empty($_POST['search']) ? $_POST['search'] : $_COOKIE['search'] ;
    $stmt = $pdo->prepare("SELECT * FROM users WHERE name LIKE '%$searchKey%' ORDER BY id DESC");
    $stmt->execute();
    $rawResult = $stmt->fetchAll();
    $total_pages = ceil(count($rawResult) / $numOfRecs);

    $stmt = $pdo->prepare("SELECT * FROM users WHERE name LIKE '%$searchKey%' ORDER BY id DESC LIMIT $offset,$numOfRecs");
    $stmt->execute();
    $result = $stmt->fetchAll();
  }
  // print "<pre>";
  // print_r($result);
?>

 <?php include('header.php'); ?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-4">
            <a href="user_add.php" class="btn btn-success">New User</a>
          </div>
          <div class="col-sm-8">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="user.php">Home</a></li>
              <li class="breadcrumb-item active"><a href="logout.php">Logout</a></li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-12">
            <div class="card">
              <div class="card-body">
                <table class="table table-bordered">
                  <thead>
                    <tr>
                      <th style="width: 10px">#</th>
                      <th>Name</th>
                      <th>Email</th>
                      <th>Role</th>
                      <th style="width: 150px">Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                      $i = 1;
                      foreach ($result as $value) {
                          if($value['role'] == 1) {
                              $role = 'admin';
                          }
                          if($value['role'] == 0) {
                              $role = 'user';
                          }
                    ?>
                        <tr>
                          <td><?php echo $i ?></td>
                          <td><?php echo escape($value['name']) ?></td>
                          <td><?php echo escape($value['email']) ?></td>
                          <td><?php echo escape($role) ?></td>
                          <td>
                            <a href="user_edit.php?id=<?php echo($value['id'])?>" class="btn btn-primary">Edit</a>
                            <a href="user_delete.php?id=<?php echo($value['id'])?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this?')">
                              Delete
                            </a>
                          </td>
                        </tr>
                    <?php
                      $i++;
                      }
                    ?>
                  </tbody>
                </table>
              </div>
              <!-- /.card-body -->

              <div class="card-footer clearfix">
                <ul class="pagination pagination-sm m-0 float-right">
                <li class="page-item">
                    <a class="page-link" href="?pageno=1">First</a>
                  </li>
                  <li class="page-item <?php if($pageno<=1){echo 'disabled';} ?>">
                    <a class="page-link" href="<?php if($pageno<=1){echo '#';}else{echo "?pageno=".($pageno-1);} ?>">Previous</a>
                  </li>
                  <li class="page-item">
                    <a class="page-link" href="#"><?php echo $pageno; ?></a>
                  </li>
                  <li class="page-item <?php if($pageno>=$total_pages){echo 'disabled';} ?>">
                    <a class="page-link" href="<?php if($pageno>=$total_pages){echo '#';}else{echo "?pageno=".($pageno+1);} ?>">Next</a>
                  </li>
                  <li class="page-item">
                    <a class="page-link" href="?pageno=<?php echo $total_pages ?>">Last</a>
                  </li>
                </ul>
              </div>
            </div>
            <!-- /.card -->
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->


  <!-- Main Footer -->
  <footer class="main-footer" style="text-align: center;">
    <!-- Default to the left -->
    <strong>Copyright &copy; 2022 <a href="#">Blog App</a>.</strong> All rights reserved.
  </footer>
</div>
<!-- ./wrapper -->

<!-- REQUIRED SCRIPTS -->

<!-- jQuery -->
<script src="plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>
</body>
</html>
