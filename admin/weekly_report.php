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
    $currentDate = date('Y-m-d');
    $fromDate = date('Y-m-d', strtotime($currentDate . '+1 day'));
    $toDate = date('Y-m-d', strtotime($currentDate . '-7 day'));
    
    $stmt = $pdo->prepare("SELECT * FROM sale_orders WHERE order_date<:fromdate AND order_date>=:todate ORDER BY id DESC");
    $stmt->execute(
        array(':fromdate'=> $fromDate, ':todate'=> $toDate)
    );
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
                      <th style="width: 10px">#</th>
                      <th>User Id</th>
                      <th>Total Amount</th>
                      <th>Order Date</th>
                    </tr>
                  </thead>
                  <tbody>
                  <?php
                      $i = 1;
                      foreach ($result as $value) {
                        $userStmt = $pdo->prepare("SELECT * FROM users WHERE id=".$value['customer_id']);
                        $userStmt->execute();
                        $userResult = $userStmt->fetchAll();
                        // print "<pre>";
                        // print_r($userResult);
                        // exit();

                    ?>
                        <tr>
                          <td><?php echo $i ?></td>
                          <td><?php echo escape($userResult[0]['name']) ?></td>
                          <td><?php echo escape($value['total_price']) ?></td>
                          <td><?php echo escape(date('Y-m-d',strtotime($value['order_date']))) ?></td>
                        </tr>
                    <?php
                      $i++;
                      }
                    ?>
                  </tbody>
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
