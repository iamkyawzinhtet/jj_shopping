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

  if($_POST) {
    if(empty($_POST['name']) || empty($_POST['email']) || empty($_POST['password']) || empty($_POST['address']) || empty($_POST['phone'])) {
      if(empty($_POST['name'])) {
        $nameError = 'Name is required';
      }
      if(empty($_POST['email'])) {
        $emailError = 'Email is required';
      }
      if(empty($_POST['password'])) {
        $passwordError = 'Password is required';
      }
      if(empty($_POST['address'])) {
        $addressError = 'Address is required';
      }
      if(empty($_POST['phone'])) {
        $phoneError = 'Phone is required';
      }
    }else {
      $name = $_POST['name'];
      $email = $_POST['email'];
      $password = password_hash($_POST['password'],PASSWORD_DEFAULT);
      $address = $_POST['address'];
      $phone = $_POST['phone'];
      if(empty($_POST['role'])) {
        $role = 0;
      }else {
          $role = 1;
      }

      $stmt = $pdo->prepare("SELECT * FROM users WHERE email=:email");
      $stmt->bindValue(':email',$email);
      $stmt->execute();
      $user = $stmt->fetch(PDO::FETCH_ASSOC);

      if($user) {
          echo "<script>alert('This email already exists')</script>";
      }else {
        $stmt = $pdo->prepare("INSERT INTO users (name,email,password,address,phone,role) VALUES (:name,:email,:password,:address,:phone,:role)");
        $result = $stmt-> execute(
          array(':name'=>$name, ':email'=>$email, ':password'=>$password, ':address'=>$address, ':phone'=>$phone, ':role'=>$role)
        );
  
        if($result) {
          echo "<script>alert('User is added!');window.location.href='user.php';</script>";
        }
      }
    }
      // print "<pre>";
      // print_r($result);
  }

?>

<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Add User</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">

  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
    </ul>
  </nav>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="dist/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
          <a href="#" class="d-block">User</a>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <li class="nav-item">
            <a href="user.php" class="nav-link">
              <i class="nav-icon fas fa-th"></i>
              <p>
                User
              </p>
            </a>
          </li>
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-12">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="user.php">Home</a></li>
              <li class="breadcrumb-item active"><a href="logout.php">Logout</a></li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <div class="container-fluid pl-3 pr-3">
        <form action="user_add.php" method="POST">
        <input name="_token" type="hidden" value="<?php echo $_SESSION['_token']; ?>">
        <div class="form-group mb-4">
            <label for="name" class="form-label" style="color: #666">Name</label>
            <p style="color: red"><?php echo empty($nameError) ? '' : '*'.$nameError; ?></p>
            <input type="text" name="name" class="form-control">
        </div>
        <div class="form-group mb-4">
            <label for="email" class="form-label" style="color: #666">Email</label>
            <p style="color: red"><?php echo empty($emailError) ? '' : '*'.$emailError; ?></p>
            <input type="email" name="email" class="form-control">
        </div>
        <div class="form-group mb-4">
            <label for="password" class="form-label" style="color: #666">Password</label>
            <p style="color: red"><?php echo empty($passwordError) ? '' : '*'.$passwordError; ?></p>
            <input type="password" name="password" class="form-control">
        </div>
        <div class="form-group mb-4">
            <label for="address" class="form-label" style="color: #666">Address</label>
            <p style="color: red"><?php echo empty($addressError) ? '' : '*'.$addressError; ?></p>
            <input type="text" name="address" class="form-control">
        </div>
        <div class="form-group mb-4">
            <label for="phone" class="form-label" style="color: #666">Phone</label>
            <p style="color: red"><?php echo empty($phoneError) ? '' : '*'.$phoneError; ?></p>
            <input type="text" name="phone" class="form-control">
        </div>
        <div class="form-group mb-4">
            <label for="role" class="form-label" style="color: #666">Admin</label><br>
            <input type="checkbox" name="role" value="1" style="width: 20px;height: 30px;">
        </div>
        <div class="form-group">
            <button type="submit" class="btn btn-success mr-1">Add</button>
        </div>
        </form>
    </div>
    
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
