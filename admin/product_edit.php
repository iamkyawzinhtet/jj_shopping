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
    if(empty($_POST['name']) || empty($_POST['description']) || empty($_POST['category'])|| empty($_POST['quantity'])|| empty($_POST['price']) || empty($_FILES['image']['name'])) {

      if(empty($_POST['name'])) {
        $nameError = 'Name is required';
      }

      if(empty($_POST['description'])) {
        $descError = 'Description is required';
      }

      if(empty($_POST['category'])) {
        $catError = 'Category is required';
      }

      if(empty($_POST['quantity'])) {
        $qtyError = 'Quantity is required';
      }elseif(is_numeric($_POST['quantity']) != 1) {
        $qtyError = 'Quantity must be Integer';
      }

      if(empty($_POST['price'])) {
        $priceError = 'Price is required';
      }elseif(is_numeric($_POST['price']) != 1) {
        $priceError = 'Price must be Integer';
      }
      
      if(empty($_FILES['image'])) {
        $imageError = 'Image is required';
      }

    }else { //validation success
      if($_FILES['image']['name']) {
        $file = 'images/'.($_FILES['image']['name']);
        $imageType = pathinfo($file,PATHINFO_EXTENSION);
  
        if($imageType != 'jpg' && $imageType != 'jpeg' && $imageType != 'png') {
            echo "<script>alert('Image must be jpg,jpeg or png');</script>";
        }else {
            $id = $_POST['id'];
            $name = $_POST['name'];
            $desc = $_POST['description'];
            $category = $_POST['category'];
            $quantity = $_POST['quantity'];
            $price = $_POST['price'];
            $image = $_FILES['image']['name'];
  
            move_uploaded_file($_FILES['image']['tmp_name'],$file);
            
            $stmt = $pdo->prepare("UPDATE products SET name=:name,description=:descripiton,category_id=:category,quantity=:quantity,price=:price,image=:image WHERE id=$id");
  
            $result = $stmt->execute(
                array(
                    ':name'=>$name,':descripiton'=>$desc,':category'=>$category,':quantity'=>$quantity,':price'=>$price,':image'=>$image
                )
            );
  
            if($result) {
              echo "<script>alert('Product is updated');window.location.href=('index.php');</script>";
            }
  
        }
      }else {
          $id = $_POST['id'];
          $name = $_POST['name'];
          $desc = $_POST['description'];
          $category = $_POST['category'];
          $quantity = $_POST['quantity'];
          $price = $_POST['price'];
        
          $stmt = $pdo->prepare("UPDATE products SET name=:name,description=:descripiton,category_id=:category,quantity=:quantity,price=:price WHERE id=$id");
  
          $result = $stmt->execute(
              array(
                  ':name'=>$name,':descripiton'=>$desc,':category'=>$category,':quantity'=>$quantity,':price'=>$price
              )
          );

          if($result) {
              echo "<script>alert('Product is updated');window.location.href=('index.php');</script>";
          }
       }
    }
      // print "<pre>";
      // print_r($result);
  }
  $stmt = $pdo->prepare("SELECT * FROM products WHERE id=".$_GET['id']);
  $stmt->execute();
  $result = $stmt->fetchAll();

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
  <title>Update Product</title>

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
          <a href="#" class="d-block">Product</a>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <li class="nav-item">
            <a href="index.php" class="nav-link">
              <i class="nav-icon fas fa-th"></i>
              <p>
                Product
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
              <li class="breadcrumb-item"><a href="index.php">Home</a></li>
              <li class="breadcrumb-item active"><a href="logout.php">Logout</a></li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <div class="container-fluid pl-3 pr-3">
        <form action="" method="POST" enctype="multipart/form-data">
        <input name="_token" type="hidden" value="<?php echo $_SESSION['_token']; ?>">
        <input type="hidden" name="id" value="<?php echo $result[0]['id'] ?>">
        <div class="form-group mb-4">
            <label for="name" class="form-label" style="color: #666">Name</label>
            <p style="color: red"><?php echo empty($nameError) ? '' : '*'.$nameError; ?></p>
            <input type="text" name="name" class="form-control" value="<?php echo escape($result[0]['name']) ?>">
        </div>
        <div class="form-group mb-4">
            <label for="description" class="form-label" style="color: #666">Description</label>
            <p style="color: red"><?php echo empty($descError) ? '' : '*'.$descError; ?></p>
            <textarea name="description" cols="30" rows="10" class="form-control"><?php echo escape($result[0]['description']) ?></textarea>
        </div>

        <?php 
            $catStmt = $pdo->prepare("SELECT * FROM categories");
            $catStmt->execute();
            $catResult = $catStmt->fetchAll();
        ?>

        <div class="form-group mb-4">
            <label for="category" class="form-label" style="color: #666">Category</label>
            <p style="color: red"><?php echo empty($catError) ? '' : '*'.$catError; ?></p>
            <select name="category" class="form-control">
                <option value="">Select Category</option>
                <?php foreach ($catResult as $value) { ?>
                  <?php if($value['id'] == $result[0]['category_id']) : ?>
                    <option value="<?php echo $value['id'] ?>" selected><?php echo $value['name'] ?></option>
                  <?php else :?>
                    <option value="<?php echo $value['id'] ?>"><?php echo $value['name'] ?></option>
                  <?php endif ?>
                <?php } ?> ?>
            </select>
        </div>
        <div class="form-group mb-4">
            <label for="quantity" class="form-label" style="color: #666">Quantity</label>
            <p style="color: red"><?php echo empty($qtyError) ? '' : '*'.$qtyError; ?></p>
            <input type="number" name="quantity" class="form-control" value="<?php echo escape($result[0]['quantity']) ?>">
        </div>
        <div class="form-group mb-4">
            <label for="price" class="form-label" style="color: #666">Price</label>
            <p style="color: red"><?php echo empty($priceError) ? '' : '*'.$priceError; ?></p>
            <input type="number" name="price" class="form-control" value="<?php echo escape($result[0]['price']) ?>">
        </div>
        <div class="form-group mb-4">
            <label for="image" class="form-label" style="color: #666">Image</label>
            <p style="color: red"><?php echo empty($imageError) ? '' : '*'.$imageError; ?></p>
            <img src="images/<?php echo escape($result[0]['image']) ?>" alt="" width="150"><br>
            <input type="file" name="image">
        </div>
        <div class="form-group">
            <button type="submit" class="btn btn-success mr-1">Update</button>
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
