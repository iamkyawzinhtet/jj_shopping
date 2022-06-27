<?php
  session_start();
  require 'config/config.php';
  require 'config/common.php';

  if(empty($_SESSION['user_id']) && empty($_SESSION['logged_in'])) {
    header('Location: login.php');
  }

  $stmt = $pdo->prepare("SELECT * FROM products WHERE id=".$_GET['id']);
  $stmt->execute();
  $products = $stmt->fetchAll();

  $id = $products[0]['category_id'];
  $catStmt = $pdo->prepare("SELECT * FROM categories WHERE id=$id");
  $catStmt->execute();
  $catResult = $catStmt->fetch(PDO::FETCH_ASSOC);


?>

<?php include('header.php') ?>
<!--================Single Product Area =================-->
<div class="product_image_area" style="padding-top: 0">
  <div class="container">
    <?php if($products) { 
        foreach ($products as $value) {
    ?>
          <div class="row s_product_inner">
          <div class="col-lg-6">
              <div class="single-prd-item">
                <img class="img-fluid" src="admin/images/<?php echo escape($value['image']) ?>" style="width: 100%">
              </div>
          </div>
          <div class="col-lg-5 offset-lg-1">
            <div class="s_product_text">
              <h3><?php echo escape($value['name']) ?></h3>
              <h2><?php echo escape($value['price']) ?></h2>
              <ul class="list">
                <li><a class="active" href="#"><span>Category</span> : <?php echo escape($catResult['name']) ?></a></li>
                <li><a href="#"><span>Availibility</span> : In Stock</a></li>
              </ul>
              <p><?php echo escape($value['description']) ?></p>
              <form action="addtocart.php" method="POST">
                <input name="_token" type="hidden" value="<?php echo $_SESSION['_token']; ?>">
                <input type="hidden" name="id" value="<?php echo escape($value['id']) ?>">
                <div class="product_count">
                  <label for="qty">Quantity:</label>
                  <input type="text" name="qty" id="sst" maxlength="12" value="1" title="Quantity:" class="input-text qty">
                  <button onclick="var result = document.getElementById('sst'); var sst = result.value; if( !isNaN( sst )) result.value++;return false;"
                  class="increase items-count" type="button"><i class="lnr lnr-chevron-up"></i></button>
                  <button onclick="var result = document.getElementById('sst'); var sst = result.value; if( !isNaN( sst ) &amp;&amp; sst > 0 ) result.value--;return false;"
                  class="reduced items-count" type="button"><i class="lnr lnr-chevron-down"></i></button>
                </div>
                <div class="card_area d-flex align-items-center">
                  <button class="primary-btn" style="border: 1px">Add to Cart</button>
                  <a class="primary-btn" href="index.php">Back</a>
                </div>
              </form>
            </div>
          </div>
        </div>
    <?php
        }
    } ?>
    
  </div>
</div><br>
<!--================End Single Product Area =================-->

<!--================End Product Description Area =================-->
<?php include('footer.php');?>
