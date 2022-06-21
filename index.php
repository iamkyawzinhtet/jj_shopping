<?php
  session_start();
  require 'config/config.php';
  require 'config/common.php';

  if(empty($_SESSION['user_id']) && empty($_SESSION['logged_in'])) {
    header('Location: login.php');
  }

  if(!empty($_GET['pageno'])) {
    $pageno = $_GET['pageno'];
  }else {
    $pageno = 1;
  }
  $numOfRecs = 3;
  $offset = ($pageno - 1) * $numOfRecs;

  if(empty($_POST['search'])) {
    $stmt = $pdo->prepare("SELECT * FROM products ORDER BY id DESC");
    $stmt->execute();
    $products = $stmt->fetchAll();
    $total_pages = ceil(count($products) / $numOfRecs);

    $stmt = $pdo->prepare("SELECT * FROM products ORDER BY id DESC LIMIT $offset,$numOfRecs");
    $stmt->execute();
    $result = $stmt->fetchAll();
  }else {
    $searchKey = $_POST['search'];
    $stmt = $pdo->prepare("SELECT * FROM products WHERE name LIKE '%$searchKey%' ORDER BY id DESC");
    $stmt->execute();
    $products = $stmt->fetchAll();
    $total_pages = ceil(count($products) / $numOfRecs);

    $stmt = $pdo->prepare("SELECT * FROM products WHERE name LIKE '%$searchKey%' ORDER BY id DESC LIMIT $offset,$numOfRecs");
    $stmt->execute();
    $result = $stmt->fetchAll();
  }

//   print "<pre>";
//   print_r($products);
//   exit();

  $stmt = $pdo->prepare("SELECT * FROM categories ORDER BY id DESC");
  $stmt->execute();
  $categories = $stmt->fetchAll();


?>

<?php include('header.php') ?>

<div class="container">
	<div class="row">
		<div class="col-xl-3 col-lg-4 col-md-5">
			<div class="sidebar-categories">
				<div class="head">Browse Categories</div>
				<ul class="main-categories">
					<?php if($categories) {
						foreach ($categories as $value) {
					?>
						<li class="main-nav-list">
						<a href="#"><?php echo escape($value['name'])?></a>
					</li>
					<?php
						}
					}?>

				</ul>
			</div>
		</div>
		<div class="col-xl-9 col-lg-8 col-md-7">
			<!-- Start Filter Bar -->
			<div class="filter-bar d-flex flex-wrap align-items-center">
				<div class="pagination">
					<a href="?pageno=1">First</a>
					<a <?php if($pageno<=1){echo 'disabled';} ?> href="<?php if($pageno<=1){echo '#';}else{echo "?pageno=".($pageno-1);} ?>" class="prev-arrow"><i class="fa fa-long-arrow-left" aria-hidden="true"></i></a>
					<a href="#" class="active"><?php echo $pageno; ?></a>
					<a <?php if($pageno>=$total_pages){echo 'disabled';} ?> href="<?php if($pageno>=$total_pages){echo '#';}else{echo "?pageno=".($pageno+1);} ?>" class="next-arrow"><i class="fa fa-long-arrow-right" aria-hidden="true"></i></a>
					<a href="?pageno=<?php echo $total_pages ?>">Last</a>
				</div>
			</div>
			<!-- End Filter Bar -->
			<!-- Start Best Seller -->
			<section class="lattest-product-area pb-40 category-list">
				<div class="row">
					<?php if($result) {
						foreach ($result as $value) {
					?>
						<!-- single product -->
						<div class="col-lg-4 col-md-6">
							<div class="single-product">
								<img class="img-fluid" src="admin/images/<?php echo escape($value['image']) ?>" alt="" style="height: 280px">
								<div class="product-details">
									<h6><?php echo escape($value['description']) ?></h6>
									<div class="price">
										<h6><?php echo escape($value['price']) ?></h6>
									</div>
									<div class="prd-bottom">

										<a href="" class="social-info">
											<span class="ti-bag"></span>
											<p class="hover-text">add to bag</p>
										</a>
										<a href="" class="social-info">
											<span class="lnr lnr-move"></span>
											<p class="hover-text">view more</p>
										</a>
									</div>
								</div>
							</div>
						</div>
					<?php	
						}
					}?>
				</div>
			</section>
			<!-- End Best Seller -->
		</div>
	</div>
</div>
<?php include('footer.php');?>
