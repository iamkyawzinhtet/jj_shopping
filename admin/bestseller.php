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
    $bestsellerItem = 5;
    // echo $fromDate;
    // echo $toDate;

    $stmt = $pdo->prepare("SELECT * FROM sale_order_detail GROUP BY product_id HAVING SUM(quantity) > 4 ORDER BY id DESC");
    $stmt->execute();
    $result = $stmt->fetchAll();

    // print "<pre>";
    // print_r($result);
    // exit();
?>

 <?php include('header.php'); ?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">

    </section>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-12">
            <div class="card">
              <div class="card-body">
                <table class="table table-bordered" id="d-table">
                  <thead>
                    <tr>
                      <th>#</th>
                      <th>Product</th>
                    </tr>
                  </thead>
                  <tbody>
                  <?php
                      $i = 1;
                      foreach ($result as $value) {
                        $userStmt = $pdo->prepare("SELECT * FROM products WHERE id=".$value['product_id']);
                        $userStmt->execute();
                        $userResult = $userStmt->fetchAll();
                        // print "<pre>";
                        // print_r($userResult);
                        // exit();

                    ?>
                        <tr>
                          <td><?php echo $i ?></td>
                          <td><?php echo escape($userResult[0]['name']) ?></td>
                        </tr>
                    <?php
                      $i++;
                      }
                    ?>
                  </tbody>
                  <p>Items Which are sold above 4.</p>
                </table>
              </div>
              <!-- /.card-body -->

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
<script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
<!-- Bootstrap 4 -->
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>
<script>
    $(document).ready(function () {
        $('#d-table').DataTable();
    });
  </script>
</body>
</html>
