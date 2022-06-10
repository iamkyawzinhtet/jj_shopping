<?php
    session_start();
    require '../config/config.php';
    require '../config/common.php';

    if(empty($_SESSION['user_id']) && empty($_SESSION['logged_in'])) {
        header('Location: login.php');
    }

    if(!empty($_GET['pageno'])) {
        $pageno = $_GET['pageno'];
    }else {
        $pageno = 1;
    }
    $numOfRecs = 5;
    $offset = ($pageno - 1) * $numOfRecs;

    $stmt = $pdo->prepare("SELECT * FROM sale_orders ORDER BY id DESC");
    $stmt->execute();
    $rawResult = $stmt->fetchAll();
    $total_pages = ceil(count($rawResult) / $numOfRecs);

    $stmt = $pdo->prepare("SELECT * FROM sale_orders ORDER BY id DESC LIMIT $offset,$numOfRecs");
    $stmt->execute();
    $result = $stmt->fetchAll();
  
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
          <!-- <div class="col-sm-4">
            <a href="cat_add.php" class="btn btn-success">New Category</a>
          </div> -->
          <div class="col-sm-12">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="order.php">Home</a></li>
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
                      <th>User</th>
                      <th>Total Price</th>
                      <th>Order Date</th>
                      <th style="width: 80px">Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                      $i = 1;
                      foreach ($result as $value) {
                        $userStmt = $pdo->prepare("SELECT * FROM users WHERE id=".$value['customer_id']);
                        $userStmt->execute();
                        $userResult = $userStmt->fetchAll();
                    ?>
                        <tr>
                          <td><?php echo $i ?></td>
                          <td><?php echo escape($userResult[0]['name']) ?></td>
                          <td><?php echo escape($value['total_price']) ?></td>
                          <td><?php echo escape(date('Y-m-d',strtotime($value['order_date']))) ?></td>
                          <td>
                            <a href="order_detail.php?id=<?php echo($value['id'])?>" class="btn btn-primary">View</a>
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
