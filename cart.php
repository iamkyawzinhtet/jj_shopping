<?php
  session_start();
  require 'config/config.php';
  require 'config/common.php';

  if(empty($_SESSION['user_id']) && empty($_SESSION['logged_in'])) {
    header('Location: login.php');
  }

?>

<?php include('header.php') ?>

    <!--================Cart Area =================-->
    <section class="cart_area" style="padding-top: 0">
        <div class="container">
            <div class="cart_inner">
                <div class="table-responsive">
                    <?php if(!empty($_SESSION['cart'])) :?>
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">Product</th>
                                <th scope="col">Price</th>
                                <th scope="col">Quantity</th>
                                <th scope="col">Total</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                                $total = 0;
                                foreach ($_SESSION['cart'] as $key => $qty) :
                                    $id = str_replace('id', '', $key);
                                    $stmt = $pdo->prepare("SELECT * FROM products WHERE id=".$id);
                                    $stmt->execute();
                                    $result = $stmt->fetch(PDO::FETCH_ASSOC);
                                    $total += $result['price'] * $qty;
                                ?>
                                
                                <tr>
                                    <td>
                                        <div class="media">
                                            <div class="d-flex">
                                                <img src="admin/images/<?php echo escape($result['image']) ?>" width="150px">
                                            </div>
                                            <div class="media-body">
                                                <h5><?php echo escape($result['name']) ?></h5>                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <h5><?php echo escape($result['price']) ?></h5>
                                    </td>
                                    <td>
                                        <div class="product_count">
                                            <h5><?php echo $qty ?></h5>
                                        </div>
                                    </td>
                                    <td>
                                        <h5><?php echo escape($result['price']) * $qty ?></h5>
                                    </td>
                                    <td>
                                        <a href="cart_item_clear.php?pid=<?php echo $result['id'] ?>">Clear</a>
                                    </td>
                                </tr>
                            <?php endforeach ?>
                            
                            
                            <tr>
                                <td>

                                </td>
                                <td>

                                </td>
                                <td>
                                    <h5>Subtotal</h5>
                                </td>
                                <td>
                                    <h5><?php echo $total ?></h5>
                                </td>
                            </tr>
                            
                            <tr class="out_button_area">
                                <td>

                                </td>
                                <td>

                                </td>
                                <td>

                                </td>
                                <td>

                                </td>
                                <td>
                                    <div class="checkout_btn_inner d-flex align-items-center">
                                        <a class="gray_btn" href="clearall.php">Clear</a>
                                        <a class="primary-btn" href="index.php">Continue Shopping</a>
                                        <a class="gray_btn" href="sale_order.php">Order Submit</a>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <?php endif ?>
                </div>
            </div>
        </div>
    </section>
    <!--================End Cart Area =================-->

    <?php include('footer.php');?>
