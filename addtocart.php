<?php
  session_start();
  require 'config/config.php';
  require 'config/common.php';

  if($_POST) {
    $id = $_POST['id'];
    $qty = $_POST['qty'];

    $stmt = $pdo->prepare("SELECT * FROM products WHERE id=".$id);
    $stmt->execute();
    $products = $stmt->fetch(PDO::FETCH_ASSOC);
  
    if($qty > $products['quantity'] ) {
        echo "<script>alert('Not enough stock');
        window.location.href='product_detail.php?id=$id';</script>";
    }else {
        if(isset($_SESSION['cart']['id'.$id])) {
            $_SESSION['cart']['id'.$id] += $qty;
        }else {
            $_SESSION['cart']['id'.$id] = $qty;
        }
    
        header('Location: index.php');
    }
  }
?>